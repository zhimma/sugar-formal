<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAdminAnnouncementIsNew7 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_announcement', function (Blueprint $table) {
            $table->boolean('is_new_7')->nullable()->default(0)->after('isVip');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_announcement', function (Blueprint $table) {
            $table->dropColumn('is_new_7');

        });
    }
}
