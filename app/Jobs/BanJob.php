<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\SimpleTables\warned_users;
use App\Models\SimpleTables\banned_users;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\BannedUsersImplicitly;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\Middleware\WithoutOverlapping;


class BanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $timeout = 300;

    protected $uid;
    protected $ban_set;
    protected $user;
    protected $type;
    
    public function __construct($uid, $ban_set, $user, $type)
    {
        Log::info('start_jobs_BanJob_construct');
        $this->uid = $uid;
        $this->ban_set = $ban_set;
        $this->user = $user;
        $this->type = $type;
    }

    public function handle()
    {
        Log::info('start_jobs_BanJob');
        Log::Info(Carbon::now());
        $that = $this;
        $user_had_been_banned_by_ban_set = banned_users::where('member_id', $this->uid)->get()->first(function($item) use ($that) {
            return $item->reason == "系統原因(".$that->ban_set->id.")";
        });
        $user_had_been_implicitly_banned_by_ban_set = BannedUsersImplicitly::where('target', $this->uid)->get()->first(function($item) use ($that) {
            return $item->reason == "系統原因(".$that->ban_set->id.")";
        });
        $user_had_been_warned_by_ban_set = warned_users::where('member_id', $this->uid)->get()->first(function($item) use ($that) {
            return $item->reason == "系統原因(".$that->ban_set->id.")";
        });
        if($this->ban_set->set_ban == 1 && !$user_had_been_banned_by_ban_set)
        {
            //直接封鎖
            $userBanned = new banned_users;
            if($this->user->engroup==2) {
               if(!($this->user->advance_auth_status ?? null)) {
                   $userBanned->adv_auth=1;
               }
               else $userBanned = null;
            }                         
            if($userBanned) {
                $userBanned->member_id = $this->uid;
                $userBanned->reason = "系統原因(".$this->ban_set->id.")";
                $userBanned->save();
                //寫入log
                DB::table('is_banned_log')->insert(['user_id' => $this->uid, 'reason' => "系統原因(".$this->ban_set->id.")"]);
            }
        }
        elseif($this->ban_set->set_ban == 2 && !$user_had_been_implicitly_banned_by_ban_set)
        {
            //隱性封鎖
            $Line = 0;
            switch($this->type)
            {
                case 'profile':
                    $Line = 79;
                    break;
                
                case 'message':
                    $Line = 124;
                    break;
            }
            BannedUsersImplicitly::insert(['fp' => 'Line ' . $Line . ', BannedInUserInfo, ban_set ID: ' . $this->ban_set->id . ', content: ' . $this->ban_set->content, 'user_id' => 0, 'target' => $this->uid]);
        }
        elseif($this->ban_set->set_ban == 3 && !$user_had_been_warned_by_ban_set)
        {
            //警示會員
            $userWarned = new warned_users;
            $userWarned->member_id = $this->uid;
            $userWarned->reason = "系統原因(".$this->ban_set->id.")";

            if($this->ban_set->expired_days !=0)
            {
                $userWarned->expire_date = Carbon::now()->addDays($this->ban_set->expired_days);
            }

            $userWarned->save();
            //寫入log
            DB::table('is_warned_log')->insert(['user_id' => $this->uid, 'reason' => "系統原因(".$this->ban_set->id.")"]);
            // UserMeta::where('user_id', $this->uid)->update(['isWarned' => 1]);
        }
        //sleep(90);
        Log::info('end_jobs_BanJob');
        Log::Info(Carbon::now());
    }
}
