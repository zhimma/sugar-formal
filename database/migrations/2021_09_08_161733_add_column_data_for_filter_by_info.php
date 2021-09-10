<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDataForFilterByInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('data_for_filter_by_info', 'differ_ip_count')) {
            Schema::table('data_for_filter_by_info', function (Blueprint $table) {
                $table->integer('differ_ip_count')->after('blocked_other_count');
            });
        }
        
        if (!Schema::hasColumn('data_for_filter_by_info', 'differ_cfpid_count')) {
            Schema::table('data_for_filter_by_info', function (Blueprint $table) {
                $table->integer('differ_cfpid_count')->after('differ_ip_count');
            });
        }  

        if (!Schema::hasColumn('data_for_filter_by_info', 'pic_name_regular_count')) {
            Schema::table('data_for_filter_by_info', function (Blueprint $table) {
                $table->integer('pic_name_regular_count')->after('differ_cfpid_count');
            });
        }  

        if (!Schema::hasColumn('data_for_filter_by_info', 'pic_name_notregular_count')) {
            Schema::table('data_for_filter_by_info', function (Blueprint $table) {
                $table->integer('pic_name_notregular_count')->after('pic_name_regular_count');
            });
        } 

        if (!Schema::hasColumn('data_for_filter_by_info', 'pic_name_empty_count')) {
            Schema::table('data_for_filter_by_info', function (Blueprint $table) {
                $table->integer('pic_name_empty_count')->after('pic_name_notregular_count');
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
        Schema::table('data_for_filter_by_info', function (Blueprint $table) {
            $table->dropColumn('differ_cfpid_count');
        });
        
        Schema::table('data_for_filter_by_info', function (Blueprint $table) {
            $table->dropColumn('differ_ip_count');
        });  

        Schema::table('data_for_filter_by_info', function (Blueprint $table) {
            $table->dropColumn('pic_name_regular_count');
        }); 

        Schema::table('data_for_filter_by_info', function (Blueprint $table) {
            $table->dropColumn('pic_name_notregular_count');
        }); 

        Schema::table('data_for_filter_by_info', function (Blueprint $table) {
            $table->dropColumn('pic_name_empty_count');
        });         
    }
}
