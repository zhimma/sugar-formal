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
use App\Models\User;
use Illuminate\Support\Facades\Log;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class BanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;
    
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
        $this->connection = app()->environment('production-misc') ? 'mysql_read' : 'mysql';
    }

    public function handle()
    {
        if (!$this->user) {
            logger("Ban job failed on user {$this->uid}, user not found, try to find user again.");
            $this->user = User::find($this->uid);
        }
        if($this->user ?? false)
        {
            Log::info("start_jobs_BanJob at " . now() . ", user {$this->user->id}, ban set {$this->ban_set?->id}");
            if (!$this->ban_set?->id) {
                logger("Ban job failed on user {$this->user->id}, no ban set id, try to display ban type: " . $this->ban_set->set_ban);
            }
            if (!$this->ban_set->set_ban) {
                logger("Ban job failed on user {$this->user->id}, no ban type, set id: {$this->ban_set->set_ban}.");
            }
            $that = $this;
            $user_had_been_banned = DB::connection($this->connection)->table('banned_users')->where('member_id', $this->uid)->get()->first();
            $user_had_been_implicitly_banned = DB::connection($this->connection)->table('banned_users_implicitly')->where('target', $this->uid)->get()->first();
            $user_had_been_warned = DB::connection($this->connection)->table('warned_users')->where('member_id', $this->uid)->get()->first();
            logger("Ban job on user {$this->user->id}, ban set {$this->ban_set->id}, user_had_been_banned: " . ($user_had_been_banned ? 'true' : 'false') . ", user_had_been_implicitly_banned: " . ($user_had_been_implicitly_banned ? 'true' : 'false') . ", user_had_been_warned: " . ($user_had_been_warned ? 'true' : 'false'));
            if($this->ban_set->set_ban == 1 && !$user_had_been_banned)
            {
                //直接封鎖
                $userBanned = new banned_users;
                if($this->user->engroup==2) {
                    logger("User {$this->user->id} is female.");
                    if(!($this->user->advance_auth_status ?? null)) {
                        $userBanned->adv_auth=1;
                        logger("Ban job set adv auth on user {$this->user->id}, ban set {$this->ban_set->id}");
                    }
                    else {
                        $userBanned = null;
                        logger("Ban job skipped on user {$this->user->id}, user is already adv authed.");
                    }
                }                         
                if($userBanned) {
                    logger("Ban job set ban on user {$this->user->id} initiated, ban set {$this->ban_set->id}");
                    $userBanned->member_id = $this->uid;
                    $userBanned->reason = "系統原因(".$this->ban_set->id.")";
                    $userBanned->save();
                    //寫入log
                    DB::connection('mysql')->table('is_banned_log')->insert(['user_id' => $this->uid, 'reason' => "系統原因(".$this->ban_set->id.")"]);
                    logger("Baned user {$this->user->id}, ban set {$this->ban_set->id}");
                }
            }
            elseif($this->ban_set->set_ban == 2 && !$user_had_been_implicitly_banned)
            {
                //隱性封鎖
                $Line = 0;
                switch($this->type)
                {
                    case 'profile':
                        $Line = 92;
                        break;
                    
                    case 'message':
                        $Line = 96;
                        break;
                }
                logger("Implicit banned user {$this->user->id} initiated, ban set {$this->ban_set->id}, line {$Line}");
                DB::connection('mysql')->table('banned_users_implicitly')->insert(['fp' => 'Line ' . $Line . ', BannedInUserInfo, ban_set ID: ' . $this->ban_set->id . ', content: ' . $this->ban_set->content, 'user_id' => 0, 'target' => $this->uid]);
                logger("Implicit banned user {$this->user->id}, ban set {$this->ban_set->id}");
            }
            elseif($this->ban_set->set_ban == 3 && !$user_had_been_warned)
            {
                //警示會員
                logger("Warned user {$this->user->id} initiated, ban set {$this->ban_set->id}");
                $userWarned = new warned_users;
                $userWarned->member_id = $this->uid;
                $userWarned->reason = "系統原因(".$this->ban_set->id.")";

                if($this->ban_set->expired_days !=0)
                {
                    $userWarned->expire_date = Carbon::now()->addDays($this->ban_set->expired_days);
                }

                $userWarned->save();
                //寫入log
                DB::connection('mysql')->table('is_warned_log')->insert(['user_id' => $this->uid, 'reason' => "系統原因(".$this->ban_set->id.")"]);
                // UserMeta::where('user_id', $this->uid)->update(['isWarned' => 1]);
                logger("Warned user {$this->user->id}, ban set {$this->ban_set->id}");
            }
            else {
                logger("Ban job failed on user {$this->user->id}, ban set {$this->ban_set->id}, user_had_been_banned: " . ($user_had_been_banned ? 'true' : 'false') . ", user_had_been_implicitly_banned: " . ($user_had_been_implicitly_banned ? 'true' : 'false') . ", user_had_been_warned: " . ($user_had_been_warned ? 'true' : 'false'));
            }
            //sleep(90);
            Log::info("end_jobs_BanJob at " . now() . ", user {$this->user->id}, ban set {$this->ban_set->id}");
        }
        else {
            logger("Ban job failed on user {$this->uid}, user not found.");
        }
        
        return 0;
    }

    /**
    * The job failed to process.
    *
    * @param  Exception  $exception
    * @return void
    */
    public function failed(\Exception $exception)
    {
        logger($exception);
    }
}
