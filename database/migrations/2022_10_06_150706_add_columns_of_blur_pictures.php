<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsOfBlurPictures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('user_meta', 'pic_blur')) {
            Schema::table('user_meta', function ($table) {
                $table->string('pic_blur', 255)->nullable()->after('pic');
            });
        }

        if(!Schema::hasColumn('member_pic', 'pic_blur')) {
            Schema::table('member_pic', function ($table) {
                $table->string('pic_blur', 255)->nullable()->after('pic');
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
        Schema::table('user_meta', function ($table) {
            $table->dropColumn('pic_blur');
        });

        Schema::table('member_pic', function ($table) {
            $table->dropColumn('pic_blur');
        });
    }
}
