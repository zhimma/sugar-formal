<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEssencePostsRewardLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('essence_posts_reward_log', function (Blueprint $table) {
            $table->id();
            $table->integer('post_id');
            $table->integer('user_id');
            $table->string('title', 100);
            $table->text('contents');
            $table->tinyInteger('category');
            $table->string('share_with',20);
            $table->timestamp('verify_time')->nullable();
            $table->timestamp('expire_origin')->nullable()->comment('獎勵前VIP到期日');
            $table->timestamp('expiry');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('essence_posts_reward_log');
    }
}
