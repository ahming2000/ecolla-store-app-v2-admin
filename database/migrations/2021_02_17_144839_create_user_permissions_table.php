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

            $table->boolean('item_view');
            $table->boolean('item_create');
            $table->boolean('item_update');
            $table->boolean('item_delete');
            $table->boolean('item_list');

            $table->boolean('order_view');
            $table->boolean('order_update');
            $table->boolean('order_delete');
            $table->boolean('order_receipt_view');
            $table->boolean('order_invoice_download');

            $table->boolean('order_item_view');
            $table->boolean('order_item_create');
            $table->boolean('order_item_update');
            $table->boolean('order_item_delete');

            $table->boolean('dashboard_view');

            $table->boolean('setting_view');
            $table->boolean('setting_item');
            $table->boolean('setting_order');
            $table->boolean('setting_pagination');
            $table->boolean('setting_account');

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
