<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Blocked;
use App\Models\Vip;
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
    protected $fillable = ['name', 'email', 'password', 'title', 'enstatus', 'engroup', 'last_login', 'isReadManual'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $append = ['isVip'];

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
        return $this->hasMany(Vip::class, 'member_id', 'id');
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

    /**
    * Whether the user is VIP
    *
    * @param int id
    *
    * @return boolean
    */
    public function getIsVipAttribute()
    {
        foreach($this->vip as $vip){
            if($vip->active == 1){
                return true;
            }
        }
        return false;
    }

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
        return Vip::select('active')->where('member_id', $this->id)->where('active', 1)->orderBy('created_at', 'desc')->first() !== null;
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

    public function isVipNotCanceledORCanceledButNotExpire()
    {
        //return true: VIP未取消
        //return false: VIP已取消，但權限還沒過期
        return Vip::where('member_id', $this->id)->where('active', 1)->where('expiry', '=', '0000-00-00 00:00:00')->orderBy('created_at', 'desc')->first() !== null;
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
        return isset($this->meta_()->pic) && ($pics >= 3);
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
            ->where('is_row_delete_1','!=',$tid)
            ->where('is_row_delete_2','!=',$tid)
            ->where('is_single_delete_1','!=',$tid)
            ->where('is_single_delete_2','!=',$tid)
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
        $collection = collect([$pic_report1, $pic_report2]);
        $pic_all_report = $collection->collapse()->unique('uid');
        // $pic_all_report->unique()->all();
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
        //訊息檢舉
        $msg_report = Message::select('to_id')->where('from_id',$this->id)->where('isReported',1)->where('cancel','0')->where('to_id','!=',$this->id)->distinct('to_id')->get();
        if(isset($msg_report) && count($msg_report)>0){
            foreach($msg_report as $row){
                $user = User::findById($row->to_id);
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
        //會員檢舉
        $report = Reported::select('member_id')->where('reported_id',$this->id)->where('cancel','0')->where('member_id','!=',$this->id)->distinct('member_id')->get();
        if(isset($report) && count($report)>0){
            foreach($report as $row){
                $user = User::findById($row->member_id);
                if(isset($user->engroup) && $user->engroup==2){
                    if($user->isPhoneAuth()==1){
                        $score = $score + 5;
                    }else{
                        $score = $score + 3.5;
                    }
                }else if(isset($user->engroup) && $user->engroup==1){
                    if($user->isVip()){
                        $score = $score + 5;
                    }else{
                        $score = $score + 3.5;
                    }
                }
            }
        }

        return $score;
    }

    public function isPhoneAuth()
    {
        $auth_phone = DB::table('short_message')->where('member_id',$this->id)->where('active',1)->count();
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
}
