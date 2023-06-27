<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdminMenuItemAddMediumLongTermManage extends Migration
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
                'title' => '中長期會員管理',
                'route_path' => '/admin/users/medium_long_term_without_adv_verification_list',
                'status' => '1',
                'sort' => '76'
            )
        );

        DB::table('queue_global_variables')->insert(
            array(
                'name' => 'medium_long_term_without_adv_verification_communication_count_set',
                'type' => 'int',
                'value' => '0',
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
        DB::table('admin_menu_items')->where('title','中長期會員管理')->delete();
        DB::table('queue_global_variables')->where('name','medium_long_term_without_adv_verification_communication_count_set')->delete();
    }
}
