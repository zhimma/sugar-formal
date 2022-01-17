<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddColumnForumManageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('forum_manage', 'forum_status')) {
            Schema::table('forum_manage', function (Blueprint $table) {
                $table->smallInteger('forum_status')->default(0)->comment('0:無權限; 1:給予權限')->after('status');
                $table->smallInteger('chat_status')->default(0)->comment('0:無權限; 1:給予權限')->after('forum_status');
            });
        }
        DB::table('forum_manage')->where('status', 1)->update(['forum_status'=>1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('forum_manage', function (Blueprint $table) {
            $table->dropColumn('forum_status');
            $table->dropColumn('chat_status');
        });
    }
}
