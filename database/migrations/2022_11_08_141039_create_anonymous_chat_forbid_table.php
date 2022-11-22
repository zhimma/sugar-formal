<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnonymousChatForbidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anonymous_chat_forbid', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('anonymous', 10)->nullable();
            $table->string('reason', 255)->nullable();
            $table->timestamp('expire_date')->nullable();
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
        Schema::dropIfExists('anonymous_chat_forbid');
    }
}
