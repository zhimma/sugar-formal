<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AdminMenuItems;

class AddAdminMenuItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $item_array = ['title' => '初階站長審查檢核統計', 'route_path' => '/admin/dashboard/juniorAdminCheckRecord', 'status' => 1];

        $stand_sort = AdminMenuItems::where('title','初階站長權限管理')->first()->sort;

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
