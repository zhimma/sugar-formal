<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsOfFemaleNewerManual extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('users', 'female_manual_login_times')) {
            Schema::table('users', function ($table) {
                $table->integer('female_manual_login_times')->unsigned()->default(0)->after('intro_login_times');
            });
        }

        if(!Schema::hasColumn('users', 'is_read_female_manual_part1')) {
            Schema::table('users', function ($table) {
                $table->tinyInteger('is_read_female_manual_part1')->unsigned()->default(0)->after('isReadIntro');
            });
        }

        if(!Schema::hasColumn('users', 'is_read_female_manual_part2')) {
            Schema::table('users', function ($table) {
                $table->tinyInteger('is_read_female_manual_part2')->unsigned()->default(0)->after('is_read_female_manual_part1');
            });
        }

        if(!Schema::hasColumn('users', 'is_read_female_manual_part3')) {
            Schema::table('users', function ($table) {
                $table->tinyInteger('is_read_female_manual_part3')->unsigned()->default(0)->after('is_read_female_manual_part2');
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
        Schema::table('users', function ($table) {
            $table->dropColumn('female_manual_login_times');
        });

        Schema::table('users', function ($table) {
            $table->dropColumn('is_read_female_manual_part1');
        });

        Schema::table('users', function ($table) {
            $table->dropColumn('is_read_female_manual_part2');
        });

        Schema::table('users', function ($table) {
            $table->dropColumn('is_read_female_manual_part3');
        });

    }
}
