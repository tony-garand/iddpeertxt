<?php

namespace peertxt\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use SSH;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Storage;

class Hypertargeting implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $hypertargeting_id;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($hypertargeting_id) {
		$this->hypertargeting_id = $hypertargeting_id;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle() {

		set_time_limit(0);

		$ht = DB::table('hypertargeting')->where('id', $this->hypertargeting_id)->first();
		if ($ht) {
			$htt = DB::table('hypertargeting_templates')->where('id', $ht->hypertargeting_template_id)->first();
			if ($htt) {

				// generate json file and put it on server..
				$data = [];

				$data['id'] = $ht->id;
				$data['repo_name'] = $ht->repo_name;
				$data['name'] = $ht->name;
				$data['pagetitle'] = $ht->pagetitle;
				$data['phone'] = $ht->phone;
				$data['logo'] = $ht->logo;
				$data['favicon'] = $ht->favicon;
				$data['color1'] = $ht->color1;
				$data['color2'] = $ht->color2;
				$data['color3'] = $ht->color3;
				$data['color4'] = $ht->color4;
				$data['hero_pretitle'] = $ht->hero_pretitle;
				$data['hero_subtitle'] = $ht->hero_subtitle;
				$data['hero_title'] = $ht->hero_title;
				$data['hero_body'] = $ht->hero_body;
				$data['hero_image'] = $ht->hero_image;
				$data['banner_text'] = $ht->banner_text;
				$data['facts_title'] = $ht->facts_title;
				$data['facts_body'] = $ht->facts_body;
				$data['testimonials_title'] = $ht->testimonials_title;
				$data['testimonials_body'] = $ht->testimonials_body;
				$data['about_title'] = $ht->about_title;
				$data['about_image'] = $ht->about_image;
				$data['about_link'] = $ht->about_link;
				$data['about_body'] = $ht->about_body;
				$data['learnmore_title'] = $ht->learnmore_title;
				$data['learnmore_image'] = $ht->learnmore_image;
				$data['learnmore_link'] = $ht->learnmore_link;
				$data['learnmore_body'] = $ht->learnmore_body;
				$data['form_url'] = $ht->form_url;
				$data['simplifi_url'] = $ht->simplifi_url;
				$data['simplifi_submission_url'] = $ht->simplifi_submission_url;
				$data['ga_key'] = $ht->ga_key;
				$data['recaptcha_key'] = $ht->recaptcha_key;
				$data['sources_list'] = $ht->sources_list;
				$data['meta_description'] = $ht->meta_description;

				$block_data = [];
				$blocks = DB::table('hypertargeting_blocks')->where('hypertargeting_id', $this->hypertargeting_id)->get();
				foreach ($blocks as $block) {
					$titles = [];
					$titles['line1'] = $block->block_title_1;
					$titles['line2'] = $block->block_title_2;
					$titles['line3'] = $block->block_title_3;
					$tmp = [];
					$tmp['block_title'] = $titles;
					$tmp['block_image'] = $block->block_image;
					$tmp['block_body'] = $block->block_body;
					$block_data[] = $tmp;
				}
				$data['blocks'] = $block_data;

				$fact_data = [];
				$facts = DB::table('hypertargeting_facts')->where('hypertargeting_id', $this->hypertargeting_id)->get();
				foreach ($facts as $fact) {
					$tmp = [];
					$tmp['fact_title'] = $fact->fact_title;
					$tmp['fact_body'] = $fact->fact_body;
					$fact_data[] = $tmp;
				}
				$data['facts'] = $fact_data;

				$testimonial_data = [];
				$testimonials = DB::table('hypertargeting_testimonials')->where('hypertargeting_id', $this->hypertargeting_id)->get();
				foreach ($testimonials as $testimonial) {
					$tmp = [];
					$tmp['testimonial_name'] = $testimonial->testimonial_name;
					$tmp['testimonial_body'] = $testimonial->testimonial_body;
					$testimonial_data[] = $tmp;
				}
				$data['testimonials'] = $testimonial_data;

				// customized fields added here
				$data['recaptcha_secret_key'] = $ht->recaptcha_secret_key;
				$data['stripe_secret_key'] = $ht->stripe_secret_key;
				$data['stripe_public_key'] = $ht->stripe_public_key;
				$data['privacy_policy'] = $ht->privacy_policy;
				$data['contact_block'] = $ht->contact_block;

				Storage::disk('local')->put('json/data_' . $this->hypertargeting_id . '.json', json_encode($data));

				SSH::into('hypertargeting')->run([
//					'git clone -b master git@bitbucket.org:id-digital/ht-gastro.git /var/www/gi.today/' . strtolower($ht->repo_name),
					'git clone -b master ' . $htt->repo_url . ' ' . $htt->server_path . strtolower($ht->repo_name)
				], function($line) {
					Log::info('Server\SSH: ' . $line.PHP_EOL);
				});

				SSH::into('hypertargeting')->put(Storage::disk('local')->path('json/data_' . $this->hypertargeting_id . '.json'), $htt->server_path . strtolower($ht->repo_name) . '/config.json');

				SSH::into('hypertargeting')->run([
					'cd ' . $htt->server_path . strtolower($ht->repo_name),
					'/bin/npm install',
					'/bin/npm run prod'
				], function($line) {
					Log::info('Server\SSH: ' . $line.PHP_EOL);
				});

				if ($htt->server_command_1) {
					SSH::into('hypertargeting')->run([
						'cd ' . $htt->server_path . strtolower($ht->repo_name),
						$htt->server_command_1
					], function($line) {
						Log::info('Server\SSH: ' . $line.PHP_EOL);
					});
				}

				if ($htt->server_command_2) {
					SSH::into('hypertargeting')->run([
						'cd ' . $htt->server_path . strtolower($ht->repo_name),
						$htt->server_command_2
					], function($line) {
						Log::info('Server\SSH: ' . $line.PHP_EOL);
					});
				}

				if ($htt->server_command_3) {
					SSH::into('hypertargeting')->run([
						'cd ' . $htt->server_path . strtolower($ht->repo_name),
						$htt->server_command_3
					], function($line) {
						Log::info('Server\SSH: ' . $line.PHP_EOL);
					});
				}

				if ($htt->server_command_4) {
					SSH::into('hypertargeting')->run([
						'cd ' . $htt->server_path . strtolower($ht->repo_name),
						$htt->server_command_4
					], function($line) {
						Log::info('Server\SSH: ' . $line.PHP_EOL);
					});
				}

				DB::table('hypertargeting')
					->where('id', $this->hypertargeting_id)
					->update([
						'status' => 1,
						'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
					]
				);

				Storage::disk('local')->delete('json/data_' . $this->hypertargeting_id . '.json');

			}

		}

	}

}
