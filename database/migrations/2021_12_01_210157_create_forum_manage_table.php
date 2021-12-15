<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumManageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('forum_manage')) {
            Schema::create('forum_manage', function (Blueprint $table) {
                $table->id();
                $table->integer('forum_id');
                $table->integer('user_id');
                $table->integer('apply_user_id');
                $table->smallInteger('status')->default(0)->comment('0:申請中; 1:通過; 2:不通過');
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
        Schema::dropIfExists('posts_manage');
    }
}
