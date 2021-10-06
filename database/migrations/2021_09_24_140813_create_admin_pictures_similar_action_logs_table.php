<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminPicturesSimilarActionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_pictures_similar_action_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('operator_id')->nullable();
            $table->integer('operator_role')->nullable();
            $table->integer('target_id')->nullable();
            $table->string('act', 20)->nullable();
            $table->string('pic', 255)->nullable();
            $table->string('reason', 255)->nullable();
            $table->string('days', 20)->nullable();
            $table->string('ip', 20)->nullable();
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
        Schema::dropIfExists('admin_pictures_similar_action_logs');
    }
}
