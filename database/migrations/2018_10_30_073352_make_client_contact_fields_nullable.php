<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeClientContactFieldsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_contacts', function (Blueprint $table) {
            $table->string('occupation')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('mobile_phone')->nullable()->change();
            $table->string('work_phone')->nullable()->change();
            $table->string('fax')->nullable()->change();
            $table->text('comments')->nullable()->change();
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
            $table->string('occupation')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->string('mobile_phone')->nullable(false)->change();
            $table->string('work_phone')->nullable(false)->change();
            $table->string('fax')->nullable(false)->change();
            $table->text('comments')->nullable(false)->change();
        });
    }
}
