<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AnonymousChatReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasTable('anonymous_chat_report')) {
            Schema::create('anonymous_chat_report', function (Blueprint $table) {
                $table->id();
                $table->integer('anonymous_chat_id');
                $table->integer('user_id');
                $table->integer('reported_user_id');
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
        //
        Schema::dropIfExists('anonymous_chat_report');
    }
}
