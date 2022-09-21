<?php

namespace Database\Seeders;

use App\Models\BannedUsersImplicitly;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Message;
use App\Models\SimpleTables\banned_users;
use App\Models\SimpleTables\warned_users;

class MessageFiller extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = [
            [
                'name' => '測試刪 60 條訊息',
                'email' => 'sandyh.dlc+60@gmail.com',
                'count' => 61
            ],
            [
                'name' => '測試刪 150 條訊息',
                'email' => 'sandyh.dlc+150@gmail.com',
                'count' => 151
            ],
            [
                'name' => '測試刪 250 條訊息',
                'email' => 'sandyh.dlc+250@gmail.com',
                'count' => 251
            ]
        ];

        // $banned_users = banned_users::all()->pluck('member_id')->toArray();
        // $implicitly_banned_users = BannedUsersImplicitly::all()->pluck('target')->toArray();
        // $warned_users = warned_users::all()->pluck('member_id')->toArray();
        // $to_users = User::whereNotIn([['id', $banned_users], ['id', ]])->inRandomOrder()->take(250)->get();

        foreach ($users as $u) {
            $user = User::firstOrCreate(
                ['name' => $u['name'],],
                [
                    'name' => $u['name'],
                    'email' => $u['email'],
                    'password' => bcrypt('123123'),
                    'engroup' => 2,
                    'is_hide_online' => 2
                ]);
    
            $user_meta = UserMeta::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'is_active' => 1
                ]);

            for ($i = 0; $i < $u['count']; $i++) {
                $receiver = User::firstOrCreate(
                    ['name' => '測試收件者' . $i,],
                    [
                        'name' => '測試收件者' . $i,
                        'email' => $u['email'] . $i,
                        'password' => bcrypt('123123'),
                        'engroup' => 1,
                        'is_hide_online' => 2
                    ]);
        
                $receiver_meta = UserMeta::firstOrCreate(
                    ['user_id' => $receiver->id],
                    [
                        'user_id' => $receiver->id,
                        'is_active' => 1
                    ]);
                Message::post($user->id, $receiver->id, '測試刪除訊息');
            }
        }
        
    }
}
