<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertNewItemToAdminMenuItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('admin_menu_items')->insert(
            array(
                'title' => '視訊驗證',
                'route_path' => '/admin/users/video_chat_verify',
                'status' => '1',
                'sort' => '43'
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('admin_menu_items')->where('title','視訊驗證')->delete();
    }
}
