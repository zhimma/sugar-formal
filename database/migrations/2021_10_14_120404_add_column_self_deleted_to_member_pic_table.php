<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSelfDeletedToMemberPicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_pic', function (Blueprint $table) {
            $table->string('self_deleted')->nullable()->default(0);
        });

        $results = DB::table('member_pic')->whereNotNull('deleted_at')->get();

        foreach ($results as $deleted) {
            DB::table('member_pic')
            ->where('id', $deleted->id)
            ->update([
                "self_deleted" => 1
            ]);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_pic', function (Blueprint $table) {
            $table->dropColumn('self_deleted');
        });
    }
}
