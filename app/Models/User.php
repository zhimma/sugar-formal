<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Blocked;
use App\Models\ValueAddedService;
use App\Models\ValueAddedServiceLog;
use App\Models\Vip;
use App\Models\Tip;
use App\Models\UserMeta;
use App\Models\MemberPic;
use App\Models\SimpleTables\banned_users;
use App\Models\SimpleTables\warned_users;
use App\Models\FaqUserGroup;
use App\Models\FaqUserReply;
use App\Models\RealAuthUserPatch;
use App\Models\RealAuthUserApply;
use App\Models\RealAuthUserReply;
use App\Models\RealAuthUserModify;
use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Ixudra\Curl\Facades\Curl;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use \App\Services\UserService;
use App\Models\LogFreeVipPicAct;
use App\Models\LogUserLogin;
use App\Models\UserTinySetting;
use App\Models\IsBannedLog;
use App\Models\IsWarnedLog;
use App\Models\SimpleTables\short_message;
use App\Models\LogAdvAuthApi;
use App\Models\UserTattoo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\StayOnlineRecord;
use App\Models\PuppetAnalysisRow;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\UserRemarksLog;
use App\Models\UserVideoVerifyRecord;
use App\Models\UserVideoVerifyMemo;
use App\Services\AdminService;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'title',
        'enstatus',
        'engroup',
        'last_login',
        'login_times',
        'intro_login_times',
        'isReadManual',
        'is_read_female_manual_part1',
        'is_read_female_manual_part2',
        'is_read_female_manual_part3',
        'exchange_period',
        'line_notify_switch',
        'is_hide_online',
        'hide_online_time',
        'is_vvip'
        ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $with = ['user_meta', 'vip'];

    /*
    |--------------------------------------------------------------------------
    | relationships
    |--------------------------------------------------------------------------
    */

    // UserMeta
    public function meta()
    {
        return $this->hasOne(UserMeta::class, 'user_id', 'id');
    }

    //Vip
    public function vip()
    {
        return $this->hasMany(Vip::class, 'member_id', 'id')->where('active', 1)->orderBy('id', 'desc');
    }

    public function vip_any()
    {
        return $this->hasMany(Vip::class, 'member_id', 'id')->orderBy('created_at', 'desc');
    }
    
    public function vip_log()
    {
        return $this->hasMany(VipLog::class, 'member_id', 'id');
    }    

    public function vas()
    {
        return $this->hasMany(ValueAddedService::class, 'member_id', 'id')->where('active', 1)->orderBy('created_at', 'desc');
    }
    
    public function vas_log()
    {
        return $this->hasMany(ValueAddedServiceLog::class, 'member_id', 'id');
    }    

    public function aw_relation() {
        return $this->hasOne(\App\Models\SimpleTables\warned_users::class, 'member_id', 'id')->where(function ($query){
            $query->whereNull('expire_date')->orWhere('expire_date', '>=', Carbon::now());
        });
    }

    public function fa_relation() {
        return $this->hasOne(\App\Models\SimpleTables\short_message::class, 'member_id', 'id')->where('mobile','!=','')->where('active', 1);
    }

    public function self_auth_tags_display() {
        return $this->hasOne(RealAuthUserTagsDisplay::class, 'user_id', 'id')->where('auth_type_id', 1);
    }

    public function beauty_auth_tags_display() {
        return $this->hasOne(RealAuthUserTagsDisplay::class, 'user_id', 'id')->where('auth_type_id', 2);
    }

    public function famous_auth_tags_display() {
        return $this->hasOne(RealAuthUserTagsDisplay::class, 'user_id', 'id')->where('auth_type_id', 3);
    }

    public function pr_log() {
        return $this->hasOne(Pr_log::class, 'user_id', 'id')->where('active', 1);
    }

    //sent messages
    public function sentMessages()
    {
        return $this->hasMany(Message_new::class, 'from_id', 'id');
    }

    //received messages
    public function receivedMessages()
    {
        return $this->hasMany(Message_new::class, 'to_id', 'id');
    }

    public function messageRooms()
    {
        return $this->hasManyThrough(MessageRoom::class, MessageRoomUserXref::class, 'user_id', 'id', 'id', 'room_id');
    }

    //生活照
    public function pic()
    {
        return $this->hasMany(MemberPic::class, 'member_id', 'id');
    }

    public function pics()
    {
        return $this->hasMany(MemberPic::class, 'member_id', 'id');
    }
    
    //免費VIP照片管理log
    public function log_free_vip_pic_acts()
    {
        return $this->hasMany(LogFreeVipPicAct::class, 'user_id', 'id');
    } 
    
    public function log_free_vip_avatar_acts()
    {
        return $this->hasMany(LogFreeVipPicAct::class, 'user_id', 'id')->where('pic_type','avatar');
    }  
    
    public function log_free_vip_member_pic_acts()
    {
        return $this->hasMany(LogFreeVipPicAct::class, 'user_id', 'id')->where('pic_type','member_pic');
    } 

    public function log_user_login()
    {
        return $this->hasMany(LogUserLogin::class, 'user_id', 'id');
    }  
    // 送往api驗證身分證、生日和電話的紀錄，
    // 驗證通過的生日和電話，會同時更新到user_meta上原本的生日和電話
    public function log_adv_auth_api()
    {
        return $this->hasMany(LogAdvAuthApi::class, 'user_id', 'id');
    }

    //新手教學時間
    public function newer_manual_stay_online_time()
    {
        return $this->hasOne(StayOnlineRecord::class, 'user_id', 'id')->select(DB::raw("SUM(newer_manual) as time"));
    }
    
    //停留時間
    public function stay_online_record()
    {
        return $this->hasMany(StayOnlineRecord::class, 'user_id', 'id');
    }

    public function stay_online_record_only_page()
    {
        return StayOnlineRecord::addOnlyPageClauseToQuery($this->stay_online_record());//->whereNotNull('stay_online_time')->whereNotNull('url');
    }  

    public function female_newer_manual_time_list()
    {
        
        return $this->stay_online_record_only_page()
            ->where('url','like','%#nr_fnm%')
            ->groupBy('url')
            ->selectRaw('SUBSTRING(url, -3, 3) as step,sum(stay_online_time) as time')
            ;
    }
    
    public function getFemaleNewerManualTotalTime()
    {
        return $this->female_newer_manual_time_list->sum('time');
    }
    
    //多重帳號row
    public function puppet_analysis_row()
    {
        return $this->hasMany(PuppetAnalysisRow::class, 'name', 'id');
    }
    
    public function puppet_analysis_row_standard()
    {
        return $this->puppet_analysis_row()->where('cat','');
    }    
    
    public function puppet_analysis_row_only_cfpid()
    {
        return $this->puppet_analysis_row()->where('cat','only_cfpid');
    } 
    //簡易設定 用在簡易量少的設定上
    public function tiny_setting() {
        return $this->hasMany(UserTinySetting::class, 'user_id', 'id');
    }
	
    public function is_banned_log() {
        return $this->hasMany(IsBannedLog::class, 'user_id', 'id');
    }	
	
    public function is_warned_log() {
        return $this->hasMany(IsWarnedLog::class, 'user_id', 'id');
    }	
    
    public function short_message() {
        return $this->hasMany(short_message::class, 'member_id', 'id');
    }	    

    // 可疑
    public function suspicious()
    {
        return $this->hasOne(SuspiciousUser::class, 'user_id', 'id')->whereNull('deleted_at');
    }
    
    public function suspicious_withTrashed()
    {
        return $this->hasMany(SuspiciousUser::class, 'user_id', 'id')->withTrashed();
    } 

    public function suspicious_withTrashed_orderByDesc()
    {
        return $this->suspicious_withTrashed()->orderByDesc('id');
    }     

    //生活照倒序
    public function pic_orderByDecs()
    {
        return $this->hasMany(MemberPic::class, 'member_id', 'id')->orderByDesc('created_at');
    }

    // 只列出已刪除的生活照
    public function pic_onlyTrashed()
    {
        return $this->hasMany(MemberPic::class, 'member_id', 'id')->onlyTrashed()->orderByDesc('created_at');
    }

    // 列出含已刪除的所有生活照
    public function pic_withTrashed()
    {
        return $this->hasMany(MemberPic::class, 'member_id', 'id')->withTrashed();
    }

    // 列出已刪除頭像
    public function avatar_deleted()
    {
        return $this->hasMany(AvatarDeleted::class, 'user_id', 'id')->orderByDesc('uploaded_at');
    }
    
    //基本資料查看紀錄
    public function advInfo_check_log()
    {
        return $this->hasMany(AdminActionLog::class, 'target_id', 'id')->where('act', '查看會員基本資料')->orderByDesc('created_at');
    }
    
    public function suspicious_remove_log()
    {
        return $this->hasMany(AdminActionLog::class, 'target_id', 'id')->where('act', '刪除可疑名單')->orderByDesc('created_at');
    }    

    /*
    |--------------------------------------------------------------------------
    | Mutators and Accessors
    |--------------------------------------------------------------------------
    | Set virtual attributes
    |
    */

    public static function id_($uid)
    {
        return User::where('id', $uid)->first();
    }


    public function meta_($queries = null)
    {
        if(!isset($queries)){
            $queries = '*';
        }
        return UserMeta::select($queries)->where('user_id', $this->id)->first();
    }

    /**
     * User Roles
     *
     * @return Relationship
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function user_meta(){
        return $this->hasOne('App\Models\UserMeta');
    }

    public function user_options_xref(){
        return $this->hasMany(UserOptionsXref::class, 'user_id', 'id');
    }

    public function banned(){
        return $this->hasOne(banned_users::class, 'member_id', 'id');
    }

    public function implicitlyBanned(){
        return $this->hasOne(BannedUsersImplicitly::class, 'target', 'id');
    }
    
    public function blocked() {
        return $this->hasMany(Blocked::class, 'member_id', 'id');
    }
    
    public function blockedInBlocked() {
        return $this->hasMany(Blocked::class, 'blocked_id', 'id');
    } 

    public function tip() {
        return $this->hasMany(Tip::class, 'member_id', 'id');
    }
    
    //faq
    public function faq_user_group() 
    {
        return $this->hasMany(FaqUserGroup::class);
    }
    
    public function faq_user_reply() 
    {
        return $this->hasMany(FaqUserReply::class);
    } 

    //real auth
    public function real_auth_user_patch() 
    {
        return $this->hasMany(RealAuthUserPatch::class,'user_id','id');
    }     
    
    public function real_auth_user_apply() 
    {
        return $this->hasMany(RealAuthUserApply::class,'user_id','id');
    } 
    
    public function self_auth_apply() 
    {
        return $this->hasOne(RealAuthUserApply::class,'user_id','id')
                ->where('auth_type_id',1)
                ->latest();
    }
    
    public function self_auth_unchecked_apply() 
    {
        
        return $this->hasOne(RealAuthUserApply::class,'user_id','id')
                ->where('auth_type_id',1)
                ->where(function($q) {$q->whereNull('status')->orWhere('status','!=',1);})
                ->latest();
    }
    
    public function video_verify_record() 
    {
        return $this->hasMany(UserVideoVerifyRecord::class,'user_id','id');
    }     
    
    public function video_verify_memo() 
    {
        return $this->hasOne(UserVideoVerifyMemo::class,'user_id','id');
    }  
 
    public function beauty_auth_apply() 
    {
        return $this->hasOne(RealAuthUserApply::class,'user_id','id')
                ->where('auth_type_id',2)
                ->latest();
    }    
    
    public function beauty_auth_unchecked_apply() 
    {
        
        return $this->hasOne(RealAuthUserApply::class,'user_id','id')
                ->where('auth_type_id',2)
                ->where(function($q) {$q->whereNull('status')->orWhere('status','!=',1);})
                ->latest();
    }
    
    public function famous_auth_apply() 
    {
        return $this->hasOne(RealAuthUserApply::class,'user_id','id')
                ->where('auth_type_id',3)
                ->latest();
    }     

    public function famous_auth_unchecked_apply() 
    {
        return $this->hasOne(RealAuthUserApply::class,'user_id','id')
                ->where('auth_type_id',3)
                ->where(function($q) {$q->whereNull('status')->orWhere('status','!=',1);})
                ->latest();
    } 
    
    public function real_auth_user_modify() 
    {
        return $this->hasManyThrough(RealAuthUserModify::class,RealAuthUserApply::class,'user_id','apply_id');
    } 
    
    public function real_auth_user_modify_with_trashed() 
    {
        return $this->real_auth_user_modify()->withTrashed();
    } 

    public function real_auth_modify_item_group_for_admincheck_last_modify()
    {
        return $this->real_auth_user_modify()
                    ->groupBy('apply_id')
                    ->groupByRaw('item_id*!ifnull(patch_id_shot,0)')
                    ->groupBy('is_formal_first')
                    ->selectRaw('max(real_auth_user_modify.id) as id,min(real_auth_user_modify.id) as check_first')
                    ->where('item_id','!=',1)
                    
                    ->Where(function($q)
                    {
                        $q->where('real_auth_user_modify.apply_status_shot',1)
                          ->orWhere('is_formal_first',1)
                          ->orWhere(function($qq)
                          {
                            $qq->where('real_auth_user_modify.apply_status_shot','!=',1)
                               ->whereNotNull('patch_id_shot')
                              
                               ;                            
                        })
                        
                        ;

                    })
                                        
                    ;
    } 

    public function real_auth_modify_item_group_for_admincheck_last_modify_with_trashed()
    {
        return $this->real_auth_modify_item_group_for_admincheck_last_modify()->withTrashed();
    }     
   
    public function real_auth_modify_item_group_modify()
    {
        return $this->real_auth_modify_item_group_for_admincheck_last_modify();

    }  

    public function real_auth_modify_item_group_modify_with_trashed()
    {
        return $this->real_auth_modify_item_group_for_admincheck_last_modify_with_trashed()->addSelect('user_id')->orderByDesc('id');        
    }     

    public function latest_real_auth_user_modify() 
    {
        return $this->hasOneThrough(RealAuthUserModify::class,RealAuthUserApply::class,'user_id','apply_id')->orderByDesc('real_auth_user_modify.id')->latest();
    }     
    
    public function real_auth_user_modify_max_created_at() 
    {
        return $this->hasOneThrough(RealAuthUserModify::class,RealAuthUserApply::class,'user_id','apply_id')
                    ->select(DB::raw('max(real_auth_user_modify.created_at) as max_created_at'))
                    ->groupBy('real_auth_user_applies.user_id')
                    ->where('real_auth_user_modify.status',0)
                    ->where('real_auth_user_modify.item_id','!=',1)
                    ;
    }

    public function order()
    {
        return $this->hasMany(Order::class,'user_id','id');
    }
    
    public function forum_manage()
    {
        return $this->hasMany(ForumManage::class,'user_id','id');
    }

    /**
     * Check if user has role
     *
     * @param  string  $role
     * @return boolean
     */
    public function hasRole($role)
    {
        $roles = array_column($this->roles->toArray(), 'name');
        return array_search($role, $roles) > -1;
    }

    /**
     * Check if user has permission
     *
     * @param  string  $permission
     * @return boolean
     */
    public function hasPermission($permission)
    {
        return $this->roles->each(function ($role) use ($permission) {
            if (in_array($permission, explode(',', $role->permissions))) {
                return true;
            }
        });

        return false;
    }

    public function cfp(){
        return $this->hasMany(CFP_User::class, 'user_id', 'id');
    }

    public function isOnline() {
        return \Cache::has('user-is-online-' . $this->id);
    }

    public function check_point_user(){
        return $this->hasMany(CheckPointUser::class, 'user_id', 'id');
    }

    public function check_point_name(){
        return $this->hasManyThrough(CheckPoints::class, CheckPointUser::class, 'user_id', 'id','id','check_point_id');
    }

    public function backend_user_details(){
        return $this->hasMany(BackendUserDetails::class, 'user_id', 'id');
    }
    
    public function tiny_setting_to() {
        return $this->hasMany(UserTinySettingTo::class, 'user_id', 'id');
    }    
    
    public function tiny_setting_to_blurry() {
        return $this->tiny_setting_to()->where('cat','blurry_to_user');
    }

    public function operator_commit(){
        return $this->hasMany(UserRemarksLog::class, 'target_user_id', 'id')->orderByDesc('created_at');
    }

    /**
     * Find by Email
     *
     * @param  string $email
     * @return User
     */
    public static function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public static function findByEnGroup($engroup)
    {
        if ($engroup == 1) $engroup = 2;
        else if ($engroup == 2) $engroup = 1;
        $bannedUsers = banned_users::select('member_id')->get();
        $results = User::join('user_meta', 'users.id', '=', 'user_meta.user_id')->where('engroup', $engroup)->whereNotIn('id', $bannedUsers)->where('birthdate', '<', Carbon::now()->subYears(18))->orderBy('last_login', 'desc');

        return $results->paginate(12);
    }

    public static function findById($id)
    {
        return User::where('id', $id)->first();
    }

    public static function isCorrectAccount($email, $password) {
        $user = auth()->user();

        if($user->email == $email && Hash::check($password, $user->password)) {
            return true;
        }
        return false;
    }

    public function isAdmin() {
        $user = auth()->user();
        //dd(Config::get('social.admin.email'));
        //dd(Config::get('social.vip.free-days'));

        if($user->email == Config::get('social.admin.user-email')) {
            return true;
        }
        return false;
    }

    /**
     * 判定是否被封鎖
     *
     * 在 banned_users 跟 banned_users_implicitly 只要有任一被封鎖就回傳 true
     * banned_users_implicitly 是隱性封鎖，和一般的封鎖一樣，只差在隱性封鎖的會員不會有任何通知
     *
     * @param int|string $id 使用者編號
     *
     * @return boolean
     */
    public static function isBanned($id){
        if(banned_users::where('member_id', $id)->get()->count() > 0){
            return true;
        }
        else if(BannedUsersImplicitly::where('target', $id)->get()->count() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * 判定是否有在 封鎖名單裡面 第二版
     *
     *
     * @param string|int $id 對象id
     *
     * @return boolean
    */
    public static function isBanned_v2($id)
    {
        $c = banned_users::where('member_id', $id)
            ->where(function ($q) use ($id) {
                $today = Carbon::today();
                //就算有被封，只要 解封時間 不是null 以及大於今日就放過
                $q->where("expire_date", null)->orWhere("expire_date", ">", $today);
            })->get()->count();

        return $c > 0;
    }

    public function is_banned()
    {
        return banned_users::where('member_id', $this->id)
                            ->where(function ($query){
                                $today = Carbon::today();
                                $query->where("expire_date", null)->orWhere("expire_date", ">", $today);
                            })
                            ->orderByDesc('created_at')
                            ->first() ?? false;
            }

    /**
     * 判定是否有在 站方警示名單裡面
     *
     *
     * @param string|int $id 對象id
     *
     * @return boolean
     */
    public static function isWarned($id)
    {
        $c = warned_users::where('member_id', $id)
            ->where(function ($q) use ($id) {
                $today = Carbon::today();
                $q->where("expire_date", null)->orWhere("expire_date", ">", $today);
            })->get()->count();

        return $c > 0;
    }

    public function is_warned()
    {
        return warned_users::where('member_id', $this->id)
                    ->where(function ($query){
                        $today = Carbon::today();
                        $query->where("expire_date", null)->orWhere("expire_date", ">", $today);
                    })
                    ->orderByDesc('created_at')
                    ->first() ?? false;
    }

    /**
     * 判定是否有在 匿名聊天室禁止進入名單裡面
     *
     *
     * @param string|int $id 對象id
     *
     * @return boolean
     */
    public static function isAnonymousChatForbid($id)
    {
        $c = AnonymousChatForbid::where('user_id', $id)
            ->where(function ($q) use ($id) {
                $today = Carbon::today();
                $q->where("expire_date", null)->orWhere("expire_date", ">", $today);
            })->get()->count();

        return $c > 0;
    }

    /**
     * 判斷在匿名聊天室檢舉次數已達禁言
     *
     *
     * @param string|int $id 對象id
     *
     * @return boolean
     */
    public static function isAnonymousChatReportedSilence($id)
    {
        $user = User::findById($id);
        $times = 3;
        if($user->isVVIP()){
            $times = 5;
        }
        $this_week = Carbon::now()->startOfWeek()->toDateTimeString();

        //檢查上週是否還在禁言中
        $last_week = Carbon::now()->startOfWeek(Carbon::now()->dayOfWeek+1)->subWeek()->setTimeFromTimeString('23:59:59');
        $checkReport_last_week = AnonymousChatReport::select('user_id', 'created_at')
            ->where('reported_user_id', $id)
            ->where('created_at', '>', $last_week)
            ->where('created_at', '<', $this_week)
            ->groupBy('user_id')
            ->orderBy('created_at', 'desc')
            ->get();
        if(count($checkReport_last_week) >= $times && Carbon::parse($checkReport_last_week[0]->created_at)->diffInDays(Carbon::now())<3){
            return true;
        }
        //檢查上週是否還在禁言中_end

        $checkReport = AnonymousChatReport::select('user_id', 'created_at')
            ->where('reported_user_id', $id)
            ->where('created_at', '>=', $this_week)
            ->groupBy('user_id')
            ->orderBy('created_at', 'desc')
            ->get();

        if(count($checkReport) >= $times && Carbon::parse($checkReport[0]->created_at)->diffInDays(Carbon::now())<3){
            return true;
        }
        return false;
    }

    public function is_waiting_for_more_data()
    {
        return BackendUserDetails::where('user_id', $this->id)
                                ->where('is_waiting_for_more_data', 1)
                                ->first() ?? false;
    }

    public function is_waiting_for_more_data_with_login_time()
    {
        return BackendUserDetails::where('user_id', $this->id)
                                ->where('remain_login_times_of_wait_for_more_data', '>', 1)
                                ->first() ?? false;
    }

    /**
     * Find by Name
     *
     * @param  string $name
     * @return User
     */
     public function findByName($name)
     {
         return $this->where('name', $name)->first();
     }

    /**
     * Send the given notification.
     *
     * @param  mixed  $instance
     * @return void
     */

    public function notify($instance){
        $blocked = false;
        $bannedPatterns = config('banned.patterns');
        foreach ($bannedPatterns as $pattern){
            if(preg_match($pattern, $this->email)){
                $blocked = true;
            }
        }
        if(in_array($this->email, config('banned.emails'))){
            $blocked = true;
        }
        if($blocked){
            logger("Email blocked: " . $this->email);
            logger("IP: " . \Request::ip());
            return;
        }
        if(db_config('send-email')){
            app(\Illuminate\Contracts\Notifications\Dispatcher::class)->send($this, $instance);
        }
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function isVip()
    {
        // Middleware 下的 VipCheck 會將「是 VIP」但「過期」的會員取消權限，
        // 如果這邊就先針對到期日過濾掉的話，後續會導致問題，如下次重新付費升級
        // 會依舊顯示非 VIP
        return $this->vip->first() !== null;
        // return Vip::select('active')->where('member_id', $this->id)->where('active', 1)->where(function($query)
        //             {$query->where('expiry', '0000-00-00 00:00:00')->orwhere('expiry', '>=', Carbon::now());}
        //            )->orderBy('created_at', 'desc')->first() !== null;
    }

    // public function isAdvanceAuth(){
    //     $count =  $this->where('advance_auth_status', 1)->count();
    //     var_dumP($count);
    //     $res = $count >0 ? 1:0;
    //     return $res;
    // }
    /**
     * 取得 VIP 資料，預設回傳所有記錄，使用參數決定是否回傳單筆記錄
     *
     * @param  string $first
     * @return User
     */
    public function getVipData($first = false)
    {
        if($first){
            return Vip::where('member_id', $this->id)->where('active', 1)->orderBy('created_at', 'desc')->first();
        }
        else{
            return Vip::where('member_id', $this->id)->where('active', 1)->orderBy('created_at', 'desc')->get();
        }
    }

    public function isFreeVip()
    {
        return Vip::where('member_id', $this->id)->where('active', 1)->where('free', 1)->orderBy('created_at', 'desc')->first() !== null;
    }

    public function isVipNotCanceledNotOnePayment()
    {
        //return true: VIP未取消
        //return false: VIP已取消，但權限還沒過期
        $vip = Vip::where('member_id', $this->id)->where('active', 1)->orderBy('created_at', 'desc')->first();
        return isset($vip) && $vip->expiry=='0000-00-00 00:00:00';
    }

    public function isVipOnePaymentNotExpire()
    {
        //return true: VIP未取消
        //return false: VIP已取消，但權限還沒過期
        $vip = Vip::where('member_id', $this->id)->where('active', 1)->orderBy('created_at', 'desc')->first();
        return isset($vip) && $vip->expiry >= now() && substr($vip->payment,0,4)=='one_';
    }

    public function isVipNotOnePaymentNotExpiry()
    {
        //return true: VIP未取消
        //return false: VIP已取消，但權限還沒過期
        $vip = Vip::where('member_id', $this->id)->where('active', 1)->orderBy('created_at', 'desc')->first();
        return isset($vip) && $vip->expiry >= now() && ($vip->payment==null || substr($vip->payment,0,3)=='cc_');
    }

    public function isVipBoolean()
    {
        //return Vip::where('member_id', $this->id)->where('expiry', '>=',   Carbon::now())->orderBy('created_at', 'desc')->first() !== null;
        $vip = Vip::where('member_id', $this->id)->where('active', 1)->orderBy('created_at', 'desc')->count();
        return  $vip > 0 ? true : false ;
    }
    
    public function getVipDiamond() {
        return Vip::vip_diamond($this->id);
    }  

    public function getVipMonths() {
        return Vip::vipMonths($this->id);
    }     

    public function existHeaderImage() {
        $pics = MemberPic::where('member_id', $this->id)->count();
        //echo $pics;
        //$user_meta = view()->shared('user_meta');
        $user_meta = $this->meta;
        return isset($user_meta->pic) && ($pics >= 3);
    }

    public function isActive() {

        if($this->engroup == 1) return;

        $now = Carbon::now();
        $user = Vip::where('member_id', $this->id)->firstOrFail();

        //if($user == null) return false;

        $activateDays = $now->diffInDays($user->created_at);


        if($activateDays >= Config::get('social.vip.free-days')) {
            Vip::cancel($this->id);
        }
        else {
            echo '您已升級為VIP會員';
        }
    }

    public static function isLoginSuccess($email, $password) {
        $response = Curl::to('http://fix-house.com/logincheck.aspx')
                    ->withData( array( 'id' => $email , 'pw' => $password) )
                    ->asJson()
                    ->get();
        //dd($response);
        return $response;
    }

    public function isBlocked($blocker)
    {
        $result = Blocked::where('member_id', $this->id)->where('blocked_id', $blocker)->count() > 0;
        if (isset($result) && strlen($result) !== 0) $result = '是';
        else $result = '否';
        return $result;
    }

    public function isSeen($visitor)
    {
        $result = Visited::where('member_id', $this->id)->where('visited_id', $visitor)->count() > 0;
        if (isset($result) && strlen($result) !== 0) $result = '是';
        else $result = '否';
        return $result;
    }

    public function isSent3Msg($tid)
    {
        $msg_count = Message::withTrashed()->where('from_id', $tid)->where('to_id', $this->id)
//            ->where('is_row_delete_1','<>',$this->id)
//            ->where('is_row_delete_2','<>',$this->id)
//            ->where('is_single_delete_1','<>',$this->id)
//            ->where('is_single_delete_2','<>',$this->id)
            ->where(function ($q) {
                $q->where(function ($q1) {
                    $q1->where('unsend', 0)->whereNull('deleted_at');
                })->orWhere(function ($q2) {
                    $q2->where('unsend', 1);
                });
            })  
            ->count();
        return $msg_count>=3;
    }

    public function WarnedScore()
    {
        $score=0;
        //照片檢舉
        $pic_report1 = ReportedAvatar::select('reporter_id as uid')->where('reported_user_id',$this->id)->where('cancel','0')->where('reporter_id','!=',$this->id)->distinct('reporter_id')->get();
        // Log::info('ReportedAvatar'.$pic_report1);
        $pic_report2 = ReportedPic::select('reported_pic.reporter_id as uid')->join('member_pic','reported_pic.reported_pic_id','=','member_pic.id')->where('member_pic.member_id',$this->id)->where('reported_pic.reporter_id','!=',$this->id)->where('reported_pic.cancel','0')->distinct('reported_pic.reporter_id')->get();
        // Log::info('ReportedPic'.$pic_report2);

        //大頭照與照片合併計算
        //$collection = collect([$pic_report1, $pic_report2]);
        //$pic_all_report = $collection->collapse()->unique('uid');
        // $pic_all_report->unique()->all();

        //訊息檢舉
        $msg_report = Message::select('to_id as uid')->where('from_id',$this->id)->where('isReported',1)->where('cancel','0')->where('to_id','!=',$this->id)->distinct('to_id')->get();
        //會員檢舉
        $report = Reported::select('member_id as uid')->where('reported_id',$this->id)->where('cancel','0')->where('member_id','!=',$this->id)->distinct('member_id')->get();

        //所有檢舉合併計算
        $collection = collect([$pic_report1, $pic_report2,$msg_report,$report]);
        $pic_all_report = $collection->collapse()->unique('uid');

        if(isset($pic_all_report) && count($pic_all_report)>0){
            foreach($pic_all_report as $row){
                $user = User::findById($row->uid);
                if(!isset($user)){
                    continue;
                }
                if($user->engroup==2){
                    if($user->isPhoneAuth()==1){
                        $score = $score + 5;
                    }else{
                        $score = $score + 3.5;
                    }
                }else if($user->engroup==1){
                    if($user->isVipOrIsVvip()){
                        $score = $score + 5;
                    }else{
                        $score = $score + 3.5;
                    }
                }
            }
        }
//        //訊息檢舉
//        $msg_report = Message::select('to_id')->where('from_id',$this->id)->where('isReported',1)->where('cancel','0')->where('to_id','!=',$this->id)->distinct('to_id')->get();
//        if(isset($msg_report) && count($msg_report)>0){
//            foreach($msg_report as $row){
//                $user = User::findById($row->to_id);
//                if($user->engroup==2){
//                    if($user->isPhoneAuth()==1){
//                        $score = $score + 5;
//                    }else{
//                        $score = $score + 3.5;
//                    }
//                }else if($user->engroup==1){
//                    if($user->isVipOrIsVvip()){
//                        $score = $score + 5;
//                    }else{
//                        $score = $score + 3.5;
//                    }
//                }
//            }
//        }
//        //會員檢舉
//        $report = Reported::select('member_id')->where('reported_id',$this->id)->where('cancel','0')->where('member_id','!=',$this->id)->distinct('member_id')->get();
//        if(isset($report) && count($report)>0){
//            foreach($report as $row){
//                $user = User::findById($row->member_id);
//                if(isset($user->engroup) && $user->engroup==2){
//                    if($user->isPhoneAuth()==1){
//                        $score = $score + 5;
//                    }else{
//                        $score = $score + 3.5;
//                    }
//                }else if(isset($user->engroup) && $user->engroup==1){
//                    if($user->isVipOrIsVvip()){
//                        $score = $score + 5;
//                    }else{
//                        $score = $score + 3.5;
//                    }
//                }
//            }
//        }

        return $score;
    }

    public function isPhoneAuth()
    {
        $auth_phone = DB::table('short_message')->where('member_id',$this->id)->where('active',1)->count(); //->where('mobile','!=','')
        return isset($auth_phone) && $auth_phone>0;
    }

    public function isAdvanceAuth()
    {
        return $this->advance_auth_status ;
    }
    
    public function isImgAuth()
    {
        $auth_img = DB::table('auth_img')->where('user_id',$this->id)->where('status',1)->count();
        return isset($auth_img) && $auth_img>0;
    }

    public function isAdminWarned(){
        $data = warned_users::where('member_id', $this->id)->first();
        if(isset($data) && ($data->expire_date==null || $data->expire_date >=  Carbon::now() )){
            return true;
        }else{
            return false;
        }
    }

    public static function isWarnedRead($uid)
    {
        DB::table('user_meta')->where('user_id',$uid)->update(['isWarnedRead'=>1]);
    }

    public static function isAdminWarnedRead($uid)
    {
        DB::table('warned_users')->where('member_id',$uid)->update(['isAdminWarnedRead'=>1]);
    }

//    public function isReportedByUser($uid)
//    {
//        $AvatarCount = ReportedAvatar::select('reported_user_id')->where('reported_user_id',$this->id)->where('cancel','0')->where('reporter_id',$uid)->count();
//        $PicCount = ReportedPic::select('reported_pic.reporter_id')
//            ->join('member_pic','reported_pic.reported_pic_id','=','member_pic.id')
//            ->where('member_pic.member_id',$this->id)
//            ->where('reported_pic.reporter_id',$uid)
//            ->where('reported_pic.cancel','0')->count();
//        $msgCount = Message::select('from_id')->where('from_id',$this->id)->where('isReported',1)->where('cancel','0')->where('to_id',$uid)->count();
//        $memberCount = Reported::select('reported_id')->where('reported_id',$this->id)->where('cancel','0')->where('member_id',$uid)->count();
//
//        return $AvatarCount>0 || $PicCount>0 || $msgCount>0 || $memberCount>0;
//    }

    public static function PR($uid)
    {
        $user = User::findById($uid);

        //車馬費次數
        $tip_count = Tip::where('member_id',$uid)->count();

        //訂單紀錄
        $order = Order::where('user_id', $uid)->where('service_name','VIP')->get();

        //註冊後如無任何傳訊紀錄 + 不是 vip 則顯示 無
        $checkMessages = Message::where('from_id', $uid)->get()->count();
        if($checkMessages==0 && !$user->isVipOrIsVvip() && count($order)==0){
            $pr = '無';
            $pr_log = '註冊後如無任何傳訊紀錄+不是vip';
            //舊紀錄刪除
            Pr_log::where('user_id',$uid)->delete();
            return Pr_log::insert([ 'user_id' => $uid, 'pr' => $pr, 'pr_log' => $pr_log, 'active' => 1 ]);
        }

        //default
        $pr = 50;
        $pr_log = '';

        //vip計分
        if(count($order)>0){

            foreach ( $order as $row){

                if($row->order_expire_date == '' || $row->order_expire_date > Carbon::now()){
                    //當前有VIP 或 VIP尚未到期
                    if(substr( $row->payment ,0,3) =='cc_' || $row->payment == ''){
                        //定期定額
                        $months = Carbon::parse($row->order_date)->diffInMonths(Carbon::now());
                        $pr = $pr + ($months * 5);
                        //+ (($months-1)*2.5);
                        $otherMonths = $months - 1;

                        if($row->payment == 'cc_quarterly_payment'){
                            $pr_log = $pr_log . '當前定期定額季付VIP累計 ' . $months . ' 個月';
                            $pr = $pr + ceil($months/3) * 5 + (ceil($months/3)-1)*2.5;
                            if(ceil($months/3)==1){
                                $pr_log = $pr_log . ', 額外連續VIP 2 個月';
                            }elseif(ceil($months/3)>1){
                                $otherMonths = 2 + (ceil($months/3 )-1)*3;
                                $pr_log = $pr_log . ', 額外連續VIP '. $otherMonths .' 個月';
                            }

                        }else {
                            $pr_log = $pr_log . '當前定期定額月付VIP累計 ' . $months . ' 個月';
                            if ($otherMonths > 0) {
                                $pr = $pr + ($months-1)*2.5;
                                $pr_log = $pr_log . ', 額外連續VIP ' . $otherMonths . ' 個月';
                            }
                        }

                        $pr_log = $pr_log . '=>'. $pr .'; ';
                    }else{
                        //單次付費加分
                        if ($row->payment == 'one_quarter_payment') {
                            $pr = $pr + (3 * 5) + 2.5 + 2.5;
                            $pr_log = $pr_log . '當前單次季付有VIP 3 個月+額外連續VIP 2 個月 =>' . $pr . '; ';
                        }
                    }

                }else{

                    if (strpos($row->payment, 'one_quarter_payment') !== false) {
                        $pr = $pr + 15;
                        $pr_log = $pr_log . '曾經單次付費季付VIP =>' . $pr . '; ';
                    }
                    else if (strpos($row->payment, 'one_month_payment') !== false) {
                        $pr = $pr + 5;
                        $pr_log = $pr_log . '曾經單次付費月付VIP =>' . $pr . '; ';
                    }
                    else if (strpos($row->payment, 'cc_quarterly_payment') !== false) {
                        $pr = $pr + 15;
                        $pr_log = $pr_log . '曾經定期定額季付VIP =>' . $pr . '; ';
                    }
                    else if (strpos($row->payment, 'cc_monthly_payment') !== false) {
                        $pr = $pr + 5;
                        $pr_log = $pr_log . '曾經定期定額月付VIP =>' . $pr . '; ';
                    }else{
                        $pr = $pr + 5;
                        $pr_log = $pr_log . '曾經定期定額(舊)月付VIP =>' . $pr . '; ';
                    }

                }
            }

        }

        //車馬費計分
        if($tip_count>0) {
            $pr = ($pr + $tip_count) * 1.04;
            $pr_log = $pr_log.'車馬費 '.$tip_count.' 次計分 => '.$pr.'; ';
        }

        //精華文章通過站長審核計分
        //Ex.他2/2通過一篇，那就2/2~3/2 +10，如果他在2/5通過一篇，那就2/5~3/5再+10。上限就是加到100為止
        $essence_posts_reward_log=EssencePostsRewardLog::where('user_id', $uid)->groupByRaw('LEFT(verify_time, 10)')->orderBy('verify_time')->get();
        foreach ($essence_posts_reward_log as $key => $reward_log){
            $period_start=$reward_log->verify_time;
            $period_end=date("Y-m-d H:i:s",strtotime("+1 month", strtotime($reward_log->verify_time)));
            $today=date('Y-m-d H:i:s');
            if(($today>=$period_start) &&($today<=$period_end)){
                $pr = $pr +10;
                $pr_log = $pr_log.date("Y/m/d",strtotime($reward_log->verify_time)).'精華文章通過審核 =>'.$pr.'; ';
            }
        }


        //曾被付費警示/封鎖扣分
        //付費警示紀錄
        $isEverBannedByVipPass = IsBannedLog::where('user_id', $uid)->where('vip_pass', 1)->get()->count();
        //付費封鎖紀錄
        $isEverWarnedByVipPass = IsWarnedLog::where('user_id', $uid)->where('vip_pass', 1)->get()->count();
        if(($isEverBannedByVipPass+$isEverWarnedByVipPass) >= 1){
            $pr = $pr - 30;
            $pr_log = $pr_log.'曾經警示/封鎖付費首次扣 30 分 => '.$pr.'; ';
        }
        //付費警示紀錄(未續費)
        $isEverBannedByVipPass2 = IsBannedLog::where('user_id', $uid)->where('vip_pass', 1)->where('reason','like','%未續費%')->get()->count();
        //付費封鎖紀錄(未續費)
        $isEverWarnedByVipPass2 = IsWarnedLog::where('user_id', $uid)->where('vip_pass', 1)->where('reason','like','%未續費%')->get()->count();
        if(($isEverBannedByVipPass2+$isEverWarnedByVipPass2) > 0){
            $temp_count = $isEverBannedByVipPass2+$isEverWarnedByVipPass2;
            $pr = $pr - $temp_count*5;
            $pr_log = $pr_log.'曾經警示/封鎖付費未續費 '. $temp_count .' 次 => '.$pr.'; ';
        }

        //非VIP 扣分 每位通訊人數扣0.2
        if(!$user->isVipOrIsVvip()) {
            $checkMessageUsers = Message::select('to_id')->where('from_id', $uid)->distinct()->get()->count();
            if($checkMessageUsers>0){
                $pr = $pr - ($checkMessageUsers * 0.2);
                $pr_log = $pr_log.'當前非VIP通訊人數 '.$checkMessageUsers.' 人扣分 =>'.$pr.'; ';
            }
        }

        $pr = round($pr,0);
        //分數上限
        if($pr>100){
            $pr=100;
            $pr_log = $pr_log.'PR超過100以100計算=>'.$pr.'; ';
        }

        //分數下限
        if($pr<1){
            $pr=0;
            $pr_log = $pr_log.'PR低於或等於0以0計算=>'.$pr.'; ';
        }

        //舊紀錄刪除
        Pr_log::where('user_id',$uid)->delete();
        //存LOG
        return Pr_log::insert([ 'user_id' => $uid, 'pr' => $pr, 'pr_log' => $pr_log, 'active' => 1]);
    }

    public function age(){
        if (isset($this->user_meta->birthdate) && $this->user_meta->birthdate !== null && $this->user_meta->birthdate != 'NULL')
        {
            $userDob = $this->user_meta->birthdate;
            $dob = new \DateTime($userDob);

            $now = new \DateTime();

            $difference = $now->diff($dob);

            $age = $difference->y;
            return $age;
        }
    }

    public static function rating($uid)
    {
        $userBlockList = Blocked::select('blocked_id')->where('member_id', $uid)->get();
        $isBlockList = Blocked::select('member_id')->where('blocked_id', $uid)->get();
        $bannedUsers = UserService::getBannedId();
        $isAdminWarnedList = warned_users::select('member_id')->where('expire_date','>=',Carbon::now())->orWhere('expire_date',null)->get();
        $isWarnedList = UserMeta::select('user_id')->where('isWarned',1)->get();

        $rating_avg = DB::table('evaluation')->where('to_id',$uid)
            ->whereNotIn('from_id',$userBlockList)
            ->whereNotIn('from_id',$isBlockList)
            ->whereNotIn('from_id',$bannedUsers)
            ->whereNotIn('from_id',$isAdminWarnedList)
            ->whereNotIn('from_id',$isWarnedList)
            ->avg('rating');

        $rating_avg = floatval($rating_avg);
        return $rating_avg;
    }

    public function msgCount()
    {
        return Message::where('from_id', $this->id)->count();
    }

        public function msgsevenCount()
    {
        return Message::where('from_id', $this->id)->whereBetween('created_at',  [Carbon::now()->subDays(7), Carbon::now()])->count();
    }

    public function favCount()
    {
        return MemberFav::where('member_id', $this->id)->count();
    }

    public function favedCount()
    {
        return MemberFav::where('member_fav_id', $this->id)->count();
    }

    public function tipCount()
    {
        return Tip::where('member_id', $this->id)->count();
    }
    
    public function getTipCountChangeGood() {
        Tip::TipCount_ChangeGood($this->id);
    }

    public function everBeenVIP()
    {
        return $this->hasMany(Vip::class, 'member_id', 'id');
    }

    public function vipTotalLength()
    {
        if ($this->everBeenVIP()) {
            $logs = $this->hasMany(VipLog::class, 'member_id', 'id')->orderBy('id')->get();
            $total = 0;
            $start = null;
            $end = null;
            foreach ($logs as $log) {
                if ($start && $end) {
                    $total += $end->diffInDays($start);
                    $start = null;
                    $end = null;
                }
                if (str_contains($log->member_name, 'upgrade')) {
                    $start = $log->created_at;
                }
                if (str_contains($log->member_name, 'cancel')) {
                    $end = $log->created_at;
                }
            }
            return $total;
        }
        return 0;
    }

    public function visitCount()
    {
        return Visited::where('member_id', $this->id)->count();
    }

        public function visitedCount()
    {
        return Visited::where('visited_id', $this->id)->count();
    }

        public function visitedsevenCount()
    {
        return Visited::where('visited_id', $this->id)->whereBetween('created_at',  [Carbon::now()->subSeconds(Config::get('social.user.viewed-seconds')), Carbon::now()])->count();
    }

    public function checkTourRead($page,$step)
    {
        $checkData = DB::table('tour_read')->where('user_id',$this->id)->where('page',$page)->where('step',$step)->where('isRead',1)->first();
        $login_times = User::select('login_times')->withOut(['user_meta', 'vip'])->where('id',$this->id)->first();
        if(isset($checkData) && $login_times->login_times >= 2){
            $isRead =1;
        }else{
            $isRead =0;
        }
        return $isRead;
    }

    public function valueAddedServiceStatus($service_name = null)
    {
        return ValueAddedService::status($this->id,$service_name);
    }

    public static function sendLineNotify($access_token, $message) {

        if (is_array($message)) {
            $message = chr(13).chr(10) . implode(chr(13).chr(10), $message);
        }

        $apiUrl = config('line.line_notify.notify_url');

        $params = [
            'message' => $message/*,
            'stickerPackageId' => $stickerPackageId,
            'stickerId' => $stickerId*/
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $access_token
        ]);
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        $output = curl_exec($ch);
        curl_close($ch);
    }

    public static function warned_icondata($id)
    {
        $userMeta = UserMeta::where('user_id', $id)->get()->first();
        $warned_users = warned_users::where('member_id', $id)->first();
        $f_user = User::findById($id);
        if (isset($warned_users) && ($warned_users->expire_date == null || $warned_users->expire_date >= Carbon::now())) {
            $data['isAdminWarned'] = 1;
        } else {
            $data['isAdminWarned'] = 0;
        }
        $data['auth_status'] = 0;
        if (isset($userMeta)) {
            $data['isWarned'] = $userMeta->isWarned();
        } else {
            $data['isWarned'] = null;
        }
        if (isset($f_user)) {
            $data['WarnedScore'] = $f_user->WarnedScore();
            $data['auth_status'] = $f_user->isPhoneAuth();
        } else {
            $data['WarnedScore'] = null;
            $data['auth_status'] = null;
        }
        return $data;
    }
    
    public function getWarnedIconData() {
        return User::warned_icondata($this->id);
    }

    public static function userAdvInfo($user_id,$wantIndexArr=[]){
        $user=User::findById($user_id);
        $date = date('Y-m-d H:m:s', strtotime('-7 days'));
        $seven_days_ago = Carbon::now()->subDays(7);

        /*使用者所有訊息*/
        $messages_all = Message::withTrashed()->select('id','room_id','to_id','from_id','read','created_at')
                                ->where(function($query) use($user) {                            
                                    $query->where('to_id', $user->id)->orwhere('from_id', $user->id);
                                })
                                ->where('from_id','!=',1049)
                                ->where('to_id','!=',1049)
                                ->orderBy('id')
                                ->get();
        /*總房間*/
        $first_messages_all = $messages_all->unique('room_id');

        /*第一則訊息為發訊的房間*/
        $first_send_room = $first_messages_all->where('from_id',$user_id)->pluck('room_id');
        /*第一則訊息為收訊的房間*/
        $first_reply_room = $first_messages_all->where('to_id',$user_id)->pluck('room_id');

        $send_message_all = Message::withTrashed()->select('id','room_id','to_id','from_id','read','created_at')
                                    ->whereIn('room_id', $first_send_room)
                                    ->where('from_id',$user_id)
                                    ->orderByDesc('id')
                                    ->get();

        $reply_message_all = Message::withTrashed()->select('id','room_id','to_id','from_id','read','created_at')
                                    ->whereIn('room_id', $first_reply_room)
                                    ->where('from_id',$user_id)
                                    ->orderByDesc('id')
                                    ->get();
        /*由對方發起的訊息*/
        $first_receive_message_all = Message::withTrashed()->select('id','room_id','to_id','from_id','read','created_at')
                                    ->whereIn('room_id', $first_reply_room)
                                    ->orderByDesc('id')
                                    ->get();

        /*發信人數*/
        $advInfo['message_people_count'] = count($send_message_all->unique('room_id'));
        /*過去7天發信人數*/
        $advInfo['message_people_count_7'] = count($send_message_all->where('created_at','>', $seven_days_ago)->unique('room_id'));

        /*發信次數*/
        $advInfo['message_count'] = count($send_message_all);
        /*過去7天發信次數*/
        $advInfo['message_count_7'] = count($send_message_all->where('created_at','>', $seven_days_ago));

        /*回信人數*/
        $advInfo['message_reply_people_count'] = count($reply_message_all->unique('room_id'));
        /*過去7天回信人數*/
        $advInfo['message_reply_people_count_7'] = count($reply_message_all->where('created_at','>', $seven_days_ago)->unique('room_id'));

        /*回信次數*/
        $advInfo['message_reply_count'] = count($reply_message_all);
        /*過去7天回信次數*/
        $advInfo['message_reply_count_7'] = count($reply_message_all->where('created_at','>', $seven_days_ago));

        /*未回人數*/
        $advInfo['message_no_reply_count'] = count($messages_all->sortByDesc('id')->unique('room_id')->where('from_id',$user_id)->where('read','Y'));
        /*過去七天未回人數*/
        $advInfo['message_no_reply_count_7'] = count($messages_all->sortByDesc('id')->where('created_at','>', $seven_days_ago)->unique('room_id')->where('from_id',$user_id)->where('read','Y'));

        /*第一則訊息為收訊的未回人數*/
        $advInfo['reply_message_no_reply_count'] = count($first_receive_message_all->sortByDesc('id')->unique('room_id')->where('to_id', $user_id)->where('read','Y'));

        /*總通訊人數*/
        $advInfo['message_people_total'] = $advInfo['message_people_count'] + $advInfo['message_reply_people_count'];
        /*過去7天總通訊人數*/
        $advInfo['message_people_total_7'] = $advInfo['message_people_count_7'] + $advInfo['message_reply_people_count_7'];

        /*過去7天罐頭訊息比例*/
        $date_start = date("Y-m-d",strtotime("-6 days", strtotime(date('Y-m-d'))));
        $date_end = date('Y-m-d');
		
		if(!$wantIndexArr || in_array('message_percent_7',$wantIndexArr)) {
			/**
			 * 效能調整：使用左結合以大幅降低處理時間
			 *
			 * @author LZong <lzong.tw@gmail.com>
			 */
			$query = Message::select('users.email','users.name','users.title','users.engroup','users.created_at','users.last_login','message.id','message.from_id','message.content','user_meta.about')
				->join('users', 'message.from_id', '=', 'users.id')
				->join('user_meta', 'message.from_id', '=', 'user_meta.user_id')
				->leftJoin('banned_users as b1', 'b1.member_id', '=', 'message.from_id')
				->leftJoin('banned_users_implicitly as b3', 'b3.target', '=', 'message.from_id')
				->leftJoin('warned_users as wu', function($join) {
                    $join->on('wu.member_id', '=', 'message.from_id')
                         ->where(function($join) {                            
                            $join->where('wu.expire_date', '>=', Carbon::now())
                            ->orWhere('wu.expire_date', null);
                         }); })
				->whereNull('b1.member_id')
				->whereNull('b3.target')
				->whereNull('wu.member_id')
				->where(function($query)use($date_start,$date_end) {
					$query->where('message.from_id','<>',1049)
						->where('message.sys_notice', 0)
                        ->orWhereNull('message.sys_notice')
						->whereBetween('message.created_at', array($date_start . ' 00:00', $date_end . ' 23:59'));
				});
			$query->where('users.email',$user->email);
			$results_a = $query->distinct('message.from_id')->get();

			if ($results_a != null) {
				$msg = array();
				$from_content = array();
				$user_similar_msg = array();

				$messages = Message::select('id','content','created_at')
					->where('from_id', $user->id)
					->where(function ($query) {
                        $query->where('sys_notice', 0)
                        ->orWhereNull('sys_notice');
                    })
					->whereBetween('created_at', array($date_start . ' 00:00', $date_end . ' 23:59'))
					->orderBy('created_at','desc')
					->take(100)
					->get();

				foreach($messages as $row){
					array_push($msg,array('id'=>$row->id,'content'=>$row->content,'created_at'=>$row->created_at));
				}

				array_push($from_content,  array('msg'=>$msg));

				$unique_id = array(); //過濾重複ID用
				//比對訊息
				foreach($from_content as $data) {
					foreach ($data['msg'] as $word1) {
						foreach ($data['msg'] as $word2) {
							if ($word1['created_at'] != $word2['created_at']) {
								similar_text($word1['content'], $word2['content'], $percent);
								if ($percent >= 70) {
									if(!in_array($word1['id'],$unique_id)) {
										array_push($unique_id,$word1['id']);
										array_push($user_similar_msg, array($word1['id'], $word1['content'], $word1['created_at'], $percent));
									}
								}
							}
						}
					}
				}
			}
			$advInfo['message_percent_7'] = count($user_similar_msg) > 0 ? round( (count($user_similar_msg) / count($messages))*100 ).'%'  : '0%';
		}

        /*每周平均上線次數*/
		if(!$wantIndexArr || in_array('login_times_per_week',$wantIndexArr)) {
			$datetime1 = new \DateTime(now());
			$datetime2 = new \DateTime($user->created_at);
			$diffDays = $datetime1->diff($datetime2)->days;
			$week = ceil($diffDays / 7);
			if($week == 0){
				$advInfo['login_times_per_week'] = 0;
			}
			else{
				$advInfo['login_times_per_week'] = round(($user->login_times / $week), 0);
			}
		}
        /*收藏會員次數*/
		if(!$wantIndexArr || in_array('fav_count',$wantIndexArr)) 
			$advInfo['fav_count'] = MemberFav::where('member_id', $user->id)->get()->count();

        /*瀏覽其他會員次數*/
		if(!$wantIndexArr || in_array('visit_other_count',$wantIndexArr)) 
			$advInfo['visit_other_count']  = Visited::where('member_id', $user->id)->distinct('visited_id')->count();

        /*過去7天瀏覽其他會員次數*/
		if(!$wantIndexArr || in_array('visit_other_count_7',$wantIndexArr)) 
			$advInfo['visit_other_count_7'] = Visited::where('member_id', $user->id)->where('created_at', '>=', $date)->distinct('visited_id')->count();

        /*此會員封鎖多少其他會員*/
		if(!$wantIndexArr || in_array('blocked_other_count',$wantIndexArr)) {
			$bannedUsers = \App\Services\UserService::getBannedId();
			$advInfo['blocked_other_count']= \App\Models\Blocked::join('users', 'users.id', '=', 'blocked.blocked_id')
                ->join('message', function($join){
                    $join->on('blocked.member_id', '=', 'message.from_id');
                    $join->on('blocked.blocked_id','=', 'message.to_id');
                })
				->where('blocked.member_id', $user->id)
				->whereNotIn('blocked.blocked_id',$bannedUsers)
				->whereNotNull('users.id')
                ->whereNotNull('message.id')
                ->distinct()
				->count('blocked.blocked_id');
		}
        /*此會員被多少會員封鎖*/
		if(!$wantIndexArr || in_array('be_blocked_other_count',$wantIndexArr)) {
			$advInfo['be_blocked_other_count'] = \App\Models\Blocked::join('users', 'users.id', '=', 'blocked.member_id')
                ->join('message', function($join){
                    $join->on('blocked.member_id', '=', 'message.from_id');
                    $join->on('blocked.blocked_id','=', 'message.to_id');
                })
				->where('blocked.blocked_id', $user->id)
				->whereNotIn('blocked.member_id',$bannedUsers)
				->whereNotNull('users.id')
                ->whereNotNull('message.id')
				->distinct()
				->count('blocked.blocked_id');
		}
        return $advInfo;
    }

    public function getAdvInfo($wantIndexArr=[]) : array{
        $user = $this;
        $date = date('Y-m-d H:m:s', strtotime('-7 days'));

        /*發信＆回信次數統計*/
        $countInfo['message_count'] = 0;
        $countInfo['message_reply_count'] = 0;
        $countInfo['message_reply_count_7'] = 0;
		$countInfo['message_count_7'] = 0;
        $send = [];
        $receive = [];

        if(!$wantIndexArr
            || in_array('message_count',$wantIndexArr)
            || in_array('message_reply_count',$wantIndexArr)
            || in_array('message_reply_count_7',$wantIndexArr)
        ) {
            $messages_all = Message::select('id','to_id','from_id','created_at')->where('to_id', $user->id)->orwhere('from_id', $user->id)->orderBy('id')->get();
            foreach ($messages_all as $message) {
                //uid主動第一次發信
                if($message->from_id == $user->id && array_get($send, $message->to_id) < $message->id){
                    $send[$message->to_id][]= $message->id;
                }
                //紀錄每個帳號第一次發信給uid
                if ($message->to_id == $user->id && array_get($receive, $message->from_id) < $message->id) {
                    $receive[$message->from_id][] = $message->id;
                }
                if(!is_null(array_get($receive, $message->to_id))){
                    $countInfo['message_reply_count'] += 1;
                    if($message->created_at >= $date){
                        //計算七天內回信次數
                        $countInfo['message_reply_count_7'] += 1;
                    }
                }
            }
            $countInfo['message_count'] = count($send);
        }

        if(!$wantIndexArr || in_array('message_count_7',$wantIndexArr)) {
            $messages_7days = Message::select('id','to_id','from_id','created_at')->whereRaw('(to_id ='. $user->id. ' OR from_id='.$user->id .')')->where('created_at','>=', $date)->orderBy('id')->get();
            $countInfo['message_count_7'] = 0;
            $send = [];
            foreach ($messages_7days as $message) {
                //七天內uid主動第一次發信
                if($message->from_id == $user->id && array_get($send, $message->to_id) < $message->id){
                    $send[$message->to_id][]= $message->id;
                }
            }
            $countInfo['message_count_7'] = count($send);
        }
        /*發信次數*/
        $advInfo['message_count'] = $countInfo['message_count'];
        /*過去7天發信次數*/
        $advInfo['message_count_7'] = $countInfo['message_count_7'];
        /*回信次數*/
        $advInfo['message_reply_count'] = $countInfo['message_reply_count'];
        /*過去7天回信次數*/
        $advInfo['message_reply_count_7'] = $countInfo['message_reply_count_7'];
        /*過去7天罐頭訊息比例*/
        $date_start = date("Y-m-d",strtotime("-6 days", strtotime(date('Y-m-d'))));
        $date_end = date('Y-m-d');

        if(!$wantIndexArr || in_array('message_percent_7',$wantIndexArr)) {
            /**
             * 效能調整：使用左結合以大幅降低處理時間
             *
             * @author LZong <lzong.tw@gmail.com>
             */
            $query = Message::select('users.email','users.name','users.title','users.engroup','users.created_at','users.last_login','message.id','message.from_id','message.content','user_meta.about')
                ->join('users', 'message.from_id', '=', 'users.id')
                ->join('user_meta', 'message.from_id', '=', 'user_meta.user_id')
                ->leftJoin('banned_users as b1', 'b1.member_id', '=', 'message.from_id')
                ->leftJoin('banned_users_implicitly as b3', 'b3.target', '=', 'message.from_id')
                ->leftJoin('warned_users as wu', function($join) {
                    $join->on('wu.member_id', '=', 'message.from_id')
                         ->where(function($join) {                            
                            $join->where('wu.expire_date', '>=', Carbon::now())
                            ->orWhere('wu.expire_date', null);
                         }); })
                ->whereNull('b1.member_id')
                ->whereNull('b3.target')
                ->whereNull('wu.member_id')
                ->where(function($query)use($date_start,$date_end) {
                    $query->where('message.from_id','<>',1049)
                        ->where('message.sys_notice', 0)
                        ->orWhereNull('message.sys_notice')
                        ->whereBetween('message.created_at', array($date_start . ' 00:00', $date_end . ' 23:59'));
                });
            $query->where('users.email',$user->email);
            $results_a = $query->distinct('message.from_id')->get();

            if ($results_a != null) {
                $msg = array();
                $from_content = array();
                $user_similar_msg = array();

                $messages = Message::select('id','content','created_at')
                    ->where('from_id', $user->id)
                    ->where(function ($query) {
                        $query->where('sys_notice', 0)
                        ->orWhereNull('sys_notice');
                    })
                    ->whereBetween('created_at', array($date_start . ' 00:00', $date_end . ' 23:59'))
                    ->orderBy('created_at','desc')
                    ->take(100)
                    ->get();

                foreach($messages as $row){
                    array_push($msg,array('id'=>$row->id,'content'=>$row->content,'created_at'=>$row->created_at));
                }

                array_push($from_content,  array('msg'=>$msg));

                $unique_id = array(); //過濾重複ID用
                //比對訊息
                foreach($from_content as $data) {
                    foreach ($data['msg'] as $word1) {
                        foreach ($data['msg'] as $word2) {
                            if ($word1['created_at'] != $word2['created_at']) {
                                similar_text($word1['content'], $word2['content'], $percent);
                                if ($percent >= 70) {
                                    if(!in_array($word1['id'],$unique_id)) {
                                        array_push($unique_id,$word1['id']);
                                        array_push($user_similar_msg, array($word1['id'], $word1['content'], $word1['created_at'], $percent));
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $advInfo['message_percent_7'] = count($user_similar_msg) > 0 ? round( (count($user_similar_msg) / count($messages))*100 ).'%'  : '0%';
        }

        /*每周平均上線次數*/
        if(!$wantIndexArr || in_array('login_times_per_week',$wantIndexArr)) {
            $datetime1 = new \DateTime(now());
            $datetime2 = new \DateTime($user->created_at);
            $diffDays = $datetime1->diff($datetime2)->days;
            $week = ceil($diffDays / 7);
            if($week == 0){
                $advInfo['login_times_per_week'] = 0;
            }
            else{
                $advInfo['login_times_per_week'] = round(($user->login_times / $week), 0);
            }
        }
        /*收藏會員次數*/
        if(!$wantIndexArr || in_array('fav_count',$wantIndexArr))
            $advInfo['fav_count'] = MemberFav::where('member_id', $user->id)->get()->count();

        /*瀏覽其他會員次數*/
        if(!$wantIndexArr || in_array('visit_other_count',$wantIndexArr))
            $advInfo['visit_other_count']  = Visited::where('member_id', $user->id)->distinct('visited_id')->count();

        /*過去7天瀏覽其他會員次數*/
        if(!$wantIndexArr || in_array('visit_other_count_7',$wantIndexArr))
            $advInfo['visit_other_count_7'] = Visited::where('member_id', $user->id)->where('created_at', '>=', $date)->distinct('visited_id')->count();

        /*此會員封鎖多少其他會員*/
        if(!$wantIndexArr || in_array('blocked_other_count',$wantIndexArr)) {
            $bannedUsers = \App\Services\UserService::getBannedId();
            $advInfo['blocked_other_count']= \App\Models\Blocked::with(['blocked_user'])
                ->join('users', 'users.id', '=', 'blocked.blocked_id')
                ->where('blocked.member_id', $user->id)
                ->whereNotIn('blocked.blocked_id',$bannedUsers)
                ->whereNotNull('users.id')
                ->count();
        }
        /*此會員被多少會員封鎖*/
        if(!$wantIndexArr || in_array('be_blocked_other_count',$wantIndexArr)) {
            $advInfo['be_blocked_other_count'] = \App\Models\Blocked::with(['blocked_user'])
                ->join('users', 'users.id', '=', 'blocked.member_id')
                ->where('blocked.blocked_id', $user->id)
                ->whereNotIn('blocked.member_id',$bannedUsers)
                ->whereNotNull('users.id')
                ->count();
        }

        return $advInfo;
    }

    public static function userLoginLog($user_id, $request){
        $userLogin_log = LogUserLogin::selectRaw('LEFT(created_at,4) as Year, RIGHT(LEFT(created_at,7), 2) as Month, LEFT(created_at,7) as loginMonth, DATE(created_at) as loginDate, user_id as userID, ip, count(*) as dataCount')
            ->where('user_id', $user_id)
            ->groupBy(DB::raw("LEFT(created_at,7)"));

        if($request->loading_data!=='all'){
            $userLogin_log=$userLogin_log->where('created_at','>=', date('Y-m-d', strtotime('-3 months')));
        }
        $userLogin_log=$userLogin_log->orderBy('created_at', 'DESC')->get();

        foreach ($userLogin_log as $key => $value) {
            $dataLog = LogUserLogin::where('user_id', $user_id)->whereYear('created_at', $value->Year)->whereMonth('created_at', $value->Month)->orderBy('created_at', 'DESC');
            $userLogin_log[$key]['items'] = $dataLog->get();

            //ip
            $Ip_group = LogUserLogin::where('user_id', $user_id)->whereYear('created_at', $value->Year)->whereMonth('created_at', $value->Month)
                ->from('log_user_login as log')
                ->selectRaw('ip, count(*) as dataCount, (select created_at from log_user_login as s where s.user_id=log.user_id and s.ip=log.ip and s.created_at like "%' . $value->loginMonth . '%" order by created_at desc LIMIT 1 ) as loginTime')
                ->groupBy(DB::raw("ip"))->orderBy('loginTime', 'desc')->get();
            $Ip = array();

            //
            $ip_list = $Ip_group->pluck('ip');
            $ip_set_auto_ban_list = SetAutoBan::where('type', 'ip')->whereIn('content', $ip_list)->get();
            $ip_login_list = LogUserLogin::whereIn('ip', $ip_list)->get();
            $user_time_login_list = LogUserLogin::where('user_id', $user_id)->whereYear('created_at', $value->Year)->whereMonth('created_at', $value->Month)->get();

            $ip_login_user_list = $ip_login_list->pluck('user_id')->unique();
            $ip_banned_users_list = banned_users::whereIn('member_id', $ip_login_user_list)->get();
            //

            
            foreach ($Ip_group as $Ip_key => $group) {
                $group['IP_set_auto_ban'] = $ip_set_auto_ban_list->where('content', $group['ip'])->where('expiry', '>=', now())->count() + $ip_set_auto_ban_list->where('content', $group['ip'])->where('expiry', '0000-00-00 00:00:00')->count();
                $Ip['Ip_group'][$Ip_key] = $group;
                $Ip['Ip_group_items'][$Ip_key] = $user_time_login_list->where('ip', $group->ip)->sortByDesc('created_at')->values();
                $Ip['Ip_online_people'][$Ip_key] = $ip_login_list->where('ip', $group->ip)->unique('user_id')->count();

                $IpUsers = $ip_login_list->where('ip', $group->ip)->pluck('user_id')->unique();
                $Ip['Ip_blocked_people'][$Ip_key] = $ip_banned_users_list->whereIn('member_id',$IpUsers)->unique('member_id')->count();
            }

            //排序$Ip
            $sortIp = [];
            arsort($Ip['Ip_blocked_people']);
            foreach($Ip['Ip_blocked_people'] as $skey => $svalue)
            {
                $sortIp['Ip_group'][] = $Ip['Ip_group'][$skey];
                $sortIp['Ip_group_items'][] = $Ip['Ip_group_items'][$skey];
                $sortIp['Ip_online_people'][] = $Ip['Ip_online_people'][$skey];
                $sortIp['Ip_blocked_people'][] = $Ip['Ip_blocked_people'][$skey];
            }
            //排序$Ip

            $userLogin_log[$key]['Ip'] = $sortIp;

            //cfp_id
            $CfpID_group = LogUserLogin::where('user_id', $user_id)->whereYear('created_at', $value->Year)->whereMonth('created_at', $value->Month)
                ->from('log_user_login as log')
                ->selectRaw('cfp_id,count(*) as dataCount, (select created_at from log_user_login as s where s.user_id=log.user_id and s.cfp_id=log.cfp_id and s.created_at like "%' . $value->loginMonth . '%" order by created_at desc LIMIT 1 ) as loginTime')
                ->whereNotNull('cfp_id')
                ->groupBy(DB::raw("cfp_id"))->orderBy('loginTime', 'desc')->get();
            $CfpID = array();

            //
            $cfp_id_list = $CfpID_group->pluck('cfp_id');
            $cfp_id_set_auto_ban_list = SetAutoBan::where('type', 'cfp_id')->whereIn('content', $cfp_id_list)->get();
            $cfp_id_login_list = LogUserLogin::whereIn('cfp_id', $cfp_id_list)->get();
            $user_time_login_list = LogUserLogin::where('user_id', $user_id)->whereYear('created_at', $value->Year)->whereMonth('created_at', $value->Month)->get();

            $cfp_id_login_user_list = $cfp_id_login_list->pluck('user_id')->unique();
            $cfp_id_banned_users_list = banned_users::whereIn('member_id', $cfp_id_login_user_list)->get();
            //

            foreach ($CfpID_group as $CfpID_key => $group) {
                //
                $group['CfpID_set_auto_ban'] = $cfp_id_set_auto_ban_list->where('content', $group['ip'])->where('expiry', '>=', now())->count() + $cfp_id_set_auto_ban_list->where('content', $group['cfp_id'])->where('expiry', '0000-00-00 00:00:00')->count();
                $CfpID['CfpID_group'][$CfpID_key] = $group;
                $CfpID['CfpID_group_items'][$CfpID_key] = $user_time_login_list->where('cfp_id', $group->cfp_id)->sortByDesc('created_at')->values();
                $CfpID['CfpID_online_people'][$CfpID_key] = $cfp_id_login_list->where('cfp_id', $group->cfp_id)->unique('user_id')->count();

                $CfpIDUsers = $cfp_id_login_list->where('cfp_id', $group->cfp_id)->pluck('user_id')->unique();
                $CfpID['CfpID_blocked_people'][$CfpID_key] = $cfp_id_banned_users_list->whereIn('member_id',$CfpIDUsers)->unique('member_id')->count();
                //

            }
            $userLogin_log[$key]['CfpID'] = $CfpID;
        }

        return $userLogin_log;
    }

    public function isForbidAdvAuth() {
        return $this->log_adv_auth_api()->where(
            function ($query) {
                $query->where('forbid_user',1);
            }
        )->count() > 0;
    }
    
    public function isDuplicateAdvAuth() {
        return $this->log_adv_auth_api()->where(
            function ($query) {
                $query->where('is_duplicate',1);
            }
        )->count() > 0;
    }    
    
    public function getPassAdvAuthApiQuery() {
        return $this->log_adv_auth_api()->where('return_code','0000')->where('user_fault',0);
    }
    
    public function getLatestPassAdvAuthApi() {
        return $this->getPassAdvAuthApiQuery()->orderBy('created_at','DESC')->first();
    }

    public function getEffectFaultAdvAuthApiQuery() {
        $effectFaultQuery = $this->log_adv_auth_api()->where('user_fault',1);
        $latestPassAdvAuthApi = $this->getLatestPassAdvAuthApi();
        if($latestPassAdvAuthApi) {
            $effectFaultQuery->where('created_at','>',Carbon::parse($latestPassAdvAuthApi->created_at));
        }
        return $effectFaultQuery;
    }

    public function isPauseAdvAuth() {
        $user_pause_during = config('memadvauth.user.pause_during');
        $latest_log = $this->log_adv_auth_api()->orderBy('created_at','DESC')->first();
        if($latest_log && $latest_log->user_fault==1) {
            return Carbon::parse($latest_log->created_at)->diffInMinutes(Carbon::now())<$user_pause_during;
        }
        else return false;
    } 

    public function getWarnedOfAdvAuthQuery() {
        return $this->aw_relation()->where('adv_auth',1);
    }
    
    public function getBannedOfAdvAuthQuery() {
        return $this->banned()->where('adv_auth',1);
    }
    //檢查是否需要進階驗證
    public function isNeedAdvAuth() {
        $userBanned = $this->getBannedOfAdvAuthQuery()->count(); 
        $user_meta = $this->meta;
        $userWarned = $this->getWarnedOfAdvAuthQuery()->count();                
        $isWarnedUser = $user_meta->isWarnedType=='adv_auth'?$user_meta->isWarned():0;        
        return ($userBanned || $userWarned || $isWarnedUser);
    }
    //將國際碼轉成09格式
    public function getAuthMobile($to_local=false) {
        $authMobile = null;
        $latestAuthSms = $this->short_message()->where('mobile','!=','')->where('active', 1)->orderByDesc('createdate')->first();
        if($latestAuthSms->mobile??null) {
            $authMobile = str_replace( ' ','',$latestAuthSms->mobile);
            $authMobile = str_replace( "\r\n",'',$authMobile);
            $authMobile = str_replace( "\n",'',$authMobile);
            $authMobile =  preg_replace("/([^0-9]+)/", "", $authMobile );
            if($to_local ) {
                if(substr($authMobile,0,3)=='886') {
                    $authMobile = substr_replace($authMobile, '0', 0, 3);
                }                
                
                if(substr($authMobile,0,4)=='+886') {
                    $authMobile = str_replace('+886','0',$authMobile);
                }
            }
        }
        return $authMobile;
    }

    public function isCanPosts_vip() {
        $checkUserVip=0;
        $isVip =Vip::where('member_id', $this->id)->where('active',1)->where('free',0)->first();
        if($isVip){
            $months = Carbon::parse($isVip->created_at)->diffInMonths(Carbon::now());
            if($months>=3 || $isVip->payment=='cc_quarterly_payment' || $isVip->payment=='one_quarter_payment'){
                $checkUserVip=1;
            }
        }
        $isVVIP = ValueAddedService::select('active')
                ->where('member_id', $this->id)
                ->where('active', 1)
                ->where('service_name', 'VVIP')
                ->where(function($query) {
                    $query->where('expiry', '0000-00-00 00:00:00')
                        ->orwhere('expiry', '>=', Carbon::now());}
                )->orderBy('created_at', 'desc')->first() !== null;
        return ($checkUserVip||$isVVIP);
    }

    public function isEverBanned() {
        return IsBannedLog::where('user_id', $this->id)->orderBy('created_at', 'desc')->first();
    }

    public function isEverWarned() {
        return IsWarnedLog::where('user_id', $this->id)->orderBy('created_at', 'desc')->first();
    }

    public function isEverWarnedAndBanned() {
        return IsWarnedLog::where('user_id', $this->id)->orderBy('created_at', 'desc')->first() !== null ||
            IsBannedLog::where('user_id', $this->id)->orderBy('created_at', 'desc')->first() !== null ||
            banned_users::where('member_id', $this->id)->orderBy('created_at', 'desc')->first() !== null ||
            warned_users::where('member_id', $this->id)->orderBy('created_at', 'desc')->first() !== null;
    }

    //略過搜尋
    public function search_ignore()
    {
        return $this->hasMany(SearchIgnore::class, 'member_id', 'id');
    }
    
    //搜尋條件記錄
    public function search_filter_remember()
    {
        return $this->hasOne(SearchFilterRemember::class, 'user_id', 'id');
    }    

    //刺青
    public function tattoo() {
        return $this->hasMany(UserTattoo::class, 'user_id', 'id');
    }
    
    public function isTattooRange($range) {
        return ($this->tattoo->first()->range??null)==$range;
    }
    
    public function isTattooPart($part) {
        return ($this->tattoo->first()->part??null)==$part;
    } 

    public function getLatestVipLog() {
        return $this->vip_log()->orderByDesc('created_at')->first();
    }
    
    public function getLatestVasLog($service_name=null) {
        $query = $this->vas_log()->orderByDesc('created_at');
        if($service_name) $query->where('service_name',$service_name);
        return $query->first();
    }

    public function spamMessagePercentIn7Days(){
        return $this->hasOne('App\Models\SpamMessagePercentIn7Days');
    }

    public function getSpamMessagePercentIn7Days($uid){
        
        $user = new User;
        $spamMessagePercentIn7DaysQuery = $user->spamMessagePercentIn7Days($uid)->where('user_id',$uid);

        if($spamMessagePercentIn7DaysQuery->count() > 0){
            $data = $spamMessagePercentIn7DaysQuery->orderBy('updated_at','desc')->first();
            if(strtotime($data->updated_at) - strtotime(now()) < 86400){ //24 hour
                return $data->percent;
            }
        }else{
            try{
                // $message_percent_7 = User::find($uid)->getSpamMessagePercentIn7Days($uid);
                $message_percent_7 = UserService::computeCanMessagePercent_7($uid);
                $data = array(
                    'user_id'=>$uid,
                    'percent'=>$message_percent_7
                );
                $spamMessagePercentIn7Days = \App\Models\SpamMessagePercentIn7Days::firstOrCreate($data);
                return $data['percent'];
            }catch(\Exception $e){
                dd($e);
            }
        }
    }

    public function message_sent()
    {
        return $this->hasMany(Message::class, 'from_id', 'id');
    }
    
    public function message_accepted()
    {
        return $this->hasMany(Message::class, 'to_id', 'id');
    } 
    
    public function message_sent_to_admin()
    {
        $admin = AdminService::checkAdmin();

        return $this->addChatWithAdminClauseToQuery($this->message_sent())->where('to_id',$admin->id);
    }
    
    public function message_accepted_from_admin()
    {
        $admin = AdminService::checkAdmin();
        return $this->addChatWithAdminClauseToQuery($this->message_accepted())->where('from_id',$admin->id);
    } 

    public function message_with_admin()
    {
        return $this->message_sent_to_admin->merge($this->message_accepted_from_admin);
    } 

    public function latest_message_with_admin()
    {
        return $this->message_with_admin()->sortByDesc('created_at')->first();
    }     

    public static function addChatWithAdminClauseToQuery($query)
    {
        $during_date = Carbon::parse("180 days ago")->toDateTimeString();
        $query->withTrashed()
                ->where('created_at','>=',$during_date)
                ->where('chat_with_admin', 1)
                ;                
        return $query;
    }
    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    //--VVIP--//
    public function VVIP()
    {
        return $this->hasMany(ValueAddedService::class, 'member_id', 'id')->where('service_name','VVIP')->where('active', 1)->orderBy('id', 'desc');
    }
    
    public function unexpired_VVIP_vas_list()
    {
        return $this->VVIP()->where(function($query) {
                    $query->where('expiry', '0000-00-00 00:00:00')
                        ->orWhere('expiry', '>=', Carbon::now());}
                );
    }    


    public function is3MonthsVip()
    {
        //三個月以上(不含三個月)"信用卡"付費的 vip
        $vip = Vip::where('member_id', $this->id)->where('active',1)->where('free',0)->whereIn('payment_method', ['CREDIT', null])->first();
        if(isset($vip)){
            $months = Carbon::parse($vip->created_at)->diffInMonths(Carbon::now());
            if($months <= 3){ return 0;}
            return 1;
        }
    }

    public function is6MonthsVip()
    {
        $months = 0;
        //6個月以上連續信用卡付費的 vip
        //抓有效訂單計算
        $getOrderData = Order::where('user_id', $this->id)
            ->where('service_name', 'VIP')
            ->where('payment_type', 'Credit_CreditCard')
            ->where('payment', 'cc_monthly_payment')
            ->get();
        if(count($getOrderData)>0) {
            foreach ($getOrderData as $row) {
                if($row->order_expire_date != ''){
                    $months = Carbon::parse($row->order_date)->diffInMonths(Carbon::parse($row->order_expire_date));
                    if($months > 6){ break; }
                }else{
                    $months = Carbon::parse($row->order_date)->diffInMonths(Carbon::now());
                    if($months > 6){ break; }
                }
            }
        }
        if( $months > 6 ){ return 1;}
        return 0;
    }

    public function is12MonthsVip()
    {
        //12個月以上累計付費的 vip
        //抓有效訂單計算
        $months = 0;
        $getOrderData = Order::where('user_id', $this->id)
            ->where('service_name', 'VIP')
            ->get();
        if(count($getOrderData)>0) {
            foreach ($getOrderData as $row) {
                if($row->order_expire_date==''){
                    $months += Carbon::parse($row->order_date)->diffInMonths(Carbon::now());
                }else{
                    $months += Carbon::parse($row->order_date)->diffInMonths(Carbon::parse($row->order_expire_date));
                }
            }
        }

        if($months > 12){ return 1;}
        return 0;
    }

    public function canVVIP()
    {
        $user = $this;
        if(( $user->is6MonthsVip() || $user->is12MonthsVip()) && !$user->isEverWarnedAndBanned() ){
            return 1;
        }else{
            return 0;
        }
    }

    public function VvipApplication()
    {
        return $this->hasMany(VvipApplication::class);
    }

    public function applyingVVIP()
    {
        // $applyingVVIP = VvipApplication::where('user_id', $this->id)->where('status',0)->orderBy('created_at', 'desc')->first();
        // if(isset($applyingVVIP)){ return 1;}
        // return 0;

        return $this->VvipApplication()->where('status', 0)->count() > 0;
    }

    public function applyingVVIP_getDeadline()
    {
        $applyingVVIP = VvipApplication::where('user_id', $this->id)->where('status',3)->orderBy('created_at', 'desc')->first();
        if(isset($applyingVVIP)){ return substr($applyingVVIP->deadline, 0, 10);}
        return 0;
    }

    public function applyVVIP_getData()
    {
        $applyVVIP = VvipApplication::where('user_id', $this->id)->orderBy('created_at', 'desc')->first();
        if(isset($applyVVIP)){ return $applyVVIP;}
        return 0;
    }

    public function passVVIP()
    {
        // $passVVIP = VvipApplication::where('user_id', $this->id)->where('status',1)->orderBy('created_at', 'desc')->first();
        // if(isset($passVVIP)){ return 1;}
        // return 0;
        
        return $this->VvipApplication()->where('status', 1)->count() > 0;
    }

    public function cancelVVIP()
    {
        $cancelVVIP = VvipApplication::where('user_id', $this->id)->where('status',4)->orderBy('created_at', 'desc')->first();
        if(isset($cancelVVIP)){ return 1;}
        return 0;
    }
    
    public function working_VvipApplication_list()
    {
        return $this->VvipApplication()->where('status',1);
    }

    public function isVVIP()
    {
        //拿掉VVIP功能
        //return 0;

        //VVIP有效狀態
        return ValueAddedService::select('active')
                ->where('member_id', $this->id)
                ->where('active', 1)
                ->where('service_name', 'VVIP')
                ->where(function($query) {
                    $query->where('expiry', '0000-00-00 00:00:00')
                        ->orWhere('expiry', '>=', Carbon::now());}
                )->orderBy('created_at', 'desc')->first() !== null &&
            VvipApplication::where('user_id', $this->id)->where('status',1)->first() !== null;
    }

    public function isVipOrIsVvip()
    {
        return $this->isVVIP() || $this->isVip();
    }

    public function VvipInfoStatus()
    {
        $vvipInfo = VvipInfo::where('user_id', $this->id)->orderBy('created_at', 'desc')->first();
        if($vvipInfo && $vvipInfo->status==1){
            return 1;
        }

        return 0;
    }

    public function VvipInfo()
    {
        return $this->hasOne(VvipInfo::class);
    } 

    public function VvipMargin()
    {
        return $this->hasOne(VvipMarginDeposit::class);
    }

    public function VvipMarginLog()
    {
        return $this->hasMany(VvipMarginLog::class);
    }
    
    //VvipOption

    public function VvipAssetsImage()
    {
        return $this->hasManyThrough(VvipAssetsImage::class, VvipOptionXref::class, 'user_id', 'id', 'id', 'option_id')->where('option_type', 'assets_image');
    }

    public function VvipBackgroundAndAssets()
    {
        return $this->hasManyThrough(VvipBackgroundAndAssets::class, VvipOptionXref::class, 'user_id', 'id', 'id', 'option_id')->where('option_type', 'background_and_assets');
    }

    public function VvipDateTrend()
    {
        return $this->hasManyThrough(VvipDateTrend::class, VvipOptionXref::class, 'user_id', 'id', 'id', 'option_id')->where('option_type', 'date_trend');
    }

    public function VvipExpectDate()
    {
        return $this->hasManyThrough(VvipExpectDate::class, VvipOptionXref::class, 'user_id', 'id', 'id', 'option_id')->where('option_type', 'expect_date');
    }

    public function VvipExtraCare()
    {
        return $this->hasManyThrough(VvipExtraCare::class, VvipOptionXref::class, 'user_id', 'id', 'id', 'option_id')->where('option_type', 'extra_care');
    }

    public function VvipPointInformation()
    {
        return $this->hasManyThrough(VvipPointInfo::class, VvipOptionXref::class, 'user_id', 'id', 'id', 'option_id')->where('option_type', 'point_information');
    }

    public function VvipQualityLifeImage()
    {
        return $this->hasManyThrough(VvipQualityLifeImage::class, VvipOptionXref::class, 'user_id', 'id', 'id', 'option_id')->where('option_type', 'quality_life_image');
    }

    //VvipSubOption

    public function VvipSubOptionCeoTitle()
    {
        return $this->hasManyThrough(VvipSubOptionCeoTitle::class, VvipSubOptionXref::class, 'user_id', 'id', 'id', 'option_id')->where('option_type', 'ceo_title');
    }

    public function VvipSubOptionEntrepreneur()
    {
        return $this->hasManyThrough(VvipSubOptionEntrepreneur::class, VvipSubOptionXref::class, 'user_id', 'id', 'id', 'option_id')->where('option_type', 'entrepreneur');
    }

    public function VvipSubOptionEntrepreneurCeoTitle()
    {
        return $this->hasManyThrough(VvipSubOptionCeoTitle::class, VvipSubOptionXref::class, 'user_id', 'id', 'id', 'option_remark')->where('option_type', 'entrepreneur');
    }

    public function VvipSubOptionHighAssets()
    {
        return $this->hasManyThrough(VvipSubOptionHighAssets::class, VvipSubOptionXref::class, 'user_id', 'id', 'id', 'option_id')->where('option_type', 'high_assets');
    }

    public function  VvipSubOptionHighNetWorth()
    {
        return $this->hasManyThrough( VvipSubOptionHighNetWorth::class, VvipSubOptionXref::class, 'user_id', 'id', 'id', 'option_id')->where('option_type', 'high_net_worth');
    }

    public function VvipSubOptionLifeCare()
    {
        return $this->hasManyThrough(VvipSubOptionLifeCare::class, VvipSubOptionXref::class, 'user_id', 'id', 'id', 'option_id')->where('option_type', 'life_care');
    }

    public function VvipSubOptionProfessional()
    {
        return $this->hasManyThrough(VvipSubOptionProfessional::class, VvipSubOptionXref::class, 'user_id', 'id', 'id', 'option_id')->where('option_type', 'professional');
    }

    public function VvipSubOptionProfessionalNetwork()
    {
        return $this->hasManyThrough(VvipSubOptionProfessionalNetwork::class, VvipSubOptionXref::class, 'user_id', 'id', 'id', 'option_id')->where('option_type', 'professional_network');
    }

    public function VvipSubOptionSpecialProblemHandling()
    {
        return $this->hasManyThrough(VvipSubOptionSpecialProblemHandling::class, VvipSubOptionXref::class, 'user_id', 'id', 'id', 'option_id')->where('option_type', 'special_problem_handling');
    }


//    public function VVIPisInvite()
//    {
//        $VVIPisInvite = VvipInvite::where('user_id', $this->id)->where('deadline','>',Carbon::now())->first();
//        if(isset($VVIPisInvite)){ return 1;}
//        return 0;
//    }
//
//    public function VVIPisBeInvited()
//    {
//        $VVIPisBeInvited = VvipInvite::where('invite_user_id', $this->id)->where('deadline','>',Carbon::now())->first();
//        if(isset($VVIPisBeInvited)){ return 1;}
//        return 0;
//    }
//
//    public function VVIPisBeInvitedCheckStatus()
//    {
//        $VVIPisBeInvitedCheckStatus = VvipInvite::where('invite_user_id', $this->id)->where('deadline','<',Carbon::now())->where('status', 0)->first();
//        if(isset($VVIPisBeInvitedCheckStatus)){ return 1;}
//        return 0;
//    }
    public function isVvipSelectionRewardActive($to_user)
    {
        if($this->engroup==2){
            $check1 = VvipSelectionReward::select('id')
                ->where('user_id', $to_user)
                ->whereIn('status', [1, 3])
                ->get();
            $check2 = null;
            if($check1) {
                $check2 = VvipSelectionRewardApply::whereIn('vvip_selection_reward_id', $check1)
                    ->where('user_id', $this->id)
                    ->where('status', 1)
                    ->get();
            }

            return count($check1)>0 && count($check2)>0;

        }
        else if($this->engroup==1){
            $check1 = VvipSelectionRewardApply::select('vvip_selection_reward_id')
                ->where('user_id', $to_user)
                ->where('status', 1)
                ->get();
            $check2 = null;
            if($check1) {
                $check2 = VvipSelectionReward::whereIn('id', $check1)
                    ->where('user_id', $this->id)
                    ->whereIn('status', [1, 3])
                    ->get();
            }
            return count($check1)>0 && count($check2)>0;
        }
    }

    //--VVIP END--//
    public static function retrive($id)
    {
        if($id) {
            return Cache::remember('user_' . $id, 3600, function () use ($id) {
                return User::find($id);
            });
        }
        return Cache::remember('users' , 3600, function () {
            return User::all();
        });
    }

    
    public function getUser()
    {
        return $this;
    }
        
    
    public function getUserDescPageStayOnlineRecordsPaginate()
    {
        $this->paginate = $this->stay_online_record_only_page()->orderByDesc('id')->paginate(20,['*'], 'pageU'.$this->id, request()->input('pageU'.$this->id));
        return $this->paginate;
    }    

    public function toSearchableArray()
    {
        $meta = $this->user_meta()->first();
        return [
            'id' => $this->id,
            'engroup' => $this->engroup,
            'name' => $this->name,
            'email' => $this->email,
            'birthdate_timestamp' => Carbon::parse($meta->birthdate)->timestamp,
            'created_at' => $this->created_at?->timestamp,
            'updated_at' => $this->updated_at?->timestamp,
        ];
    }

    public static function getSearchFilterAttributes()
    {
        return [
            'name',
            'engroup',
            'birthdate_timestamp',
        ];
    }

    public function ComputeRemainDay()
    {      
        $user = Vip::where('member_id', $this->id)->where('active', 1)->orderBy('created_at', 'desc')->first();;
        $expiryDate = $user->expiry;
        if($expiryDate == '0000-00-00 00:00:00'){
        
            $now = \Carbon\Carbon::now();
            $latestUpdatedAt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->updated_at);
            $baseDate = clone $now;
            $daysDiff = clone $now;
            $daysDiff = $daysDiff->diffInDays($latestUpdatedAt);
            // 依照付款類形計算不同的取消當下距預計下一週期扣款日的天數
            if($user->payment == 'cc_quarterly_payment'){
                $periodRemained = 92 - ($daysDiff % 92);
            }else {
                $periodRemained = 30 - ($daysDiff % 30);
            }
            // 基準日加上得出的天數再加 1 (不加 1 到期日會少一天)，即為取消後的到期日
            $expiryDate = $baseDate->addDays($periodRemained + 1);
            /**
             * Debugging codes.
             * $output = new \Symfony\Component\Console\Output\ConsoleOutput();
             * $output->writeln('$daysDiff: ' . $daysDiff);
             * $output->writeln('$periodRemained: ' . $periodRemained);
             * $output->writeln('$expiryDate: ' . $expiryDate);
             */
            // 如果是使用綠界付費，且取消日距預計下次扣款日小於七天，則到期日再加一個週期
            // 3137610: 正式商店編號
            // 2000132: 測試商店編號
            if(($user->business_id == '3137610' || $user->business_id == '2000132') && $now->diffInDays($expiryDate) <= 7) {
                // addMonthsNoOverflow(): 避免如 10/31 加了一個月後變 12/01 的情形出現
                if($user->payment=='cc_quarterly_payment'){
                    $expiryDate = $expiryDate->addMonthsNoOverflow(3);
                }else {
                    $expiryDate = $expiryDate->addMonthNoOverflow(1);
                }
            }

            $order = Order::where('order_id', $user->order_id)->get()->first();
            if (strpos($user->order_id, 'SG') !== false && isset($order)) {
                $remain_days = $order->remain_days;
            } else {
                $remain_days = $user->remain_days;
            }
        
            if($remain_days > 0){
                $expiryDate = $expiryDate->addDays($remain_days);
            }
        }
        
        return $expiryDate;
    }

    public function warned_users()
    {
        return $this->hasOne(warned_users::class, 'member_id', 'id');
    }

    public function user_record()
    {
        return $this->hasOne(user_record::class, 'user_id', 'id');
    }

    public function get_city_list()
    {
        return explode(",", $this->meta->city);
    }

    public function get_area_list()
    {
        return explode(",", $this->meta->area);
    }
}
