<?php

use App\Models\AdminMenuItems;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdminMenuItemsAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(!AdminMenuItems::where('title', '會員檢查 step 3')->first()){
            $record = new AdminMenuItems;
            $record->title = '會員檢查 step 3';
            $record->url = '/admin/users/message/check';
            $record->status = 1;
            $record->sort = 43;
            $record->save();
        }
        if(!AdminMenuItems::where('title', '匿名聊天室')->first()){
            $record = new AdminMenuItems;
            $record->title = '匿名聊天室';
            $record->url = '/admin/users/anonymousChat';
            $record->status = 1;
            $record->sort = 44;
            $record->save();
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
