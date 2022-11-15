<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVideoVerifyRecordLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('user_video_verify_record_log')) {
            Schema::create('user_video_verify_record_log', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->string('type',100)->nullable()->index();
                $table->integer('signal_data_id')->nullable()->index();
                $table->string('method', 255)->nullable()->index();
                $table->string('step', 255)->nullable()->index();
                $table->string('act', 255)->nullable()->index();
                $table->string('act_step', 255)->nullable()->index();
                $table->string('topic', 255)->nullable()->index();
                $table->string('topic_step', 255)->nullable()->index();
                $table->string('ajax_url', 255)->nullable()->index();
                $table->string('ajax_step', 255)->nullable()->index();
                $table->string('ajax_sdata', 255)->nullable()->index();
                $table->string('ajax_rdata', 255)->nullable()->index();
                $table->string('ajax_error', 255)->nullable()->index();
                $table->string('from_file', 255)->nullable()->index();
                $table->string('title', 255)->nullable();
                $table->text('url')->nullable();
                $table->text('data')->nullable();
                $table->text('request')->nullable();
                $table->text('server')->nullable();
                $table->text('file')->nullable();
                $table->string('ip', 255)->nullable()->index();
                $table->string('user_agent', 255)->nullable()->index();
                $table->string('sid',255)->nullable()->index();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent();
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
        if (Schema::hasTable('user_video_verify_record_log')) {
            Schema::dropIfExists('user_video_verify_record_log');
        }
    }
}
