<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($ban_set->set_ban == 1 && banned_users::where('member_id', $uid)->first() == null)
        {
            //直接封鎖
            $userBanned = new banned_users;
            if($user->engroup==2 ) {
               if(!($user->advance_auth_status??null)) {
                   $userBanned->adv_auth=1;
               }
               else $userBanned=null;
            }                         
            if($userBanned) {
                $userBanned->member_id = $uid;
                $userBanned->reason = "系統原因($ban_set->id)";
                $userBanned->save();
                //寫入log
                DB::table('is_banned_log')->insert(['user_id' => $uid, 'reason' => "系統原因($ban_set->id)"]);
            }
        }
        elseif($ban_set->set_ban == 2 && BannedUsersImplicitly::where('target', $uid)->first() == null)
        {
            //隱性封鎖
            BannedUsersImplicitly::insert(['fp' => 'Line 124, BannedInUserInfo, ban_set ID: ' . $ban_set->id . ', content: ' . $ban_set->content, 'user_id' => 0, 'target' => $uid]);
        }
        elseif($ban_set->set_ban == 3 && warned_users::where('member_id', $uid)->first() == null)
        {
            //警示會員
            $userWarned = new warned_users;
            $userWarned->member_id = $uid;
            $userWarned->reason = "系統原因($ban_set->id)";

            if($ban_set->expired_days !=0)
            {
                $userWarned->expire_date = Carbon::now()->addDays($ban_set->expired_days);
            }

            $userWarned->save();
            //寫入log
            DB::table('is_warned_log')->insert(['user_id' => $uid, 'reason' => "系統原因($ban_set->id)"]);
            // UserMeta::where('user_id', $uid)->update(['isWarned' => 1]);
        }
    }
}
