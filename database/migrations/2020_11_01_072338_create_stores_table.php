<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('store_no', 16)->nullable()->comment('店舗番号');
            $table->string('email',64)->comment('ログインID（メール）');
            $table->string('password', 64)->nullable()->comment('パスワード');
            $table->tinyInteger('type')->nullable()->comment('種別');
            $table->string('store_name', 256)->nullable()->comment('会社名（店舗名）');
            $table->string('store_address', 256)->nullable()->comment('会社所在地');
            $table->string('tel',32)->nullable()->comment('電話番号');
            $table->string('charger_name',32)->nullable()->comment('担当者名');
            $table->time('work_from')->nullable()->comment('営業時間(from)');
            $table->time('work_to')->nullable()->comment('営業時間(to)');
            $table->string('url')->nullable()->comment('URL');
            $table->text('detail')->nullable()->comment('店舗紹介');
            $table->tinyInteger('status')->default(0)->comment('登録ステータス');
            $table->dateTime('last_login_at')->nullable()->comment('最終ログイン日時');
            $table->timestamp('email_verified_at')->nullable()->comment('メール確認日時');
            $table->string('email_verify_token')->nullable()->comment('メール確認トークン');
            $table->text('note')->nullable()->comment('備考');
            $table->rememberToken();
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
        Schema::dropIfExists('stores');
    }
}
