<?php

namespace peertxt\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use peertxt\models\Campaign;
use peertxt\models\Chat;
use peertxt\models\ChatThread;
use peertxt\models\Contact;
use peertxt\models\MessagingService;
use Twilio\Rest\Client;
use Exception;
use Illuminate\Support\Facades\Log;

class SendReply implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	protected $thread_id;

	public function __construct($thread_id)
	{
		$this->thread_id = $thread_id;
	}

	public function handle()
	{
		if ( ($this->thread_id) ) {

			$chat_thread = ChatThread::where('id', $this->thread_id)->first();

			if ($chat_thread) {
				Log::info('yes chat_thread  ... ');
				$chat = Chat::where('id', $chat_thread->chat_id)->first();
				if ($chat) {
					Log::info('yes chat  ... ');
					$campaign = Campaign::where('id', $chat->campaign_id)->first();
					if ($campaign) {
						Log::info('yes campaign  ... ');
						$contact = Contact::where('id', $chat->contact_id)->first();
						if ($contact) {
							Log::info('yes contact  ... ');
							$messaging_service = MessagingService::where('id', $campaign->messaging_service_id)->first();
							if ($messaging_service) {
								Log::info('yes messaging_service  ... ');

								$client = new Client(env('SMS_SID'), env('SMS_TOKEN'));
								try {

									$content = array(
										'messagingServiceSid' => $messaging_service->sid,
										'body' => trim($chat_thread->message)
									);
//									if (trim($chat_thread->media_url)) {
//										$content["mediaUrl"] = array(trim($chat_thread->media_url));
//									}

									$resp = $client->messages->create(
										$contact->phone,
										$content
									);
									Log::info('Jobs\SendReply: message_sid : ' . $resp->sid);

									if (@$resp->sid) {
										$chat_thread->status = 2;
										$chat_thread->sms_sid = $resp->sid;
										$chat_thread->audit_sms_sent = microtime();
										$chat_thread->save();
									} else {
										Log::error('Jobs\SendReply error: no response message id received!');
										$chat_thread->status = -1;
										$chat_thread->save();
									}

								} catch(Exception $e) {
									Log::error('Jobs\SendReply error: message_service_id[' . $messaging_service->id . '] ' . $e->getCode() . ' : ' . $e->getMessage());
									$chat_thread->status = -1;
									$chat_thread->save();
								}

							}
						}
					}
				}
			}

		}

	}

}
