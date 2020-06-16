<?php

namespace peertxt\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use peertxt\models\Campaign;
use peertxt\models\CampaignReplyNotification;
use peertxt\models\Chat;
use peertxt\models\ChatThread;
use peertxt\models\Company;
use peertxt\models\Contact;
use peertxt\models\MessagingService;
use peertxt\models\SmsConvo;
use peertxt\models\SmsConvoScript;
use peertxt\models\SmsConvoThreadReply;
use peertxt\models\SmsData;
use peertxt\SmsConvoThread;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Twilio\Rest\Client;
use Twilio\Twiml;
use Webpatser\Uuid\Uuid;

class SmsController extends Controller {

	public function __construct() {
	}

	public function convo_hello(Request $request) {

		if ( ($request->get('convo_id')) && ($request->get('phone')) ) {

			$phone = str_replace('-', '', filter_var($request->get('phone'), FILTER_SANITIZE_NUMBER_INT));
			$stripped_phone = str_replace("+1", "", $phone);
			$stripped_phone = str_replace("+", "", $stripped_phone);
			$stripped_phone = trim(preg_replace("/[^0-9]/", "", $stripped_phone));

			// get the sms_convo data..
			$convo = SmsConvo::where('id', $request->get('convo_id'))->first();
			if ($convo) {
				if ($convo->all_locations == 1) {
					// get this clients default mg..
					$ms = MessagingService::where('id', $convo->client_id)->first();
				} else {
					// TODO: need to get the mg for this specific location
				}

				$msg_service = $ms->sid;

				// we should return the 'welcome' message with the trigger code included.
				$reply = $convo->welcome . " - To continue, reply with " . strtoupper($convo->trigger);

				$client = new Client(env('SMS_SID'), env('SMS_TOKEN'));
				$resp = $client->messages->create(
					$stripped_phone,
					array(
						'messagingServiceSid' => $msg_service,
						'body' => trim($reply)
					)
				);

//				$convo_opener = DB::table('sms_convo_scripts')->where('sms_convo_id', $request->get('convo_id'))->where('step', 1)->first();
//
//				$my_thread_id = DB::table('sms_convo_threads')->insertGetId(
//					[
//						'sms_convo_id' => $request->get('convo_id'),
//						'messaging_service_sid' => $msg_service,
//						'sms_message_sid' => '',
//						'from' => '',
//						'to' => $stripped_phone,
//						'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//						'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
//					]
//				);
			}

		}

		return redirect($request->get('redirect'));

	}

	private function check_keywords() {

	}

	public function incoming_sms(Request $request) {

		$userInput = $request->all();
		foreach ($userInput as $k=>$v) {
			Log::info('incoming_sms ... ' . $k . ' -> ' . $v);
		}

		$raw_incoming_message = trim($request->Body);
		$incoming_message = strtolower(trim($request->Body));
		$messaging_service_sid = $request->MessagingServiceSid;
		$phone_from = $request->From;
		$phone_to = $request->To;
		$sms_message_sid = $request->SmsMessageSid;
		$message_sid = $request->MessageSid;

		$clean_phone = str_replace("+1", "", $phone_from);
		$clean_phone = str_replace("+", "", $clean_phone);
		$clean_phone_to = str_replace("+1", "", $phone_to);
		$clean_phone_to = str_replace("+", "", $clean_phone_to);

		// get company_id - if we dont have one, skip processing as we dont know what they are trying to do..
		// in addition, we need to add this as an incoming message to chats; we may not have a thread yet but that doesnt matter, its a new conversation then..

		$ms_check = MessagingService::where('sid', $messaging_service_sid)->first();

		if (@$ms_check->company_id) {

			// check if we have an active campaign for this messaging service
			$campaign_check = Campaign::where('company_id', $ms_check->company_id)->where('messaging_service_id', $ms_check->id)->whereIn('campaign_status', [20, 21, 50])->first();
			if ($campaign_check) {
				// if we do, check if we have a contact for this campaign
				$contact_check = Contact::where('phone', $clean_phone)->where('company_id', $ms_check->company_id)->where('status', 1)->first();
				if ($contact_check) {
					// now, we need to check if we already have a chat record for this conversation; if we don't we need to create one..
					// if we do, we need to use that (and set a cookie)
					// we can also check if we have a cookie for the conversation since its already a trusted conversation..

					$chat_id = 0;

					$chat_check = Chat::where('company_id', $ms_check->company_id)->where('campaign_id', $campaign_check->id)->where('contact_id', $contact_check->id)->where('overall_status', '<', 10)->first();
					if ($chat_check) {
						// already exists, go to processing thread..
						$chat_id = $chat_check->id;
						$chat_check->overall_status = 1;
						$chat_check->save();
					} else {
						// need a chat, then go to processing thread..
						$chat_data = new Chat();
						$chat_data->uuid = Uuid::generate()->string;
						$chat_data->company_id = $ms_check->company_id;
						$chat_data->campaign_id = $campaign_check->id;
						$chat_data->contact_id = $contact_check->id;
						$chat_data->overall_status = 1;
						$chat_data->save();
						$chat_id = $chat_data->id;
					}

					if ($chat_id) {
						// save the thread..
						$chat_thread = new ChatThread();
						$chat_thread->uuid = Uuid::generate()->string;
						$chat_thread->chat_id = $chat_id;
						$chat_thread->direction = 1; // in
						$chat_thread->status = 1; // received
						$chat_thread->subject = null;
						$chat_thread->message = $raw_incoming_message;
						$chat_thread->private_notes = null;
						$chat_thread->media_url = null;
						$chat_thread->user_id = 0;
						$chat_thread->audit_sms_rcvd = microtime();
						$chat_thread->audit_sms_sent = null;
						$chat_thread->sms_sid = $sms_message_sid;
						$chat_thread->save();

						// now check the keyword; it may be a magic that needs a trigger..
						// unsubscribe ////////////////////////////////////////////////////////
						if (($incoming_message == "unsubscribe") || ($incoming_message == "remove") || ($incoming_message == "stop")) {

							Log::info('incoming_sms ... unsubscribe = ' . $clean_phone);

							$chat_update = Chat::where('id', $chat_id)->first();
							$chat_update->overall_status = 9;
							$chat_update->save();

							$contact_update = Contact::where('id', $contact_check->id)->first();
							$contact_update->sms_stopped = 1;
							$contact_update->save();

							// restart (resubscribe) //////////////////////////////////////////////
						} elseif (($incoming_message == "unstop") || ($incoming_message == "start")) {

							Log::info('incoming_sms ... start = ' . $clean_phone);

							$chat_update = Chat::where('id', $chat_id)->first();
							$chat_update->overall_status = 1;
							$chat_update->save();

							$contact_update = Contact::where('id', $contact_check->id)->first();
							$contact_update->sms_stopped = 0;
							$contact_update->save();

						}

						## track the reply for notifications
						$notify = CampaignReplyNotification::where('campaign_id', $campaign_check->id)
							->where('user_id', $campaign_check->created_by)
							->where('in_use', false)
							->first();
						if ($notify) {
							## update the count on this record
							$notify->reply_count = $notify->reply_count + 1;
							$notify->save();
						} else {
							## create a new record
							$notify = new CampaignReplyNotification();
							$notify->campaign_id = $campaign_check->id;
							$notify->user_id = $campaign_check->created_by;
							$notify->reply_count = 1;
							$notify->save();
						}
					}

				}

			}

		}

	}

	private function _magic_tags($body, $_company, $_subscriber, $_subscriber_catch) {
		$message = $body;
		if ($_company) {
			$message = str_replace("[[LocationName]]", $_company->company_name, $message);
		} else {
			$message = str_replace("[[LocationName]]", "", $message);
		}
		if ($_subscriber) {
			$message = str_replace("[[FullName]]", ((trim($_subscriber->last_name)) ? $_subscriber->first_name . " " . $_subscriber->last_name : $_subscriber->first_name), $message);
			$message = str_replace("[[FirstName]]", $_subscriber->first_name, $message);
			$message = str_replace("[[Email]]", $_subscriber->email, $message);
		} else {
			$message = str_replace("[[FullName]]", "", $message);
			$message = str_replace("[[FirstName]]", "", $message);
			$message = str_replace("[[Email]]", "", $message);
		}
		return $message;
	}

	private function _handle_convo_event($convo_id, $my_thread_id, $my_step, $messaging_service_sid, $sms_message_sid, $clean_phone, $clean_phone_to, $company_info) {

		Log::info('FIRE: _handle_convo_event(' . $convo_id . ',' . $my_thread_id . ',' . $my_step . ',' . $messaging_service_sid . ',' . $sms_message_sid . ',' . $clean_phone . ',' . $clean_phone_to . ',' . 'array()' . ')');

		$has_thread = 0;

		if ($my_thread_id) {
			// check thread
			$thread_info = SmsConvoThread::where('id', $my_thread_id)->first();
			if ($thread_info) {
				$has_thread = 1;
			}
		}

		if ($has_thread == 0) {
			// create new thread
            $thread_info = new SmsConvoThread();
            $thread_info->sms_convo_id = $convo_id;
            $thread_info->messaging_service_sid = $messaging_service_sid;
            $thread_info->sms_message_sid = $sms_message_sid;
            $thread_info->from = $clean_phone;
            $thread_info->to = $clean_phone_to;
            $thread_info->save();

            //$thread_info = DB::table('sms_convo_threads')->where('id', $convoThread->id)->first();
		}

		if ($my_step) {
			// try to get the next step..
			$script_info = SmsConvoScript::where('sms_convo_id', $thread_info->sms_convo_id)
                ->where('step', '>=', $my_step)
                ->first();
		} else {
			// get step 1..
			$script_info = SmsConvoScript::where('sms_convo_id', $thread_info->sms_convo_id)
                ->where('step', '>=', 1)
                ->first();
		}

		if ($script_info) {
			$_subscriber = SmsData::where('company_id', $company_info->id)->where('from', $clean_phone)->first();
			$_company = $company_info;
			$_subscriber_catch = array();

			$message = $this->_magic_tags($script_info->script_body, $_company, $_subscriber, $_subscriber_catch);
			Log::info('$message = ' . $message);

			$response = new Twiml;
			$response->message($message);

			return array($response, $thread_info, $script_info);
		} else {
			Log::info('$script_info = false');
		}

	}


}
