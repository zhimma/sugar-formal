<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

use App\Models\MessageRoomUserXref;

use App\Models\MessageRoom;

use App\Models\Message;

class CanMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $to_users = User::where('engroup', 2)
                            ->where(function ($query) {
                                $query->where('email', 'like', '%@test$')
                                    ->orWhere('email', 'like', '%sandyh.dlc$');
                            })->inRandomOrder()->take(10)->get();
        $from_user_id_array = [15598, 15601, 185840, 185841, 185842, 185843, 185844, 185845];
        foreach ($from_user_id_array as $from_user_id)
        {
            foreach($to_users as $to_user)
            {
                $room_id = 0;
                $room = MessageRoomUserXref::select('message_room_user_xrefs.room_id as r_id')->leftJoin('message_room_user_xrefs as sub_table', 'message_room_user_xrefs.room_id', '=', 'sub_table.room_id')
                                    ->where('message_room_user_xrefs.user_id', $from_user_id)
                                    ->where('sub_table.user_id', $to_user->id)
                                    ->first();
                if($room ?? false)
                {
                    $room_id = $room->r_id;
                }
                else
                {
                    $new_room = new MessageRoom();
                    $new_room->save();
                    $room_id = $new_room->id;
                    MessageRoomUserXref::create([
                        'room_id' => $room_id,
                        'user_id' => $from_user_id
                    ]);
                    MessageRoomUserXref::create([
                        'room_id' => $room_id,
                        'user_id' => $to_user->id
                    ]);
                }
                Message::create([
                    'room_id' => $room_id,
                    'to_id' => $to_user->id,
                    'from_id' => $from_user_id,
                    'content' => '哈囉你好嗎?'
                ]);
            }
        }
    }
}
