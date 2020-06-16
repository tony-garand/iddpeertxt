<?php

namespace peertxt\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use peertxt\Events\CampaignSendFinished;
use peertxt\models\Campaign;
use peertxt\models\CampaignContact;

class SendTextCleanup implements ShouldQueue
{
	use InteractsWithQueue, Queueable, SerializesModels, DispatchesJobs;
	protected $campaign_id;

	public function __construct($campaign_id)
	{
		$this->campaign_id = $campaign_id;
	}

	public function handle()
	{

		// update status if all of these are now done..
		$count_undone = CampaignContact::where('campaign_id', $this->campaign_id)
			->where('cc_status', 1)
			->count();

		if ($count_undone < 1) {
			// we are done, we can set this campaign status = 50
			// should also try to get a total count of records here and update the rollup_total

			$count_completed = CampaignContact::where('campaign_id', $this->campaign_id)
				->where('cc_status', '>=', 50)
				->count();

			$campaign = Campaign::find($this->campaign_id);
			$campaign->campaign_status = 50;
			$campaign->rollup_completed = $count_completed;
			$campaign->save();

			broadcast(new CampaignSendFinished($campaign->created_by, 'Campaign send finished'));
		} else {

			// update the total completed, and trigger self..
			$count_completed = CampaignContact::where('campaign_id', $this->campaign_id)
				->where('cc_status', '>=', 50)
				->count();

			$campaign = Campaign::find($this->campaign_id);
			$campaign->rollup_completed = $count_completed;
			$campaign->save();

			// trigger self
			$this->dispatch((new SendTextCleanup($this->campaign_id))->onQueue("low")->delay(now()->addMinutes(5)));
		}

	}

}
