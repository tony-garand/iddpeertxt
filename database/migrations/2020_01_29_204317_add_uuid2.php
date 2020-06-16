<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUuid2 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('chat_threads', function (Blueprint $table) {
			$table->uuid('uuid')->nullable()->after('id')->index();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('chat_threads', function (Blueprint $table) {
			$table->dropColumn('uuid');
		});
	}
}
