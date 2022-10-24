<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCard4noOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('order', function (Blueprint $table) {
            if (!Schema::hasColumn('order', 'card4no')) {
                Schema::table('order', function (Blueprint $table) {
                    $table->string('card4no', 4)->nullable()->after('pay_date')->comment('卡號末4碼');
                    $table->string('card6no', 6)->nullable()->after('card4no')->comment('卡號前6碼');
                });
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('order', function (Blueprint $table) {
            Schema::table('order', function (Blueprint $table) {
                $table->dropColumn('card4no');
                $table->dropColumn('card6no');
            });
        });
    }
}
