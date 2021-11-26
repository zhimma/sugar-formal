<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesCompareStatusTable extends Migration
{
    public function up()
    {
        Schema::create('images_compare_status', function (Blueprint $table) {

            $table->increments('id');
            $table->string('pic')->nullable();
            $table->tinyInteger('queue')->default(0);
            $table->timestamp('qstart_time')->nullable();
            $table->tinyInteger('status');
            $table->timestamp('start_time')->nullable();
            $table->integer('encode_break_id')->nullable();
            $table->tinyInteger('is_specific')->default(0);
            $table->tinyInteger('is_error')->default(0);
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('images_compare_status');
    }
}