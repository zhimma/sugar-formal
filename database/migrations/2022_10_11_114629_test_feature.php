<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TestFeature extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('special_industries_test_setup')) {
            Schema::create('special_industries_test_setup', function (Blueprint $table) {
                $table->id();
                $table->string('title')->comment('測試標題');
                $table->boolean('is_banned')->default(false)->comment('是否封鎖中');
                $table->boolean('is_warned')->default(false)->comment('是否警示中');
                $table->boolean('is_ever_banned')->default(false)->comment('是否曾被封鎖');
                $table->boolean('is_ever_warned')->default(false)->comment('是否曾被警示');
                $table->timestamp('start_time')->nullable()->comment('開始時間');
                $table->timestamp('end_time')->nullable()->comment('結束時間');
                $table->integer('gender')->nullable()->comment('性別1:男2:女');
                $table->integer('select_member_count')->default(false)->comment('選擇異常會員人數');
                $table->integer('normal_member_count')->default(false)->comment('正常會員相對人數');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('special_industries_test_topic')) {
            Schema::create('special_industries_test_topic', function (Blueprint $table) {
                $table->id();
                $table->integer('test_setup_id');
                $table->text('test_topic');
                $table->text('correct_answer');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('special_industries_test_answer')) {
            Schema::create('special_industries_test_answer', function (Blueprint $table) {
                $table->id();
                $table->integer('test_topic_id');
                $table->integer('test_user');
                $table->text('user_answer');
                $table->timestamps();
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
        Schema::dropIfExists('special_industries_test_setup');
        Schema::dropIfExists('special_industries_test_topic');
        Schema::dropIfExists('special_industries_test_answer');
    }
}
