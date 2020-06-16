<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShortlinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('shortlinks', function (Blueprint $table) {
			$table->increments('id');
			$table->string('code')->nullable();
			$table->text('destination')->nullable();
			$table->integer('campaign_id')->default(0);
			$table->integer('contact_id')->default(0);
			$table->timestamps();
			$table->softDeletes();
		});
		Schema::create('shortlink_clicks', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('shortlink_id')->default(0);
			$table->string('ip')->nullable();
			$table->string('refer')->nullable();
			$table->text('geodata')->nullable();
			$table->text('full_request_headers')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::dropIfExists('shortlinks');
		Schema::dropIfExists('shortlink_clicks');
    }
}
