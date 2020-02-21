<?php

use App\Models\User;
use App\Models\UserMeta;
use App\Services\UserService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $service = app(UserService::class);
        $email = Config::get('social.admin.email');

        $user = User::create([
            'name' => '站長',
            'email' => $email,
            'password' => bcrypt('admin'),
            'engroup' => 1
        ]);

        $user_meta = UserMeta::create([
            'user_id' => $service->findByEmail($email)->id,
            'is_active' => 1
        ]);


        $user = User::create([
            'name' => 'Test',
            'email' => 'test@gmail.com',
            'password' => bcrypt('test'),
            'engroup' => 1
        ]);

        $user_meta = UserMeta::create([
            'user_id' => $service->findByEmail('test@gmail.com')->id,
            'is_active' => 1
        ]);

            //$service->create($user, 'admin', 'admin', false);

    }
}
