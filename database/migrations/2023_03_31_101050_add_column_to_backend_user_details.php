<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToBackendUserDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('backend_user_details', function (Blueprint $table) {
            $table->after('login_times_after_need_video_verify_date', function (Blueprint $table) {
                $table->boolean('is_need_reverify')->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('backend_user_details', function (Blueprint $table) {
            $table->dropColumn('is_need_reverify');
        });
    }
}
