<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPassFault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('log_adv_auth_api', 'pass_fault')) {
            Schema::table('log_adv_auth_api', function (Blueprint $table) {
                $table->tinyInteger('pass_fault')->unsigned()->default(0)->after('api_fault')->comment('修改進階驗證次數,1:是 0:否（預設)');
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
        if (Schema::hasColumn('log_adv_auth_api', 'pass_fault')) {
            Schema::table('log_adv_auth_api', function (Blueprint $table) {
                $table->dropColumn('pass_fault');
            });
        }
    }
}
