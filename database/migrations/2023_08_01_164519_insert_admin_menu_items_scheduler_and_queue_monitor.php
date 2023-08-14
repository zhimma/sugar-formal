<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertAdminMenuItemsSchedulerAndQueueMonitor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('admin_menu_items', function (Blueprint $table) {
            //
            DB::table('admin_menu_items')->Insert(
                [
                    array(
                        'title' => '背景程序監控',
                        'route_path' => '/admin/queue',
                        'status' => 1,
                        'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now(),
                    )
                ]
            );
            DB::table('admin_menu_items')->Insert(
                [
                    array(
                        'title' => '排程監控',
                        'route_path' => '/admin/stats/schedulerLog',
                        'status' => 1,
                        'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now(),
                    )
                ]
            );
        });
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
