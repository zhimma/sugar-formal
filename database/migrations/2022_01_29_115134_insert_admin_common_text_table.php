<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertAdminCommonTextTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $data = DB::table('admin_common_text')->where('category_alias', 'anonymous_chat')->where('alias', 'announcement')->first();

        if(!$data) {
            DB::table('admin_common_text')->insert(
                array(
                    'status' => 1,
                    'category' => '未分類',
                    'category_alias' => 'anonymous_chat',
                    'alias' => 'announcement',
                    'title' => '匿名聊天室版規',
                    'content' => '請理性發言'
                )
            );
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
