<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataForFilterByInfoSubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('data_for_filter_by_info_sub')) {
            Schema::create('data_for_filter_by_info_sub', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('data_id');
                $table->string('cat');
                $table->string('type');
                $table->integer('count_num');	                
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
        Schema::dropIfExists('data_for_filter_by_info_sub');
    }
}
