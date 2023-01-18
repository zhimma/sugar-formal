<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsForTableUserVideoVerifyRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('user_video_verify_record', 'admin_id')) {
            Schema::table('user_video_verify_record', function (Blueprint $table) {
                $table->integer('admin_id')->nullable()->after('user_id')->index();
            });
        }  

        if (!Schema::hasColumn('user_video_verify_record', 'is_caller_admin')) {
            Schema::table('user_video_verify_record', function (Blueprint $table) {
                $table->boolean('is_caller_admin')->nullable()->after('admin_id')->index();
            });
        }  


        if (!Schema::hasColumn('user_video_verify_record', 'user_last_action')) {
            Schema::table('user_video_verify_record', function (Blueprint $table) {
                $table->string('user_last_action',100)->nullable()->after('is_caller_admin')->index();
            });
        } 
        
        if (!Schema::hasColumn('user_video_verify_record', 'user_last_action_at')) {
            Schema::table('user_video_verify_record', function (Blueprint $table) {
                $table->timestamp('user_last_action_at')->nullable()->after('user_last_action');
            });
        }         

        if (!Schema::hasColumn('user_video_verify_record', 'admin_last_action')) {
            Schema::table('user_video_verify_record', function (Blueprint $table) {
                $table->string('admin_last_action',100)->nullable()->after('user_last_action_at')->index();
            });
        }   

        if (!Schema::hasColumn('user_video_verify_record', 'admin_last_action_at')) {
            Schema::table('user_video_verify_record', function (Blueprint $table) {
                $table->timestamp('admin_last_action_at')->nullable()->after('admin_last_action');
            });
        }         


        if (!Schema::hasColumn('user_video_verify_record', 'user_question')) {
            Schema::table('user_video_verify_record', function (Blueprint $table) {
                $table->text('user_question')->nullable()->after('user_video');
            });
        } 
        
        if (!Schema::hasColumn('user_video_verify_record', 'blurryAvatar')) {
            Schema::table('user_video_verify_record', function (Blueprint $table) {
                $table->char('blurryAvatar',20)->nullable()->after('user_question');
            });
        } 

        if (!Schema::hasColumn('user_video_verify_record', 'blurryLifePhoto')) {
            Schema::table('user_video_verify_record', function (Blueprint $table) {
                $table->char('blurryLifePhoto',20)->nullable()->after('blurryAvatar');
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
        if (Schema::hasColumn('user_video_verify_record', 'admin_id')) {
            Schema::table('user_video_verify_record', function (Blueprint $table) {
                $table->dropColumn('admin_id');
            });  
        }


        if (Schema::hasColumn('user_video_verify_record', 'is_caller_admin')) {
            Schema::table('user_video_verify_record', function (Blueprint $table) {
                $table->dropColumn('is_caller_admin');
            });  
        }


        if (Schema::hasColumn('user_video_verify_record', 'user_last_action')) {
            Schema::table('user_video_verify_record', function (Blueprint $table) {
                $table->dropColumn('user_last_action');
            });  
        }
        
        if (Schema::hasColumn('user_video_verify_record', 'user_last_action_at')) {
            Schema::table('user_video_verify_record', function (Blueprint $table) {
                $table->dropColumn('user_last_action_at');
            });  
        } 

        if (Schema::hasColumn('user_video_verify_record', 'admin_last_action')) {
            Schema::table('user_video_verify_record', function (Blueprint $table) {
                $table->dropColumn('admin_last_action');
            });  
        }
        
        if (Schema::hasColumn('user_video_verify_record', 'admin_last_action_at')) {
            Schema::table('user_video_verify_record', function (Blueprint $table) {
                $table->dropColumn('admin_last_action_at');
            });  
        }        

       
        if (Schema::hasColumn('user_video_verify_record', 'user_question')) {
            Schema::table('user_video_verify_record', function (Blueprint $table) {
                $table->dropColumn('user_question');
            });  
        } 
        
        if (Schema::hasColumn('user_video_verify_record', 'blurryAvatar')) {
            Schema::table('user_video_verify_record', function (Blueprint $table) {
                $table->dropColumn('blurryAvatar');
            });  
        } 
        
        if (Schema::hasColumn('user_video_verify_record', 'blurryLifePhoto')) {
            Schema::table('user_video_verify_record', function (Blueprint $table) {
                $table->dropColumn('blurryLifePhoto');
            });  
        }         
        
    }
}
