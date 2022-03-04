<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SyncToArticleId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('posts')){
            DB::statement("UPDATE posts SET article_id=id WHERE reply_id is null");

            DB::statement("UPDATE posts SET article_id = reply_id WHERE reply_id is not null");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('posts')){
            DB::statement("UPDATE posts SET article_id=null");
        }
    }
}
