<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemUtilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_utils', function (Blueprint $table) {
            $table->foreignId('item_id')->primary()->constrained('items')->cascadeOnDelete();
            $table->boolean('is_listed')->default('0');
            $table->bigInteger('view_count')->default(0);
            $table->bigInteger('sold')->default(0);

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
        Schema::dropIfExists('item_utils');
    }
}
