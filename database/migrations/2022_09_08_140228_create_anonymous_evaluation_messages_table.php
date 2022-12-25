<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnonymousEvaluationMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anonymous_evaluation_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('anonymous_evaluation_chat_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('reply_id');
            $table->unsignedTinyInteger('read')->nullable();
            $table->text('content')->charset('utf8mb4')->collation('utf8mb4_general_ci')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anonymous_evaluation_messages');
    }
}
