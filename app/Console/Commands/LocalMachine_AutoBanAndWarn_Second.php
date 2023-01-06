<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Models\SetAutoBan;

class LocalMachine_AutoBanAndWarn_Second extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'LocalMachine_AutoBanAndWarn_Second';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '地端主機自動警示及封鎖';

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
        Log::Info('LocalMachine_AutoBanAndWarn_Second_Start');

        $ban_list = [];
        $users = User::where('last_login', '>', Carbon::now()->subSeconds(61))->get();
        foreach($users as $user)
        {
            Log::info('User: ' . $user->id);
            $start_time = Carbon::now();
            $merge_ban_list = SetAutoBan::local_machine_ban_and_warn_second($user->id);
            $end_time = Carbon::now();
            $time_elapsed = $end_time->diffInSeconds($start_time, true);
            Log::Info('User: ' . $user->id . ', time elapsed: ' . $time_elapsed);
            
            if($merge_ban_list != [])
            {
                $ban_list = array_merge($ban_list, $merge_ban_list);
            }
        }
        Log::Info($ban_list);

        $link_address = config('localmachine.MISC_LINK_SERVER').'/LocalMachineReceive/BanAndWarn';
        $post_data = [
            'form_params' => [
                'key' => config('localmachine.MISC_KEY'),
                'ban_list' => $ban_list
            ]
        ];
        $client   = new Client();
        $response = $client->request('POST', $link_address, $post_data);
        $contents = $response->getBody()->getContents();
        Log::Info($contents);

        Log::Info('LocalMachine_AutoBanAndWarn_Second_End');
    }
}
