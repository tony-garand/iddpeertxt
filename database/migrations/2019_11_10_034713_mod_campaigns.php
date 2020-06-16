<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModCampaigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('campaigns', function (Blueprint $table) {
			$table->string('zipcode')->nullable();
			$table->string('areacode')->nullable();
		});

		Schema::table('companies', function (Blueprint $table) {
			$table->string('default_zipcode')->nullable();
			$table->string('default_areacode')->nullable();
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
			$table->removeColumn('zipcode');
			$table->removeColumn('areacode');
		});

		Schema::table('companies', function (Blueprint $table) {
			$table->removeColumn('default_zipcode');
			$table->removeColumn('default_areacode');
		});

    }
}
