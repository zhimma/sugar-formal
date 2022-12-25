<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnonymousEvaluationChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('anonymous_evaluation_chats')) {
            Schema::create('anonymous_evaluation_chats', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('evaluation_id');
                $table->string('members',50);
                $table->unsignedInteger('deletor')->nullable();
                $table->unsignedInteger('creator')->nullable();
                $table->unsignedTinyInteger('status')->default(1);
                $table->timestamps();
                $table->unique(['creator', 'status'], 'unique_chat');
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
        Schema::dropIfExists('anonymous_evaluation_chats');
    }
}
