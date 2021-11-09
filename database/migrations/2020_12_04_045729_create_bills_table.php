<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id')->comment('店舗ID');
            $table->tinyInteger('status')->default(0)->comment('振込ステータス');
            $table->double('fix_price')->nullable()->comment('店頭売上');
            $table->double('ec_price')->nullable()->comment('通販売上');
            $table->double('fix_fee')->nullable()->comment('店頭手数料');
            $table->double('ec_fee')->nullable()->comment('通販手数料');
            $table->double('total_price')->nullable()->comment('合計');
            $table->json('fix_order_products')->nullable()->comment('注文・商品テーブルID（店頭）');
            $table->json('ec_order_products')->nullable()->comment('注文・商品テーブルID（通販）');
            $table->double('fee_fix')->nullable()->comment('月額（円）');
            $table->double('fee_percent')->nullable()->comment('手数料（%）');
            $table->dateTime('applied_at')->nullable()->comment('申請日時');
            $table->dateTime('completed_at')->nullable()->comment('完了日時');
            $table->text('comment')->nullable()->comment('コメント');

            $table->integer('created_by')->comment('データ作成者ID');
            $table->integer('updated_by')->comment('データ最終更新者ID');
            $table->integer('deleted_by')->nullable()->comment('データ論理削除者ID');
  
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
        Schema::dropIfExists('bills');
    }
}
