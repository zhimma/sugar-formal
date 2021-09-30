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

        if (!Schema::hasColumn('users', 'advance_auth_timestamp')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('advance_auth_timestamp', $precision = 0);
            });
        }

        if (!Schema::hasColumn('users', 'advance_auth_identitiy_no')) {
            Schema::table('users', function (Blueprint $table) {
                $table->char('advance_auth_identitiy_no', 20);
            });
        }
        if (!Schema::hasColumn('users', 'advance_auth_birth')) {
            Schema::table('users', function (Blueprint $table) {
                $table->date('advance_auth_identitiy_no');
            });
        }
        if (!Schema::hasColumn('users', 'advance_auth_phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->char('advance_auth_phone', 20);
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
            $table->dropColumn('advance_auth_timestamp');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('advance_auth_identitiy_no');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('advance_auth_birth');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('advance_auth_phone');
        });
    }
}
