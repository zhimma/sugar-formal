<?php

namespace App\Listeners;

use App\Events\CheckWarnedOfReport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserMeta;
use App\Models\User;
use Carbon\Carbon;

class CheckWarnedOfReportListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\CheckWarnedOfReport  $event
     * @return void
     */
    public function handle(CheckWarnedOfReport $event)
    {
        $uid = $event->uid;
        $user = User::where('id', $uid)->first();
        $auth_status = 0;
        if($user->isPhoneAuth()==1)
        {
            $auth_status = 1;
        }
        if($user->meta->isWarned == 1)
        {
            if($auth_status==1 && !$user->isAdminWarned())
            {
                //取消警示
                UserMeta::where('user_id',$user->id)
                        ->where(function ($q){
                            $q->whereNull('isWarnedType');
                            $q->orwhere('isWarnedType','<>','adv_auth');
                        })
                        ->update(['isWarned'=>0, 'isWarnedRead'=>0, 'isWarnedTime' => null]);
            }
        }
        if($user->meta->isWarned == 0 && $user->WarnedScore() >= 10 && $auth_status == 0 && $user->id != 1049)
        {
            //加入警示
            UserMeta::where('user_id',$user->id)->update(['isWarned'=>1, 'isWarnedRead'=>0, 'isWarnedTime' => Carbon::now()]);
        }
    }
}
