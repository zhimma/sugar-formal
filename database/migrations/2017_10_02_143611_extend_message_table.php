<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message', function (Blueprint $table) {
            if (!Schema::hasColumn('from_id', 'to_id', 'content', 'read')) {
                // $table->integer('from_id')->unsigned();
                // $table->integer('to_id')->unsigned();
                // $table->text('content');
                // $table->char('read', 1)->default('N');
                // $table->integer('is_delete_1')->unsigned();
                // $table->integer('is_delete_2')->unsigned();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('message', function (Blueprint $table) {
            //
        });
    }
}
