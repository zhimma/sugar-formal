<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorIDTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_id', function (Blueprint $table) {
            $table->id();
            $table->string('hash', 255);
            $table->string('host', 255)->nullable()->default(NULL);
            $table->timestamps();
        });

        Schema::create('visitor_id_user', function (Blueprint $table) {
            $table->id();
            $table->integer('visitor_id')->nullable()->default(NULL);
            $table->integer('user_id');
            $table->timestamps();
        });

        Schema::table('log_user_login', function($table) {
            $table->integer('visitor_id')->after('cfp_id')->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitor_id');
        Schema::dropIfExists('visitor_id_User');
    }
}
