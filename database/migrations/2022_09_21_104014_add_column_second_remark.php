<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSecondRemark extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('vvip_option_xref', 'option_second_remark')) {
            Schema::table('vvip_option_xref', function (Blueprint $table) {
                $table->string('option_second_remark')->nullable()->after('option_remark');
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
        if (Schema::hasColumn('vvip_option_xref', 'option_second_remark')) {
            Schema::table('vvip_option_xref', function (Blueprint $table) {
                $table->dropColumn('option_second_remark');
            });
        }
    }
}
