<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('contacts', function (Blueprint $table) {
			$table->increments('id');
			$table->uuid('uuid')->nullable();
			$table->integer('company_id')->default(0);
			$table->integer('status')->default(1);
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('phone');
			$table->string('email')->nullable();
			$table->string('address1')->nullable();
			$table->string('address2')->nullable();
			$table->string('city')->nullable();
			$table->string('state')->nullable();
			$table->string('zip')->nullable();
			$table->string('url')->nullable();
			$table->text('custom1')->nullable();
			$table->text('custom2')->nullable();
			$table->text('custom3')->nullable();
			$table->text('custom4')->nullable();
			$table->text('custom5')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->index('uuid');
			$table->index('status');
			$table->index('company_id');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::dropIfExists('contacts');
    }
}
