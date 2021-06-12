<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->constrained('users')->cascadeOnDelete();

            $table->boolean('item_view')->default(true);
            $table->boolean('item_create')->default(false);
            $table->boolean('item_update')->default(false);
            $table->boolean('item_delete')->default(false);
            $table->boolean('item_list')->default(false);

            $table->boolean('order_view')->default(true);
            $table->boolean('order_update')->default(false);
            $table->boolean('order_delete')->default(false);
            $table->boolean('order_receipt_view')->default(false);
            $table->boolean('order_invoice_download')->default(false);

            $table->boolean('order_item_view')->default(true);
            $table->boolean('order_item_create')->default(false);
            $table->boolean('order_item_update')->default(false);
            $table->boolean('order_item_delete')->default(false);

            $table->boolean('dashboard_view')->default(true);

            $table->boolean('setting_view')->default(true);
            $table->boolean('setting_item')->default(false);
            $table->boolean('setting_order')->default(false);
            $table->boolean('setting_pagination')->default(false);
            $table->boolean('setting_account')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_permissions');
    }
}
