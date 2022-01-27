<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyWeightField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        if (Schema::hasColumn('user_meta', 'weight'))
        {
            Schema::table('user_meta', function (Blueprint $table)
            {
                $table->renameColumn('weight', 'weight_old');
            });
        }
        
        Schema::table('user_meta', function (Blueprint $table) {
            //加入weight欄位到height欄位後方
            $table->integer('weight')->default(0)->after('height');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_meta', function (Blueprint $table) {
            $table->dropColumn('weight');
        });

        Schema::table('user_meta', function (Blueprint $table)
            {
                $table->renameColumn('weight_old', 'weight');
            });
    }
}
