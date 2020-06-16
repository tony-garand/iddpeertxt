<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoTemplateToRepos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $id = DB::table('repo_types')
            ->insertGetId([
                'name' => 'no-template'
            ]);
        
        DB::table('repos')
            ->insert([
                'bb_source' => 'no-template',
                'repo_type_id' => $id,
                'theme_name' => 'no-template'
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('repos')->where('bb_source', '=', 'no-template')->delete();
        DB::table('repo_types')
            ->where('name', '=', 'no-template')->delete();
    }
}
