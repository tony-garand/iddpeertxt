<?php

namespace peertxt\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use peertxt\models\Campaign;
use peertxt\models\CampaignContact;
use peertxt\models\Contact;
use peertxt\models\LogOutgoing;
use peertxt\models\MessagingService;
use Twilio\Rest\Client;
use Exception;
use Illuminate\Support\Facades\Log;

class SendDirectText implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	protected $campaign_id;
	protected $campaign_contact_id;

	public function __construct($campaign_id, $campaign_contact_id)
	{
		$this->campaign_id = $campaign_id;
		$this->campaign_contact_id = $campaign_contact_id;
	}

	public function handle()
	{
		if ( ($this->campaign_id) && ($this->campaign_contact_id) ) {

			$campaign = Campaign::where('id', $this->campaign_id)->first();
			$campaign_contact = CampaignContact::where('id', $this->campaign_contact_id)->where('cc_status', 1)->first();

			if (($campaign) && ($campaign_contact)) {

				$contact = Contact::where('id', $campaign_contact->contact_id)->first();
				$messaging_service = MessagingService::where('id', $campaign->messaging_service_id)->first();

				if ( ($contact) && ($messaging_service) ) {

					// insert into log_outgoing to make sure we don't already have this..
					$log_id = 0;
					try {
						$log_id = DB::table('log_outgoing')->insertGetId(
							[
								'campaign_id' => $this->campaign_id,
								'campaign_contact_id' => $this->campaign_contact_id,
								'created_at' => Carbon::now(),
								'updated_at' => Carbon::now()
							]
						);
					} catch (\Illuminate\Database\QueryException $e) {
						$campaign_contact_lock = CampaignContact::where('id', $campaign_contact->id)->first();
						$campaign_contact_lock->cc_status = 51;
						$campaign_contact_lock->save();
						Log::info('Jobs\SendDirectText: duplicate record! c:' . $this->campaign_id . ' ccid:' . $this->campaign_contact_id);
					}

					if ($log_id > 0) {

						// lock this as sending..
						$campaign_contact_lock = CampaignContact::where('id', $campaign_contact->id)->first();
						$campaign_contact_lock->cc_status = 20;
						$campaign_contact_lock->save();

						$client = new Client(env('SMS_SID'), env('SMS_TOKEN'));
						try {

							$content = array(
								'messagingServiceSid' => $messaging_service->sid,
								'body' => trim($campaign_contact->content_sent)
							);

							if (trim($campaign_contact->mms_sent)) {
								$content["mediaUrl"] = array(trim($campaign_contact->mms_sent));
							}

							contactAction('SMS SENT', $contact->id);
							$resp = $client->messages->create(
								$contact->phone,
								$content
							);
							Log::info('Jobs\SendDirectText: message_sid : ' . $resp->sid);

							if (@$resp->sid) {
								$cnt = CampaignContact::where('id', $campaign_contact->id)->first();
								$cnt->cc_status = 50;
								$cnt->sms_sid = $resp->sid;
								$cnt->audit_sms_sid_rcvd = microtime();
								$cnt->save();
							} else {
								Log::error('Jobs\SendDirectText error: no response message id received!');
								$cnt = CampaignContact::where('id', $campaign_contact->id)->first();
								$cnt->cc_status = 51;
								$cnt->save();
							}

						} catch(Exception $e) {
							Log::error('Jobs\SendDirectText error: message_service_id[' . $messaging_service->sid . '] ' . $e->getCode() . ' : ' . $e->getMessage());
							$cnt = CampaignContact::where('id', $campaign_contact->id)->first();
							$cnt->cc_status = 51;
							$cnt->save();
						}
					}

				} else {
					Log::error('Jobs\SendDirectText error: Missing contact and/or messaging service');
				}

			} else {
				// cant find campaign and/or campaign_contact
				Log::error('Jobs\SendDirectText error: Missing campaign and/or campaign_contact');
			}

		}

	}

}
