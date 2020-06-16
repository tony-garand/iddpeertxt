<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStackpathIdToServers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servers', function(Blueprint $table) {
            $table->string('stackpath_id');
            $table->string('node_ip');
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
            $table->dropColumn('stackpath_id');
            $table->dropColumn('node_ip');
        });
    }
}
