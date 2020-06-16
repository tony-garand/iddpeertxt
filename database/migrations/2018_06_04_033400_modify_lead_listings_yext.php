<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyLeadListingsYext extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('lead_listings', function (Blueprint $table) {
			$table->longText('raw_data')->after('listing_phone')->nullable();

			$table->string('match_name_score', 50)->after('listing_phone')->nullable();
			$table->string('match_address_score', 50)->after('listing_phone')->nullable();
			$table->string('match_phone_score', 50)->after('listing_phone')->nullable();

			$table->tinyInteger('match_name')->after('listing_phone')->default(0);
			$table->tinyInteger('match_address')->after('listing_phone')->default(0);
			$table->tinyInteger('match_phone')->after('listing_phone')->default(0);

		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lead_listings', function (Blueprint $table) {
            //
        });
    }
}
