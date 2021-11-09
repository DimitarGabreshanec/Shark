<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankTranserToStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->integer('bank')->nullable()->after('detail')->comment('銀行名');
            $table->integer('bank_branch')->nullable()->after('detail')->comment('支店名');
            $table->integer('account_type')->nullable()->after('detail')->comment('口座種別');
            $table->string('account_no', 256)->nullable()->after('detail')->comment('口座番号');
            $table->string('account_name', 256)->nullable()->after('detail')->comment('口座名(半角カタカナ)');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            //
        });
    }
}
