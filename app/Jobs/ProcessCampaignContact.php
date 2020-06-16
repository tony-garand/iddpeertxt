<?php

namespace peertxt\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use peertxt\models\Campaign;
use peertxt\models\CampaignContact;
use SSH;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Storage;
use Twilio\Rest\Client;

class ProcessCampaignContact implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $campaign_id;
	protected $contact_id;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($campaign_id, $contact_id) {
		$this->campaign_id = $campaign_id;
		$this->contact_id = $contact_id;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle() {

		set_time_limit(0);

		Log::info('Jobs\ProcessCampaignContact: Starting with campaign_id = ' . $this->campaign_id . ' and contact_id = ' . $this->contact_id);

		$campaign = Campaign::where('id', $this->campaign_id)
			->where('campaign_status', 6)
			->first();

		if ($campaign) {

			Log::info('Jobs\ProcessCampaignContact: Have campaign');

			// how many content_templates do we have?
			$content_options = [];
			$link_options = [];
			$mms_options = [];

			if (trim($campaign->content_template_1)) {
				$content_options[] = 1;
				if (trim($campaign->conversion_link_1)) {
					$link_options[] = 1;
				}
				if (trim($campaign->content_media_1)) {
					$mms_options[] = 1;
				}
			}

			if (trim($campaign->content_template_2)) {
				$content_options[] = 2;
				if (trim($campaign->conversion_link_2)) {
					$link_options[] = 2;
				}
				if (trim($campaign->content_media_2)) {
					$mms_options[] = 2;
				}
			}

			if (trim($campaign->content_template_3)) {
				$content_options[] = 3;
				if (trim($campaign->conversion_link_3)) {
					$link_options[] = 3;
				}
				if (trim($campaign->content_media_3)) {
					$mms_options[] = 3;
				}
			}

			if (trim($campaign->content_template_4)) {
				$content_options[] = 4;
				if (trim($campaign->conversion_link_4)) {
					$link_options[] = 4;
				}
				if (trim($campaign->content_media_4)) {
					$mms_options[] = 4;
				}
			}

			// get random content option for A/B distribution..
			$content_option_key = $content_options[array_rand($content_options)];

			Log::info('Jobs\ProcessCampaignContact: Using content option ' . $content_option_key);

			$campaign_contact = CampaignContact::where('campaign_id', $this->campaign_id)
				->where('contact_id', $this->contact_id)
                //TODO: where verifed == 2 ??
				->where('cc_status', 0)
				->first();

			if ($campaign_contact) {

				Log::info('Jobs\ProcessCampaignContact: Have campaign_contact record .. ');

				if ($campaign_contact->Contact && $campaign_contact->Contact->status == 1) {

					Log::info('Jobs\ProcessCampaignContact: Have contact and status = 1, moving on .. ');

					$raw_content = ${'campaign'}->{'content_template_' . $content_option_key};
					$raw_link = ${'campaign'}->{'conversion_link_' . $content_option_key};
					$raw_mms = ${'campaign'}->{'content_media_' . $content_option_key};

					// if we have a link, it needs to do the shortlink processing..
					if (trim($raw_link)) {

						Log::info('Jobs\ProcessCampaignContact: process raw link .. ');

						$processed_link = $this->_magic_tags($raw_link, $campaign, $campaign_contact->Contact, 2);

						Log::info('Jobs\ProcessCampaignContact: processed link = ' . $processed_link);

						$unique_code = $this->_generate_shortlink_code();

						Log::info('Jobs\ProcessCampaignContact: unique code = ' . $unique_code);

						$shortlink_id = DB::table('shortlinks')->insertGetId(
							[
								'code' => $unique_code,
								'destination' => $processed_link,
								'campaign_id' => $this->campaign_id,
								'contact_id' => $this->contact_id,
								'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
								'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
							]
						);
						$click_link = 'https://' . env('APP_DOMAIN', 'www.peertxt.co') . '/c/' . $unique_code;
					} else {
						$click_link = "";
					}

					Log::info('Jobs\ProcessCampaignContact: click link done = ' . $click_link . ' .. ');

					$processed_content = $this->_magic_tags($raw_content, $campaign, $campaign_contact->Contact, 1);
					if (trim($click_link)) {
						$processed_content = $processed_content . ' ' . $click_link;
					}

					$campaign_contact->cc_status = 1;
					$campaign_contact->content_sent = $processed_content;
					if ($raw_mms) {
						$campaign_contact->mms_sent = $raw_mms;
					}
					$campaign_contact->content_option = $content_option_key;
					$campaign_contact->save();

					Log::info('Jobs\ProcessCampaignContact: Proceeding with processing.. ');

				} else {
					// this contact got disabled or deleted; we need to remove it from the run..
					$campaign_contact->cc_status = -1;
					$campaign_contact->save();

				}

			} else {
				Log::error('Jobs\ProcessCampaignContact: could not find campaign');
			}

		} else {
			Log::error('Jobs\ProcessCampaignContact: could not find campaign');
		}

	}

	private function _magic_tags($body, $_campaign, $_contact, $type=1) {

		// type .. 1 = body, 2 = link

		$message = $body;
		if ($_campaign) {
			$message = str_replace("[[campaign_uuid]]", (($type == 1) ? $_campaign->uuid : urlencode($_campaign->uuid)), $message);

		} else {
			$message = str_replace("[[campaign_uuid]]", "", $message);
		}

		if ($_contact) {
			$message = str_replace("[[first_name]]", (($type == 1) ? $_contact->first_name : urlencode($_contact->first_name)), $message);
			$message = str_replace("[[last_name]]", (($type == 1) ? $_contact->last_name : urlencode($_contact->last_name)), $message);
			$message = str_replace("[[email]]", (($type == 1) ? $_contact->email : urlencode($_contact->email)), $message);
			$message = str_replace("[[phone]]", (($type == 1) ? $_contact->phone : urlencode($_contact->phone)), $message);
			$message = str_replace("[[contact_uuid]]", (($type == 1) ? $_contact->uuid : urlencode($_contact->uuid)), $message);
		} else {
			$message = str_replace("[[first_name]]", "", $message);
			$message = str_replace("[[last_name]]", "", $message);
			$message = str_replace("[[email]]", "", $message);
			$message = str_replace("[[phone]]", "", $message);
			$message = str_replace("[[contact_uuid]]", "", $message);
		}

		return $message;
	}

	private function _generate_shortlink_code() {
		$unique = str_random(7);
		$check = DB::table('shortlinks')->where('code', $unique)->whereNull('deleted_at')->first();
		if ($check) {
			return $this->_generate_shortlink_code();
		}
		return $unique;
	}

}
