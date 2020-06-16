<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHypertargeting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('hypertargeting', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('status')->default(0);

			$table->string('repo_name')->nullable();
			$table->string('name')->nullable();
			$table->string('pagetitle')->nullable();
			$table->string('phone')->nullable();
			$table->string('color1')->nullable();
			$table->string('color2')->nullable();
			$table->string('color3')->nullable();
			$table->string('color4')->nullable();
			$table->string('logo')->nullable();
			$table->string('favicon')->nullable();
			$table->text('meta_description')->nullable();

			$table->string('form_url')->nullable();
			$table->string('simplifi_url')->nullable();
			$table->string('simplifi_submission_url')->nullable();
			$table->string('ga_key')->nullable();
			$table->string('recaptcha_key')->nullable();

			$table->string('hero_pretitle')->nullable();
			$table->string('hero_subtitle')->nullable();
			$table->string('hero_title')->nullable();
			$table->string('hero_image')->nullable();
			$table->text('hero_body')->nullable();

			$table->string('banner_text')->nullable();

			$table->string('facts_title')->nullable();
			$table->text('facts_body')->nullable();

			$table->string('testimonials_title')->nullable();
			$table->text('testimonials_body')->nullable();

			$table->string('about_title')->nullable();
			$table->string('about_image')->nullable();
			$table->string('about_link')->nullable();
			$table->text('about_body')->nullable();

			$table->string('learnmore_title')->nullable();
			$table->string('learnmore_image')->nullable();
			$table->text('learnmore_body')->nullable();
			$table->string('learnmore_link')->nullable();

			$table->text('sources_list')->nullable();

			$table->timestamps();
		});

		Schema::create('hypertargeting_blocks', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('hypertargeting_id');
			$table->string('block_title_1')->nullable();
			$table->string('block_title_2')->nullable();
			$table->string('block_title_3')->nullable();
			$table->string('block_image')->nullable();
			$table->text('block_body')->nullable();
			$table->timestamps();
		});

		Schema::create('hypertargeting_facts', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('hypertargeting_id');
			$table->string('fact_title')->nullable();
			$table->text('fact_body')->nullable();
			$table->timestamps();
		});

		Schema::create('hypertargeting_testimonials', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('hypertargeting_id');
			$table->string('testimonial_name')->nullable();
			$table->text('testimonial_body')->nullable();
			$table->timestamps();
		});


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::dropIfExists('hypertargeting');
		Schema::dropIfExists('hypertargeting_blocks');
		Schema::dropIfExists('hypertargeting_facts');
		Schema::dropIfExists('hypertargeting_testimonials');
    }
}
