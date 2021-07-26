<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPicOriginalName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('member_pic', 'original_name')) {
            Schema::table('member_pic', function ($table) {
                $table->string('original_name', 255)->nullable()->after('pic');
            });
        }

        if (!Schema::hasColumn('user_meta', 'pic_original_name')) {
            Schema::table('user_meta', function ($table) {
                $table->string('pic_original_name', 255)->nullable()->after('pic');
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
        //
        Schema::table('member_pic', function ($table) {
            $table->dropColumn('original_name')->default(null)->after('pic');
        });

        Schema::table('user_meta', function ($table) {
            $table->dropColumn('pic_original_name')->default(null)->after('pic');
        });
    }
}
