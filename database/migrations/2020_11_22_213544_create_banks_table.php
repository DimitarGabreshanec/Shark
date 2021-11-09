<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('bank_code', 4)->comment('銀行コード');
            $table->string('branch_code', 3)->comment('支店コード');
            $table->string('name_kana', 128)->comment('名称カナ');
            $table->string('name', 128)->comment('名称');
            $table->tinyInteger('type')->comment('名称区分');
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
        Schema::dropIfExists('banks');
    }
}
