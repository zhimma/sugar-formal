<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOfForum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forum', function (Blueprint $table) {
            //
            if (!Schema::hasColumn('forum', 'is_warned')) {
                Schema::table('forum', function (Blueprint $table) {
                    $table->boolean('is_warned')->default(0)->after('status');
                });
            }
        });
        Schema::table('forum_manage', function (Blueprint $table) {
            //
            if (!Schema::hasColumn('forum_manage', 'active')) {
                Schema::table('forum_manage', function (Blueprint $table) {
                    $table->boolean('active')->default(1)->after('chat_status');
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
        Schema::table('forum', function (Blueprint $table) {
            //
            Schema::table('forum', function (Blueprint $table) {
                $table->dropColumn('is_warned');
            });
        });
        Schema::table('forum_manage', function (Blueprint $table) {
            //
            Schema::table('forum_manage', function (Blueprint $table) {
                $table->dropColumn('active');
            });
        });
    }
}
