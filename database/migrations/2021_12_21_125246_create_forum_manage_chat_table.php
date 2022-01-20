<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumManageChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('forum_manage_chat')) {
            Schema::create('forum_manage_chat', function (Blueprint $table) {
                $table->id();
                $table->integer('forum_id');
                $table->integer('from_id');
                $table->integer('to_id');
                $table->mediumText('content')->nullable();
                $table->mediumtext('pic')->nullable();
                $table->string('read', 1)->default('N');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent();
                $table->softDeletes();
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
        Schema::dropIfExists('forum_manage_chat');
    }
}
