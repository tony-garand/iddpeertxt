<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->text('note');
            $table->boolean('manual')->default(false);
            $table->timestamps();

            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')
                ->references('id')
                ->on('clients');

            $table->integer('author_id')->unsigned()->nullable();
            $table->foreign('author_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_notes', function (Blueprint $table) {
            $table->dropForeign(['client_id'], ['author_id']);
            $table->dropColumn(['client_id'], ['author_id']);
        });
        Schema::dropIfExists('client_notes');
    }
}