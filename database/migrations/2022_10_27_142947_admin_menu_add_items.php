<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AdminMenuItems;

class AdminMenuAddItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $max_sort = AdminMenuItems::orderByDesc('sort')->first()->sort;

        $item = new AdminMenuItems;
        $item->title = '八大判斷訓練設定頁';
        $item->route_path = '/admin/special_industries_judgment_training_setup';
        $item->status = 1;
        $item->sort = $max_sort + 1;
        $item->save();

        $item2 = new AdminMenuItems;
        $item2->title = '八大判斷訓練測試頁';
        $item2->route_path = '/admin/special_industries_judgment_training_select';
        $item2->status = 1;
        $item2->sort = $max_sort + 2;
        $item2->save();

        $admins = DB::table('role_user')->where('role_id',3)->get();
        foreach($admins as $admin)
        {
            DB::table('role_user')->where('id',$admin->id)->update(['item_permission' => $admin->item_permission.','.$item2->id]);
        }
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
