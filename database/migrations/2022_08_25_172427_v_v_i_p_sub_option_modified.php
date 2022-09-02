<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VVIPSubOptionModified extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('vvip_sub_option_xref', 'parent_xref_id')) {
            Schema::table('vvip_sub_option_xref', function (Blueprint $table) {
                $table->integer('parent_xref_id')->nullable()->after('option_id');
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
        //
        if (Schema::hasColumn('vvip_sub_option_xref', 'parent_xref_id')) {
            Schema::table('vvip_sub_option_xref', function (Blueprint $table) {
                $table->dropColumn('parent_xref_id');
            });
        }
    }
}
