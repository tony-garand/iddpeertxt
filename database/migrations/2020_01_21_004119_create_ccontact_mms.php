<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCcontactMms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('campaign_contacts', function (Blueprint $table) {
			$table->string('mms_sent')->after('content_sent')->nullable();
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
			$table->dropColumn('mms_sent');
		});
    }

}
