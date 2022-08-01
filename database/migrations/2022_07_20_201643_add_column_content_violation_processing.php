<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnContentViolationProcessing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluation', function (Blueprint $table) {
            if (!Schema::hasColumn('evaluation', 'content_violation_processing')) {
                $table->enum('content_violation_processing', ['return', 'modify_directly'])->nullable();
            }
            if (!Schema::hasColumn('evaluation', 'anonymous_content_status')) {
                $table->integer('anonymous_content_status')->default(0);  // 0:未處理  1:通過審核  2:未通過審核
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
        Schema::table('evaluation', function (Blueprint $table) {
            if (Schema::hasColumn('evaluation', 'content_violation_processing')) {
                $table->dropColumn('content_violation_processing');
            }
            if (Schema::hasColumn('evaluation', 'anonymous_content_status')) {
                $table->dropColumn('anonymous_content_status');
            }
        });
    }
}
