<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_credentials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label')->default('');
            $table->text('value')->nullable();
            $table->string('image_url')->default('');
            $table->string('image_path')->default('');

            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients');

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
        Schema::table('client_credentials', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
        });
        Schema::dropIfExists('client_credentials');
    }
}