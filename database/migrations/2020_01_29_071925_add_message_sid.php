<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMessageSid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('chat_threads', function (Blueprint $table) {
			$table->string('sms_sid')->nullable()->after('audit_sms_sent')->index();
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
			$table->dropColumn('audit_sms_sent');
	});
    }
}
