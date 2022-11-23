<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\BackendUserDetails;

use App\Models\User;

use App\Models\Message;

use App\Models\SuspiciousUser;

use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

class Step2CheckExtendRecheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Step2CheckExtendRecheck';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '等待更多資料名單檢查';

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
        $user_list = [];
        $check_list = BackendUserDetails::with('check_extend_admin_action_log')
                                        ->with('user')
                                        ->select('user_id')
                                        ->where('user_check_step2_wait_login_times', '>', 0)
                                        ->get();
        foreach($check_list as $check)
        {
            //如果最後登入時間早於等待檢查開始的時間則跳過
            $check_start_time = $check->check_extend_admin_action_log->first() ? $check->check_extend_admin_action_log->first()->created_at : false;
            /*
            if($check_start_time)
            {
                if($check_start_time > $check->user->last_login)
                {
                    continue;
                }
            }
            */
            $user_list[$check->user->id]['check_data'] = $check;
            $user_list[$check->user->id]['check_start_time'] = $check_start_time;
        }
        $message = Message::select('from_id','to_id','created_at')->whereIn('from_id', array_keys($user_list))->orderByDesc('created_at')->get();
        
        $now_time = Carbon::now();
        $today = Carbon::today();
        $thirty_days_age = Carbon::today()->subDays(30);

        foreach($user_list as $user_id => $check_data)
        {
            //2點到4點間以外的時間
            //跳過30天沒發訊息的會員
            if(!($now_time > $today->addHours(2) && $now_time < $today->addHours(4)))
            {
                if($message->where('from_id', $user_id)->first() ?? false)
                {
                    if(Carbon::parse($message->where('from_id', $user_id)->first()->created_at) < $thirty_days_age)
                    {
                        continue;
                    }
                }
                else
                {
                    continue;
                }
            }

            $user_message_count = $message->where('from_id', $user_id)->where('created_at', '>', $check_data['check_start_time'])->unique('to_id')->count();
            //Log::Info($user_id);
            //Log::Info($user_message_count);
            if($user_message_count > 5)
            {
                SuspiciousUser::where('user_id', $user_id)->delete();
                $suspicious_user = new SuspiciousUser();
                $suspicious_user->admin_id = 0;
                //$suspicious_user->admin_id = $check_data['check_data']->check_extend_admin_action_log->first() ? $check_data['check_data']->check_extend_admin_action_log->first()->operator : 0;
                $suspicious_user->user_id = $user_id;
                $suspicious_user->reason = '(系統自動新增)等待更多資料時與超過五個會員對話';
                $suspicious_user->save();
            }
        }
        
    }
}
