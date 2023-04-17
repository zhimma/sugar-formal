<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertVideoVerifyItemToAdminMenuItems extends Migration
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
                'title' => '視訊錄影驗證紀錄',
                'route_path' => '/admin/users/video_verify_record_list',
                'status' => '1',
                'sort' => '75'
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
        DB::table('admin_menu_items')->where('title','視訊錄影驗證紀錄')->delete();
    }
}
