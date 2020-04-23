<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->integer('status');
            $table->dateTime('billing_at')->nullable();
            $table->dateTime('receipt_at')->nullable();
            $table->dateTime('export_at')->nullable();
            $table->float('sale_excluded_price',10,2)->nullable();
            $table->float('sale_included_price',10,2)->nullable();
            $table->integer('table_id');
            $table->string('table_name');
            $table->integer('user_id');
            $table->string('user_name');
            $table->timestamps();
        });

        Schema::create('receipt_product', function (Blueprint $table) {
            $table->integer('receipt_id');
            $table->integer('product_id');
            $table->integer('quantity');
            $table->string('note')->nullable();
            $table->string('product_name');
            $table->float('product_price',10,2);
            $table->float('product_sale_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipt_product');
        Schema::dropIfExists('receipts');
    }
}
