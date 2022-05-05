<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProvisionalVariables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_provisional_variables', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->boolean('has_adjusted_period')->default(0);
            $table->integer('login_time_of_adjusted_period')->default(0);
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
        Schema::dropIfExists('user_provisional_variables');
    }
}
