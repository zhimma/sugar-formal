<?php

namespace App\Console\Commands;

use App\Models\BackendUserDetails;
use App\Models\Message;
use App\Models\SuspiciousUser;
use Illuminate\Console\Command;

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
        logger('Step2CheckExtendRecheck start');
        $user_list = [];
        $check_list = BackendUserDetails::with('check_extend_admin_action_log')
            ->with('user')
            ->select('user_id')
            ->where('is_waiting_for_more_data', 1)
            ->get();
        logger('Step2CheckExtendRecheck check_list count: ' . count($check_list));
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
            if ($check->user) {
                $user_list[$check->user->id]['check_data'] = $check;
                $user_list[$check->user->id]['check_start_time'] = $check_start_time;
            }
        }
        $message = Message::select('from_id','to_id','created_at')->whereIn('from_id', array_keys($user_list))->orderByDesc('created_at')->get();

        foreach($user_list as $user_id => $check_data)
        {
            $user_message_count = $message->where('from_id', $user_id)->where('created_at', '>', $check_data['check_start_time'])->unique('to_id')->count();
            //Log::Info($user_id);
            logger('Step2CheckExtendRecheck user_id: ' . $user_id . ', user_message_count: ' . $user_message_count);
            if($user_message_count > 5)
            {
                SuspiciousUser::where('user_id', $user_id)->delete();
                $suspicious_user = new SuspiciousUser();
                $suspicious_user->admin_id = 0;
                //$suspicious_user->admin_id = $check_data['check_data']->check_extend_admin_action_log->first() ? $check_data['check_data']->check_extend_admin_action_log->first()->operator : 0;
                $suspicious_user->user_id = $user_id;
                $suspicious_user->reason = '(系統自動新增)等待更多資料時與超過五個會員對話';
                $suspicious_user->save();

                BackendUserDetails::where('user_id', $user_id)->update(['is_waiting_for_more_data' => 0]);
                logger('Step2CheckExtendRecheck user_id: ' . $user_id . ', is_waiting_for_more_data: 0');
            }
        }
        
    }
}
