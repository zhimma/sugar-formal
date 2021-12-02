<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsDuplicateTableLogAdvAuthApi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('log_adv_auth_api', 'is_duplicate')) {
            Schema::table('log_adv_auth_api', function (Blueprint $table) {
                $table->tinyInteger('is_duplicate')->default(0)->after('forbid_user');
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
        Schema::table('log_adv_auth_api', function (Blueprint $table) {
            $table->dropColumn('is_duplicate');
        });     
    }
}
