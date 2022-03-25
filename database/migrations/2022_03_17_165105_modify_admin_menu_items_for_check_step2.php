<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAdminMenuItemsForCheckStep2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('admin_menu_items')->where('title', '會員照片管理-以圖搜圖')->update(['title' => '會員檢查 step 2']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('admin_menu_items')->where('title', '會員檢查 step 2')->update(['title' => '會員照片管理-以圖搜圖']);
    }
}
