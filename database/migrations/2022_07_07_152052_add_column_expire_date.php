<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnExpireDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('is_warned_log', function (Blueprint $table) {
            if (!Schema::hasColumn('is_warned_log', 'expire_date')) {
                Schema::table('is_warned_log', function (Blueprint $table) {
                    $table->timestamp('expire_date')->nullable()->after('reason');
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
        Schema::table('is_warned_log', function (Blueprint $table) {
            Schema::table('is_warned_log', function (Blueprint $table) {
                $table->dropColumn('expire_date');
            });
        });
    }
}
