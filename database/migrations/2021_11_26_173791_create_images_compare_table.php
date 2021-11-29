<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesCompareTable extends Migration
{
    public function up()
    {
        Schema::create('images_compare', function (Blueprint $table) {

            $table->increments('id');
            $table->string('pic')->nullable();
            $table->string('finded_pic')->nullable();
            $table->integer('asc_diff_count')->nullable();
            $table->integer('desc_diff_count')->nullable();
            $table->integer('asc_diff_sum')->nullable();
            $table->integer('desc_diff_sum')->nullable();
            $table->integer('asc_percent')->nullable();
            $table->integer('desc_percent')->nullable();
            $table->integer('asc_inter_part_percent')->nullable();
            $table->integer('desc_inter_part_percent')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('images_compare');
    }
}