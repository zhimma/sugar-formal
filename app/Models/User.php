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
use App\Models\LogFreeVipPicAct;
use App\Models\LogUserLogin;
use App\Models\UserTinySetting;
use App\Models\IsBannedLog;
use App\Models\IsWarnedLog;
use App\Models\SimpleTables\short_message;
use App\Models\LogAdvAuthApi;

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

    public function cfp(){
        return $this->hasMany(CFP_User::class, 'user_id', 'id');
    }

    public function isOnline() {
        return \Cache::has('user-is-online-' . $this->id);
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

    public function isAdvanceAuth()
    {
        $count = $this->where('id',$this->id)->where('advance_auth_status',1)->count();
        return $count >0 ;
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
        if($checkMessages==0 && !$user->isVip() && count($order)==0){
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
        if(!$user->isVip()) {
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

    public static function userAdvInfo($user_id,$wantIndexArr=[]){
        $user=User::findById($user_id);
        $date = date('Y-m-d H:m:s', strtotime('-7 days'));
		
        /*發信＆回信次數統計*/
		$countInfo['message_count'] = 0;
		$countInfo['message_reply_count'] = 0;
		$countInfo['message_reply_count_7'] = 0;
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
						->where('wu.expire_date', '>=', Carbon::now())
						->orWhere('wu.expire_date', null); })
				->whereNull('b1.member_id')
				->whereNull('b3.target')
				->whereNull('wu.member_id')
				->where(function($query)use($date_start,$date_end) {
					$query->where('message.from_id','<>',1049)
						->where('message.sys_notice',0)
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
					->where('sys_notice',0)
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
                        ->where('wu.expire_date', '>=', Carbon::now())
                        ->orWhere('wu.expire_date', null); })
                ->whereNull('b1.member_id')
                ->whereNull('b3.target')
                ->whereNull('wu.member_id')
                ->where(function($query)use($date_start,$date_end) {
                    $query->where('message.from_id','<>',1049)
                        ->where('message.sys_notice',0)
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
                    ->where('sys_notice',0)
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
    
    public function isForbidAdvAuth() {
        return $this->log_adv_auth_api()->where('forbid_user',1)->count()>0;
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
        $isWarnedUser = $user_meta->isWarnedType=='adv_auth'?$user_meta->isWarned:0;        
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
}
