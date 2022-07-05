<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPureDating extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_meta', function (Blueprint $table) {
            if (!Schema::hasColumn('user_meta', 'is_pure_dating')) {
                Schema::table('user_meta', function (Blueprint $table) {
                    $table->boolean('is_pure_dating')->nullable()->after('style');
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
        Schema::table('user_meta', function (Blueprint $table) {
            Schema::table('user_meta', function (Blueprint $table) {
                $table->dropColumn('is_pure_dating');
            });
        });
    }
}
