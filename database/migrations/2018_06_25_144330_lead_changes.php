<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LeadChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
			$table->tinyInteger('yext_scan_status')->default(0)->after('sub_status');
			$table->tinyInteger('google_scan_status')->default(0)->after('sub_status');
			$table->renameColumn('sub_status', 'overall_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            //
        });
    }
}
