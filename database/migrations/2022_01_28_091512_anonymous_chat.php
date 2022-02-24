<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AnonymousChat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasTable('anonymous_chat')) {
            Schema::create('anonymous_chat', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->mediumText('content')->nullable();
                $table->mediumtext('pic')->nullable();
                $table->string('anonymous', 10)->nullable();
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
        Schema::dropIfExists('anonymous_chat');
    }
}
