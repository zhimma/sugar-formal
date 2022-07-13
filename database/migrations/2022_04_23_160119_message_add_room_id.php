<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MessageAddRoomId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('message', 'room_id')) {
            DB::statement('ALTER TABLE `message` ADD `room_id` varchar(50) DEFAULT NULL AFTER `deleted_at`, ALGORITHM = INPLACE, LOCK=NONE;');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
