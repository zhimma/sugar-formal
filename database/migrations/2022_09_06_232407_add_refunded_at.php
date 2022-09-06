<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefundedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_value_added_service', function (Blueprint $table) {
            //
            $table->timestamp('refunded_at')->nullable()->after('refund_amount');
        });
        Schema::table('order', function (Blueprint $table) {
            //
            $table->timestamp('refunded_at')->nullable()->after('refund_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_value_added_service', function (Blueprint $table) {
            //
            $table->dropColumn('refunded_at');
        });
        Schema::table('order', function (Blueprint $table) {
            //
            $table->dropColumn('refunded_at');
        });
    }
}
