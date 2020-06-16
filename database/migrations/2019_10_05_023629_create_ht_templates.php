<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHtTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
		Schema::create('hypertargeting_templates', function (Blueprint $table) {
			$table->increments('id');
			$table->string('template_name')->nullable();
			$table->string('repo_url')->nullable();
			$table->string('server_path')->nullable();
			$table->string('server_command_1')->nullable();
			$table->string('server_command_2')->nullable();
			$table->string('server_command_3')->nullable();
			$table->string('server_command_4')->nullable();
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
		Schema::dropIfExists('hypertargeting_templates');
	}
}
