<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImportOrderDiscountConfigSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('system_configs')->insert(
            array(
                'name' => 'clt_o_shipping_discount',
                'value' => '1',
                'desc' => '订单免运费开关',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            )
        );

        DB::table('system_configs')->insert(
            array(
                'name' => 'clt_o_shipping_discount_threshold',
                'value' => '50',
                'desc' => '订单免运费触发价格',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            )
        );

        DB::table('system_configs')->insert(
            array(
                'name' => 'clt_o_shipping_discount_desc',
                'value' => '满RM50包邮',
                'desc' => '订单免运费详情',
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
        //
    }
}
