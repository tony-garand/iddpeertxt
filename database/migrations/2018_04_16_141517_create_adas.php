<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('adas', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('status')->default(0);
			$table->string('key')->nullable();
			$table->string('name')->nullable();
			$table->string('domain')->nullable();
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
        Schema::dropIfExists('adas');
    }
}
