<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsTableMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('message', 'unsend')) {
            Schema::table('message', function (Blueprint $table) {
                $table->tinyInteger('unsend')->default(0)->after('hide_reported_log');
            });
        }  

        if (!Schema::hasColumn('message', 'parent_msg')) {
            Schema::table('message', function (Blueprint $table) {
                $table->Integer('parent_msg')->unsigned()->nullable()->after('unsend');
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

        Schema::table('message', function (Blueprint $table) {
            $table->dropColumn('unsend');
        });
        
        Schema::table('message', function (Blueprint $table) {
            $table->dropColumn('parent_msg');
        });        

    }
}
