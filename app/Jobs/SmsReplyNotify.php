<?php

namespace peertxt\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;
use peertxt\Events\CampaignReplyNotifySend;
use peertxt\models\CampaignReplyNotification;

class SmsReplyNotify implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		## get any notifications that need to be sent
		$n = CampaignReplyNotification::where('in_use', false)->get();

		if ($n->count() > 0) {
			Log::info(sprintf('%s CampaignReplyNotifications to process...', $n->count()));

			foreach ($n as $notify) {
				$notify->in_use = true;
				$notify->save();

				broadcast(new CampaignReplyNotifySend($notify->user_id, sprintf('%s replies for campaign %s', $notify->reply_count, $notify->campaign_id)));
				$notify->delete();
			}
		}
	}
}
