<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdminMenuItemsAddSimilarImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $updateMenuList=[
            '會員照片管理-以圖搜圖' =>array('route_path'=>'/admin/users/picturesSimilar', 'sort'=>41),
            '會員照片管理結果列表' =>array('route_path'=>'/admin/users/picturesSimilarLog', 'sort'=>42),
        ];

        //更新menu項目＆排序
        foreach ($updateMenuList as $key =>$value){
            \App\Models\AdminMenuItems::updateOrInsert(['title'=>$key], $value);
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
