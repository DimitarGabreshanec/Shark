<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->string('login_id')->comment('ログインID');
            $table->string('name')->comment('名前');
            $table->string('password')->comment('パスワード');
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("ALTER TABLE admins COMMENT 'サイト管理者'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
