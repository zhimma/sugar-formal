<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminMenuItemShowObserveUserList extends Migration
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
                'title' => '觀察名單列表',
                'route_path' => '/admin/users/observe_user_list',
                'status' => '1',
                'sort' => '72'
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
        DB::table('admin_menu_items')->where('title','觀察名單列表')->delete();

    }
}
