<?php

namespace peertxt\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use peertxt\Helpers\DateTimeHelper;
use peertxt\Http\Requests\CampaignStoreRequest;
use peertxt\Jobs\BeginCampaign;
use peertxt\Jobs\SendCampaign;
use peertxt\Jobs\SendReply;
use peertxt\Jobs\SendText;
use peertxt\models\Campaign;
use peertxt\models\CampaignContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use peertxt\models\CampaignDeliveryRule;
use peertxt\models\CampaignRight;
use peertxt\models\CampaignTag;
use peertxt\models\Chat;
use peertxt\models\ChatThread;
use peertxt\models\Company;
use peertxt\models\Contact;
use peertxt\models\ContactField;
use peertxt\models\CustomLabel;
use peertxt\models\CustomReply;
use peertxt\models\MessagingService;
use peertxt\models\MessagingServiceNumber;
use peertxt\models\Right;
use peertxt\models\Tag;
use peertxt\models\User;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Auth;
use Illuminate\Support\Facades\Session;
use Twilio\Rest\Client;
use Validator;
use Webpatser\Uuid\Uuid;
use Mockery\Exception;
use Yajra\DataTables\DataTables;
use Image;

class CampaignController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ajax_attach_contact_field(Request $request)
    {
        Log::info('ajax_attach_contact_field ... ');

        $data = [];
        $thread_uuid = trim($request->get('thread_uuid'));
        $label_id = trim($request->get('field_id'));
        $data['status'] = 'notok';

        if ($thread_uuid) {
            $thread = ChatThread::where('uuid', $thread_uuid)->first();

            if ($thread) {
                if ($label_id) {
                    if ($label_id > 0) {
                        // custom label
                        if (@$thread->Chat->contact_id) {
                            if (@$thread->Chat->company_id) {
                                $custom_label = CustomLabel::where('id', $label_id)->where('company_id', $thread->Chat->company_id)->first();
                                if ($custom_label) {
                                    if (@$thread->Chat->contact_id) {
                                        $contact_field = ContactField::where('contact_id', $thread->Chat->contact_id)->where('custom_label_id', $custom_label->id)->first();
                                        if ($contact_field) {
                                            // update
                                            Log::info('ajax_attach_contact_field ... update contact field ');
                                            $contact_field->value = $thread->message;
                                            $contact_field->save();
                                        } else {
                                            // add
                                            $contact_field = new ContactField();
                                            $contact_field->contact_id = $thread->Chat->contact_id;
                                            $contact_field->custom_label_id = $custom_label->id;
                                            $contact_field->value = $thread->message;
                                            $contact_field->status = 1;
                                            $contact_field->save();
                                        }
                                        $data['status'] = 'ok';
                                    }
                                }
                            }
                        }
                    } else {
                        // contact field (raw)
                        $contact = Contact::where('id', $thread->Chat->contact_id)->first();
                        if ($contact) {

                            switch ($label_id) {
                                case -1:
                                    $contact->first_name = $thread->message;
                                    break;
                                case -2:
                                    $contact->last_name = $thread->message;
                                    break;
                                case -3:
                                    $contact->email = $thread->message;
                                    break;
                                case -4:
                                    $contact->address1 = $thread->message;
                                    break;
                                case -5:
                                    $contact->city = $thread->message;
                                    break;
                                case -6:
                                    $contact->state = $thread->message;
                                    break;
                                case -7:
                                    $contact->zip = $thread->message;
                                    break;
                            }
                            $contact->save();

                        }
                    }
                }
            }
        }

        $json = json_encode($data);
        echo $json;
        exit;
    }

    public function ajax_inbox_list_refresh(Request $request)
    {

        $data = [];
        $campaign_uuid = trim($request->get('campaign_uuid'));
        $data['status'] = 'notok';

        if ($campaign_uuid) {
            // get list of inbox items
            $campaign = Campaign::where('uuid', $campaign_uuid)->first();
            if ($campaign) {
                $chats = Chat::where('campaign_id', $campaign->id)->where('overall_status', '<', 10)->orderBy('updated_at', 'desc')->get();
            }
        }


//		<div class="inbox_item" data-uuid="{{ $chat->uuid }}">
//			<div class="name">
//				{{ $chat->Contact->first_name }} {{ $chat->Contact->last_name }}
//			</div>
//			<div class="last_date">
//				{{ Carbon\Carbon::createFromTimeString($chat->updated_at)->diffForHumans() }}
//			</div>
//			<div class="snippet">
//				@if (@$chat->LatestThread->message)
//					{{ @$chat->LatestThread->message }}
//				@else
//					&lt;No recent message&gt;
//				@endif
//			</div>
//		</div>

    }

    public function ajax_inbox_send(Request $request)
    {
        Log::info('ajax_inbox_send ... ');

        // chat_uuid: chat_uuid, message: msg

        $data = [];
        $chat_uuid = trim($request->get('chat_uuid'));
        $message = trim($request->get('message'));
        $data['status'] = 'notok';

        if (($chat_uuid) && ($message)) {

            $chat_check = Chat::where('uuid', $chat_uuid)->first();
            if ($chat_check) {
                $chat_thread = new ChatThread();
                $chat_thread->uuid = Uuid::generate()->string;
                $chat_thread->chat_id = $chat_check->id;
                $chat_thread->direction = 2; // out
                $chat_thread->status = 0; // queued
                $chat_thread->subject = null;
                $chat_thread->message = $message;
                $chat_thread->private_notes = null;
                $chat_thread->media_url = null;
                $chat_thread->user_id = Auth::user()->id;
                $chat_thread->audit_sms_rcvd = null;
                $chat_thread->audit_sms_sent = null;
                $chat_thread->save();

                if ($chat_thread->id) {
                    $this->dispatch(new SendReply($chat_thread->id));
                    $data['status'] = 'ok';
                }
            }

        }

        $json = json_encode($data);
        echo $json;
        exit;

    }

    public function ajax_chat_thread_info(Request $request)
    {
        $data = [];
        $thread_uuid = trim($request->get('thread_uuid'));
        $data['status'] = 'notok';

        if ($thread_uuid) {
            $thread = ChatThread::where('uuid', $thread_uuid)->first();
            if ($thread) {
                $chat = Chat::where('id', $thread->chat_id)->first();
                if ($chat) {
                    $data['contact'] = $chat->Contact->first_name . " " . $chat->Contact->last_name;
                    $data['message'] = $thread->message;

                    // get a list of fields available
                    $fields = [];

                    $labels = CustomLabel::where('company_id', $chat->company_id)->get();
                    foreach ($labels as $label) {
                        $tmp = [];
                        $tmp['id'] = $label->id;
                        $tmp['val'] = $label->label;
                        $fields[] = $tmp;
                    }

                    $other_fields = [
                        'First Name',
                        'Last Name',
                        'Email',
                        'Address',
                        'City',
                        'State',
                        'Zip'
                    ];

                    $x = 0;
                    for ($i = 0; $i < count($other_fields); $i++) {
                        $x--;
                        $tmp = [];
                        $tmp['id'] = $x;
                        $tmp['val'] = $other_fields[$i];
                        $fields[] = $tmp;
                    }

                    $data['fields'] = $fields;
                    $data['status'] = 'ok';
                }
            }
        }

        $json = json_encode($data);
        echo $json;
        exit;

    }

    public function ajax_inbox_chat(Request $request)
    {
        // get the initial data
        // get all threads

        $data = [];
        $chat_uuid = trim($request->get('chat_uuid'));
        $data['status'] = 'notok';

        if ($chat_uuid) {
            $chat = Chat::where('uuid', $chat_uuid)->first();
            if ($chat) {

                if (Auth::user()->hasRole('administrator')) {
                    $campaign_check = Campaign::where('id', $chat->campaign_id)->first();
                } else {
                    $campaign_check = Campaign::where('id', $chat->campaign_id)->where('company_id', Auth::user()->company_id)->first();
                }
                if ($campaign_check) {

                    $campaign_contact_info = CampaignContact::where('campaign_id', $chat->campaign_id)->where('contact_id', $chat->contact_id)->first();
                    if ($campaign_contact_info) {
                        $contact = [];
                        $messages = [];

                        $contact['first_name'] = $campaign_contact_info->Contact->first_name;
                        $contact['last_name'] = $campaign_contact_info->Contact->last_name;
                        $contact['email'] = $campaign_contact_info->Contact->email;
                        $contact['address1'] = $campaign_contact_info->Contact->address1;
                        $contact['city'] = $campaign_contact_info->Contact->city;
                        $contact['state'] = $campaign_contact_info->Contact->state;
                        $contact['zip'] = $campaign_contact_info->Contact->zip;

                        $tmp = [];
                        $tmp['uuid'] = '';
                        $tmp['message'] = $campaign_contact_info->content_sent;
                        $tmp['date'] = Carbon::createFromTimeString($campaign_contact_info->created_at)->diffForHumans();
                        $tmp['full_date'] = Carbon::createFromTimeString($campaign_contact_info->created_at)->toDayDateTimeString();
                        $tmp['direction'] = 2; // out
                        $tmp['status'] = 2; // processed / sent
                        $tmp['who'] = 'System';
                        $tmp['saved'] = 0;
                        $tmp['saved_msg'] = '';
                        $messages[] = $tmp;

                        foreach ($chat->ChatThread as $thread) {
                            $tmp = [];
                            $tmp['uuid'] = $thread->uuid;
                            $tmp['message'] = $thread->message;
                            $tmp['date'] = Carbon::createFromTimeString($thread->created_at)->diffForHumans();
                            $tmp['full_date'] = Carbon::createFromTimeString($thread->created_at)->toDayDateTimeString();
                            $tmp['direction'] = $thread->direction;
                            $tmp['status'] = $thread->status;
                            $tmp['saved'] = $thread->saved;
                            $tmp['saved_msg'] = '';
                            if ($thread->direction === 1) {
                                $tmp['who'] = $campaign_contact_info->Contact->first_name;
                                if ($campaign_contact_info->Contact->last_name) {
                                    $tmp['who'] = $tmp['who'] . " " . $campaign_contact_info->Contact->last_name;
                                }
                            } else {
                                if (($thread->user_id) && ($thread->user_id > 0)) {
                                    $user_info = User::where('id', $thread->user_id)->first();
                                    if ($user_info) {
                                        $tmp['who'] = $user_info->name;
                                    } else {
                                        $tmp['who'] = 'Unknown User';
                                    }
                                } else {
                                    $tmp['who'] = 'System';
                                }
                            }
                            $messages[] = $tmp;
                        }

                        $data['contact'] = $contact;
                        $data['messages'] = $messages;
                        $data['status'] = 'ok';

                    }

                }
            }
        }

        $json = json_encode($data);
        echo $json;
        exit;

    }

    public function ajax_run_send_item(Request $request)
    {

        $data = [];
        $campaign_uuid = trim($request->get('campaign_uuid'));
        $uuid = trim($request->get('uuid'));
        $data['status'] = 'notok';

        if ($campaign_uuid && $uuid) {

            if (Auth::user()->hasRole('administrator')) {
                $campaign = Campaign::where('uuid', $campaign_uuid)
                    ->where('campaign_status', 20)
                    ->first();
            } else {
                $campaign = Campaign::where('uuid', $campaign_uuid)
                    ->where('company_id', Auth::user()->company_id)
                    ->where('campaign_status', 20)
                    ->first();
            }
            if ($campaign && $campaign->campaign_status !== 30) {

                $campaign_contact_check = CampaignContact::where('uuid', $uuid)
                    ->where('user_id', Auth::user()->id)
                    ->where('cc_status', 10)
                    ->first();
                if ($campaign_contact_check) {
                    $campaign_contact_check->cc_status = 20;
                    $campaign_contact_check->audit_submit_sms = microtime();

                    if ($campaign_contact_check->save()) {
                        // send text job..
                        $this->dispatch(new SendText($campaign, $campaign_contact_check));
                        $data['status'] = 'ok';
                    }

                }

            }
        }

        $json = json_encode($data);
        echo $json;
        exit;

    }

    public function ajax_run_cancel_item(Request $request)
    {

        $data = [];
        $campaign_uuid = trim($request->get('campaign_uuid'));
        $uuid = trim($request->get('uuid'));
        if ($campaign_uuid && $uuid) {

            if (Auth::user()->hasRole('administrator')) {
                $campaign = Campaign::where('uuid', $campaign_uuid)
                    ->where('campaign_status', 20)
                    ->first();
            } else {
                $campaign = Campaign::where('uuid', $campaign_uuid)
                    ->where('company_id', Auth::user()->company_id)
                    ->where('campaign_status', 20)
                    ->first();
            }
            if ($campaign) {

                $campaign_contact_check = CampaignContact::where('uuid', $uuid)
                    ->where('user_id', Auth::user()->id)
                    ->where('cc_status', 10)
                    ->first();

                if ($campaign_contact_check) {
                    // good to release
                    $campaign_contact_check->user_id = null;
                    $campaign_contact_check->cc_status = 1;
                    $campaign_contact_check->audit_locked_by_user = null;

                    if ($campaign_contact_check->save()) {
                        $data['status'] = 'ok';
                    }

                }
            }

        }

        $json = json_encode($data);
        echo $json;
        exit;

    }

    public function ajax_run_get_totals(Request $request)
    {

        $data = [];
        $campaign_uuid = trim($request->get('campaign_uuid'));
        if ($campaign_uuid) {

            if (Auth::user()->hasRole('administrator')) {
                $campaign = Campaign::where('uuid', $campaign_uuid)
                    ->where('campaign_status', '>=', 20)
                    ->first();
            } else {
                $campaign = Campaign::where('uuid', $campaign_uuid)
                    ->where('company_id', Auth::user()->company_id)
                    ->where('campaign_status', '>=', 20)
                    ->first();
            }
            if ($campaign) {
                $data['campaign'] = $campaign;
            }

        }

        $json = json_encode($data);
        echo $json;
        exit;

    }

    public function ajax_run_get_item(Request $request)
    {

        $data = [];
        $campaign_uuid = trim($request->get('campaign_uuid'));
        if ($campaign_uuid) {

            if (Auth::user()->hasRole('administrator')) {
                $campaign = Campaign::where('uuid', $campaign_uuid)
                    ->where('campaign_status', '>=', 20)
                    ->first();
            } else {
                $campaign = Campaign::where('uuid', $campaign_uuid)
                    ->where('company_id', Auth::user()->company_id)
                    ->where('campaign_status', '>=', 20)
                    ->first();
            }
            if ($campaign && $campaign->campaign_status !== 30) {

                $item = [];
                $count_completed = $campaign->rollup_completed;

                // do we have something already locked for this user / campaign? if so, return that first..
                $campaign_contact_check = DB::table('campaign_contacts')->select('campaign_contacts.*', 'contacts.first_name', 'contacts.last_name', 'contacts.phone')->join('contacts', 'contacts.id', '=', 'campaign_contacts.contact_id')->where('campaign_contacts.campaign_id', $campaign->id)->where('campaign_contacts.cc_status', 10)->where('campaign_contacts.user_id', Auth::user()->id)->whereNull('campaign_contacts.deleted_at')->first();
                if ($campaign_contact_check) {
                    $item = $campaign_contact_check;
                } else {
                    // if not, get a random contact (use random to prevent overlap)
                    $campaign_contact_random = DB::table('campaign_contacts')->select('campaign_contacts.*', 'contacts.first_name', 'contacts.last_name', 'contacts.phone')->join('contacts', 'contacts.id', '=', 'campaign_contacts.contact_id')->where('campaign_contacts.campaign_id', $campaign->id)->where('campaign_contacts.cc_status', 1)->whereNull('campaign_contacts.deleted_at')->inRandomOrder()->first();
                    if ($campaign_contact_random) {
                        // need to immediately lock this..

                        $cnt = DB::table('campaign_contacts')
                            ->where('id', $campaign_contact_random->id)
                            ->where('cc_status', 1)
                            ->update([
                                'user_id' => Auth::user()->id,
                                'cc_status' => 10,
                                'audit_locked_by_user' => microtime(),
                                'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                            ]);

                        if ($cnt > 0) {
                            // we good; we snagged this..
                            $item = $campaign_contact_random;
                            $count_completed = DB::table('campaign_contacts')->where('campaign_id', $campaign->id)->where('cc_status', '>=', 50)->whereNull('deleted_at')->count();
                        }

                    }

                }

                $data['item'] = $item;
                $data['campaign'] = $campaign;
                $data['count_completed'] = $count_completed;

            }

        }

        $json = json_encode($data);
        echo $json;
        exit;

    }

    public function run(Request $request, $id)
    {
        ## if a user is a replier, they should go directly to inbox
        if (Auth::user()->can('reply'))
            return redirect(route('campaigns.inbox', ['id' => $id]));

        if (Auth::user()->hasRole('administrator')) {
            $campaign = DB::table('campaigns')->where('id', $id)->where('campaign_status', '>=', 20)->whereNull('deleted_at')->first();
        } else {
            $campaign = DB::table('campaigns')->where('id', $id)->where('campaign_status', '>=', 20)->where('company_id', Auth::user()->company_id)->whereNull('deleted_at')->first();
        }

        if (!$campaign) {
            $request->session()->flash("status", "Campaign not found!");
            return redirect('/campaigns');
        }

        return view('campaigns.run', ['campaign' => $campaign]);

    }

    public function index(Request $request)
    {

        $my_rights = [];
        $contact_tags = [];
        $messaging_services = null;

        if (Auth::user()->hasRole('administrator')) {
            $campaigns = Campaign::with('Company')
                ->orderBy('id')
                ->get();

            $company = [];

        } elseif (Auth::user()->hasRole('manager')) {
            $campaigns = Campaign::with('Company')
                ->where('company_id', Auth::user()->company_id)
                ->orderBy('id')
                ->get();

            $company = Company::where('id', Auth::user()->company_id)->first();

            $my_rights = Right::with('Company')
                ->where('rights.company_id', Auth::user()->company_id)
                ->orderBy('name')
                ->get();

            $messaging_services = MessagingService::where('company_id', Auth::user()->company_id)
							->doesntHave('Campaign')
							->get();

            /*$my_contacts = Contact::with('Company')
                    ->where('contacts.company_id', Auth::user()->company_id)
                    ->orderBy('last_name')
                    ->get();*/

            $tags = Tag::getWithType('company' . Auth::user()->company_id)->pluck('name');

            foreach ($tags as $key => $tag) {
                $count = Contact::where('company_id', Auth::user()->company_id)
                    ->withAnytags([$tag])
                    ->count();

                $contact_tags[] = [
                    'tag' => $tag,
                    'data' => sprintf('%s - %s contacts', $tag, $count)
                ];
            }
        } else {
            $campaigns = Campaign::with('Company')
                ->where('company_id', Auth::user()->company_id)
                ->where('campaign_status', '>=', 20)
                ->orderBy('id')
                ->get();

            $company = Company::where('id', Auth::user()->company_id)->first();

        }

        $companies = Company::orderBy('company_name')->where('status', 1)->get();
        $day_list = DateTimeHelper::DayList();
        $time_list = DateTimeHelper::TimeList();

        if (is_null($messaging_services))
        	$messaging_services = collect([]);

        return view('campaigns.index', [
            'campaigns' => $campaigns,
            'companies' => $companies,
            'my_rights' => $my_rights,
            'contact_tags' => $contact_tags,
            'company' => $company,
            'day_list' => $day_list,
            'time_list' => $time_list,
					'messaging_services' => $messaging_services
        ]);
    }

    public function indexTable(Request $request)
    {
        $query = Campaign::with('Company');

        if (Auth::user()->hasRole('administrator')) {
            // no special filtering
        } elseif (Auth::user()->hasRole('manager')) {
            $query = $query->where('company_id', Auth::user()->company_id);
        } else {
            $query = $query->where('company_id', Auth::user()->company_id)
                ->where('campaign_status', '>=', 20);
        }

        $query = $query->where('campaign_status', '!=', 50);

        return Datatables::of($query)
            ->editColumn('id', function (Campaign $campaign) {
                return $campaign->id;
            })
            ->editColumn('id', 'campaigns.id')
            ->editColumn('campaign_name', 'campaigns.name')
            ->editColumn('campaign_status', function (Campaign $campaign) {
                return campaign_status($campaign->campaign_status);
            })
            ->editColumn('campaign_type', function (Campaign $campaign) {
                return campaign_type($campaign->campaign_type);
            })
            ->editColumn('button', 'campaigns.button')
            ->rawColumns(['id', 'campaign_name', 'button'])
            ->make(true);
    }

	public function go_send(Request $request, $id)
	{

		if (Auth::user()->hasRole('administrator')) {
			$campaign = DB::table('campaigns')->where('id', $id)->where('campaign_status', 10)->whereNull('deleted_at')->first();
		} elseif (Auth::user()->hasRole('manager')) {
			$campaign = DB::table('campaigns')->where('id', $id)->where('campaign_status', 10)->where('company_id', Auth::user()->company_id)->whereNull('deleted_at')->first();
		} else {
			$request->session()->flash("status", "Access not allowed!");
			return redirect('/');
			exit;
		}

		if (!$campaign) {
			$request->session()->flash("status", "Campaign not found!");
			return redirect('/campaigns');
		}

		DB::table('campaigns')
			->where('id', $id)
			->update([
				'campaign_status' => 20,
				'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
			]);

		// kick off job to send texts..
		$this->dispatch(new SendCampaign($id));

		$request->session()->flash("status", "Campaign is now sending!");
		return redirect('/campaigns');

	}

	public function go_live(Request $request, $id)
	{

        if (Auth::user()->hasRole('administrator')) {
            $campaign = DB::table('campaigns')->where('id', $id)->where('campaign_status', 10)->whereNull('deleted_at')->first();
        } elseif (Auth::user()->hasRole('manager')) {
            $campaign = DB::table('campaigns')->where('id', $id)->where('campaign_status', 10)->where('company_id', Auth::user()->company_id)->whereNull('deleted_at')->first();
        } else {
            $request->session()->flash("status", "Access not allowed!");
            return redirect('/');
            exit;
        }

        if (!$campaign) {
            $request->session()->flash("status", "Campaign not found!");
            return redirect('/campaigns');
        }

        DB::table('campaigns')
            ->where('id', $id)
            ->update([
                'campaign_status' => 20,
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
            ]);

        $request->session()->flash("status", "Campaign is now live!");
        return redirect('/campaigns');

    }

    public function begin(Request $request, $id)
    {


        ## if a user is a replier, they should go directly to inbox
        if (Auth::user()->can('reply')) {
            return redirect(route('campaigns.inbox', ['id' => $id]));
        }

        if (Auth::user()->hasRole('administrator')) {
            $campaign = Campaign::with('delivery_rule')->where('id', $id)->where('campaign_status', 1)->whereNull('deleted_at')->first();
        } elseif (Auth::user()->hasRole('manager')) {
            $companyIds = Company::getCompanyIds(Auth::user()->company_id);
            $campaign = Campaign::with('delivery_rule')->where('id', $id)->where('campaign_status', 1)->whereIn('company_id', $companyIds)->whereNull('deleted_at')->first();
        } else {
            $request->session()->flash("status", "Access not allowed!");
            return redirect('/');
            exit;
        }

        if ($campaign->delivery_rule) {
            $now = Carbon::now();
            if ($delivery_rule = $campaign->delivery_rule->firstWhere('day', $now->dayOfWeek)) {
                if (!$delivery_rule->whole_day) {
                    $nowTimeInt = DateTimeHelper::convertToIntTime($now->toTimeString());
                    if ($nowTimeInt < $delivery_rule->from_time || $nowTimeInt > $delivery_rule->to_time) {
                        return redirect()->back()->withErrors(['Campaign has delivery time rules that are outside current time. Text wont be sent.']);
                    }
                }
            }
        }

        if (!$campaign) {
            $request->session()->flash("status", "Campaign not found!");
            return redirect('/campaigns');
        }

        DB::table('campaigns')
            ->where('id', $id)
            ->update([
                'campaign_status' => 5,
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
            ]);
        $this->dispatch(new BeginCampaign($id));

        $request->session()->flash("status", "Campaign processing has begun!");
        return redirect('/campaigns');

    }

    public function ajax_get_campaign_work_data(Request $request)
    {

        if (!Auth::user()->hasRole('administrator', 'manager')) {
            echo "";
            exit;
        }

        $campaign_id = (int)trim($request->get('campaign_id'));

        $data = [];
        if ($campaign_id) {

            if (Auth::user()->hasRole('administrator')) {
                $campaign = Campaign::where('id', $campaign_id)
                    ->where('campaign_status', '>', 19)
                    ->first();
            } elseif (Auth::user()->hasRole('manager')) {
                $campaign = Campaign::where('id', $campaign_id)
                    ->where('company_id', Auth::user()->company_id)
                    ->where('campaign_status', '>', 19)
                    ->first();
            }

            if ($campaign && $campaign->campaign_status !== 30) {

                $recent_contact_sends = DB::table('campaign_contacts')
                    ->select('campaign_contacts.*', 'contacts.first_name', 'contacts.last_name', 'contacts.phone', 'contacts.email')
                    ->join('contacts', 'campaign_contacts.contact_id', '=', 'contacts.id')
                    ->join('campaigns', 'campaign_contacts.campaign_id', '=', 'campaigns.id')
                    ->where('campaign_contacts.campaign_id', $campaign_id)
                    ->where('campaign_contacts.cc_status', 50)
                    ->whereNull('campaign_contacts.deleted_at')
                    ->orderBy('campaign_contacts.updated_at', 'DESC')
                    ->limit(5)
                    ->get();

                $data['recent'] = $recent_contact_sends;
                $data['campaign'] = $campaign;
            }
        }

        $json = json_encode($data);
        echo $json;
        exit;

    }

    public function ajax_get_campaign_creation_data(Request $request)
    {

        if (!Auth::user()->hasRole('administrator')) {
            echo "";
            exit;
        }

        $data = [];
        if ($request->get('company_id')) {
            $company = Company::where('id', $request->get('company_id'))->first();
            if ($company) {
                $companyId = $request->get('company_id');

                $rights = Right::where('rights.company_id', $companyId)
                    ->with('Company')
                    ->orderBy('name')
                    ->get();

                $tags = Tag::getWithType('company' . $companyId)->pluck('name');

                $contact_tags = [];
                foreach ($tags as $key => $tag) {
                    $count = Contact::where('company_id', $companyId)
                        ->withAnytags([$tag])
                        ->count();

                    $contact_tags[] = [
                        'tag' => $tag,
                        'data' => sprintf('%s - %s contacts', $tag, $count)
                    ];
                }

                $messaging_services = MessagingService::where('company_id', $companyId)
									->doesntHave('Campaign')
									->get();

                $data['rights'] = $rights;
                $data['tags'] = $contact_tags;
                $data['company'] = $company;
                $data['messaging_services'] = $messaging_services;
            }
        }

        return json_encode($data);

    }

    public function update(CampaignStoreRequest $request, $id)
    {
        if (Auth::user()->hasRole('administrator')) {
            $campaign = Campaign::where('id', $id)->first();
            $company_id = $request->get('company_id');
        } elseif (Auth::user()->hasRole('manager')) {
            $campaign = Campaign::where('id', $id)
                ->where('company_id', Auth::user()->company_id)
                ->first();
            $company_id = Auth::user()->company_id;
        } else {
            $request->session()->flash("status", "Access not allowed!");
            return redirect('/');
        }

        if (!$campaign) {
            $request->session()->flash("status", "Campaign not found!");
            return redirect('/campaigns');
        }

        if ($campaign->campaign_status > 1) {
            $request->session()->flash("status", "Campaign is locked!");
            return redirect('/campaigns');
        }

        $validated = $request->validated();

        $phone = str_replace('-', '', filter_var($request->get('nearphone'), FILTER_SANITIZE_NUMBER_INT));
        $stripped_phone = str_replace("+1", "", $phone);
        $stripped_phone = str_replace("+", "", $stripped_phone);
        $stripped_phone = ltrim($stripped_phone, '0');
        $stripped_phone = ltrim($stripped_phone, '1');
        $stripped_phone = trim(preg_replace("/[^0-9]/", "", $stripped_phone));

        DB::beginTransaction();

        try {

            $campaign->company_id = $company_id;
            $campaign->campaign_status = $request->get('campaign_status');
            $campaign->campaign_type = $request->get('campaign_type');
            $campaign->campaign_name = $request->get('campaign_name');
            $campaign->description = $request->get('description');
            $campaign->content_template_1 = $request->get('content_template_1');
            $campaign->content_template_2 = $request->get('content_template_2');
            $campaign->content_template_3 = $request->get('content_template_3');
            $campaign->content_template_4 = $request->get('content_template_4');
            $campaign->conversion_link_1 = $request->get('conversion_link_1');
            $campaign->conversion_link_2 = $request->get('conversion_link_2');
            $campaign->conversion_link_3 = $request->get('conversion_link_3');
            $campaign->conversion_link_4 = $request->get('conversion_link_4');

            for ($i = 1; $i < 5; $i++) {
                if (@$request->exists('delete_mms_img_' . $i)) {
                    $campaign->{'content_media_' . $i} = "";
                }
            }

						if ($request->has('messaging_service') && $request->get('messaging_service') != 'none') {
							$campaign->messaging_service_id = $request->get('messaging_service');
						} else {
							$campaign->messaging_service_id = 0;
						}

            $campaign->zipcode = $request->get('zipcode');
            $campaign->areacode = $request->get('areacode');
            $campaign->nearphone = $stripped_phone;
            $campaign->rights_type = $request->get('rights_type');
            $campaign->tags = explode(",", $request->get('tags'));
            $campaign->save();

            CampaignRight::where('campaign_id', $id)->delete();
            CampaignTag::where('campaign_id', $id)->delete();

            ## get a list of contacts for each tag
            if ($request->has('tags_list')) {
                foreach ($request->get('tags_list') as $key => $tag) {
                    $campaignTag = new CampaignTag();
                    $campaignTag->campaign_id = $campaign->id;
                    $campaignTag->tag = $tag;
                    $campaignTag->save();
                }
            }


            CampaignDeliveryRule::where('campaign_id', $id)->delete();
            if ($request->has('day')) {
                foreach ($request->input('day') as $key => $day) {
                    $whole_day = isset($request->input('whole_day')[$day]) ? $request->input('whole_day')[$day] == 1 : false;
                    $from_time = isset($request->input('from_time')[$day]) ? intval($request->input('from_time')[$day]) : null;
                    $to_time = isset($request->input('to_time')[$day]) ? intval($request->input('to_time')[$day]) : null;
                    if (!$whole_day) {
                        if (!$from_time || !$to_time || ($from_time && $to_time && $from_time >= $to_time)) {
                            return redirect()->back()->withErrors(['Make sure you have chosen correct from and to time. To time cannot be smaller than from time.']);
                        }
                    }
                    $campaignDeliveryRule = new CampaignDeliveryRule();
                    $campaignDeliveryRule->campaign_id = $campaign->id;
                    $campaignDeliveryRule->day = $day;
                    $campaignDeliveryRule->whole_day = $whole_day;
                    $campaignDeliveryRule->from_time = $from_time;
                    $campaignDeliveryRule->to_time = $to_time;
                    $campaignDeliveryRule->save();
                }
            }

            if ($request->has('rights_type') && $request->get('rights_type') != 1) {
                foreach ($request->get('rights_list') as $right_id) {
                    $campaign_right_id = DB::table('campaign_rights')->insertGetId(
                        [
                            'campaign_id' => $id,
                            'right_id' => $right_id,
                            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                        ]
                    );
                }
            }

            for ($i = 1; $i < 5; $i++) {
                if (@$request->hasFile('file_upload_' . $i)) {
                    $this->_saveImage($request->file('file_upload_' . $i), $campaign->id, $i);
                }
            }

            DB::commit();

            $request->session()->flash("status", "Campaign updated successfully!");
            return redirect('/campaigns');

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            throw $exception;
        }
    }

    public function watch(Request $request, $id)
    {
        ## if a user is a replier, they should go directly to inbox
        if (Auth::user()->can('reply')) {
            return redirect(route('campaigns.inbox', ['id' => $id]));
        }

        if (Auth::user()->hasRole('administrator')) {
            $campaign = DB::table('campaigns')->where('id', $id)->where('campaign_status', '>=', 20)->first();
        } elseif (Auth::user()->hasRole('manager')) {
            $campaign = DB::table('campaigns')->where('id', $id)->where('campaign_status', '>=', 20)->where('company_id', Auth::user()->company_id)->whereNull('deleted_at')->first();
        } else {
            $request->session()->flash("status", "Access not allowed!");
            return redirect('/');
            exit;
        }

        if (!$campaign) {
            $request->session()->flash("status", "Campaign not found!");
            return redirect('/campaigns');
        }

        return view('campaigns.watch', ['campaign' => $campaign]);

    }

    public function view(Request $request, $id)
    {
        ## if a user is a replier, they should go directly to inbox
        if (Auth::user()->can('reply')) {
            return redirect(route('campaigns.inbox', ['id' => $id]));
        }

        if (Auth::user()->hasRole('administrator')) {
            $campaign = Campaign::where('id', $id)
                ->first();
        } elseif (Auth::user()->hasRole('manager')) {
            $campaign = Campaign::where('id', $id)
                ->where('company_id', Auth::user()->company_id)
                ->first();
        } else {
            $request->session()->flash("status", "Access not allowed!");
            return redirect('/');
        }

        if (!$campaign) {
            $request->session()->flash("status", "Campaign not found!");
            return redirect('/campaigns');
        }

        $my_rights = Right::with('Company')
            ->where('rights.company_id', $campaign->company_id)
            ->orderBy('name')
            ->get();

				$messaging_services = MessagingService::where('company_id', $campaign->company_id)
					->doesntHave('Campaign')
					->get();

        $tags = Tag::getWithType('company' . $campaign->company_id)->pluck('name');

        $day_list = DateTimeHelper::DayList();
        $time_list = DateTimeHelper::TimeList();

        $contact_tags = [];
        foreach ($tags as $key => $tag) {
            $count = Contact::where('company_id', $campaign->company_id)
                ->withAnytags([$tag])
                ->count();

            $contact_tags[] = [
                'tag' => $tag,
                'data' => sprintf('%s - %s contacts', $tag, $count)
            ];
        }

        $selected_tags = array();
        $campaign_tags = CampaignTag::where('campaign_id', $id)->get();
        foreach ($campaign_tags as $campaign_tag) {
            $selected_tags[] = $campaign_tag->tag;
        }

        $selected_rights = array();
        $campaign_rights = CampaignRight::where('campaign_rights.campaign_id', $id)->get();
        foreach ($campaign_rights as $campaign_right) {
            $selected_rights[] = $campaign_right->right_id;
        }
        $campaign_delivery_rules = CampaignDeliveryRule::where('campaign_id', $id)->get();

        $companies = Company::orderBy('company_name')->where('status', 1)->get();
        return view('campaigns.view', [
            'campaign' => $campaign,
            'companies' => $companies,
            'my_rights' => $my_rights,
            'contact_tags' => $contact_tags,
            'selected_tags' => $selected_tags,
            'selected_rights' => $selected_rights,
            'time_list' => $time_list,
            'day_list' => $day_list,
            'campaign_delivery_rules' => $campaign_delivery_rules,
					'messaging_services' => $messaging_services
        ]);
    }

    public function delete(Request $request, $id)
    {

        if (Auth::user()->hasRole('administrator')) {
            $contact = Contact::where('id', $id)
                ->withTrashed()
                ->first();
        } elseif (Auth::user()->hasRole('manager')) {
            $contact = Contact::where('id', $id)
                ->where('company_id', Auth::user()->company_id)
                ->first();
        } else {
            $request->session()->flash("status", "Access not allowed!");
            return redirect('/');
        }

        if (!$contact) {
            $request->session()->flash("status", "Contact not found!");
            return redirect('/contacts');
        }

        $contact->delete();

        $request->session()->flash("status", "Contact deleted successfully!");
        return redirect('/contacts');
    }

    private function _saveImage($incoming_file, $campaign_id = 0, $num = 1)
    {
        if (@$incoming_file) {
            if (@$campaign_id) {
                $uploaded_file = $incoming_file;
                $save_name = "mms_" . $num . "." . $uploaded_file->extension();
                $save_as = $campaign_id . "/" . $save_name;

                $img = Image::make($uploaded_file)->orientate();
                $img->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img_big = $img->stream(null, 90);

                Storage::disk('s3')->put($save_as, $img_big->__toString(), 'public');
                $image_url = Storage::disk('s3')->url($save_as);

                $campaign = Campaign::where('id', $campaign_id)->first();
                $campaign->{'content_media_' . $num} = $image_url;
                $campaign->save();
            }
        }
    }

    public function save(Request $request)
    {

        if (Auth::user()->hasRole('administrator')) {
            $company_id = $request->get('company_id');
        } elseif (Auth::user()->hasRole('manager')) {
            $company_id = Auth::user()->company_id;
        } else {
            $request->session()->flash("status", "Access not allowed!");
            return redirect('/');
        }

        $this->validate($request, [
            'campaign_name' => 'required',
            'zipcode' => 'required',
            'areacode' => 'required',
            'content_template_1' => 'required',
        ]);

        $phone = str_replace('-', '', filter_var($request->get('nearphone'), FILTER_SANITIZE_NUMBER_INT));
        $stripped_phone = str_replace("+1", "", $phone);
        $stripped_phone = str_replace("+", "", $stripped_phone);
        $stripped_phone = ltrim($stripped_phone, '0');
        $stripped_phone = ltrim($stripped_phone, '1');
        $stripped_phone = trim(preg_replace("/[^0-9]/", "", $stripped_phone));

        $campaign_uuid = Uuid::generate()->string;

        DB::beginTransaction();

        try {

            $campaign = new Campaign();
            $campaign->uuid = $campaign_uuid;
            $campaign->company_id = $company_id;
            $campaign->messaging_service_id = 0;
            $campaign->campaign_status = 0;
            $campaign->campaign_type = $request->get('campaign_type');
            $campaign->campaign_name = $request->get('campaign_name');
            $campaign->description = $request->get('description');
            $campaign->content_template_1 = trim($request->get('content_template_1'));
            $campaign->content_template_2 = trim($request->get('content_template_2'));
            $campaign->content_template_3 = trim($request->get('content_template_3'));
            $campaign->content_template_4 = trim($request->get('content_template_4'));
            $campaign->conversion_link_1 = trim($request->get('conversion_link_1'));
            $campaign->conversion_link_2 = trim($request->get('conversion_link_2'));
            $campaign->conversion_link_3 = trim($request->get('conversion_link_3'));
            $campaign->conversion_link_4 = trim($request->get('conversion_link_4'));

            if ($request->has('messaging_service') && $request->get('messaging_service') != 'none') {
            	$campaign->messaging_service_id = $request->get('messaging_service');
						}

            $campaign->zipcode = $request->get('zipcode');
						$campaign->areacode = $request->get('areacode');
						$campaign->nearphone = $stripped_phone;

            $campaign->rights_type = $request->get('rights_type');
            $campaign->tags = explode(",", $request->get('tags'));
            $campaign->created_by = Auth::user()->id;
            $campaign->save();

            if ($request->has('tags_list')) {
                ## get a list of contacts for each tag
                foreach ($request->get('tags_list') as $key => $tag) {
                    $campaignTag = new CampaignTag();
                    $campaignTag->campaign_id = $campaign->id;
                    $campaignTag->tag = $tag;
                    $campaignTag->save();
                    /*$contact = new CampaignContact();
                    $contact->uuid = Uuid::generate()->string;
                    $contact->campaign_id = $campaign->id;
                    $contact->contact_id = $contact_id;
                    $contact->cc_status = 0;
                    $contact->content_option = 0;
                    $contact->save();*/
                }
            }

            if ($request->has('rights_type') && $request->get('rights_type') != 1) {
                foreach ($request->get('rights_list') as $right_id) {
                    $right = new CampaignRight();
                    $right->campaign_id = $campaign->id;
                    $right->right_id = $right_id;
                    $right->save();
                }
            }

            if ($request->has('day')) {
                foreach ($request->input('day') as $key => $day) {
                    $whole_day = isset($request->input('whole_day')[$day]) ? $request->input('whole_day')[$day] == 1 : false;
                    $from_time = isset($request->input('from_time')[$day]) ? intval($request->input('from_time')[$day]) : null;
                    $to_time = isset($request->input('to_time')[$day]) ? intval($request->input('to_time')[$day]) : null;
                    if (!$whole_day) {
                        if (!$from_time || !$to_time || ($from_time && $to_time && $from_time >= $to_time)) {
                            return redirect()->back()->withErrors(['Make sure you have chosen correct from and to time. To time cannot be smaller than from time.']);
                        }
                    }
                    $campaignDeliveryRule = new CampaignDeliveryRule();
                    $campaignDeliveryRule->campaign_id = $campaign->id;
                    $campaignDeliveryRule->day = $day;
                    $campaignDeliveryRule->whole_day = $whole_day;
                    $campaignDeliveryRule->from_time = $from_time;
                    $campaignDeliveryRule->to_time = $to_time;
                    $campaignDeliveryRule->save();
                }
            }

            for ($i = 1; $i < 5; $i++) {
                if (@$request->hasFile('file_upload_' . $i)) {
                    $this->_saveImage($request->file('file_upload_' . $i), $campaign->id, $i);
                }
            }

            DB::commit();
            $request->session()->flash("status", "Campaign created successfully!");
            return redirect('/campaigns');

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            throw $exception;
        }
    }

    public function completed(Request $request)
    {
        return view('campaigns.completed');
    }

    public function completedTable(Request $request)
    {
        $query = Campaign::with('Company')
            ->where('campaign_status', 50);   ## 50 = completed

        return Datatables::of($query)
            ->editColumn('id', 'campaigns.completed_id')
            ->editColumn('campaign_type', function (Campaign $campaign) {
                return campaign_type($campaign->campaign_type);
            })
            ->editColumn('rollup_completed', function (Campaign $campaign) {
                return sprintf('%s / %s', $campaign->rollup_total, $campaign->rollup_completed);
            })
            ->editColumn('duration', function (Campaign $campaign) {
                return $campaign->updated_at->diffAsCarbonInterval($campaign->created_at)->cascade()->forHumans();
            })
            ->editColumn('button', 'campaigns.button')
            ->rawColumns(['id', 'button'])
            ->make(true);
    }

    public function completedView(Request $request, $id)
    {
        ## if a user is a replier, they should go directly to inbox
        if (Auth::user()->can('reply'))
            return redirect(route('campaigns.inbox', ['id' => $id]));

        $campaign = Campaign::find($id);

        return view('campaigns.completed_view')->with(compact('campaign'));
    }

    public function inbox(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($id);
        $chats = Chat::where('campaign_id', $id)->where('overall_status', '<', 10)->orderBy('updated_at', 'desc')->get();
        $customReplies = CustomReply::where('company_id', $campaign->company_id)->pluck('reply_name', 'id');

        return view('campaigns.inbox')->with(compact('campaign', 'chats', 'customReplies'));
    }

    public function pause(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($id);

        $campaign->campaign_status = 30;
        $campaign->save();

        $request->session()->flash("status", "Campaign paused successfully!");
        return redirect(route('campaigns.view', ['id' => $id]));
    }

    public function resume(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($id);

        $campaign->campaign_status = 20;
        $campaign->save();

        $request->session()->flash("status", "Campaign resumed successfully!");
        return redirect(route('campaigns.view', ['id' => $id]));
    }

    public function ajax_get_custom_reply(Request $request)
    {
        $replyId = $request->get('reply_id');
        $reply = CustomReply::where('id', $replyId)->first();

        if ($reply) {
            $body = $reply->reply_body;

            $chat = Chat::where('uuid', $request->get('chat_uuid'))->first();

            if ($chat) {
                $company = Company::where('id', $chat->company_id)->first();

                if ($company) {
                    $matches = preg_match_all("/\[\[(.*?)\]\]/", $body, $fields);

                    if ($matches > 0) {
                        $replyFields = customReplyFields();

                        foreach ($fields[1] as $k => $field) {
                            if (array_key_exists($field, $replyFields) === true) {
                                $data = explode('.', $field);
                                $replaceField = $data[1];

                                $replace = '';
                                if ($data[0] == 'contacts') {
                                    $replace = $chat->Contact->$replaceField;
                                } elseif ($data[0] == 'companies') {
                                    $replace = $company->$replaceField;
                                }
                                $body = str_replace('[[' . $field . ']]', $replace, $body);
                            }
                        }

                        return $body;
                    }
                }
            }
        }

        return '';
    }

    public function numbers_add(Request $request)
    {
        if ($request->has('add_nums') && $request->has('campaign_id')) {
            $campaignId = $request->get('campaign_id');
            $lines = $request->get('add_nums');

            $campaign = Campaign::findOrFail($campaignId);

            if ($lines > 0) {
                $twilio = new Client(env('SMS_SID'), env('SMS_TOKEN'));

                $numbers = $twilio->availablePhoneNumbers('US')->local->read(array("areaCode" => $campaign->area_code));
                for ($i = 1; $i <= $lines; $i++) {
                    if (@$numbers[$i - 1]->phoneNumber) {
                        $new_number = $twilio->incomingPhoneNumbers->create(array("phoneNumber" => $numbers[$i - 1]->phoneNumber));
                        if (@$new_number->sid) {
                            $added_phone = $twilio->messaging->v1->services($campaign->MessagingService->sid)->phoneNumbers->create($new_number->sid);
                            $msNo = new MessagingServiceNumber();
                            $msNo->messaging_service_id = $campaign->MessagingService->id;
                            $msNo->number = $numbers[$i - 1]->phoneNumber;
                            $msNo->save();
                        }
                    }
                }
            }

            $request->session()->flash('status', 'Added new numbers!');
            return redirect(route('campaigns.view', ['id' => $campaign->id]));
        } else {
            $request->session()->flash("status", "Invalid request!");
            return redirect(route('campaigns.index'));
        }
    }

	public function archive(Request $request, Campaign $campaign)
	{
		$campaign->messaging_service_id = 0;   ## free the messaging service so a different campaign can use it
		$campaign->campaign_status = 99;
		$campaign->save();

		$request->session()->flash('status', 'Campaign Archived');
		return redirect(route('campaigns.completed'));
	}
}
