<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEssenceStatisticsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('essence_statistics_log')) {
            Schema::create('essence_statistics_log', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('essence_posts_id');
                $table->string('message_client_id',50)->nullable();
                $table->timestamp('message_send_time');
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
        Schema::dropIfExists('essence_statistics_log');
    }
}
