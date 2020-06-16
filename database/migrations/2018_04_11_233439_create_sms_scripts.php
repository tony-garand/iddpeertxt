<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsScripts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('sms_convo_scripts', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('sms_convo_id');
			$table->smallInteger('step')->default(0);
			$table->text('script_body');
			$table->string('data_destination')->nullable();
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
        Schema::dropIfExists('sms_convo_scripts');
    }
}
