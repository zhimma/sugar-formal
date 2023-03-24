<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSearchFilterRememberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('search_filter_remember')) {
            Schema::create('search_filter_remember', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->index();
                $table->text('filter');	                
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
        Schema::dropIfExists('search_filter_remember');
    }
}
