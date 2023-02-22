<?php

use App\Models\AdminMenuItems;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminMenuItemVvipSelectionReward extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $item_array = [];
        $item_array[] = ['title' => 'VVIP 徵選活動管理', 'route_path' => '/admin/users/VvipSelectionReward', 'status' => 1];

        $max_sort = AdminMenuItems::orderByDesc('sort')->first()->sort;
        for($i = 0; $i < count($item_array); $i++)
        {
            $max_sort = $max_sort + 1;
            $item = new AdminMenuItems;
            $item->title = $item_array[$i]['title'];
            $item->route_path = $item_array[$i]['route_path'];
            $item->status = $item_array[$i]['status'];
            $item->sort = $max_sort;
            $item->save();
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
