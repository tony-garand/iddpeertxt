<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagingServiceNumbers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messaging_service_numbers', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('messaging_service_id')->unsigned();
			$table->string('number')->nullable();
            $table->timestamps();
			$table->index('messaging_service_id');
			$table->foreign('messaging_service_id')->references('id')->on('messaging_services');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messaging_service_numbers');
    }
}
