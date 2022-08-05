<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsShortMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(!Schema::hasColumn('short_message', 'canceled_from')) {
            Schema::table('short_message', function (Blueprint $table) {
                $table->string('canceled_from',100)->nullable()->after('canceled_date')->index();
            });
        }         
        
        if(!Schema::hasColumn('short_message', 'auto_created')) {
            Schema::table('short_message', function (Blueprint $table) {
                $table->boolean('auto_created')->default(0)->after('member_id');
            });
        }           
 
        if(!Schema::hasColumn('short_message', 'created_from')) {
            Schema::table('short_message', function (Blueprint $table) {
                $table->string('created_from',100)->nullable()->after('auto_created')->index();
            });
        } 

        if(!Schema::hasColumn('short_message', 'created_by')) {
            Schema::table('short_message', function (Blueprint $table) {
                $table->integer('created_by')->nullable()->after('created_from')->index();
            });
        } 

        if(!Schema::hasColumn('short_message', 'auto_deleted')) {
            Schema::table('short_message', function (Blueprint $table) {
                $table->boolean('auto_deleted')->default(0);
            });
        }          
 
        if (!Schema::hasColumn('short_message', 'deleted_from')) {
            Schema::table('short_message', function (Blueprint $table) {
                $table->string('deleted_from',100)->nullable()->index();
            });
        }   


        if (!Schema::hasColumn('short_message', 'deleted_by')) {
            Schema::table('short_message', function (Blueprint $table) {
                $table->integer('deleted_by')->nullable()->index();
            });
        } 

        if (!Schema::hasColumn('short_message', 'deleted_at')) {
            Schema::table('short_message', function (Blueprint $table) {
                $table->softDeletes();
            });
        } 
        
        if (Schema::hasColumn('short_message', 'deleted_at') && Schema::hasColumn('short_message', 'canceled_date')) {
            DB::update('update short_message set deleted_at = canceled_date where  canceled_date is not null');
        } 
        
        if (Schema::hasColumn('short_message', 'deleted_from') && Schema::hasColumn('short_message', 'canceled_by')) {
            DB::update('update short_message set deleted_from = canceled_by where  canceled_by is not null');
        } 

        if (Schema::hasColumn('short_message', 'canceled_from') && Schema::hasColumn('short_message', 'canceled_by')) {
            DB::update('update short_message set canceled_from = canceled_by where  canceled_by is not null');
            DB::update('update short_message set canceled_by = null where  canceled_by is not null');
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
        if (Schema::hasColumn('short_message', 'auto_created')) {
            Schema::table('short_message', function (Blueprint $table) {
                $table->dropColumn('auto_created');
            });  
        }        
        
        if (Schema::hasColumn('short_message', 'created_from')) {
            Schema::table('short_message', function (Blueprint $table) {
                $table->dropColumn('created_from');
            });  
        }


        if (Schema::hasColumn('short_message', 'created_by')) {
            Schema::table('short_message', function (Blueprint $table) {
                $table->dropColumn('created_by');
            });  
        }

        if (Schema::hasColumn('short_message', 'auto_deleted')) {
            Schema::table('short_message', function (Blueprint $table) {
                $table->dropColumn('auto_deleted');
            });  
        }          
        
        if (Schema::hasColumn('short_message', 'deleted_from')) {
            Schema::table('short_message', function (Blueprint $table) {
                $table->dropColumn('deleted_from');
            });  
        }


        if (Schema::hasColumn('short_message', 'deleted_by')) {
            Schema::table('short_message', function (Blueprint $table) {
                $table->dropColumn('deleted_by');
            });  
        }


        if (Schema::hasColumn('short_message', 'deleted_at')) {
            Schema::table('short_message', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });  
        }
    }
}
