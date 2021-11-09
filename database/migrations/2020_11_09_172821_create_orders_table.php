<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_no', 64)->nullable()->comment('注文番号');
            $table->tinyInteger('order_type')->default(0)->comment('注文形式');
            $table->tinyInteger('order_status')->default(0)->comment('注文ステータス');
            $table->integer('cart_id')->comment('カートID');
            $table->integer('user_id')->comment('購入者');
            $table->double('cart_price')->nullable()->comment('カート価格');
            $table->double('ship_price')->nullable()->comment('送料');
            $table->double('order_price')->nullable()->comment('注文価格');
            $table->dateTime('ordered_at')->nullable()->comment('注文時間');
            $table->dateTime('paid_at')->nullable()->comment('決済時間');
            $table->dateTime('completed_at')->nullable()->comment('完了時間');
            $table->string('last_name', 128)->nullable()->comment('名前（氏）');
            $table->string('last_name_kana', 128)->nullable()->comment('名前（氏）フリガナ');
            $table->string('first_name', 128)->nullable()->comment('名前（名）');
            $table->string('first_name_kana', 128)->nullable()->comment('名前（名）フリガナ');
            $table->string('post_first', 3)->nullable()->comment('郵便番号（前）');
            $table->string('post_second',4)->nullable()->comment('郵便番号（後）');
            $table->integer('prefecture')->nullable()->comment('都道府県');
            $table->string('address1')->nullable()->comment('住所１');
            $table->string('address2')->nullable()->comment('住所２');
            $table->string('address3', 128)->nullable()->comment('ビル名');
            $table->string('tel')->nullable()->comment('電話番号');
            $table->text('order_note')->nullable()->comment('受注メモ');
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
        Schema::dropIfExists('orders');
    }
}
