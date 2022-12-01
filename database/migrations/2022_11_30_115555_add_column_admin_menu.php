<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AdminMenuItems;

class AddColumnAdminMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $max_sort = AdminMenuItems::orderByDesc('sort')->first()->sort;

        $item_array = [];
        $item_array[] = ['title' => '等待更多資料(發回)', 'route_path' => '/admin/users/wait_for_more_data_login_time_list', 'status' => 1];

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
