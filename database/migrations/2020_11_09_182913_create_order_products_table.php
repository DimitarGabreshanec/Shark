<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->comment('注文ID');
            $table->integer('cart_id')->comment('カートID');
            $table->integer('user_id')->comment('購入者ID');
            $table->integer('product_id')->comment('商品ID');
            $table->integer('quantity')->default(0)->comment('商品数量');
            $table->double('price')->nullable()->comment('商品単価');
            $table->tinyInteger('discount_type')->nullable()->comment('割引形式');
            $table->double('discount')->nullable()->comment('割引額（率）');
            $table->double('discounted_price')->nullable()->comment('割引価格');
            $table->double('tax_price')->nullable()->comment('税料');
            $table->double('products_price')->nullable()->comment('商品本価格');
            $table->double('total_price')->nullable()->comment('総商品価格');
            $table->text('note')->nullable()->comment('備考');
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
        Schema::dropIfExists('order_products');
    }
}
