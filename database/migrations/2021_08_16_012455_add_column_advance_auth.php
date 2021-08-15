<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAdvanceAuth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('users', 'advance_auth_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->smallInteger('advance_auth_status')->nullable()->default(0);
            });
        }

        if (!Schema::hasColumn('users', 'vip_pass')) {
            Schema::table('users', function (Blueprint $table) {
                $table->smallInteger('advance_auth_status')->nullable()->default(0);
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('advance_auth_status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('advance_auth_status');
        });
    }
}
