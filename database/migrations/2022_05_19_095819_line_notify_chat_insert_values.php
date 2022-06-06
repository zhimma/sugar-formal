<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LineNotifyChatInsertValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!DB::table('line_notify_chat')
                ->where(['name' => '封鎖會員', 'gender' => 0, 'active' => 1, 'order' => 99])
                ->exists()
        ) {
            DB::table('line_notify_chat')
            ->insert(array(
                array('name' => '封鎖會員', 'gender' => 0, 'active' => 1, 'order' => 99),
            ));
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
    }
}
