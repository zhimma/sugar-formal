<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOfSuspicious extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suspicious', function (Blueprint $table) {
            if (!Schema::hasColumn('suspicious', 'reason')) {
                Schema::table('suspicious', function (Blueprint $table) {
                    $table->text('reason')->nullable()->after('account_text');
                });
            }
            if (!Schema::hasColumn('suspicious', 'images')) {
                Schema::table('suspicious', function (Blueprint $table) {
                    $table->text('images')->nullable()->after('reason');
                });
            }
            if (!Schema::hasColumn('suspicious', 'report_type')) {
                Schema::table('suspicious', function (Blueprint $table) {
                    $table->tinyInteger('report_type')->nullable()->comment('提報類型 (1:使用者糾紛有見過面 2.車馬費詐騙沒見過面)')->after('images');
                });
            }
            if (!Schema::hasColumn('suspicious', 'reporter_user_id')) {
                Schema::table('suspicious', function (Blueprint $table) {
                    $table->integer('reporter_user_id')->nullable()->comment('提報者')->after('report_type');
                });
            }
            if (!Schema::hasColumn('suspicious', 'reporter_user_id_list')) {
                Schema::table('suspicious', function (Blueprint $table) {
                    $table->text('reporter_user_id_list')->nullable()->comment('被提報次數')->after('reporter_user_id');
                });
            }
            if (!Schema::hasColumn('suspicious', 'deleted_at')) {
                Schema::table('suspicious', function (Blueprint $table) {
                    $table->timestamp('deleted_at')->nullable()->after('created_at');
                });
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
        Schema::table('suspicious', function (Blueprint $table) {
            $table->dropColumn('reason');
            $table->dropColumn('images');
            $table->dropColumn('report_type');
            $table->dropColumn('reporter_user_id');
            $table->dropColumn('reporter_user_id_list');
            $table->dropColumn('deleted_at');
        });
    }
}
