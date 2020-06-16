<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMicros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_contacts', function (Blueprint $table) {
			$table->string('audit_locked_by_user')->change();
			$table->string('audit_submit_sms')->change();
			$table->string('audit_sms_sid_rcvd')->change();
			$table->string('audit_sms_stop_rcvd')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_contacts', function (Blueprint $table) {
            //
        });
    }
}
