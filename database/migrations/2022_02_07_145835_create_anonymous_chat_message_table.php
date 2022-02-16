<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnonymousChatMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('anonymous_chat_message')) {
            Schema::create('anonymous_chat_message', function (Blueprint $table) {
                $table->id();
                $table->integer('anonymous_chat_id');
                $table->integer('user_id');
                $table->integer('to_user_id');
                $table->mediumText('content')->nullable();
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
        Schema::dropIfExists('anonymous_chat_message');
    }
}
