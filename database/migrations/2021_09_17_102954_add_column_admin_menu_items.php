<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAdminMenuItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('admin_menu_items', 'status')) {
            Schema::table('admin_menu_items', function (Blueprint $table) {
                $table->tinyInteger('status')->default(1)->after('route_path');
                $table->tinyInteger('sort')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('admin_menu_items', 'status')) {
            Schema::table('admin_menu_items', function (Blueprint $table) {
                $table->dropColumn('status');
                $table->dropColumn('sort');
            });
        }
    }
}
