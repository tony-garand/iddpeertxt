<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeToConvosCompant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sms_convos', function (Blueprint $table) {
			$table->renameColumn('client_id', 'company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sms_convos', function (Blueprint $table) {
			$table->renameColumn('company_id', 'client_id');
        });
    }
}
