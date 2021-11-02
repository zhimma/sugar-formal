<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsWarnedTypeTableUserMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('user_meta', 'isWarnedType')) {
            Schema::table('user_meta', function (Blueprint $table) {
                $table->string('isWarnedType',50)->nullable()->after('isWarned');
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
        Schema::table('user_meta', function (Blueprint $table) {
            $table->dropColumn('isWarnedType');
        });  
    }
}
