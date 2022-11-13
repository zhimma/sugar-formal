<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;
use App\Models\SetAutoBan;

class UserLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UserLogin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '檢查過去一小時使用者資訊';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        set_time_limit(0);
        Log::info('start_command_UserLogin');
        // $users = User::where('last_login', '>', Carbon::now()->subMinutes(31))->get();
        // foreach($users as $user)
        // {
        //     Log::info('UserLogin:' . $user->id);
        //     SetAutoBan::logout_warned($user->id);
        // }
    }
}
