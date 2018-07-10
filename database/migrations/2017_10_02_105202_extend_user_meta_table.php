<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendUserMeta2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_meta', function (Blueprint $table) {
            if (!Schema::hasColumn('isHideArea', 'isHideCup', 'isHideWeight')) {
                // $table->char('isHideArea', 1)->nullable();
                // $table->char('isHideCup', 1)->nullable();
                // $table->char('isHideWeight', 1)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_meta', function (Blueprint $table) {
            //
        });
    }
}
