<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVvipApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('vvip_application')) {
            Schema::create('vvip_application', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->string('order_id', 50)->nullable();
                $table->string('plan', 50)->nullable();
                $table->timestamp('deadline')->nullable();
                $table->tinyInteger('status')->default(0)->comment('0:申請中; 1:通過; 2:不通過; 3:待補件; 4:取消申請;');
                $table->text('user_note')->nullable();
                $table->text('note')->nullable();
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
        Schema::dropIfExists('vvip_application');
    }
}
