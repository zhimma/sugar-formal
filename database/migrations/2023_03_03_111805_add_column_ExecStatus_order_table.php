<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnExecStatusOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('order', 'ExecStatus')) {
            Schema::table('order', function (Blueprint $table) {
                $table->string('business_id', 50)->nullable()->after('user_id');
                $table->text('pay_fail')->nullable()->after('pay_date');
                $table->tinyInteger('ExecStatus')->nullable()->after('amount');
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
        //
        if (!Schema::hasColumn('order', 'ExecStatus')) {
            Schema::table('order', function (Blueprint $table) {
                $table->dropColumn('business_id');
                $table->dropColumn('pay_fail');
                $table->dropColumn('ExecStatus');
            });

        }
    }
}
