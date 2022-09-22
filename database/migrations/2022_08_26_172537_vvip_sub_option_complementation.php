<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VvipSubOptionComplementation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('vvip_sub_option_ceo_title', 'is_custom')) {
            Schema::table('vvip_sub_option_ceo_title', function (Blueprint $table) {
                $table->boolean('is_custom')->default(false);
            });     
        }
        if (!Schema::hasColumn('vvip_sub_option_professional', 'is_custom')) {
            Schema::table('vvip_sub_option_professional', function (Blueprint $table) {
                $table->boolean('is_custom')->default(false);
            });     
        }
        if (!Schema::hasColumn('vvip_sub_option_high_net_worth', 'is_custom')) {
            Schema::table('vvip_sub_option_high_net_worth', function (Blueprint $table) {
                $table->boolean('is_custom')->default(false);
            });     
        }
        if (!Schema::hasColumn('vvip_sub_option_entrepreneur', 'is_custom')) {
            Schema::table('vvip_sub_option_entrepreneur', function (Blueprint $table) {
                $table->boolean('is_custom')->default(false);
            });     
        }
        if (!Schema::hasColumn('vvip_sub_option_life_care', 'is_custom')) {
            Schema::table('vvip_sub_option_life_care', function (Blueprint $table) {
                $table->boolean('is_custom')->default(false);
            });     
        }
        if (!Schema::hasColumn('vvip_sub_option_special_problem_handling', 'is_custom')) {
            Schema::table('vvip_sub_option_special_problem_handling', function (Blueprint $table) {
                $table->boolean('is_custom')->default(false);
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
    }
}
