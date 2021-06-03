<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImportSystemConfigs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
         * Config naming convention
         *
         * {website_type}_{module_name}_{description}
         *
         * website_type : Website that use this config to generate content
         * e.g. clt (client), mgmt (management)
         *
         * module_name : Related module
         * e.g. i (item), o (order)
         *
         * description : Config description in Chinese

         */

        DB::table('system_configs')->insert(
            array(
                'name' => 'clt_o_codePrefix',
                'value' => 'ECOLLA',
                'desc' => '网站的订单编号开头',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            )
        );

        DB::table('system_configs')->insert(
            array(
                'name' => 'clt_i_recordPerPage',
                'value' => '24',
                'desc' => '在顾客商品主页，一页可显示的商品数量',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            )
        );

        DB::table('system_configs')->insert(
            array(
                'name' => 'mgmt_o_recordPerPage',
                'value' => '5',
                'desc' => '在管理员订单查看页面，一页可显示的商品数量',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            )
        );

        DB::table('system_configs')->insert(
            array(
                'name' => 'clt_o_shippingFeeKampar',
                'value' => '3',
                'desc' => '运费，仅限于金宝',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            )
        );

        DB::table('system_configs')->insert(
            array(
                'name' => 'mgmt_i_defaultCategoryCount',
                'value' => '4',
                'desc' => 'Fixed and not modifiable for management',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
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

    }
}
