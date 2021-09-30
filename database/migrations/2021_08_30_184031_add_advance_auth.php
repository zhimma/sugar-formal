<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdvanceAuth extends Migration
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

        if (!Schema::hasColumn('users', 'advance_auth_time')) {
            Schema::table('users', function (Blueprint $table) {
                $table->datetime('advance_auth_time');
            });
        }

        if (!Schema::hasColumn('users', 'advance_auth_identity_no')) {
            Schema::table('users', function (Blueprint $table) {
                $table->char('advance_auth_identity_no', 20);
            });
        }
        if (!Schema::hasColumn('users', 'advance_auth_birth')) {
            Schema::table('users', function (Blueprint $table) {
                $table->date('advance_auth_birth');
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
            $table->dropColumn('advance_auth_time');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('advance_auth_identity_no');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('advance_auth_birth');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('advance_auth_phone');
        });
    }
}
