<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefundColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            if (!Schema::hasColumn('order', 'refund_amount')) {
                Schema::table('order', function (Blueprint $table) {
                    $table->boolean('need_to_refund')->default(false)->comment('1:需要退費');
                    $table->integer('refund_amount')->nullable()->comment('退費金額 有值時代表需要退費 退費完成時need_to_refund設為0');
                });
            }
        });
        Schema::table('member_value_added_service_log', function (Blueprint $table) {
            if (!Schema::hasColumn('member_value_added_service_log', 'refund_amount')) {
                Schema::table('member_value_added_service_log', function (Blueprint $table) {
                    $table->boolean('need_to_refund')->default(false)->comment('1:需要退費');
                    $table->integer('refund_amount')->nullable()->comment('退費金額 有值時代表需要退費 退費完成時need_to_refund設為0');
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
        Schema::table('order', function (Blueprint $table) {
            if (Schema::hasColumn('order', 'refund_amount')) {
                Schema::table('order', function (Blueprint $table) {
                    $table->dropColumn('need_to_refund');
                    $table->dropColumn('refund_amount');
                });
            }
        });
        Schema::table('member_value_added_service_log', function (Blueprint $table) {
            if (Schema::hasColumn('member_value_added_service_log', 'refund_amount')) {
                Schema::table('member_value_added_service_log', function (Blueprint $table) {
                    $table->dropColumn('need_to_refund');
                    $table->dropColumn('refund_amount');
                });
            }
        });
    }
}
