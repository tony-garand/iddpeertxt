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
use peertxt\Events\CampaignCreateFinished;
use peertxt\models\Campaign;
use peertxt\models\CampaignContact;

class CampaignCleanup implements ShouldQueue
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
			->where('cc_status', 0)
			->count();

		if ($count_undone < 1) {
			// we are done, we can set this campaign status = 10
			// should also try to get a total count of records here and update the rollup_total
			$count_total = CampaignContact::where('campaign_id', $this->campaign_id)
				->where('cc_status', 1)
				->count();

			$campaign = Campaign::find($this->campaign_id);
			$campaign->campaign_status = 10;
			$campaign->rollup_total = $count_total;
			$campaign->save();

			broadcast(new CampaignCreateFinished($campaign->created_by, 'Campaign setup finished'));
		} else {
			// trigger self
			$this->dispatch((new CampaignCleanup($this->campaign_id))->onQueue("low")->delay(now()->addMinutes(5)));
		}

	}

}
