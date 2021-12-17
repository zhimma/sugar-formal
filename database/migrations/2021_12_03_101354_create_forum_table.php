<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateForumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('forum')) {
            Schema::create('forum', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->string('title', 64);
                $table->string('sub_title', 256)->nullable();
                $table->smallInteger('status')->default(1)->comment('0:關閉; 1:啟用中;');
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
        Schema::dropIfExists('forum');
    }
}
