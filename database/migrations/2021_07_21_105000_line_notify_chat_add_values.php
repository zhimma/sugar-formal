<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class LineNotifyChatAddValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::table('line_notify_chat')
            ->insert(array(
                array('name' => '誰來看我', 'gender' => 0, 'active' => 1, 'order' => 101),
                array('name' => '收藏我的會員', 'gender' => 0, 'active' => 1, 'order' => 102),
            ));
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
