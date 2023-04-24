<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVerifyChatTypeToBackendDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('backend_user_details', function (Blueprint $table) {
            $table->after('is_need_reverify', function (Blueprint $table) {
                $table->boolean('has_upload_video_verify')->default(0);
                $table->boolean('temp_stop_video_verify')->default(0);
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
            $table->dropColumn('has_upload_video_verify');
            $table->dropColumn('temp_stop_video_verify');
        });
    }
}
