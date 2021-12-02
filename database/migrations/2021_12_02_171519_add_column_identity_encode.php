<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIdentityEncode extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('users', 'advance_auth_identity_encode')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('advance_auth_identity_encode',255)->after('advance_auth_identity_no');
            });
        }   
        
        if (Schema::hasColumn('users', 'advance_auth_identity_no')) {
            $exist_no = DB::table('users')->whereNotNull('advance_auth_identity_no')->where('advance_auth_identity_no','<>','')->get();
            foreach($exist_no as $no) {
                DB::table('users')->where('id',$no->id)->update(['advance_auth_identity_encode'=>md5(sha1(md5($no->advance_auth_identity_no)))]);
            }
        }

        if (!Schema::hasColumn('log_adv_auth_api', 'identity_encode')) {
            Schema::table('log_adv_auth_api', function (Blueprint $table) {
                $table->string('identity_encode',255)->after('identity_no');;
            });
        }  

        if (Schema::hasColumn('log_adv_auth_api', 'identity_no')) {
            $exist_no = DB::table('log_adv_auth_api')->whereNotNull('identity_no')->where('identity_no','<>','')->get();
            foreach($exist_no as $no) {
                DB::table('log_adv_auth_api')->where('id',$no->id)->update(['identity_encode'=>md5(sha1(md5($no->identity_no)))]);
            }
        }        
            
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('advance_auth_identity_encode');
        });        
        
        Schema::table('log_adv_auth_api', function (Blueprint $table) {
            $table->dropColumn('identity_encode');
        });  
    }
}