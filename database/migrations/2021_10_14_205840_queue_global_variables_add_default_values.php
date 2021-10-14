<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class QueueGlobalVariablesAddDefaultValues extends Migration
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
                'name' => 'sent_today_200',
                'type' => 'bool',
                'value' => '0',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
            DB::table('queue_global_variables')->updateOrInsert([
                'name' => 'sent_today_400',
                'type' => 'bool',
                'value' => '0',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
            DB::table('queue_global_variables')->updateOrInsert([
                'name' => 'sent_today_4500',
                'type' => 'bool',
                'value' => '0',
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
            DB::table('queue_global_variables')->where('name', 'sent_today_200')->delete();
            DB::table('queue_global_variables')->where('name', 'sent_today_400')->delete();
            DB::table('queue_global_variables')->where('name', 'sent_today_4500')->delete();
        });
    }
}
