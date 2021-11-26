<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesCompareEncodeTable extends Migration
{
    public function up()
    {
        Schema::create('images_compare_encode', function (Blueprint $table) {

            $table->increments('id');
            $table->string('pic')->nullable();
            $table->string('file_md5')->nullable();
            $table->longText('encode');
            $table->integer('total_spot')->nullable();
            $table->integer('total_diff_code')->nullable();
            $table->string('pic_cat',50)->nullable();
            $table->string('encode_by',100)->nullable();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('images_compare_encode');
    }
}