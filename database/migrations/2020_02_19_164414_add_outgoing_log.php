<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOutgoingLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('log_outgoing', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('campaign_id');
			$table->integer('campaign_contact_id')->unique();
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
		Schema::dropIfExists('log_outgoing');
    }
}
