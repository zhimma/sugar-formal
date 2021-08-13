<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataForFilterByInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_for_filter_by_info', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id')->unique();
			$table->integer('message_count_7');
			$table->integer('visit_other_count_7');
			$table->integer('message_count');
			$table->integer('visit_other_count');
			$table->integer('be_reported_other_count');
			$table->integer('be_blocked_other_count');
			$table->integer('blocked_other_count');		
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
        Schema::dropIfExists('data_for_filter_by_info');
    }
}
