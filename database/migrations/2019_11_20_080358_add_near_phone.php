<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNearPhone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('campaigns', function (Blueprint $table) {
			$table->string('nearphone')->nullable();
		});

		Schema::table('companies', function (Blueprint $table) {
			$table->string('default_nearphone')->nullable();
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
			$table->removeColumn('nearphone');
		});

		Schema::table('companies', function (Blueprint $table) {
			$table->removeColumn('default_nearphone');
		});

    }
}
