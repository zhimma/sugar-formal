<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddErrorCodeColumnFaqUserRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('faq_user_replies', 'error_code')) {
            Schema::table('faq_user_replies', function (Blueprint $table) {
                 $table->string('error_code',100)->nullable()->after('is_pass')->index();
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
        if (Schema::hasColumn('faq_user_replies', 'error_code')) {
            Schema::table('faq_user_replies', function (Blueprint $table) {
                $table->dropColumn('error_code');
            });
        }
    }
}
