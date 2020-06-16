<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadListings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_listings', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('lead_id')->unsigned();
			$table->tinyInteger('is_missing')->default(0);
			$table->string('listing_type');
			$table->tinyInteger('listing_name_status')->default(0);
			$table->tinyInteger('listing_address_status')->default(0);
			$table->tinyInteger('listing_phone_status')->default(0);
			$table->string('listing_name');
			$table->string('listing_address');
			$table->string('listing_phone');
			$table->timestamps();
			$table->index('lead_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_listings');
    }
}
