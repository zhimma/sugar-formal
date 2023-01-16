<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsMoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('posts_mood')) {
            Schema::create('posts_mood', function (Blueprint $table) {
                $table->id();
                $table->string('type', 10)->nullable()->comment('主要留言串');
                $table->integer('reply_id')->nullable();
                $table->integer('user_id');
                $table->integer('article_id')->nullable();
                $table->integer('tag_user_id')->nullable();
                $table->string('title', 100);
                $table->text('contents',2000);
                $table->text('images')->nullable();
                $table->string('is_anonymous', 11);
                $table->integer('anonymous');
                $table->integer('combine');
                $table->integer('agreement');
                $table->integer('views');
                $table->smallInteger('top')->default(0);
                $table->integer('deleted_by')->nullable();
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
        Schema::dropIfExists('posts_mood');
    }
}
