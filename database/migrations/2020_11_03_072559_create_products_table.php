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
            $table->integer('store_id')->comment('店舗PK');
            $table->tinyInteger('type')->nullable()->comment('商品種類'); 
            $table->string('product_name',256)->comment('商品名');
            $table->text('list_reason')->nullable()->comment('出品理由');
            $table->time('available_from')->nullable()->comment('引取可能時間(from)');
            $table->time('available_to')->nullable()->comment('引取可能時間(to)');
            $table->double('price')->nullable()->comment('出品価格（商品価格）');
            $table->dateTime('post_from')->nullable()->comment('掲載期間(from)');
            $table->dateTime('post_to')->nullable()->comment('掲載期間(to)');
            $table->text('introduction')->nullable()->comment('商品紹介');
            $table->integer('quantity')->nullable()->comment('在庫数');
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
        Schema::dropIfExists('products');
    }
}
