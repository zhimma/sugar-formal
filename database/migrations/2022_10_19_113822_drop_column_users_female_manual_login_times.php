<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnUsersFemaleManualLoginTimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('female_manual_login_times');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(!Schema::hasColumn('users', 'female_manual_login_times')) {
            Schema::table('users', function ($table) {
                $table->integer('female_manual_login_times')->unsigned()->default(0)->after('intro_login_times');
            });
        }
    }
}
