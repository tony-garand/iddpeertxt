<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsConvos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('sms_convos', function (Blueprint $table) {
			$table->increments('id');
			$table->string('trigger', 100);
			$table->tinyInteger('all_locations')->default(0);
			$table->timestamps();
			$table->index('trigger');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_convos');
    }
}
