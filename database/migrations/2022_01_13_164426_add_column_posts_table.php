<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('deleted_by')->nullable()->after('top');
            $table->softDeletes()->after('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('deleted_by');
            $table->dropSoftDeletes();
        });
    }
}
