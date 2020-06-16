<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('contacts', function (Blueprint $table) {
			$table->integer('sms_stopped')->default(0);
			$table->integer('verified_phone')->default(0);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('contacts', function (Blueprint $table) {
			$table->removeColumn('sms_stopped');
			$table->removeColumn('verified_phone');
		});
    }
}
