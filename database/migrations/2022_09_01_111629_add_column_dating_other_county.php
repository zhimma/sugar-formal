<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDatingOtherCounty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_meta', function (Blueprint $table) {
            if (!Schema::hasColumn('user_meta', 'is_dating_other_county')) {
                Schema::table('user_meta', function (Blueprint $table) {
                    $table->tinyInteger('is_dating_other_county')->unsigned()->default(0)->after('is_pure_dating')->comment('是否接受約外縣市,1:是 0:否（預設)');
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
                $table->dropColumn('is_dating_other_county');
            });
        });
    }
}
