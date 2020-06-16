<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('contact_type');
            $table->string('name');
            $table->string('occupation');
            $table->string('email');
            $table->string('mobile_phone');
            $table->string('work_phone');
            $table->string('fax');
            $table->text('comments');
            $table->timestamps();

            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')
                ->references('id')
                ->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_contacts', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->removeColumn('client_id');
        });
        Schema::dropIfExists('client_contacts');
    }
}
