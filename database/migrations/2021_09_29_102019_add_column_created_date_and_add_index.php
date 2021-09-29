<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCreatedDateAndAddIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_user_login', function (Blueprint $table) {
            $table->date('created_date')->nullable()->after('country');
            $table->index(['user_id', 'cfp_id', 'created_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_user_login', function (Blueprint $table) {
            $table->dropColumn('created_date');
            $table->dropIndex(['user_id', 'cfp_id', 'created_date']);
        });
    }
}
