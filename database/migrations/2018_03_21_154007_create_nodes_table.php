<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('nodes', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('status')->default(0);
			$table->integer('node_id');
			$table->string('name');
			$table->string('ip')->nullable();
			$table->string('location');
			$table->string('vcpus');
			$table->string('ram');
			$table->string('storage');
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
		Schema::dropIfExists('nodes');
	}
}
