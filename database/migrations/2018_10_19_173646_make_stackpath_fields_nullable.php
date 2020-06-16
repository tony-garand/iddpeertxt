<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeStackpathFieldsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servers', function(Blueprint $table) {
            $table->string('stackpath_id')->nullable()->change();
            $table->string('node_ip')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servers', function(Blueprint $table) {
            $table->string('stackpath_id')->nullable(false)->change();
            $table->string('node_ip')->nullable(false)->change();
        });
    }
}
