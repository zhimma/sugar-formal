<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QueueGlobalVariables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasTable('queue_global_variables')) {
            Schema::create('queue_global_variables', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type')->default('int');
                $table->string('value');
                $table->timestamps();
            });
            DB::table('queue_global_variables')->updateOrInsertTs([
                'name' => 'similar_images_search',
                'type' => 'bool',
                'value' => '1',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ])->withTimestamp;
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
        Schema::dropIfExists('queue_global_variables');
    }
}
