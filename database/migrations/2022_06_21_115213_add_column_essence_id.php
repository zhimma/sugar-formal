<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnEssenceId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forum_posts', function (Blueprint $table) {
            //
            if (!Schema::hasColumn('forum_posts', 'essence_id')) {
                Schema::table('forum_posts', function (Blueprint $table) {
                    $table->integer('essence_id')->nullable()->after('top');
                });
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
        Schema::table('forum_posts', function (Blueprint $table) {
            //
            Schema::table('forum_posts', function (Blueprint $table) {
                $table->dropColumn('essence_id');
            });
        });
    }
}
