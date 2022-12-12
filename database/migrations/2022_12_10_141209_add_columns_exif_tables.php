<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsExifTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('user_meta', 'pic_exif')) {
            Schema::table('user_meta', function (Blueprint $table) {
                $table->text('pic_original_exif')->nullable()->after('pic_original_name');
            });
        }
        
        if (!Schema::hasColumn('member_pic', 'original_exif')) {
            Schema::table('member_pic', function (Blueprint $table) {
                $table->text('original_exif')->nullable()->after('original_name');
            });
        }  

        if (!Schema::hasColumn('real_auth_user_modify_pic', 'original_exif')) {
            Schema::table('real_auth_user_modify_pic', function (Blueprint $table) {
                $table->text('original_exif')->nullable()->after('original_name');
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
        if (Schema::hasColumn('user_meta', 'pic_original_exif')) {
            Schema::table('user_meta', function (Blueprint $table) {
                $table->dropColumn('pic_original_exif');
            });
        }
        
        if (Schema::hasColumn('member_pic', 'original_exif')) {
            Schema::table('member_pic', function (Blueprint $table) {
                $table->dropColumn('original_exif');
            });
        }  

        if (Schema::hasColumn('real_auth_user_modify_pic', 'original_exif')) {
            Schema::table('real_auth_user_modify_pic', function (Blueprint $table) {
                $table->dropColumn('original_exif');
            });
        }         
    }
}
