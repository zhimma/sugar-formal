<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAliasToAdminCommonText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('admin_common_text')->insert([
            ['status' => 1, 'category' => '信件說明', 'category_alias' => 'letter_text', 'alias' => 'vvip', 'title' => 'VVIP會員', 'content' => 'VVIP會員'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
