<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AdminMenuItems;

class AddValueInAdminMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_menu_items', function (Blueprint $table) {
            //
            if(!AdminMenuItems::where('title', 'FunPoint VIP 付費取消資料')->first()){
                $record = new AdminMenuItems;
                $record->title = 'FunPoint VIP 付費取消資料';
                $record->route_path = '/users/VIP/FunPointCancellations';
                $record->status = 1;
                $record->sort = 6;
                $record->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_menu_items', function (Blueprint $table) {
            //
        });
    }
}
