<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('price', 10,2);
            $table->float('sale_price', 10, 2)->nullable()->default(null);
            $table->string('url')->default('default_url_product.png');
            $table->string('type');
            $table->integer('promotion_id')->nullable();
            $table->timestamps();
        });

        // This table was created from Many-to-many relations (products - material)
        Schema::create('ingredients', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('material_id');
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
            $table->float('quantity', 10, 2)->default(0);
            $table->string('unit');
            $table->primary(['product_id', 'material_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('ingredients');
    }
}
