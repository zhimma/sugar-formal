<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRealAuthUseTagsDisplayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('real_auth_user_tags_display')) {
            Schema::create('real_auth_user_tags_display', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('auth_type_id');
                $table->smallInteger('vip_show')->default(0);
                $table->integer('more_than_pr_show')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent();
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
        Schema::dropIfExists('real_auth_user_tags_display');
    }
}
