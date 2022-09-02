<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVvipInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('vvip_info')) {
            Schema::create('vvip_info', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->unique();
                $table->text('point')->nullable();
                $table->text('date_trend')->nullable();
                $table->text('assets')->nullable();
                $table->text('extra_care')->nullable();
                $table->text('assets_image')->nullable();
                $table->text('life')->nullable();
                $table->text('date_expect')->nullable();
                $table->string('about', 512)->nullable();
                $table->tinyInteger('status')->default(0)->comment('0：關閉；1：啟用');
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
        Schema::dropIfExists('vvip_info');
    }
}
