<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexForPicColumns extends Migration
{
    public function up()
    {

        Schema::table('user_meta', function (Blueprint $table) {
            $table->index('pic');           
        });       

        Schema::table('member_pic', function (Blueprint $table) {
            $table->index('pic');
            $table->index(['member_id','pic']);
            $table->index(['pic','member_id']);
        });
        
        Schema::table('avatar_deleted', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('pic');
            $table->index(['user_id','pic']);
            $table->index(['pic','user_id']);
        });        
        
        if (Schema::hasColumn('images_compare', 'found_pic')) {
            Schema::table('images_compare', function (Blueprint $table) {
                $table->index('encode_id');
                $table->index('found_encode_id');
                $table->index('pic');
                $table->index('found_pic');
                $table->index(['pic','found_pic']);
                $table->index(['found_pic','pic']);
                $table->index(['encode_id','found_encode_id']);
                $table->index(['found_encode_id','encode_id']);                
            });
        }          

        Schema::table('images_compare_encode', function (Blueprint $table) {
            $table->index('pic');
        });

        Schema::table('images_compare_status', function (Blueprint $table) {
            $table->index('pic');
        });         
        
     

    }

    public function down()
    {

        Schema::table('user_meta', function (Blueprint $table) {
            $table->dropIndex(['pic']);           
        });       

        Schema::table('member_pic', function (Blueprint $table) {
            $table->dropIndex(['pic']);
            $table->dropIndex(['member_id','pic']);
            $table->dropIndex(['pic','member_id']);
        });
        
        Schema::table('avatar_deleted', function (Blueprint $table) {
            $table->dropIndex('user_id');
            $table->dropIndex(['pic']);
            $table->dropIndex(['user_id','pic']);
            $table->dropIndex(['pic','user_id']);            
        });            
        
        Schema::table('images_compare', function (Blueprint $table) {
            $table->dropIndex(['encode_id']);
            $table->dropIndex(['found_encode_id']);
            $table->dropIndex(['pic']);
            $table->dropIndex(['found_pic']);
            $table->dropIndex(['pic','found_pic']);
            $table->dropIndex(['found_pic','pic']);
            $table->dropIndex(['encode_id','found_encode_id']);
            $table->dropIndex(['found_encode_id','encode_id']);                
        });       

        Schema::table('images_compare_encode', function (Blueprint $table) {
            $table->dropIndex(['pic']);
        });

        Schema::table('images_compare_status', function (Blueprint $table) {
            $table->dropIndex(['pic']);
        });         
                  
    }
}