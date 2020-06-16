<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCustomFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('custom1');
            $table->dropColumn('custom2');
            $table->dropColumn('custom3');
            $table->dropColumn('custom4');
            $table->dropColumn('custom5');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->text('custom1')->nullable();
            $table->text('custom2')->nullable();
            $table->text('custom3')->nullable();
            $table->text('custom4')->nullable();
            $table->text('custom5')->nullable();
        });
    }
}
