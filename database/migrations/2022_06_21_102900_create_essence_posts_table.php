<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEssencePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('essence_posts')) {
            Schema::create('essence_posts', function (Blueprint $table) {
                $table->id();
                $table->string('type', 10)->nullable()->comment('主要留言串');
                $table->integer('reply_id')->nullable();
                $table->integer('user_id');
                $table->integer('article_id')->nullable();
                $table->integer('tag_user_id')->nullable();
                $table->string('title', 100);
                $table->text('contents');
                $table->tinyInteger('category');
                $table->string('share_with',20);
                $table->tinyInteger('verify_status')->default(0)->comment('審核狀態');
                $table->timestamp('verify_time')->nullable();
                $table->tinyInteger('reward')->default(0)->comment('獎勵是否發放0:尚未 1:已發放');
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
        Schema::dropIfExists('essence_posts');
    }
}
