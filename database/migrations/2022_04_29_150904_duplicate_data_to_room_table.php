<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DuplicateDataToRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try{
            $message_room = DB::table('message')->whereNotNull('room_id')->groupBy('room_id')->limit(10000)->get();
            
            foreach(json_decode($message_room,true) as $room_id){
                
                $room = explode("_",$room_id['room_id']);
                // dd($room);
                foreach($room as $user_id){
                    \App\Models\MessageRoom::insert([
                        'room_id'=>$room_id['room_id'],
                        'user_id'=>$user_id
                    ]);
                }
            }
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
        Schema::table('room', function (Blueprint $table) {
            //
        });
    }
}
