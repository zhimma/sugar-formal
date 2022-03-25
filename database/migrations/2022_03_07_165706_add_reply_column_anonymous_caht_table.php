<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReplyColumnAnonymousCahtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('anonymous_chat', 'reply_id')) {
            Schema::table('anonymous_chat', function (Blueprint $table) {
                $table->integer('reply_id')->nullable()->default(NULL)->after('user_id');
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
        Schema::table('anonymous_chat', function (Blueprint $table) {
            $table->dropColumn('reply_id');
        });
    }
}
