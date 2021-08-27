<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSetAutoBanExpiry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		if (!Schema::hasColumn('set_auto_ban', 'expiry')) {
			Schema::table('set_auto_ban', function (Blueprint $table) {
				$table->dateTime('expiry')->default('0000-00-00 00:00:00')->after('cuz_user_set');
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
        Schema::table('set_auto_ban', function (Blueprint $table) {
			$table->dropColumn('expiry');
        });
    }
}
