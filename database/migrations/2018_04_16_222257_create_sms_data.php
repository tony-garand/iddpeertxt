<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('sms_data', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('status')->default(1);
			$table->integer('client_id')->default(0);
			$table->string('from')->nullable();
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('email')->nullable();
			$table->index('client_id');
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
        Schema::dropIfExists('sms_data');
    }
}
