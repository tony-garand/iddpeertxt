<?php

namespace peertxt\Console\Commands;

use Illuminate\Console\Command;
use Log;
use peertxt\models\Campaign;
use peertxt\models\CampaignContact;

class ResetCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaign:reset {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset a campaign back to draft';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
			$campaignId = $this->argument('id');

			$this->info('attempting to reset campaign: ' . $campaignId);

			$campaign = Campaign::find($campaignId);

			if ($campaign) {
				$this->info('found campaign :: ' . $campaign->campaign_name);

				$campaign->campaign_status = 0;
				$campaign->save();

				$this->info('campaign status set to 0');

				$deletedRows = CampaignContact::where('campaign_id', $campaignId)
					->delete();

				$this->info(sprintf('marked %s campaign contacts deleted', $deletedRows));

			} else {
				$this->error('invalid campaign');
			}

			return true;
    }
}
