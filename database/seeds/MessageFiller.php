<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Message;

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
                'count' => 60
            ],
            [
                'name' => '測試刪 150 條訊息',
                'email' => 'sandyh.dlc+150@gmail.com',
                'count' => 150
            ],
            [
                'name' => '測試刪 250 條訊息',
                'email' => 'sandyh.dlc+250@gmail.com',
                'count' => 250
            ]
        ];
        $to_users = User::inRandomOrder()->take(250)->get();
        foreach ($users as $u) {
            $user = User::firstOrCreate(
                ['name' => $u['name'],],
                [
                    'name' => $u['name'],
                    'email' => $u['email'],
                    'password' => bcrypt('123123'),
                    'engroup' => 2
                ]);
    
            $user_meta = UserMeta::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'is_active' => 1
                ]);

            for ($i = 0; $i < $u['count']; $i++) {
                Message::post($user->id, $to_users[$i]->id, '測試刪除訊息');
            }
        }
        
    }
}
