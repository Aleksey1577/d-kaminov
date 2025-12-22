<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->string('naimenovanie');
            $table->decimal('price', 10, 2);
            $table->string('image_url')->nullable();
            $table->string('kategoriya');
            $table->string('v_nalichii_na_sklade');
            $table->text('opisanije')->nullable();
            $table->string('sku')->nullable();
            $table->string('proizvoditel')->nullable();
            $table->decimal('price2', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
