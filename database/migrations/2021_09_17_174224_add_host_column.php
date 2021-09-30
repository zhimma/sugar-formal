<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHostColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('custom_fingerprint', 'host')) {
            Schema::table('custom_fingerprint', function (Blueprint $table) {
                $table->string('host', 255)->nullable()->default(null)->after('hash');
            });
        }

        if (!Schema::hasColumn('set_auto_ban', 'host')) {
            Schema::table('set_auto_ban', function (Blueprint $table) {
                $table->string('host', 255)->nullable()->default(null)->after('expiry');
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
        Schema::table('custom_fingerprint', function (Blueprint $table) {
            //
            $table->dropColumn('host');
        });

        Schema::table('set_auto_ban', function (Blueprint $table) {
            //
            $table->dropColumn('host');
        });
    }
}
