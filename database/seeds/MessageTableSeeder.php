<?php

use Illuminate\Database\Seeder;
use App\Models\Message_new;
use App\Models\User;

class MessageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $msgs = factory(Message_new::class, 10)->create();
    }
}
