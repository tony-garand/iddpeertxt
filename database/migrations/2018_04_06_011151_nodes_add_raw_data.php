<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NodesAddRawData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('nodes', function (Blueprint $table) {
			$table->integer('memory')->after('storage')->default(0);
			$table->integer('disk')->after('storage')->default(0);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nodes', function (Blueprint $table) {
            //
        });
    }
}
