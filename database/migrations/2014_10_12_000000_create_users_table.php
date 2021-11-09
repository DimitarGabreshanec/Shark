<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('member_no', 16)->nullable()->comment('UID');
            $table->string('name', 32)->nullable()->comment('お名前');
            $table->string('email',32)->comment('メール');
            $table->dateTime('birthday')->nullable()->comment('生年月日');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email_verify_token')->nullable()->comment('メール確認トークン');
            $table->string('password', 32)->nullable()->comment('パスワード');
            $table->tinyInteger('gender')->nullable()->comment('性別');
            $table->tinyInteger('status')->default(0)->nullable()->comment('登録ステータス');
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
        Schema::dropIfExists('users');
    }
}
