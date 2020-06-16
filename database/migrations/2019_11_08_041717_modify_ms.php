<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyMs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('messaging_services', function (Blueprint $table) {
			$table->integer('company_id')->default(0)->after('id');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('messaging_services', function (Blueprint $table) {
			$table->removeColumn('company_id');
		});
    }
}
