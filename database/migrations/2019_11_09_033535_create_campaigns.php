<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('campaigns', function (Blueprint $table) {
			$table->increments('id');
			$table->uuid('uuid')->nullable();
			$table->integer('company_id')->default(0);
			$table->integer('messaging_service_id')->default(0);
			$table->integer('campaign_status')->default(0);
			$table->integer('campaign_type')->default(1);
			$table->string('campaign_name')->nullable();
			$table->text('description')->nullable();
			$table->text('content_template_1')->nullable();
			$table->text('content_template_2')->nullable();
			$table->text('content_template_3')->nullable();
			$table->text('content_template_4')->nullable();
			$table->string('conversion_link_1')->nullable();
			$table->string('conversion_link_2')->nullable();
			$table->string('conversion_link_3')->nullable();
			$table->string('conversion_link_4')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->index('uuid');
			$table->index('campaign_status');
			$table->index('company_id');
		});

		Schema::create('campaign_contacts', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('campaign_id')->default(0);
			$table->integer('contact_id')->default(0);
			$table->integer('cc_status')->default(1);
			$table->integer('user_id')->nullable();
			$table->string('sms_sid')->nullable();
			$table->integer('content_option')->default(0);
			$table->text('content_sent')->nullable();
			$table->integer('audit_locked_by_user')->nullable();
			$table->integer('audit_submit_sms')->nullable();
			$table->integer('audit_sms_sid_rcvd')->nullable();
			$table->integer('audit_sms_stop_rcvd')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->index('campaign_id');
			$table->index('contact_id');
			$table->index('cc_status');
			$table->index('user_id');
		});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::dropIfExists('campaigns');
		Schema::dropIfExists('campaign_contacts');
    }
}
