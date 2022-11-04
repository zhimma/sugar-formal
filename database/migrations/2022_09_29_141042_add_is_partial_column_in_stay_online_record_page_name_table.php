<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPartialColumnInStayOnlineRecordPageNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('stay_online_record_page_name', 'is_partial')) {
            Schema::table('stay_online_record_page_name', function (Blueprint $table) {
                $table->boolean('is_partial')->default(0)->nullable()->after('name')->index();
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
        Schema::table('stay_online_record_page_name', function (Blueprint $table) {
            $table->dropColumn('is_partial');
        });
    }
}
