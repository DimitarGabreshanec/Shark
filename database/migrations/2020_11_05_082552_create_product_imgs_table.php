<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductImgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_imgs', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->comment('商品PK');
            $table->tinyInteger('kind')->default(2)->comment('画像種類');
            $table->string('img_name',256)->comment('ファイル名');
            $table->integer('sequence')->default(0)->comment('サーブ画像の表示順序');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_imgs');
    }
}
