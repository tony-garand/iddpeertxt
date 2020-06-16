<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignDeliveryRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_delivery_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('campaign_id');
            $table->integer('day');
            $table->boolean('whole_day')->default(1);
            $table->integer('from_time')->nullable();
            $table->integer('to_time')->nullable();
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
        Schema::dropIfExists('campaign_delivery_rules');
    }
}
