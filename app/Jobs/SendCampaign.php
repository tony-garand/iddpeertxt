<?php

namespace peertxt\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\DispatchesJobs;
use peertxt\models\Campaign;
use peertxt\models\CampaignContact;
use peertxt\models\CampaignTag;
use peertxt\models\Company;
use peertxt\models\Contact;
use peertxt\models\MessagingService;
use peertxt\models\MessagingServiceNumber;
use Twilio\Rest\Client;
use peertxt\Jobs\ProcessCampaignContact;
use peertxt\Jobs\CampaignCleanup;
use Carbon\Carbon;
use Webpatser\Uuid\Uuid;

class SendCampaign implements ShouldQueue
{
	use InteractsWithQueue, Queueable, SerializesModels, DispatchesJobs;

	protected $campaign_id;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($campaign_id) {
		$this->campaign_id = $campaign_id;
	}

	/**
	 * Execute the direct send job.
	 *
	 * @return void
	 */
	public function handle() {

		set_time_limit(0);

		Log::info('Jobs\SendCampaign: beginning for campaign_id = ' . $this->campaign_id);

		$campaign = Campaign::where('id', $this->campaign_id)
			->where('campaign_status', 20)
			->where('campaign_type', 2)
			->first();

		if ($campaign) {

			$campaign_lock = Campaign::where('id', $this->campaign_id)->first();
			$campaign_lock->campaign_status = 21;
			$campaign_lock->save();

			$ms_id = $campaign->messaging_service_id;

			// loop through campaign_contacts and send the actual text (call sendText for each)
			$campaign_contacts = CampaignContact::where('campaign_id', $this->campaign_id)->where('cc_status', 1)->get();
			foreach ($campaign_contacts as $campaign_contact) {
				$this->dispatch(new SendDirectText($this->campaign_id, $campaign_contact->id));
			}

			// clean up
			Log::info('Jobs\SendCampaign: Dispatching cleanup for campaign_id = ' . $this->campaign_id);
			$this->dispatch((new SendTextCleanup($this->campaign_id))->onQueue("low")->delay(now()->addMinutes(1)));

		} else {

			// we couldnt find this campaign or the parameters dont match; should we throw it back?

		}


	}

}
