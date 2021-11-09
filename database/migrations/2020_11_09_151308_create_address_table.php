<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('ユーザーID');
            $table->string('last_name', 128)->nullable()->comment('名前（氏）');
            $table->string('first_name', 128)->nullable()->comment('名前（名）');
            $table->string('last_name_kana', 128)->nullable()->comment('名前（氏）フリガナ');
            $table->string('first_name_kana', 128)->nullable()->comment('名前（名）フリガナ');
            $table->string('post_first')->nullable()->comment('郵便番号（前）');
            $table->string('post_second')->nullable()->comment('郵便番号（後）');
            $table->integer('prefecture')->nullable()->comment('都道府県');
            $table->string('address1')->nullable()->comment('住所１');
            $table->string('address2')->nullable()->comment('住所２');
            $table->string('address3', 128)->nullable()->comment('ビル名');
            $table->string('tel')->nullable()->comment('電話番号');
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
        Schema::dropIfExists('saves');
    }
}
