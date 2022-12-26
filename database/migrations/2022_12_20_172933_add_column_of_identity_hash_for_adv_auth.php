<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOfIdentityHashForAdvAuth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('log_adv_auth_api', 'identity_hash')) {
            Schema::table('log_adv_auth_api', function (Blueprint $table) {
                $table->string('identity_hash',255)->nullable()->after('identity_encode');
            });
        } 
        
        if (!Schema::hasColumn('log_adv_auth_api', 'duplicate_user_id')) {
            Schema::table('log_adv_auth_api', function (Blueprint $table) {
                $table->Integer('duplicate_user_id')->default(0)->nullable()->after('is_duplicate');
            });
        }         

        if (!Schema::hasColumn('users', 'advance_auth_identity_hash')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('advance_auth_identity_hash',255)->nullable()->after('advance_auth_identity_encode');
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
        if (Schema::hasColumn('log_adv_auth_api', 'identity_hash')) {
            Schema::table('log_adv_auth_api', function (Blueprint $table) {
                $table->dropColumn('identity_hash');
            }); 
        }
        
        if (Schema::hasColumn('log_adv_auth_api', 'duplicate_user_id')) {
            Schema::table('log_adv_auth_api', function (Blueprint $table) {
                $table->dropColumn('duplicate_user_id');
            }); 
        }        
        
        if (Schema::hasColumn('users', 'advance_auth_identity_hash')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('advance_auth_identity_hash');
            }); 
        }        
    }
}
