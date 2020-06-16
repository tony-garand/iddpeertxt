<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMmsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('campaigns', function (Blueprint $table) {
			$table->string('content_media_1')->after('conversion_link_4')->nullable();
			$table->string('content_media_2')->after('content_media_1')->nullable();
			$table->string('content_media_3')->after('content_media_2')->nullable();
			$table->string('content_media_4')->after('content_media_3')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
			$table->dropColumn('content_media_1');
			$table->dropColumn('content_media_2');
			$table->dropColumn('content_media_3');
			$table->dropColumn('content_media_4');
        });
    }
}
