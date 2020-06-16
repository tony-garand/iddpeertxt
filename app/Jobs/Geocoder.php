<?php

namespace peertxt\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Jcf\Geocode\Geocode;
use Illuminate\Support\Facades\Log;

class Geocoder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $business_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($business_id)
    {
        //
        $this->business_id = $business_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

		$b = DB::table('businesses')->where('id', $this->business_id)->first();
		if ($b) {

			$add = $b->Address . ", " . $b->City . ", " . $b->Mailing_State . " " . $b->Zip . "-" . $b->Zip4;
			$data = Geocode::make()->address($add);
			Log::info('Jobs\Geocoder : ' . $b->id . ' .. ' . $add . ' ... ' . $data->latitude() . "," . $data->longitude());

			DB::table('businesses')
				->where('id', $b->id)
				->update([
					'lat' => $data->latitude() . "",
					'lng' => $data->longitude() . "",
					'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
				]
			);

		}

    }
}
