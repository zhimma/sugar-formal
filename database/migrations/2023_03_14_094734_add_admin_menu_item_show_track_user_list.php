<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminMenuItemShowTrackUserList extends Migration
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
                'title' => '列管追蹤列表',
                'route_path' => '/admin/users/track_user_list',
                'status' => '1',
                'sort' => '73'
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
        DB::table('admin_menu_items')->where('title','列管追蹤列表')->delete();

    }
}
