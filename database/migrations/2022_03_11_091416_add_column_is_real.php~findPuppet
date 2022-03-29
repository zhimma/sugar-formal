<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsReal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            if (!Schema::hasColumn('users', 'is_real')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->tinyInteger('is_real')->unsigned()->default(1)->after('line_notify_alert')->comment('是本人（1:是 0:否)');
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
        Schema::table('users', function (Blueprint $table) {
            //
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_real');
            });
        });
    }
}
