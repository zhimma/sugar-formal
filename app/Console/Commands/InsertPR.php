<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class InsertPR extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'InsertPR';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert PR';

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
     * @return mixed
     */
    public function handle()
    {
        //
        try {

            //每天更新男會員PR值
            //DB: pr_log
            //暫定半年內有上線的會員進行更新
            $users = User::where('engroup', 1)->where('last_login', '>',Carbon::now()->subDays(180))->get();
            foreach($users as $user){
                User::PR($user->id);
            }
        } catch (\Exception $e) {
            Log::info('PR新增失敗'.$e->getMessage() .' LINE:'.$e->getLine());
        }
    }
}
