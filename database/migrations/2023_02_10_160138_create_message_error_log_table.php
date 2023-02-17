<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageErrorLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_error_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('from_id');
            $table->integer('to_id');
            $table->string('error_from',50)->nullable();                        
            $table->string('error',255)->nullable();
            $table->text('error_return_data',255)->nullable();
            $table->string('error_back_url',255)->nullable();
            $table->text('content')->nullable();
            $table->text('pic')->nullable();
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
        Schema::dropIfExists('message_error_log');
    }
}
