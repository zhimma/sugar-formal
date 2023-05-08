<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdminMenuItemsVideoAuthRename extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('admin_menu_items')->where('title', '視訊驗證影片紀錄')->update(['title' => '本人認證視訊紀錄']);
        DB::table('admin_menu_items')->where('title', '視訊錄影驗證紀錄')->update(['title' => '視訊驗證錄影紀錄']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('admin_menu_items')->where('title', '本人認證視訊紀錄')->update(['title' => '視訊驗證影片紀錄']);
        DB::table('admin_menu_items')->where('title', '視訊驗證錄影紀錄')->update(['title' => '視訊錄影驗證紀錄']);
    }
}
