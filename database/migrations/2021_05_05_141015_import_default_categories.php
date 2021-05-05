<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImportDefaultCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::table('categories')->insert(
            array(
                'id' => 1,
                'name' => '未分类',
                'name_en' => 'Uncategorized'
            )
        );

        DB::table('categories')->insert(
            array(
                'id' => 2,
                'name' => '热卖',
                'name_en' => 'Hot Selling'
            )
        );

        DB::table('categories')->insert(
            array(
                'id' => 3,
                'name' => '新品',
                'name_en' => 'New Product'
            )
        );

        DB::table('categories')->insert(
            array(
                'id' => 4,
                'name' => '推荐',
                'name_en' => 'Recommended'
            )
        );


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('categories')->where('id', '=', 1)->delete();
        DB::table('categories')->where('id', '=', 2)->delete();
        DB::table('categories')->where('id', '=', 3)->delete();
        DB::table('categories')->where('id', '=', 4)->delete();
    }
}
