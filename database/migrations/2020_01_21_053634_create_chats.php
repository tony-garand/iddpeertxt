<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('chats', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->uuid('uuid')->nullable()->index();
			$table->integer('company_id')->default(0)->index();
			$table->integer('campaign_id')->default(0)->index();
			$table->integer('contact_id')->default(0)->index();
			$table->integer('overall_status')->default(0)->index();
			$table->timestamps();
		});

		Schema::create('chat_threads', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('chat_id')->default(0)->index();
			$table->integer('direction')->default(1)->index();
			$table->integer('status')->default(0)->index();
			$table->string('subject')->nullable();
			$table->text('message')->nullable();
			$table->text('private_notes')->nullable();
			$table->string('media_url')->nullable();
			$table->string('user_id')->nullable();
			$table->string('audit_sms_rcvd')->nullable();
			$table->string('audit_sms_sent')->nullable();
			$table->timestamps();
		});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::dropIfExists('chats');
		Schema::dropIfExists('chat_threads');
    }
}
