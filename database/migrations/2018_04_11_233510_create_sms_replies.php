<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsReplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('sms_convo_thread_replies', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('sms_convo_thread_id');
			$table->integer('sms_convo_script_id')->nullable();
			$table->text('reply_body');
			$table->timestamps();
			$table->index('sms_convo_thread_id');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_convo_thread_replies');
    }
}
