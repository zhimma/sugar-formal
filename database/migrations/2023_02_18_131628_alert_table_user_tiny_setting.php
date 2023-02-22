<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlertTableUserTinySetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_tiny_setting', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('cat');
            $table->index('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_tiny_setting', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['cat']);
            $table->dropIndex(['value']);

        });
    }
}
