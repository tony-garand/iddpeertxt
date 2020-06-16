<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignReplyNotify extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('campaign_reply_notifications', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('campaign_id')->index();
			$table->integer('user_id')->index();
			$table->integer('reply_count')->default(0)->index();
			$table->boolean('in_use')->default(false)->index();
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
		Schema::dropIfExists('campaign_reply_notifications');
	}
}
