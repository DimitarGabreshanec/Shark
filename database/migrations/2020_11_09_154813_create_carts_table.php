<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('購入者ID');
            $table->double('products_price')->nullable()->comment('商品本価格');
            $table->double('discounted_price')->nullable()->comment('割引価格');
            $table->double('tax_price')->nullable()->comment('税料');
            $table->double('cart_price')->nullable()->comment('カート価格');
            $table->tinyInteger('cart_status')->default(0)->comment('カートステータス');
            $table->text('note')->nullable()->comment('備考');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
