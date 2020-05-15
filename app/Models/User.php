<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Blocked;
use App\Models\Vip;
use App\Models\UserMeta;
use App\Models\MemberPic;
use App\Models\SimpleTables\banned_users;
use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Ixudra\Curl\Facades\Curl;
use Carbon\Carbon;

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
    protected $fillable = ['name', 'email', 'password', 'title', 'enstatus', 'engroup', 'last_login'];

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
    {dd($this->vip());
        return $this->vip != NULL and $this->vip->active == 1 ;
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
