<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsClientCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients_client_categories', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients');

            $table->integer('client_category_id')->unsigned();
            $table->foreign('client_category_id')->references('id')->on('client_categories');

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

        Schema::table('clients_client_categories', function (Blueprint $table) {
            $table->dropForeign(['client_id','client_category_id']);
        });
        Schema::dropIfExists('clients_client_categories');
    }
}
