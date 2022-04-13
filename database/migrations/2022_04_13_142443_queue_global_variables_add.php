<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class QueueGlobalVariablesAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('queue_global_variables', function (Blueprint $table) {
            //
            DB::table('queue_global_variables')->updateOrInsert([
                'name' => 'sent_today_600',
                'type' => 'bool',
                'value' => '1',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('queue_global_variables', function (Blueprint $table) {
            //
        });
    }
}
