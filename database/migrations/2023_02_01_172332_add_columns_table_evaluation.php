<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsTableEvaluation extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('evaluation', 'only_show_text')) {
            Schema::table('evaluation', function (Blueprint $table) {
                $table->boolean('only_show_text')->nullable()->default(0)->after('anonymous_content_status');
            });
        }
        
        if (!Schema::hasColumn('evaluation', 'status_message_id')) {
            Schema::table('evaluation', function (Blueprint $table) {
                $table->integer('status_message_id')->nullable()->after('status_reason');
            });
        }        

        if (!Schema::hasColumn('evaluation', 'status_at')) {
            Schema::table('evaluation', function (Blueprint $table) {
                $table->timestamp('status_at')->nullable()->after('status_message_id');
            });
        }  

        if (!Schema::hasColumn('evaluation', 'last_content_status')) {
            Schema::table('evaluation', function (Blueprint $table) {
                $table->integer('last_content_status')->nullable()->default(0)->after('status_at');
            });
        } 

        if (!Schema::hasColumn('evaluation', 'last_only_show_text')) {
            Schema::table('evaluation', function (Blueprint $table) {
                $table->boolean('last_only_show_text')->nullable()->default(0)->after('last_content_status');
            });
        } 

        if (!Schema::hasColumn('evaluation', 'last_status_reason')) {
            Schema::table('evaluation', function (Blueprint $table) {
                $table->string('last_status_reason',255)->nullable()->after('last_only_show_text');
            });
        } 
        
        if (!Schema::hasColumn('evaluation', 'last_status_message_id')) {
            Schema::table('evaluation', function (Blueprint $table) {
                $table->integer('last_status_message_id')->nullable()->after('last_status_reason');
            });
        }         

        if (!Schema::hasColumn('evaluation', 'last_status_at')) {
            Schema::table('evaluation', function (Blueprint $table) {
                $table->timestamp('last_status_at')->nullable()->after('last_status_message_id');
            });
        } 

        if (!Schema::hasColumn('evaluation', 'last_status_canceled_at')) {
            Schema::table('evaluation', function (Blueprint $table) {
                $table->timestamp('last_status_canceled_at')->nullable()->after('last_status_at');
            });
        }         
    }

    public function down()
    {
        if (Schema::hasColumn('evaluation', 'only_show_text')) {
            Schema::table('evaluation', function (Blueprint $table) {
                $table->dropColumn('only_show_text');
            }); 
        }
        
        if (Schema::hasColumn('evaluation', 'status_message_id')) {
            Schema::table('evaluation', function (Blueprint $table) {
                $table->dropColumn('status_message_id');
            }); 
        }        
        
        if (Schema::hasColumn('evaluation', 'status_at')) {
            Schema::table('evaluation', function (Blueprint $table) {
                $table->dropColumn('status_at');
            }); 
        }

        if (Schema::hasColumn('evaluation', 'last_content_status')) {
            Schema::table('evaluation', function (Blueprint $table) {
                $table->dropColumn('last_content_status');
            }); 
        }

        if (Schema::hasColumn('evaluation', 'last_only_show_text')) {
            Schema::table('evaluation', function (Blueprint $table) {
                $table->dropColumn('last_only_show_text');
            }); 
        }
        
        if (Schema::hasColumn('evaluation', 'last_status_reason')) {
            Schema::table('evaluation', function (Blueprint $table) {
                $table->dropColumn('last_status_reason');
            }); 
        }
        
        if (Schema::hasColumn('evaluation', 'last_status_message_id')) {
            Schema::table('evaluation', function (Blueprint $table) {
                $table->dropColumn('last_status_message_id');
            }); 
        }         

        if (Schema::hasColumn('evaluation', 'last_status_at')) {
            Schema::table('evaluation', function (Blueprint $table) {
                $table->dropColumn('last_status_at');
            }); 
        } 

        if (Schema::hasColumn('evaluation', 'last_status_canceled_at')) {
            Schema::table('evaluation', function (Blueprint $table) {
                $table->dropColumn('last_status_canceled_at');
            }); 
        }         
    }
}