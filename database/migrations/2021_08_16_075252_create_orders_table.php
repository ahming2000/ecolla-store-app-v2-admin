<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('mode')->default('pickup'); // pickup/delivery
            $table->string('delivery_id')->nullable(); // Use to track delivery mode's delivery status
            $table->double('shipping_fee')->default(0.0);
            $table->string('payment_method'); // boost/tng/bank-in/quin-pay/duit-now
            $table->string('status')->default('pending'); // pending/prepared/completed/refunded/canceled
            $table->string('receipt_image');

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
        Schema::dropIfExists('orders');
    }
}
