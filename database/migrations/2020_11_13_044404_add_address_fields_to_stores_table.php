<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressFieldsToStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('post_second',4)->nullable()->comment('郵便番号（後）');
            $table->string('post_first', 3)->nullable()->comment('郵便番号（前）');
            $table->integer('prefecture')->nullable()->comment('都道府県');
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
            $table->dropColumn('prefecture');
            $table->dropColumn('post_first');
            $table->dropColumn('post_second');
        });
    }
}
