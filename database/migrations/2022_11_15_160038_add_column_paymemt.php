<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPaymemt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('member_vip_expiry_log', 'payment')) {
            Schema::table('member_vip_expiry_log', function (Blueprint $table) {
                $table->string('payment', 255)->nullable()->after('member_id');
                $table->tinyInteger('is_cancel')->unsigned()->default(0)->after('payment');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('member_vip_expiry_log', 'payment')) {
            Schema::table('member_vip_expiry_log', function (Blueprint $table) {
                $table->dropColumn('payment');
                $table->dropColumn('is_cancel');
            });
        }
    }
}
