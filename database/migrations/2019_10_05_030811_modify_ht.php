<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyHt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
		Schema::table('hypertargeting', function (Blueprint $table) {
			$table->integer('hypertargeting_template_id')->default(0)->after('status');
			$table->string('recaptcha_secret_key')->nullable();
			$table->string('stripe_secret_key')->nullable();
			$table->string('stripe_public_key')->nullable();
			$table->text('privacy_policy')->nullable();
			$table->text('contact_block')->nullable();
		});
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
	public function down()
	{
		Schema::table('hypertargeting', function (Blueprint $table) {
			$table->removeColumn('hypertargeting_template_id');
			$table->removeColumn('recaptcha_secret_key');
			$table->removeColumn('stripe_secret_key');
			$table->removeColumn('stripe_public_key');
			$table->removeColumn('privacy_policy');
			$table->removeColumn('contact_block');
		});
	}
}
