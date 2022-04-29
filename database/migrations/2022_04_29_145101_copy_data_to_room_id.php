<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CopyDataToRoomId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try{
            DB::statement("UPDATE message SET CONCAT(from_id,'_',to_id) WHERE from_id < to_id");
            DB::statement("UPDATE message SET CONCAT(to_id,'_',from_id) WHERE to_id < from_id");
        }catch(\Exception $e){
            dd($e);
        }
    }   

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('room_id', function (Blueprint $table) {
            //
        });
    }
}
