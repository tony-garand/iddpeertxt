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

class BeginCampaign implements ShouldQueue
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
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle() {

		set_time_limit(0);

		$campaign = Campaign::where('id', $this->campaign_id)
            ->where('campaign_status', 5)
            ->first();

		if ($campaign) {

			$campaign_lock = Campaign::where('id', $this->campaign_id)->first();
			$campaign_lock->campaign_status = 6;
			$campaign_lock->save();

			// first, go out and create a messaging service and tie it to this campaign, then find phone numbers (2?) in the areacode / zipcode
			// try matching an area code first, then a zip code with a wide search.. if nothing comes back, go for a toll-free number

			## TODO: this could be handled better, the same code is reused  a few times

			if ($campaign->messaging_service_id === 0) {

				$company = Company::where('id', $campaign->company_id)->first();

				$twilio = new Client(env('SMS_SID'), env('SMS_TOKEN'));
				$ms_name = $company->company_name . " - " . $campaign->uuid;
				$add_service = $twilio->messaging->v1->services->create($ms_name, array('InboundRequestUrl' => 'https://' . env('APP_DOMAIN', 'www.peertxt.co') . '/sms/incoming_sms'));
				$ms = new MessagingService();
				$ms->company_id = $company->id;
				$ms->sid = $add_service->sid;
				$ms->name = $campaign->uuid;
				$ms->save();

				$ms_id = $ms->id;

				$lines = 2;
				$assigned_lines = 0;

				if ($lines > 0) {

					// first, try to find numbers by area code..
					$numbers = $twilio->availablePhoneNumbers('US')->local->read(array('areaCode' => $campaign->areacode, 'excludeAllAddressRequired' => true), 10);
					if (count($numbers) > 0) {
						for ($i = 1; $i <= $lines; $i++) {
							if (@$numbers[$i-1]->phoneNumber) {
								$new_number = $twilio->incomingPhoneNumbers->create(array('phoneNumber' => $numbers[$i-1]->phoneNumber));
								if (@$new_number->sid) {
									$added_phone = $twilio->messaging->v1->services($add_service->sid)->phoneNumbers->create($new_number->sid);
									$msn = new MessagingServiceNumber();
									$msn->messaging_service_id = $ms_id;
									$msn->number = $numbers[$i-1]->phoneNumber;
									$msn->save();

                                    $phone_id = $msn->id;

									$assigned_lines++;
								}
							}
						}
					}

					if ($assigned_lines < $lines) {
						// ok, lets next try by phone number with a distance..
						$numbers = $twilio->availablePhoneNumbers("US")->local->read(array('nearNumber' => $campaign->nearphone, 'distance' => 100, 'excludeAllAddressRequired' => true), 20);
						if (count($numbers) > 0) {
							for ($i = 1; $i <= $lines; $i++) {
								if (@$numbers[$i-1]->phoneNumber) {
									$new_number = $twilio->incomingPhoneNumbers->create(array('phoneNumber' => $numbers[$i-1]->phoneNumber));
									if (@$new_number->sid) {
										$added_phone = $twilio->messaging->v1->services($add_service->sid)->phoneNumbers->create($new_number->sid);
                                        $msn = new MessagingServiceNumber();
                                        $msn->messaging_service_id = $ms_id;
                                        $msn->number = $numbers[$i-1]->phoneNumber;
                                        $msn->save();

                                        $phone_id = $msn->id;

										$assigned_lines++;
									}
								}
							}
						}
					}

					if ($assigned_lines < $lines) {
						// ok, lets next try by zipcode..
						$numbers = $twilio->availablePhoneNumbers("US")->local->read(array('inPostalCode' => $campaign->zipcode, 'excludeAllAddressRequired' => true), 10);
						if (count($numbers) > 0) {
							for ($i = 1; $i <= $lines; $i++) {
								if (@$numbers[$i-1]->phoneNumber) {
									$new_number = $twilio->incomingPhoneNumbers->create(array('phoneNumber' => $numbers[$i-1]->phoneNumber));
									if (@$new_number->sid) {
										$added_phone = $twilio->messaging->v1->services($add_service->sid)->phoneNumbers->create($new_number->sid);

                                        $msn = new MessagingServiceNumber();
                                        $msn->messaging_service_id = $ms_id;
                                        $msn->number = $numbers[$i-1]->phoneNumber;
                                        $msn->save();

                                        $phone_id = $msn->id;

                                        $assigned_lines++;
									}
								}
							}
						}
					}

					if ($assigned_lines < $lines) {
						// ok, still having trouble, lets just add 800 numbers..
						$numbers = $twilio->availablePhoneNumbers("US")->tollFree->read(array('excludeAllAddressRequired' => true), 10);
						if (count($numbers) > 0) {
							for ($i = 1; $i <= $lines; $i++) {
								if (@$numbers[$i-1]->phoneNumber) {
									$new_number = $twilio->incomingPhoneNumbers->create(array('phoneNumber' => $numbers[$i-1]->phoneNumber));
									if (@$new_number->sid) {
										$added_phone = $twilio->messaging->v1->services($add_service->sid)->phoneNumbers->create($new_number->sid);
                                        $msn = new MessagingServiceNumber();
                                        $msn->messaging_service_id = $ms_id;
                                        $msn->number = $numbers[$i-1]->phoneNumber;
                                        $msn->save();

                                        $phone_id = $msn->id;

										$assigned_lines++;
									}
								}
							}
						}
					}

				}

			} else {
				$ms_id = $campaign->messaging_service_id;
			}

			## TODO: this code and the contact code right after should probably be combined
			$campaignTags = CampaignTag::where('campaign_id', $this->campaign_id)->pluck('tag');
			$contacts = Contact::withAnyTags($campaignTags)->distinct('phone')->get();
			foreach ($contacts as $cont) {
				$contact = new CampaignContact();
				$contact->uuid = Uuid::generate()->string;
				$contact->campaign_id = $this->campaign_id;
				$contact->contact_id = $cont->id;
				$contact->cc_status = 0;
				$contact->content_option = 0;
				$contact->save();

				contactAction('CAMPAIGN ADD [' . $this->campaign_id . ']', $cont->id);
			}

			// now, lock in the status of all contacts for this specific campaign - as part of this, we want to run through the shortlink customizer
			$campaign_contacts = CampaignContact::where('campaign_id', $this->campaign_id)
                ->where('cc_status', 0)
                ->get();

			foreach ($campaign_contacts as $campaign_contact) {
				Log::info('Jobs\BeginCampaign: Calling Jobs\ProcessCampaignContact  - ' . $this->campaign_id . ' - ' . $campaign_contact->contact_id);
				$this->dispatch(new ProcessCampaignContact($this->campaign_id, $campaign_contact->contact_id));
			}

			$campaign->messaging_service_id = $ms_id;
			$campaign->save();

			Log::info('Jobs\BeginCampaign: Dispatching cleanup for campaign_id = ' . $this->campaign_id);
			$this->dispatch((new CampaignCleanup($this->campaign_id))->onQueue("low")->delay(now()->addMinutes(1)));

		} else {

			// we couldnt find this campaign or the parameters dont match; should we throw it back?

		}


	}

}
