<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsStayOnlineRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(!Schema::hasColumn('stay_online_record', 'client_storage_record_id')) {
            Schema::table('stay_online_record', function (Blueprint $table) {
                $table->integer('client_storage_record_id')->nullable()->after('user_id')->index();

            });
        }        
        
        if(!Schema::hasColumn('stay_online_record', 'url')) {
            Schema::table('stay_online_record', function (Blueprint $table) {
                $table->text('url')->nullable()->after('client_storage_record_id')->index();

            });
        }
        
        if(!Schema::hasColumn('stay_online_record', 'title')) {
            Schema::table('stay_online_record', function (Blueprint $table) {
                $table->string('title',255)->nullable()->after('url');

            });
        }  

        if(!Schema::hasColumn('stay_online_record', 'page_uid')) {
            Schema::table('stay_online_record', function (Blueprint $table) {
                $table->string('page_uid',255)->nullable()->after('title')->index();

            });
        }         
        
        if(!Schema::hasColumn('stay_online_record', 'userAgent')) {
            Schema::table('stay_online_record', function (Blueprint $table) {
                $table->string('userAgent',200)->nullable()->after('newer_manual');

            });
        } 

        if(!Schema::hasColumn('stay_online_record', 'ip')) {
            Schema::table('stay_online_record', function (Blueprint $table) {
                $table->string('ip',32)->nullable()->after('title')->index();

            });
        } 

        if (Schema::hasColumn('stay_online_record', 'client_storage_record_id')) {
            DB::update('update stay_online_record set client_storage_record_id = id ');
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
        if (Schema::hasColumn('stay_online_record', 'url')) {
            Schema::table('stay_online_record', function (Blueprint $table) {
                $table->dropColumn('url');
            });  
        } 

        if (Schema::hasColumn('stay_online_record', 'title')) {
            Schema::table('stay_online_record', function (Blueprint $table) {
                $table->dropColumn('title');
            });  
        } 

        if (Schema::hasColumn('stay_online_record', 'ip')) {
            Schema::table('stay_online_record', function (Blueprint $table) {
                $table->dropColumn('ip');
            });  
        } 

        if (Schema::hasColumn('stay_online_record', 'userAgent')) {
            Schema::table('stay_online_record', function (Blueprint $table) {
                $table->dropColumn('userAgent');
            });  
        } 

        if (Schema::hasColumn('stay_online_record', 'client_storage_record_id')) {
            Schema::table('stay_online_record', function (Blueprint $table) {
                $table->dropColumn('client_storage_record_id');
            });  
        }           

    }
}
