<?php

namespace peertxt\Http\Controllers;

use http\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use peertxt\models\Company;
use peertxt\models\MessagingService;
use peertxt\models\MessagingServiceNumber;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Cache;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ToolController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware(['admin', 'auth']);
    }

    public function index()
    {
    }

    // companies /////////////////////////////////////////////////////////////////////

    public function companies_index()
    {
        $companies = Company::with('parent_company')->orderBy('company_name')
            ->get();
        $parentCompanies = Company::whereDoesnthave('parent_company')
//            ->where('status', 1)
            ->get();


        return view('tools.companies.index', ['companies' => $companies, 'parentCompanies' => $parentCompanies]);
    }

    public function companies_view(Request $request, $id)
    {
        $company = Company::with('parent_company')->where('id', $id)->first();
        $isParent = false;
        if ($company->child_companies->count()) {
        $isParent = true;
            $companies = [];
        } else {

            $companies = Company::where('id', '!=', $id)
                ->whereDoesnthave('parent_company')
//            ->where('status', 1)
                ->get();
        }

        return view('tools.companies.view', ['company' => $company, 'companies' => $companies, 'isParent' => $isParent]);
    }

    public function companies_save(Request $request)
    {

        $this->validate($request, [
            'company_name' => 'required|unique:companies,company_name'
        ]);

        $phone = str_replace('-', '', filter_var($request->get('default_nearphone'), FILTER_SANITIZE_NUMBER_INT));
        $stripped_phone = str_replace("+1", "", $phone);
        $stripped_phone = str_replace("+", "", $stripped_phone);
        $stripped_phone = ltrim($stripped_phone, '0');
        $stripped_phone = ltrim($stripped_phone, '1');
        $stripped_phone = trim(preg_replace("/[^0-9]/", "", $stripped_phone));

        $company = new Company();
        $company->status = $request->get('status');
        $company->parent_company_id = $request->get('parent_company_id');
        $company->company_name = $request->get('company_name');
        $company->default_zipcode = $request->get('default_zipcode');
        $company->default_areacode = $request->get('default_areacode');
        $company->default_nearphone = $stripped_phone;
        $company->save();

        $request->session()->flash("status", "Company created successfully!");
        return redirect('/tools/companies');
    }

    public function companies_update(Request $request, $id)
    {

        $phone = str_replace('-', '', filter_var($request->get('default_nearphone'), FILTER_SANITIZE_NUMBER_INT));
        $stripped_phone = str_replace("+1", "", $phone);
        $stripped_phone = str_replace("+", "", $stripped_phone);
        $stripped_phone = ltrim($stripped_phone, '0');
        $stripped_phone = ltrim($stripped_phone, '1');
        $stripped_phone = trim(preg_replace("/[^0-9]/", "", $stripped_phone));

        $company = Company::where('id', $id)->first();

        $company->status = $request->get('status');
        $company->parent_company_id = $request->get('parent_company_id');
        $company->company_name = $request->get('company_name');
        $company->default_zipcode = $request->get('default_zipcode');
        $company->default_areacode = $request->get('default_areacode');
        $company->default_nearphone = $stripped_phone;
        $company->save();

        $request->session()->flash("status", "Company updated successfully!");
        return redirect('/tools/companies');
    }

    // /companies ////////////////////////////////////////////////////////////////////

    // roles ////////////////////////////////////////////////////////////////////////

    public function roles_index()
    {
        $roles = Role::orderBy('id', 'DESC')->paginate(50);
        return view('tools.roles.index', ['roles' => $roles]);
    }

    public function roles_view(Request $request, $id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("auth_role_has_permissions", "auth_role_has_permissions.permission_id", "=", "auth_permissions.id")->where("auth_role_has_permissions.role_id", $id)->get();
        return view('tools.roles.view', compact('role', 'rolePermissions'));
    }

    public function roles_save(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|unique:auth_roles,name'
        ]);

        $role = Role::create(['name' => $request->input('name')]);

        $request->session()->flash("status", "Role created successfully!");
        return redirect('/tools/roles');
    }

    public function roles_update(Request $request, $id)
    {

        $this->validate($request, [
            'name' => 'required|unique:auth_roles,name,' . $id
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->guard_name = $request->input('guard_name');
        $role->save();


        $request->session()->flash("status", "Role updated successfully!");
        return redirect('/tools/roles');
    }

    // /roles ///////////////////////////////////////////////////////////////////////

    // messaging services ///////////////////////////////////////////////////////////////

    public function messaging_services_index()
    {
        $companies = Company::orderBy('company_name')->where('status', 1)->get();
        $messaging_services = MessagingService::with('Company')
            ->orderBy('name', 'asc')
            ->get();
        return view('tools.messaging_services.index', ['messaging_services' => $messaging_services, 'companies' => $companies]);
    }

    public function messaging_services_view(Request $request, $id)
    {
        $companies = Company::orderBy('company_name')->where('status', 1)->get();
        $messaging_service = MessagingService::where('id', $id)->first();
        $messaging_service_numbers = MessagingServiceNumber::where('messaging_service_id', $messaging_service->id)->get();
        return view('tools.messaging_services.view', ['messaging_service' => $messaging_service, 'messaging_service_numbers' => $messaging_service_numbers, 'companies' => $companies]);
    }

    public function messaging_services_save(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'company_id' => 'required'
        ]);

        $company = Company::where('id', $request->get('company_id'))->first();
        if ($company) {

            $twilio = new Client(env('SMS_SID'), env('SMS_TOKEN'));
            $ms_name = $company->company_name . " - " . $request->get('name');
            $add_service = $twilio->messaging->v1->services->create($ms_name, array('InboundRequestUrl' => 'https://' . env('APP_DOMAIN', 'www.peertxt.co') . '/sms/incoming_sms'));

            $ms = new MessagingService();
            $ms->company_id = $request->get('company_id');
            $ms->sid = $add_service->sid;
            $ms->name = $request->get('name');
            $ms->save();

            $lines = $request->get('add_nums');

            if ($lines > 0) {
                $numbers = $twilio->availablePhoneNumbers('US')->local->read(array("areaCode" => $request->get('area_code')));
                for ($i = 1; $i <= $lines; $i++) {
                    if (@$numbers[$i - 1]->phoneNumber) {
                        $new_number = $twilio->incomingPhoneNumbers->create(array("phoneNumber" => $numbers[$i - 1]->phoneNumber));
                        if (@$new_number->sid) {
                            $added_phone = $twilio->messaging->v1->services($add_service->sid)->phoneNumbers->create($new_number->sid);
                            $msNo = new MessagingServiceNumber();
                            $msNo->messaging_service_id = $ms->id;
                            $msNo->number = $numbers[$i - 1]->phoneNumber;
                            $msNo->save();
                        }
                    }
                }
            }

        }

        $request->session()->flash("status", "Messaging Service created successfully!");
        return redirect('/tools/messaging_services');
    }

    public function messaging_services_update(Request $request, $id)
    {
        $ms = MessagingService::where('id', $id)->first();
        $ms->company_id = $request->get('company_id');
        $ms->save();

        $request->session()->flash("status", "Messaging Service updated successfully!");
        return redirect('/tools/messaging_services');
    }

    public function messaging_services_add_number(Request $request)
    {

        if ($request->get('area_code')) {
            $messaging_service = MessagingService::where('id', $request->get('messaging_service_id'))->first();

            $twilio = new Client(env('SMS_SID'), env('SMS_TOKEN'));
            $numbers = $twilio->availablePhoneNumbers('US')->local->read(array("areaCode" => $request->get('area_code')));
            if (@$numbers[0]->phoneNumber) {
                $new_number = $twilio->incomingPhoneNumbers->create(array("phoneNumber" => $numbers[0]->phoneNumber));
                if (@$new_number->sid) {
                    $added_phone = $twilio->messaging->v1->services($messaging_service->sid)->phoneNumbers->create($new_number->sid);
                    $msNo = new MessagingServiceNumber();
                    $msNo->messaging_service_id = $messaging_service->id;
                    $msNo->number = $numbers[0]->phoneNumber;
                    $msNo->save();
                }

                $request->session()->flash("status", "Phone Number added successfully!");
                return redirect('/tools/messaging_services/view/' . $request->get('messaging_service_id'));

            } else {
                // couldnt add it, throw a message..
                $request->session()->flash("status", "ERROR - Could not find phone number in area code provided.");
                return redirect('/tools/messaging_services/view/' . $request->get('messaging_service_id'));
            }
        }
        exit;

    }

    // /messaging services //////////////////////////////////////////////////////////////


    // sms convo ////////////////////////////////////////////////////////////////////////

    public function sms_conversations_index()
    {
        ## TODO: is there supposed to be a clients table???
        $clients = DB::table('clients')->orderBy('name')->where('status', 1)->get();
        $messaging_services = MessagingService::orderBy('name', 'asc')->get();
        $sms_conversations = DB::table('sms_convos')->leftJoin('clients', 'clients.id', '=', 'sms_convos.client_id')->select('sms_convos.*', 'clients.name as client_name')->orderBy('id', 'desc')->get();

        foreach ($sms_conversations as &$sms_conversation) {
            $count_threads = DB::table('sms_convo_threads')->where('sms_convo_id', $sms_conversation->id)->count();
            $count_client_users = DB::table('sms_data')->where('client_id', $sms_conversation->client_id)->where('status', 1)->count();
            $sms_conversation->count_threads = $count_threads;
            $sms_conversation->count_client_users = $count_client_users;
        }

        return view('tools.sms_conversations.index', ['clients' => $clients, 'messaging_services' => $messaging_services, 'sms_conversations' => $sms_conversations]);
    }

    public function sms_conversation_threads(Request $request, $id)
    {
        $sms_convo = DB::table('sms_convos')->where('id', $id)->first();
        $client = DB::table('clients')->where('id', $sms_convo->client_id)->first();
        $threads = DB::table('sms_convo_threads')->where('sms_convo_id', $sms_convo->id)->get();

        return view('tools.sms_conversations.threads', ['client' => $client, 'sms_convo' => $sms_convo, 'threads' => $threads]);
    }

    public function sms_conversation_thread_view(Request $request, $id)
    {
        $thread = DB::table('sms_convo_threads')->where('id', $id)->first();
        $sms_convo = DB::table('sms_convos')->where('id', $thread->sms_convo_id)->first();
        $client = DB::table('clients')->where('id', $sms_convo->client_id)->first();

        $out = array();
        $cnt = 0;

        $scripts = DB::table('sms_convo_scripts')->where('sms_convo_id', $thread->sms_convo_id)->orderBy('step', 'asc')->get();
        foreach ($scripts as $script) {
            $tmp = array();
            $script_reply = DB::table('sms_convo_thread_replies')->where('sms_convo_thread_id', $thread->id)->where('sms_convo_script_id', $script->id)->first();
            $tmp['q'] = $script->script_body;
            $out[] = $tmp;
            if (@$script_reply->reply_body) {
                $out[$cnt - 1]['r'] = $script_reply->reply_body;
                $out[$cnt - 1]['r_ts'] = $script_reply->created_at;
            }
            $cnt++;
        }

        return view('tools.sms_conversations.thread_view', ['client' => $client, 'sms_convo' => $sms_convo, 'thread' => $thread, 'out' => $out]);
    }

    public function sms_conversation_users(Request $request, $id)
    {
        $sms_convo = DB::table('sms_convos')->where('id', $id)->first();
        $client = DB::table('clients')->where('id', $sms_convo->client_id)->first();
        $users = DB::table('sms_data')->where('client_id', $sms_convo->client_id)->get();

        return view('tools.sms_conversations.users', ['client' => $client, 'sms_convo' => $sms_convo, 'users' => $users]);
    }

    public function sms_conversations_edit_script(Request $request, $id)
    {
        $sms_script = DB::table('sms_convo_scripts')->where('id', $id)->first();
        $sms_convo = DB::table('sms_convos')->where('id', $sms_script->sms_convo_id)->first();
        return view('tools.sms_conversations.edit_script', ['sms_script' => $sms_script, 'sms_convo' => $sms_convo]);
    }

    public function sms_conversations_view(Request $request, $id)
    {
        $clients = DB::table('clients')->orderBy('name')->where('status', 1)->get();
        $selected_ms = array();
        $sms_convo = DB::table('sms_convos')->where('id', $id)->first();
        if ($sms_convo) {
            $sms_convo_messaging_services = DB::table('sms_convo_messaging_services')->where('sms_convo_id', $sms_convo->id)->get();
            foreach ($sms_convo_messaging_services as $sms_convo_messaging_service) {
                $selected_ms[] = $sms_convo_messaging_service->messaging_service_id;
            }
        } else {
            $request->session()->flash("status", "SMS Convo not found!");
            return redirect('/tools/sms_conversations');
        }
        $messaging_services = DB::table('messaging_services')->where('client_id', $sms_convo->client_id)->orderBy('name', 'asc')->get();

        // get scripts
        $sms_convo_scripts = DB::table('sms_convo_scripts')->where('sms_convo_id', $sms_convo->id)->orderBy('step', 'asc')->get();

        return view('tools.sms_conversations.view', ['clients' => $clients, 'sms_convo_scripts' => $sms_convo_scripts, 'selected_ms' => $selected_ms, 'messaging_services' => $messaging_services, 'sms_convo' => $sms_convo, 'sms_convo_messaging_services' => $sms_convo_messaging_services]);
    }

    public function sms_conversations_update_script(Request $request, $id)
    {
        $this->validate($request, [
            'script_body' => 'required'
        ]);

        $sms_script = DB::table('sms_convo_scripts')->where('id', $id)->first();
        $sms_convo = DB::table('sms_convos')->where('id', $sms_script->sms_convo_id)->first();

        DB::table('sms_convo_scripts')
            ->where('id', $id)
            ->update([
                    'script_body' => $request->get('script_body'),
                    'data_destination' => $request->get('data_destination'),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ]
            );

        $request->session()->flash("status", "SMS Conversation Script updated successfully!");
        return redirect('/tools/sms_conversations/view/' . $sms_convo->id . '?' . rander(10));
    }

    public function sms_conversations_delete(Request $request, $id)
    {
        DB::table('sms_convos')->where('id', $id)->delete();
        $request->session()->flash("status", "SMS Conversation deleted successfully!");
        return redirect('/tools/sms_conversations/?' . rander(10));
    }

    public function sms_conversations_delete_script(Request $request, $id)
    {
        $sms_script = DB::table('sms_convo_scripts')->where('id', $id)->first();
        $sms_convo = DB::table('sms_convos')->where('id', $sms_script->sms_convo_id)->first();

        DB::table('sms_convo_scripts')->where('id', $id)->delete();
        $request->session()->flash("status", "SMS Conversation Script deleted successfully!");
        return redirect('/tools/sms_conversations/view/' . $sms_convo->id . '?' . rander(10));
    }

    public function sms_conversations_save_script(Request $request)
    {
        $this->validate($request, [
            'script_body' => 'required'
        ]);

        // get last step in this chain..
        $last_script_step = DB::table('sms_convo_scripts')->where('sms_convo_id', $request->get('sms_convo_id'))->orderBy('step', 'desc')->first();
        if ($last_script_step) {
            $next_step = $last_script_step->step + 1;
        } else {
            $next_step = 1;
        }

        $sms_convo_script_id = DB::table('sms_convo_scripts')->insertGetId(
            [
                'sms_convo_id' => $request->get('sms_convo_id'),
                'step' => $next_step,
                'script_body' => $request->get('script_body'),
                'data_destination' => $request->get('data_destination'),
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
            ]
        );

        $request->session()->flash("status", "SMS Conversation Script added successfully!");
        return redirect('/tools/sms_conversations/view/' . $request->get('sms_convo_id') . '?' . rander(10));
    }

    public function sms_conversations_save(Request $request)
    {

        $this->validate($request, [
            'trigger' => 'required'
        ]);

        $all_locs = 0;
        foreach ($request->get('messaging_services') as $msid) {
            if ($msid == -1) {
                $all_locs = 1;
            }
        }

        if ($all_locs == 1) {
            $sms_convo_id = DB::table('sms_convos')->insertGetId(
                [
                    'client_id' => trim(strtolower($request->get('client_id'))),
                    'trigger' => trim(strtolower($request->get('trigger'))),
                    'welcome' => trim(strtolower($request->get('welcome'))),
                    'all_locations' => 1,
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ]
            );
        } else {
            $sms_convo_id = DB::table('sms_convos')->insertGetId(
                [
                    'client_id' => trim(strtolower($request->get('client_id'))),
                    'trigger' => trim(strtolower($request->get('trigger'))),
                    'welcome' => trim(strtolower($request->get('welcome'))),
                    'all_locations' => 0,
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ]
            );
            foreach ($request->get('messaging_services') as $msid) {
                $sms_convo_messaging_service_id = DB::table('sms_convo_messaging_services')->insertGetId(
                    [
                        'sms_convo_id' => $sms_convo_id,
                        'messaging_service_id' => $msid,
                        'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                        'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                    ]
                );
            }
        }

        $request->session()->flash("status", "SMS conversation created successfully!");
        return redirect('/tools/sms_conversations');
    }

    public function sms_conversations_update(Request $request, $id)
    {

        $this->validate($request, [
            'trigger' => 'required'
        ]);

        $all_locs = 0;
        foreach ($request->get('messaging_services') as $msid) {
            if ($msid == -1) {
                $all_locs = 1;
            }
        }

        DB::table('sms_convo_messaging_services')->where('sms_convo_id', $id)->delete();

        if ($all_locs == 1) {
            DB::table('sms_convos')
                ->where('id', $id)
                ->update([
                        'trigger' => $request->get('trigger'),
                        'welcome' => $request->get('welcome'),
                        'all_locations' => 1,
                        'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                    ]
                );
        } else {
            DB::table('sms_convos')
                ->where('id', $id)
                ->update([
                        'trigger' => $request->get('trigger'),
                        'welcome' => $request->get('welcome'),
                        'all_locations' => 0,
                        'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                    ]
                );
            foreach ($request->get('messaging_services') as $msid) {
                $sms_convo_messaging_service_id = DB::table('sms_convo_messaging_services')->insertGetId(
                    [
                        'sms_convo_id' => $id,
                        'messaging_service_id' => $msid,
                        'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                        'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                    ]
                );
            }
        }

        $request->session()->flash("status", "SMS Conversation updated successfully!");
        return redirect('/tools/sms_conversations/view/' . $id . '?' . rander(10));
    }

    // /sms convo //////////////////////////////////////////////////////////////////////

}
