<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRollups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('campaigns', function (Blueprint $table) {
			$table->integer('rollup_total')->default(0);
			$table->integer('rollup_completed')->default(0);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('campaigns', function (Blueprint $table) {
			$table->removeColumn('rollup_total');
			$table->removeColumn('rollup_completed');
		});
    }
}
