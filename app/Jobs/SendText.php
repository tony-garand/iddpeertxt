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
use peertxt\models\MessagingService;
use Twilio\Rest\Client;
use Exception;
use Illuminate\Support\Facades\Log;

class SendText implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	protected $campaign;
	protected $campaign_contact;

	public function __construct($campaign, $campaign_contact)
	{
		$this->campaign = $campaign;
		$this->campaign_contact = $campaign_contact;
	}

	public function handle()
	{
		if ( ($this->campaign_contact) && ($this->campaign) ) {

			$contact = Contact::where('id', $this->campaign_contact->contact_id)->first();
			$messaging_service = MessagingService::where('id', $this->campaign->messaging_service_id)->first();

			if ($contact) {

				// insert into log_outgoing to make sure we don't already have this..
				$log_id = 0;
				try {
					$log_id = DB::table('log_outgoing')->insertGetId(
						[
							'campaign_id' => $this->campaign->id,
							'campaign_contact_id' => $this->campaign_contact->id,
							'created_at' => Carbon::now(),
							'updated_at' => Carbon::now()
						]
					);
				} catch (\Illuminate\Database\QueryException $e) {
					$campaign_contact_lock = CampaignContact::where('id', $this->campaign_contact->id)->first();
					$campaign_contact_lock->cc_status = 51;
					$campaign_contact_lock->save();
					Log::info('Jobs\SendText: duplicate record! c:' . $this->campaign->id . ' ccid:' . $this->campaign_contact->id);
				}

				if ($log_id > 0) {

					$client = new Client(env('SMS_SID'), env('SMS_TOKEN'));
					try {

						$content = array(
							'messagingServiceSid' => $messaging_service->sid,
							'body' => trim($this->campaign_contact->content_sent)
						);

						if (trim($this->campaign_contact->mms_sent)) {
							$content["mediaUrl"] = array(trim($this->campaign_contact->mms_sent));
						}

						contactAction('SMS SENT', $contact->id);

						$resp = $client->messages->create(
							$contact->phone,
							$content
						);
						Log::info('Jobs\SendText: message_sid : ' . $resp->sid);

						if (@$resp->sid) {
						    $cnt = CampaignContact::where('id', $this->campaign_contact->id)
								->where('cc_status', 20)
	                            ->first();

						    $cnt->cc_status = 50;
						    $cnt->sms_sid = $resp->sid;
						    $cnt->audit_sms_sid_rcvd = microtime();
						    $cnt->save();
						} else {
							Log::error('Jobs\SendText error: no response message id received!');
							$cnt = CampaignContact::where('id', $this->campaign_contact->id)
								->where('cc_status', 20)
								->first();

							$cnt->cc_status = 51;
							$cnt->save();
						}

					} catch(Exception $e) {
						Log::error('Jobs\SendText error: message_service_id[' . $this->campaign->messaging_service_id . '] ' . $e->getCode() . ' : ' . $e->getMessage());
						$cnt = CampaignContact::where('id', $this->campaign_contact->id)
							->where('cc_status', 20)
							->first();

						$cnt->cc_status = 51;
						$cnt->save();
					}

				} else {

					// this is probably a duplicate attempt..

				}

			}

			// rollup and check if we are the last text going out..
			$count_completed = CampaignContact::where('campaign_id', $this->campaign->id)
				->where('cc_status', '>=', 50)
				->count();

			$cnt = Campaign::where('id', $this->campaign->id)
				->first();

			$cnt->rollup_completed = $count_completed;
			$cnt->save();

			if ($count_completed >= $this->campaign->rollup_total) {
				$cnt = Campaign::where('id', $this->campaign->id)
					->where('campaign_status', 20)
					->update([
						'campaign_status' => 50,
						'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
				]);
			}

		}

	}

}
