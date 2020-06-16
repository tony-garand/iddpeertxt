<?php

namespace peertxt\Http\Controllers;

use peertxt\Jobs\ProcessLead;
use peertxt\Jobs\Yext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DOMDocument;
use DOMXPath;
use Browser\Casper;
use Twilio\Rest\Client;
use Twilio\Twiml;
use GuzzleHttp\Client as GuzzleClient;

class ApiController extends Controller {

	public function __construct() {
	}

	public function index() {
	}

	private function rander($length=10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	private function yext_publisher_list() {
		$uri = "https://api.yext.com/v2/accounts/me/powerlistings/publishers?api_key=" . env('YEXT_API_KEY') . "&v=20161012";
		try {
			$client = new \GuzzleHttp\Client();
			$result = $client->get($uri, []);
			$data = json_decode($result->getBody());
			$keys = [];
			if ($data) {
				foreach ($data->response->publishers as $publisher) {
					if (in_array('US', $publisher->supportedCountries)) {
						$keys[] = $publisher->id;
					}
				}
			}
			return array(true, $keys);
		} catch (\GuzzleHttp\Exception\ClientException $e) {
			return array(false, null);
		}
	}

	public function google_name_lookup() {

		set_time_limit(0);

		$client = new GuzzleClient();
		$businesses = DB::table('businesses')->where('status', 1)->where('name_scrubbed', 0)->limit(500)->get();
		foreach ($businesses as $business) {

			$results = $client->get('https://maps.googleapis.com/maps/api/place/findplacefromtext/json', [
				'query' => [
					'input' => $business->Company,
					'inputtype' => 'textquery',
					'fields' => 'formatted_address,name',
					'locationbias' => 'circle:2000@' . $business->lat . ',' . $business->lng,
					'key' => env('GOOGLE_PLACES_API_KEY')
				]
			]);

			$json = json_decode($results->getBody());

			if (@$json->status == "OK") {
				$scrubbed_name = $json->candidates[0]->name;
				echo "previous name = " . $business->Company . ", scrubbed_name = " . $scrubbed_name . "<br/>";
				if ($scrubbed_name) {
					DB::table('businesses')
						->where('id', $business->id)
						->update([
							'Company' => $scrubbed_name,
							'name_scrubbed' => 1,
							'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
						]
					);
				}
			} else {

				if (@$json->status == "ZERO_RESULTS") {
					DB::table('businesses')
						->where('id', $business->id)
						->update([
							'name_scrubbed' => -1,
							'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
						]
					);
				} else {
					echo "<pre>";
					print_r($json);
					echo "</pre>";
				}

			}

		}

	}

	public function cron_messaging_services() {

		$twilio = new Client(env('SMS_SID'), env('SMS_TOKEN'));
		$services = $twilio->messaging->v1->services->read();
		foreach ($services as $service) {

			// check if we already have this messaging_service record.. if we dont, create it. if we do, use that id for looking
			// at the phone numbers associated.

			$messaging_service = DB::table('messaging_services')->where('sid', $service->sid)->first();
			if (!$messaging_service) {
				$messaging_service_id = DB::table('messaging_services')->insertGetId(
					[
						'sid' => $service->sid,
						'name' => $service->friendlyName,
						'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
						'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
					]
				);
			} else {
				$messaging_service_id = $messaging_service->id;
			}

			DB::table('messaging_service_numbers')->where('messaging_service_id', $messaging_service_id)->delete();
			$phoneNumbers = $twilio->messaging->v1->services($service->sid)->phoneNumbers->read();
			foreach ($phoneNumbers as $phoneNumber) {
				$messaging_service_phone_id = DB::table('messaging_service_numbers')->insertGetId(
					[
						'messaging_service_id' => $messaging_service_id,
						'number' => $phoneNumber->phoneNumber,
						'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
						'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
					]
				);
			}

		}

		echo "done.";

	}

}
