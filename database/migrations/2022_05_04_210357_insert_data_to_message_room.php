<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\MessageRoomUserXref;
use App\Models\MessageRoom;
use App\Models\Message;
class InsertDataToMessageRoom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $messagesList = DB::table('message')->orderBy('id','desc')->chunk(10000, function($messages) {

            foreach($messages->lazy() as $row){

                $rows = array(
                    $row->from_id,
                    $row->to_id
                );

                $checkData = MessageRoomUserXref::whereIn('user_id',$rows)->groupBy('room_id')->havingRaw('count(user_id) = ?', [2]);
        
                if($checkData->count()==0){
                    $messageRoom = new MessageRoom;
                    $messageRoom->save();
                    $room_id = $messageRoom->id;
                
        
                    foreach($rows as $row){
                        $messageRoomUserXref = new MessageRoomUserXref;
                        $messageRoomUserXref->user_id = $row;
                        $messageRoomUserXref->room_id = $room_id;
                        $messageRoomUserXref->save();
                    }

                }else{
                    $room_id = $checkData->first()['room_id'];
                }    

                //sometime it will happen error
                if(isset($row->id)){
                    dump($row->id);
                    DB::table('message')->where('id', $row->id)->update(['room_id'=>$room_id]);
                }
                
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
        Schema::table('message_room', function (Blueprint $table) {
            //
        });
    }
}
