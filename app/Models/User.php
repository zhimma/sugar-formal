<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Blocked;
use App\Models\ValueAddedService;
use App\Models\Vip;
use App\Models\Tip;
use App\Models\UserMeta;
use App\Models\MemberPic;
use App\Models\SimpleTables\banned_users;
use App\Models\SimpleTables\warned_users;
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

class User extends Authenticatable
{
    use Notifiable;
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
    protected $fillable = ['name', 'email', 'password', 'title', 'enstatus', 'engroup', 'last_login', 'login_times', 'intro_login_times', 'isReadManual', 'exchange_period', 'line_notify_switch'];

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
        return $this->hasMany(Vip::class, 'member_id', 'id')->where('active', 1)->orderBy('created_at', 'desc');
    }

    public function vas()
    {
        return $this->hasMany(ValueAddedService::class, 'member_id', 'id')->where('active', 1)->orderBy('created_at', 'desc');
    }

    public function aw_relation() {
        return $this->hasOne(\App\Models\SimpleTables\warned_users::class, 'member_id', 'id')->where(function ($query){
            $query->whereNull('expire_date')->orWhere('expire_date', '>=', Carbon::now());
        });
    }

    public function fa_relation() {
        return $this->hasOne(\App\Models\SimpleTables\short_message::class, 'member_id', 'id')->where('mobile','!=','')->where('active', 1);
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

    //生活照
    public function pic()
    {
        return $this->hasMany(MemberPic::class, 'member_id', 'id');
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

    public function banned(){
        return $this->hasOne(banned_users::class, 'member_id', 'id');
    }

    public function implicitlyBanned(){
        return $this->hasOne(BannedUsersImplicitly::class, 'target', 'id');
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

        if($user->email == Config::get('social.admin.email')) {
            return true;
        }
        return false;
    }

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
        if(config('social.send-email')){
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

    public function existHeaderImage() {
        $pics = MemberPic::where('member_id', $this->id)->count();
        //echo $pics;
        $user_meta = view()->shared('user_meta');
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
        $msg_count = Message::where('from_id', $tid)->where('to_id', $this->id)
//            ->where('is_row_delete_1','<>',$this->id)
//            ->where('is_row_delete_2','<>',$this->id)
//            ->where('is_single_delete_1','<>',$this->id)
//            ->where('is_single_delete_2','<>',$this->id)
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
//        $collection = collect([$pic_report1, $pic_report2]);
//        $pic_all_report = $collection->collapse()->unique('uid');
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
                    if($user->isVip()){
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
//                    if($user->isVip()){
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
//                    if($user->isVip()){
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
        //註冊天數
//        $days = Carbon::parse($user->created_at)->diffInDays(Carbon::now());
//        if( /*(!$user->isVip() && $tip_count==0) || */ (!$user->isVip() && $days<=30) || $user->engroup==2){
//            return false;//普通會員需註冊滿1個月後，其餘不列計
//        }

        //註冊後如無任何傳訊紀錄 + 不是 vip 則顯示 無
        $checkMessages = Message::where('from_id', $uid)->get()->count();
        if($checkMessages==0 && !$user->isVip()){
            $pr = '無';
            $pr_log = '註冊後如無任何傳訊紀錄+不是vip';
            //舊紀錄刪除
            Pr_log::where('user_id',$uid)->delete();
            return Pr_log::insert([ 'user_id' => $uid, 'pr' => $pr, 'pr_log' => $pr_log, 'active' => 1 ]);
        }

        //default
        $pr = 50;
        $pr_log = '';

        //車馬費計分 次數上限5
//        if($tip_count>0){
//            if($tip_count>5){
//                $tip_count = 5;
//            }
//            $pr = $pr + ($tip_count*2);
//            $pr_log = $pr_log.'車馬費計分+'.$tip_count*2 .'分=>'.$pr.'; ';
//        }

        $pr = $pr + ($tip_count * 1.004);
        $pr_log = $pr_log.'車馬費 '.$tip_count.' 次計分 +'.$tip_count*1.004 .' 分=>'.$pr.'; ';


//        $userBlockList = Blocked::select('blocked_id')->where('member_id', $uid)->get();
//        $isBlockList = Blocked::select('member_id')->where('blocked_id', $uid)->get();
//        $bannedUsers = UserService::getBannedId();
//        $isAdminWarnedList = warned_users::select('member_id')->where('expire_date','>=',Carbon::now())->orWhere('expire_date',null)->get();
//        $isWarnedList = UserMeta::select('user_id')->where('isWarned',1)->get();
//
//        //評價計分
//        $evaluation = DB::table('evaluation')->select('rating')->where('to_id',$uid)
//            ->whereNotIn('from_id',$userBlockList)
//            ->whereNotIn('from_id',$isBlockList)
//            ->whereNotIn('from_id',$bannedUsers)
//            ->whereNotIn('from_id',$isAdminWarnedList)
//            ->whereNotIn('from_id',$isWarnedList)
//            ->get();
//
//        $r5=0;
//        $r4=0;
//        $r3=0;
//        $r2=0;
//        $r1=0;
//        if(isset($evaluation)){
//            foreach ($evaluation as $row){
//                if($row->rating==5 && $r5 <= 5){
//                    $pr = $pr + 2;
//                    $pr_log = $pr_log.'評價計分5星+2分=>'.$pr.'; ';
//                    $r5 = $r5 + 1;
//                }elseif($row->rating==4 && $r4 <= 5){
//                    $pr = $pr + 1;
//                    $pr_log = $pr_log.'評價計分4星+1分=>'.$pr.'; ';
//                    $r4 = $r4 + 1;
//                }elseif($row->rating==3 && $r3 <= 5){
//                    $pr = $pr + 0.3;
//                    $pr_log = $pr_log.'評價計分3星+0.3分=>'.$pr.'; ';
//                    $r3 = $r3 + 1;
//                }elseif($row->rating==2 && $r2 <= 5){
//                    $pr = $pr - 2;
//                    $pr_log = $pr_log.'評價計分2星-2分=>'.$pr.'; ';
//                    $r2 = $r2 + 1;
//                }elseif($row->rating==1 && $r1 <= 5){
//                    $pr = $pr - 5;
//                    $pr_log = $pr_log.'評價計分1星-5分=>'.$pr.'; ';
//                    $r1 = $r1 + 1;
//                }
//            }
//        }

        //連續VIP
//        $vip = Vip::where('member_id',$uid)->where('expiry','0000-00-00 00:00:00')->where('active',1)->where('free',0)->first();
//        if(isset($vip)){
//            $months = Carbon::parse($vip->created_at)->diffInMonths(Carbon::now());
//            //$pr_log = $pr_log.'VIPMonths=>'.$months.'; ';
//            if($months>=6){
//                $pr = $pr + 50;
//                $pr_log = $pr_log.'連續VIP六個月+50分=>'.$pr.'; ';
//            }elseif($months>=5){
//                $pr = $pr + 40;
//                $pr_log = $pr_log.'連續VIP五個月+40分=>'.$pr.'; ';
//            }elseif($months>=4){
//                $pr = $pr + 30;
//                $pr_log = $pr_log.'連續VIP四個月+30分=>'.$pr.'; ';
//            }elseif($months>=3){
//                $pr = $pr + 20;
//                $pr_log = $pr_log.'連續VIP三個月+20分=>'.$pr.'; ';
//            }elseif($months>=2){
//                $pr = $pr + 10;
//                $pr_log = $pr_log.'連續VIP二個月+10分=>'.$pr.'; ';
//            }elseif($months>=1){
//                $pr = $pr + 10;
//                $pr_log = $pr_log.'連續VIP一個月+10分=>'.$pr.'; ';
//            }
//        }


        //註冊後沒有VIP扣分計算
        //$vip = Vip::where('member_id',$uid)->where('active',1)->where('free',0)->where('amount','<>',0)->first();
//        $vip = Vip::where('member_id',$uid)->where('amount','<>',0)->first();
//        if(isset($vip)){
//            //曾有VIP 計算VIP前未刷扣分
//            $months = Carbon::parse($user->created_at)->diffInMonths($vip->created_at);
//            $pr = $pr - ($months * 2.5);
//            $pr_log = $pr_log.'註冊後未刷VIP '.$months.' 個月=>'.$pr.'; ';
//        }else{
//            //未曾有付費VIP紀錄 計算扣分
//            $months = Carbon::parse($user->created_at)->diffInMonths(Carbon::now());
//            $pr = $pr - ($months * 2.5);
//            $pr_log = $pr_log . '註冊後未刷VIP ' . $months . ' 個月=>' . $pr . '; ';
//        }

        //當前有VIP 連續加分計算
        $vip = Vip::where('member_id',$uid)->where('amount','<>',0)->first();
        if(isset($vip) && $vip->active == 1) {
            $months = Carbon::parse($vip->created_at)->diffInMonths(Carbon::now());
            //定期定額累計加分
            if ($vip->payment != null && substr($vip->payment, 0, 3) == 'cc_') {

                if($vip->expiry != '0000-00-00 00:00:00'){
                    $months = Carbon::parse($vip->created_at)->diffInMonths(Carbon::parse($vip->expiry));
                }

                $pr = $pr + ($months * 5)+ (($months-1)*2.5);
                $otherMonths = $months - 1;
                $pr_log = $pr_log . '當前定期定額VIP累計 ' .$months. ' 個月, 額外連續VIP '.$otherMonths.' 個月=>' . $pr .'; ';
                if($vip->payment == 'cc_quarterly_payment'){
                    $pr = $pr - 15;
                    $pr_log = $pr_log . '扣除1次單次季繳計算=>' . $pr .'; ';
                }elseif($vip->payment == 'cc_monthly_payment'){
                    $pr = $pr - 5;
                    $pr_log = $pr_log . '扣除1次單次月繳計算=>' . $pr .'; ';
                }
            }
            
            //舊的定期定額付費紀錄
            if ($vip->payment == null && $vip->expiry == '0000-00-00 00:00:00') {
                $pr = $pr + ($months * 5) + (($months-1)*2.5);
                $otherMonths = $months - 1;
                $pr_log = $pr_log . '當前定期定額VIP累計 ' .$months. ' 個月, 額外連續VIP '.$otherMonths.' 個月=>' . $pr .'; ';
            } elseif ($vip->payment == null && $vip->expiry != '0000-00-00 00:00:00') {
                $months = Carbon::parse($vip->created_at)->diffInMonths(Carbon::parse($vip->expiry));
                $pr = $pr + ($months * 5) + (($months-1)*2.5);
                $otherMonths = $months - 1;
                $pr_log = $pr_log . '當前定期定額VIP累計 ' . $months . ' 個月, 額外連續VIP '.$otherMonths.' 個月=>' . $pr .'; ';
            }

            //單次付費加分
            if ($vip->payment == 'one_quarter_payment') {
                $pr = $pr + 2.5 + 2.5;
                $pr_log = $pr_log . '當前有VIP+額外連續VIP =>' . $pr . '; ';
            }
//            elseif ($vip->payment != null && $vip->payment == 'one_month_payment') {
//                $pr = $pr + 5;
//                $pr_log = $pr_log . '單次付費月付VIP =>' . $pr . '; ';
//            }
        }

        //從 log 取得
        $vip_log = DB::table('member_vip_log')
            ->where('member_id', $uid)
            ->where('action', 1)
            ->where('free', 0)
            ->where('txn_id', '')
            ->where('member_name','like','%SG%')
            ->where('member_name','like','%order id%')
            ->get();

        foreach ($vip_log as $row){
            if (strpos($row->member_name, 'one_quarter_payment') !== false) {
                $pr = $pr + 15;
                $pr_log = $pr_log . '曾經單次付費季付VIP =>' . $pr . '; ';
            }
            if (strpos($row->member_name, 'one_month_payment') !== false) {
                $pr = $pr + 5;
                $pr_log = $pr_log . '曾經單次付費月付VIP =>' . $pr . '; ';
            }
            if (strpos($row->member_name, 'cc_quarterly_payment') !== false) {
                $pr = $pr + 15;
                $pr_log = $pr_log . '曾經定期定額季付VIP =>' . $pr . '; ';
            }
            if (strpos($row->member_name, 'cc_monthly_payment') !== false) {
                $pr = $pr + 5;
                $pr_log = $pr_log . '曾經定期定額月付VIP =>' . $pr . '; ';
            }
        }



        //vip 一個月內
//        if(isset($vip)){
//            $days = Carbon::parse($vip->created_at)->diffInDays(Carbon::now());
//            //$pr_log = $pr_log.'VIPdays=>'.$days.'; ';
//            if($days<30){
//                $pr = $pr + 5;
//                $pr_log = $pr_log.'VIP一個月內+5分=>'.$pr.'; ';
//            }
//
//            //不連續VIP
//            if($vip->payment=='one_month_payment'){
//                $pr = $pr + 5;
//                $pr_log = $pr_log.'不連續VIP一個月+5分=>'.$pr.'; ';
//            }elseif($vip->payment=='one_quarter_payment'){
//                $pr = $pr + 15;
//                $pr_log = $pr_log.'不連續VIP三個月+15分=>'.$pr.'; ';
//            }
//        }

        //罐頭訊息計分
//        $msg = array();
//        $from_content = array();
//        $user_similar_msg = array();
//        $message = Message::where('from_id',$uid)->orderBy('created_at','desc')->where('sys_notice',0)->take(100)->get();
//        foreach($message as $row){
//            array_push($msg,array('id'=>$row->id,'content'=>$row->content,'created_at'=>$row->created_at));
//        }
//        array_push($from_content,  array('msg'=>$msg));
//        //比對訊息
//        foreach($from_content as $data) {
//            foreach ($data['msg'] as $word1) {
//                foreach ($data['msg'] as $word2) {
//                    if ($word1['created_at'] != $word2['created_at']) {
//                        similar_text($word1['content'], $word2['content'], $percent);
//                        if ($percent >= 70) {
//                                array_push($user_similar_msg, array($word1['id'], $word1['content'], $word1['created_at'], $percent));
//                        }
//                    }
//                }
//            }
//        }
//        $spam_percent = round(count($user_similar_msg) / count($message))*100;
//        if($spam_percent>70){
//            $pr = $pr - 30;
//            $pr_log = $pr_log.'罐頭訊息比例70%-30分=>'.$pr.'; ';
//        }elseif($spam_percent>60){
//            $pr = $pr - 20;
//            $pr_log = $pr_log.'罐頭訊息比例60%-20分=>'.$pr.'; ';
//        }elseif($spam_percent>50){
//            $pr = $pr - 10;
//            $pr_log = $pr_log.'罐頭訊息比例50%-10分=>'.$pr.'; ';
//        }

        //沒有VIP計分
//        if(!$user->isVip() && $pr>=40){
//            $o_pr = $pr;
//            $pr = ($pr-40)/2 + 40;
//            $pr_log = $pr_log.'沒有VIP('.$o_pr.'-40)/2+40=>'.$pr.'; ';
//        }

        //VVIP直接100計算 待VVIP實作後加入

        //非VIP 扣分 每位通訊人數扣0.2
        if(!$user->isVip()) {
            $checkMessageUsers = Message::select('to_id')->where('from_id', $uid)->distinct()->get()->count();
            if($checkMessageUsers>0){
                $pr = $pr - ($checkMessageUsers * 0.2);
                $pr_log = $pr_log.'非VIP通訊人數 '.$checkMessageUsers.' 人扣分 =>'.$pr.'; ';
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
//        $query_pr = DB::table('pr_log')->where('user_id',$uid)->orderBy('created_at','desc')->first();
//        if( (isset($query_pr) && $query_pr->pr_log != $pr_log) || !isset($query_pr)) {
//            DB::table('pr_log')->insert([
//                'user_id' => $uid,
//                'pr_log' => $pr_log
//            ]);
//        }
//
//        return $pr;
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
            $data['isWarned'] = $userMeta->isWarned;
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

}
