<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variation_discounts', function (Blueprint $table) {
            $table->foreignId('variation_id')->primary()->constrained('variations')->cascadeOnDelete();
            $table->dateTime('start');
            $table->dateTime('end')->nullable();
            $table->double('rate')->default(1.0);

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
        Schema::dropIfExists('variation_discounts');
    }
}
