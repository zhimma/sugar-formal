<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTinySettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('user_tiny_setting')) {
            Schema::create('user_tiny_setting', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('cat');
                $table->string('value',100);	                
                $table->timestamps();
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
        Schema::dropIfExists('user_tiny_setting');
    }
}
