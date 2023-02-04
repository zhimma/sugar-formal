<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVideoVerifyMemoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        if (!Schema::hasTable('user_video_verify_memo')) {
            Schema::create('user_video_verify_memo', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->unique();
                $table->text('user_question')->nullable();           
                $table->timestamp('user_question_at')->nullable();
                $table->timestamp('user_question_into_chat_at')->nullable();
                $table->char('blurryAvatar',20)->nullable();
                $table->char('blurryLifePhoto',20)->nullable();
                $table->integer('last_edit_admin_id')->nullable()->index();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
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
        if (Schema::hasTable('user_video_verify_memo')) {
            Schema::dropIfExists('user_video_verify_memo');
        }      
    }
}
