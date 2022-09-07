<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSupplementNotice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('vvip_application', 'supplement_notice')) {
            Schema::table('vvip_application', function (Blueprint $table) {
                $table->string('supplement_notice')->nullable()->after('deadline');
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
        if (Schema::hasColumn('vvip_application', 'supplement_notice')) {
            Schema::table('vvip_application', function (Blueprint $table) {
                $table->dropColumn('supplement_notice');
            });
        }
    }
}
