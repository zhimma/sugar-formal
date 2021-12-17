<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PostsAddColumnTop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('posts', 'top')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->smallInteger('top')->default(0)->after('views');
            });

            DB::table('posts')->where('id', 69)->update(['top'=>1]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('top');
        });
    }
}
