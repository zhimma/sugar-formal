<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnMessageExpiryTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message_board', function (Blueprint $table) {
            $table->string('set_period', 20)->nullable()->after('contents');
            $table->timestamp('message_expiry_time')->nullable()->after('set_period');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('message_board', function (Blueprint $table) {
            $table->dropColumn('set_period');
            $table->dropColumn('message_expiry_time');
        });
    }
}
