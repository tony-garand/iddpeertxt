<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsThreads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('sms_convo_threads', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('sms_convo_id')->nullable();
			$table->string('messaging_service_sid')->nullable();
			$table->string('sms_message_sid')->nullable();
			$table->string('account_sid')->nullable();
			$table->string('from')->nullable();
			$table->string('to')->nullable();
			$table->timestamps();
			$table->index('sms_convo_id');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_convo_threads');
    }
}
