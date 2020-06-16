<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('sms_convo_messaging_services', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('sms_convo_id');
			$table->integer('messaging_service_id');
			$table->timestamps();
			$table->index('sms_convo_id');
			$table->index('messaging_service_id');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_convo_locations');
    }
}
