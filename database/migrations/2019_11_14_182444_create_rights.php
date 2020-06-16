<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRights extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('campaigns', function (Blueprint $table) {
			$table->integer('rights_type')->default(1); // 1 = everyone access, 2 = rights access, 0 = no access
		});

		Schema::create('groups', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('company_id')->default(0);
			$table->integer('status')->default(1);
			$table->string('name')->nullable();
			$table->text('description')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->index('company_id');
			$table->index('status');
		});

		Schema::create('group_users', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('group_id')->default(0);
			$table->integer('user_id')->default(0);
			$table->timestamps();
			$table->index('group_id');
			$table->index('user_id');
		});

		Schema::create('rights', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('company_id')->default(0);
			$table->integer('status')->default(1);
			$table->string('name')->nullable();
			$table->text('description')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->index('company_id');
			$table->index('status');
		});

		Schema::create('right_groups', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('right_id')->default(0);
			$table->integer('group_id')->default(0);
			$table->timestamps();
			$table->index('right_id');
			$table->index('group_id');
		});

		Schema::create('right_users', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('right_id')->default(0);
			$table->integer('user_id')->default(0);
			$table->timestamps();
			$table->index('right_id');
			$table->index('user_id');
		});

		Schema::create('campaign_rights', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('campaign_id')->default(0);
			$table->integer('right_id')->default(0);
			$table->timestamps();
			$table->index('campaign_id');
			$table->index('right_id');
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
			$table->removeColumn('rights_type');
		});

		Schema::dropIfExists('groups');
		Schema::dropIfExists('group_users');
		Schema::dropIfExists('rights');
		Schema::dropIfExists('right_groups');
		Schema::dropIfExists('right_users');
		Schema::dropIfExists('campaign_rights');

    }
}
