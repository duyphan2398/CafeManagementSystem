<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('days');
            $table->float('sale_percent', 10, 2);
            $table->timestamps();
        });

        Schema::create('product_promotion', function (Blueprint $table) {
            $table->integer('product_id');
            $table->integer('promotion_id');
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
        Schema::dropIfExists('promotions');
    }
}
