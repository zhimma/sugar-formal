<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_posts', function (Blueprint $table) {
            $table->id();
            $table->integer('forum_id');
            $table->string('type', 10)->nullable();
            $table->integer('reply_id')->nullable();
            $table->integer('user_id');
            $table->integer('tag_user_id')->nullable();
            $table->string('title', 100);
            $table->text('contents');
            $table->string('is_anonymous', 11);
            $table->integer('anonymous');
            $table->integer('combine');
            $table->integer('agreement');
            $table->integer('views');
            $table->smallInteger('top')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->useCurrent();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forum_posts');
    }
}
