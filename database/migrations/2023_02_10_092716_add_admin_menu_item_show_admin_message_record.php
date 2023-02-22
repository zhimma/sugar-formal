<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AdminMenuItems;

class AddAdminMenuItemShowAdminMessageRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $item_array = ['title' => '站長會員通訊紀錄', 'route_path' => '/admin/users/message/record/all', 'status' => 1];

        $stand_sort = AdminMenuItems::where('title','留言板管理')->first()->sort;

        $modify_list = AdminMenuItems::where('sort','>',$stand_sort)->get();
        foreach($modify_list as $modify_item)
        {
            $modify_item->sort = $modify_item->sort + 1;
            $modify_item->save();
        }

        $item = new AdminMenuItems;
        $item->title = $item_array['title'];
        $item->route_path = $item_array['route_path'];
        $item->status = $item_array['status'];
        $item->sort = $stand_sort + 1;
        $item->save();
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
