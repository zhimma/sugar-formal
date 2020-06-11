<?php

namespace App\Services;

use App\Models\Tip;
use App\Models\Vip;
use DB;
use Auth;
use Mail;
use Config;
use Session;
use Exception;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Role;
use App\Models\Message;
use App\Repositories\UserRepository;
use App\Events\UserRegisteredEmail;
use App\Notifications\ActivateUserEmail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * User model
     * @var User
     */
    public $model;

    /**
     * User Meta model
     * @var UserMeta
     */
    protected $userMeta;

    /**
     * Role Service
     * @var RoleService
     */
    protected $role;

    public function __construct(
        User $model,
        UserMeta $userMeta,
        Role $role
    ) {
        $this->model = $model;
        $this->userMeta = $userMeta;
        $this->role = $role;
    }

    /**
     * Get all users
     *
     * @return array
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Find a user
     * @param  integer $id
     * @return User
     */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /**
     * Search the users
     *
     * @param  string $input
     * @return mixed
     */
    public function search($input)
    {
        $query = $this->model->orderBy('created_at', 'desc');

        $columns = Schema::getColumnListing('users');

        foreach ($columns as $attribute) {
            $query->orWhere($attribute, 'LIKE', '%'.$input.'%');
        };

        return $query->paginate(env('PAGINATE', 25));
    }

    /**
     * Find a user by email
     *
     * @param  string $email
     * @return User
     */
    public function findByEmail($email)
    {
        return $this->model->findByEmail($email);
    }

    public function findByName($name)
    {
        return $this->model->findByName($name);
    }

    /**
     * Find by Role ID
     * @param  integer $id
     * @return Collection
     */
    public function findByRoleID($id)
    {
        $usersWithRepo = [];
        $users = $this->model->all();

        foreach ($users as $user) {
            if ($user->roles->first()->id == $id) {
                $usersWithRepo[] = $user;
            }
        }

        return $usersWithRepo;
    }

    /**
     * Find by the user meta activation token
     *
     * @param  string $token
     * @return boolean
     */
    public function findByActivationToken($token)
    {
        $userMeta = UserMeta::where('activation_token', $token)->first();

        if ($userMeta) {
            return $userMeta->user;
        }

        return false;
    }

    /**
     * Create a user's profile
     *
     * @param  User $user User
     * @param  string $password the user password
     * @param  string $role the role of this user
     * @param  boolean $sendEmail Whether to send the email or not
     * @return User
     */
    public function create($user, $password, $sendEmail = true)
    {
        try {
            DB::transaction(function () use ($user, $password, $sendEmail) {
                $this->userMeta->firstOrCreate([
                    'user_id' => $user->id
                ]);

                //$this->assignRole($role, $user->id);

                if ($sendEmail) {
                    event(new UserRegisteredEmail($user, $password));
                }

            });
            $domains = config('banned.domains');
            $isExists = \DB::table('banned_users_implicitly')->where('target', $user->id)->exists();
            foreach ($domains as $domain){
                if(str_contains($user->email, $domain) && !$isExists){
                    \DB::table('banned_users_implicitly')->insert(
                        ['fp' => 'DirectlyBanned',
                            'user_id' => '0',
                            'target' => $user->id,
                            'created_at' => \Carbon\Carbon::now()]
                    );
                }
            }
            $this->setAndSendUserActivationToken($user);

            return $user;
        } catch (Exception $e) {
            throw new Exception("We were unable to generate your profile, please try again later. " . $e, 1);
        }
    }

    /**
     * Update a user's profile
     *
     * @param  int $userId User Id
     * @param  array $inputs UserMeta info
     * @return User
     */
    public function update($userId, $payload)
    {
        $setBlockKeys = ['blockcity','blockarea'];
        $notLikeBlockKeys = ['blockarea' => 'isHideArea'];
        foreach($setBlockKeys as $setBlockKeys){
            // dump($setBlockKeys);
            foreach($payload as $key => $value) {
                
                if($key!='blockcity'&&$key!='blockarea'&&preg_match("/$setBlockKeys/i", $key)){
                    if($key != $setBlockKeys){
                        if(is_null($payload[$key])){
                            unset($payload[$key]);
                        }else{
                            if(isset($notLikeBlockKeys[$setBlockKeys])){
                                if(!in_array($key, $notLikeBlockKeys)){
                                    $payload[$setBlockKeys] = $payload[$setBlockKeys]. ",". $value;
                                    unset($payload[$key]);
                                 }
                            }else{
                                $payload[$setBlockKeys] = $payload[$setBlockKeys]. ",". $value;
                                unset($payload[$key]);
                            }
                        }
                    }
                }
            }
        }
        $setKeys = ['city','area'];
        $notLikeKeys = ['area' => 'isHideArea'];
        foreach($setKeys as $setKey){
            foreach($payload as $key => $value) {
                if($key!='blockcity'&&$key!='blockarea'&&preg_match("/$setKey/i", $key)){
                    if($key != $setKey){
                        if(is_null($payload[$key])){
                            unset($payload[$key]);
                        }else{
                            if(isset($notLikeKeys[$setKey])){
                                if(!in_array($key, $notLikeKeys)){
                                    $payload[$setKey] = $payload[$setKey]. ",". $value;
                                    unset($payload[$key]);
                                }
                            }else{
                                $payload[$setKey] = $payload[$setKey]. ",". $value;
                                unset($payload[$key]);
                            }
                        }
                    }
                }
            }
        }
        
        if (isset($payload['meta']) && ! isset($payload['meta']['terms_and_cond'])) {
            throw new Exception("You must agree to the terms and conditions.", 1);
        }
        try {
            return DB::transaction(function () use ($userId, $payload) {
                //$user = $this->model->find($userId);
                $user = $this->find($userId);

                if (isset($payload['meta']['terms_and_cond']) && ($payload['meta']['terms_and_cond'] == 1 || $payload['meta']['terms_and_cond'] == 'on')) {
                    $payload['meta']['terms_and_cond'] = 1;
                } else {
                    $payload['meta']['terms_and_cond'] = 0;
                }

                unset($payload['meta']['marketing']);
                if (isset($payload['city']))
                {
                  $payload['meta']['city'] = $payload['city'];
                  unset($payload['city']);
                }
                  if (isset($payload['isHideCup']))
                  {
                  $payload['meta']['isHideCup'] = $payload['isHideCup'];
                  unset($payload['isHideCup']);
                  }
                  if (isset($payload['isHideArea']))
                  {
                  $payload['meta']['isHideArea'] = $payload['isHideArea'];
                  unset($payload['isHideArea']);
                  }
                  if (isset($payload['isHideWeight']))
                  {
                  $payload['meta']['isHideWeight'] = $payload['isHideWeight'];
                  unset($payload['isHideWeight']);
                  }
                  if (isset($payload['isHideOccupation']))
                  {
                  $payload['meta']['isHideOccupation'] = $payload['isHideOccupation'];
                  unset($payload['isHideOccupation']);
                  }
                  if (isset($payload['income']))
                  {
                  $payload['meta']['income'] = $payload['income'];
                  unset($payload['income']);
                  }
                  if (isset($payload['assets']))
                  {
                  $payload['meta']['assets'] = $payload['assets'];
                  unset($payload['assets']);
                  }
                  if (isset($payload['area']))
                  {
                  $payload['meta']['area'] = $payload['area'];
                  unset($payload['area']);
                  }
                  if (isset($payload['budget']))
                  {
                  $payload['meta']['budget'] = $payload['budget'];
                  unset($payload['budget']);
                  }
                  if (isset($payload['birthdate']))
                  {
                  $payload['meta']['birthdate'] = $payload['birthdate'];
                  unset($payload['birthdate']);
                  }
                  if (isset($payload['year']) && isset($payload['month']) && isset($payload['day']))
                  {
                      $payload['meta']['birthdate'] = $payload['year'].'-'.$payload['month'].'-'.$payload['day'];
                      unset($payload['year']);
                      unset($payload['month']);
                      unset($payload['day']);
                  }
                  if (isset($payload['height']))
                  {
                  $payload['meta']['height'] = $payload['height'];
                  unset($payload['height']);
                  }
                  if (isset($payload['weight']))
                  {
                  $payload['meta']['weight'] = $payload['weight'];
                  unset($payload['weight']);
                  }
                  if (isset($payload['cup']))
                  {
                  $payload['meta']['cup'] = $payload['cup'];
                  unset($payload['cup']);
                  }
                  if (isset($payload['job']))
                  {
                  $payload['meta']['job'] = $payload['job'];
                  unset($payload['job']);
                  }
                  if (isset($payload['domain']))
                  {
                  $payload['meta']['domain'] = $payload['domain'];
                  unset($payload['domain']);
                  }
                  if (isset($payload['domainType']))
                  {
                  $payload['meta']['domainType'] = $payload['domainType'];
                  unset($payload['domainType']);
                  }
                   if (isset($payload['blockdomain']))
                  {
                  $payload['meta']['blockdomain'] = $payload['blockdomain'];
                  unset($payload['blockdomain']);
                  }
                  if (isset($payload['domainType']))
                  {
                  $payload['meta']['domainType'] = $payload['domainType'];
                  unset($payload['domainType']);
                  }
                  if (isset($payload['blockdomainType']))
                  {
                  $payload['meta']['blockdomainType'] = $payload['blockdomainType'];
                  unset($payload['blockdomainType']);
                  }
                  if (isset($payload['blockcity']))
                  {
                    $payload['meta']['blockcity'] = $payload['blockcity'];
                    unset($payload['blockcity']);
                  }
                  if (isset($payload['blockarea']))
                  {
                    $payload['meta']['blockarea'] = $payload['blockarea'];
                    unset($payload['blockarea']);
                  }
                  if (isset($payload['body']))
                  {
                  $payload['meta']['body'] = $payload['body'];
                  unset($payload['body']);
                  }
                  if (isset($payload['about']))
                  {
                  $payload['meta']['about'] = $payload['about'];
                  unset($payload['about']);
                  }
                  if (isset($payload['style']))
                  {
                  $payload['meta']['style'] = $payload['style'];
                  unset($payload['style']);
                  }
                  if (isset($payload['situation']))
                  {
                  $payload['meta']['situation'] = $payload['situation'];
                  unset($payload['situation']);
                  }
                  if (isset($payload['education']))
                  {
                  $payload['meta']['education'] = $payload['education'];
                  unset($payload['education']);
                  }
                  if (isset($payload['marriage']))
                  {
                  $payload['meta']['marriage'] = $payload['marriage'];
                  unset($payload['marriage']);
                  }
                  if (isset($payload['drinking']))
                  {
                  $payload['meta']['drinking'] = $payload['drinking'];
                  unset($payload['drinking']);
                  }
                  if (isset($payload['smoking']))
                  {
                  $payload['meta']['smoking'] = $payload['smoking'];
                  unset($payload['smoking']);
                  }
                  if (isset($payload['occupation']))
                  {
                $payload['meta']['occupation'] = $payload['occupation'];
                  unset($payload['occupation']);
                  }
                  if (isset($payload['notifmessage']))
                  {
                $payload['meta']['notifmessage'] = $payload['notifmessage'];
                  unset($payload['notifmessage']);
                  }
                   if (isset($payload['notifhistory']))
                  {
                                  $payload['meta']['notifhistory'] = $payload['notifhistory'];
                  unset($payload['notifhistory']);
                  }
                $meta = $user->meta_();
                if (isset($payload['meta']))
                {
                    $meta->exists = true;
                    $meta->update($payload['meta']);
                    $userMetaResult = true;
                }
                else $userMetaResult = false;
                if(isset($payload['engroup'])) {
                    if ($payload['engroup'] != $user->engroup) {
                        $user->engroup_change = $user->engroup_change + 1;
                    }
                }

                if (isset($payload['roles'])) {
                    $this->unassignAllRoles($userId);
                    $this->assignRole($payload['roles'], $userId);
                }
                $user->update($payload);
                return $user;
            });
        } catch (Exception $e) {
            throw new Exception("We were unable to update your profile " . $e, 1);
        }
    }


    public static function checkRecommendedUser($targetUser){
        $description = null;
        $stars = null;
        $background = null;
        $title = null;
        $button = null;
        $height = null;
        $now = \Carbon\Carbon::now();
        if($targetUser->engroup == 1 && $targetUser->isVip()){
            $vip_date = Vip::select('id', 'updated_at')->where('member_id', $targetUser->id)->orderBy('updated_at', 'desc')->get()->first();
            if(!isset($vip_date->updated_at)){
                return ['description' => $description,
                    'stars' => $stars,
                    'background' => $background,
                    'title' => $title,
                    'button' =>  $button,
                    'height' => $height];
            }
            $vip_date = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $vip_date->updated_at);
            $diff_in_months = $vip_date->diffInMonths($now);
            $title = "優選糖爹";
            $button = "../../img/member_tags/rcmd_daddy.png";
            switch ($diff_in_months){
                case 0:  //未滿一個月
                    break;
                case 1:
                case 2:
                    $tip_count = Tip::select('id')->where('member_id', $targetUser->id)->count();
                    if($tip_count >= 1){
                        $background = '../../img/member_tags/bg_1.png';
                        $description = $targetUser->name."是本站於".$vip_date->toDateString()."成為VIP的新進的VIP會員，願意使用站方的車馬費制度。建議甜心可請求".$targetUser->name."向站方支付車馬費與您進行第一次約會。<a href='".url('feature')."' target='_blank'>(甚麼是車馬費?)</a>";
                        $stars = "<img src='../../img/member_tags/star_19.png'>
                                  <img src='../../img/member_tags/star_19.png'>
                                  <img src='../../img/member_tags/star_19.png'>
                                  <img src='../../img/member_tags/star_23.png'>
                                  <img src='../../img/member_tags/star_23.png'>";
                        $height = '480px';
                    }
                    break;
                case 3:
                    $tip_count = Tip::select('id')->where('member_id', $targetUser->id)->count();
                    if($tip_count >= 1){
                        $background = '../../img/member_tags/bg_1.png';
                        $description = $targetUser->name."是本站於".$vip_date->toDateString()."成為VIP的長期VIP會員，願意使用站方的車馬費制度。建議甜心可請求".$targetUser->name."向站方支付車馬費與您進行第一次約會。<a href='".url('feature')."' target='_blank'>(甚麼是車馬費?)</a>";
                        $stars = "<img src='../../img/member_tags/star_19.png'>
                                  <img src='../../img/member_tags/star_19.png'>
                                  <img src='../../img/member_tags/star_19.png'>
                                  <img src='../../img/member_tags/star_21.png'>
                                  <img src='../../img/member_tags/star_23.png'>";
                        $height = '480px';
                    }
                    else{
                        $background = '../../img/member_tags/bg_1.png';
                        $description = $targetUser->name."是本站於".$vip_date->toDateString()."成為VIP的長期VIP會員。";
                        $stars = "<img src='../../img/member_tags/star_19.png'>
                                  <img src='../../img/member_tags/star_19.png'>
                                  <img src='../../img/member_tags/star_19.png'>
                                  <img src='../../img/member_tags/star_23.png'>
                                  <img src='../../img/member_tags/star_23.png'>";
                        $height = '380px';
                    }
                    break;
                case 4:
                    $tip_count = Tip::select('id')->where('member_id', $targetUser->id)->count();
                    if($tip_count >= 1){
                        $background = '../../img/member_tags/bg_1.png';
                        $description = $targetUser->name."是本站於".$vip_date->toDateString()."成為VIP的長期VIP會員，願意使用站方的車馬費制度。建議甜心可請求".$targetUser->name."向站方支付車馬費與您進行第一次約會。<a href='".url('feature')."' target='_blank'>(甚麼是車馬費?)</a>";
                        $stars = "<img src='../../img/member_tags/star_19.png'>
                                  <img src='../../img/member_tags/star_19.png'>
                                  <img src='../../img/member_tags/star_19.png'>
                                  <img src='../../img/member_tags/star_19.png'>
                                  <img src='../../img/member_tags/star_21.png'>";
                        $height = '480px';
                    }
                    else{
                        $background = '../../img/member_tags/bg_1.png';
                        $description = $targetUser->name."是本站於".$vip_date->toDateString()."成為VIP的長期VIP會員。";
                        $stars = "<img src='../../img/member_tags/star_19.png'>
                                  <img src='../../img/member_tags/star_19.png'>
                                  <img src='../../img/member_tags/star_19.png'>
                                  <img src='../../img/member_tags/star_19.png'>
                                  <img src='../../img/member_tags/star_23.png'>";
                        $height = '380px';
                    }
                    break;
                default:  //五個月以上
                    $background = '../../img/member_tags/bg_1.png';
                    $description = $targetUser->name."是本站於".$vip_date->toDateString()."成為VIP的長期VIP會員。";
                    $stars = "<img src='../../img/member_tags/star_19.png'>
                              <img src='../../img/member_tags/star_19.png'>
                              <img src='../../img/member_tags/star_19.png'>
                              <img src='../../img/member_tags/star_19.png'>
                              <img src='../../img/member_tags/star_19.png'>";
                    $height = '380px';
                    break;
            }
        }
        elseif ($targetUser->engroup == 2 && $targetUser->isVip() && isset($targetUser->created_at)){
            $registration_date = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $targetUser->created_at);
            $diff_in_months = $registration_date->diffInMonths($now);
            if($diff_in_months == 0){
                $background = '../../img/member_tags/bg_2.png';
                $button = "../../img/member_tags/new_baby.png";
                $title = "新進甜心";
                $description = $targetUser->name."是本站新註冊的甜心寶貝。";
                $stars = "<img src='../../img/member_tags/star_19.png'>
                          <img src='../../img/member_tags/star_19.png'>
                          <img src='../../img/member_tags/star_19.png'>
                          <img src='../../img/member_tags/star_19.png'>
                          <img src='../../img/member_tags/star_19.png'>";
                $height = '380px';
            }
        }

        return ['description' => $description,
                'stars' => $stars,
                'background' => $background,
                'title' => $title,
                'button' =>  $button,
                'height' => $height];
    }

    /**
     * Invite a new member
     * @param  array $info
     * @return void
     */
    public function invite($info)
    {
        $password = substr(md5(rand(1111, 9999)), 0, 10);

        return DB::transaction(function () use ($password, $info) {
            $user = $this->model->create([
                'email' => $info['email'],
                'name' => $info['name'],
                'password' => bcrypt($password)
            ]);

            return $this->create($user, $password, $info['roles'], true);
        });
    }

    /**
     * Destroy the profile
     *
     * @param  int $id
     * @return bool
     */
    public function destroy($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $this->unassignAllRoles($id);
                $this->leaveAllTeams($id);

                $userMetaResult = $this->userMeta->where('user_id', $id)->delete();
                //$userResult = $this->model->find($id)->delete();
                $userResult = $this->find($id)->delete();

                return ($userMetaResult && $userResult);
            });
        } catch (Exception $e) {
            throw new Exception("We were unable to delete this profile", 1);
        }
    }

    /**
     * Switch user login
     *
     * @param  integer $id
     * @return boolean
     */
    public function switchToUser($id)
    {
        try {
            //$user = $this->model->find($id);
            $user = $this->find($id);
            Session::put('original_user', Auth::id());
            Auth::login($user);
            return true;
        } catch (Exception $e) {
            throw new Exception("Error logging in as user", 1);
        }
    }

    /**
     * Switch back
     *
     * @param  integer $id
     * @return boolean
     */
    public function switchUserBack()
    {
        try {
            $original = Session::pull('original_user');
            //$user = $this->model->find($original);
            $user = $this->find($original);
            Auth::login($user);
            return true;
        } catch (Exception $e) {
            throw new Exception("Error returning to your user", 1);
        }
    }

    /**
     * Set and send the user activation token via email
     *
     * @param void
     */
    public function setAndSendUserActivationToken($user)
    {
        $token = md5(str_random(40));

        $user->meta_()->update([
            'activation_token' => $token
        ]);

        $user->notify(new ActivateUserEmail($token));
    }

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */

    /**
     * Assign a role to the user
     *
     * @param  string $roleName
     * @param  integer $userId
     * @return void
     */
    public function assignRole($roleName, $userId)
    {
        $role = $this->role->findByName($roleName);
        //$user = $this->model->find($userId);
        $user = $this->find($userId);

        $user->roles()->attach($role);
    }

    /**
     * Unassign a role from the user
     *
     * @param  string $roleName
     * @param  integer $userId
     * @return void
     */
    public function unassignRole($roleName, $userId)
    {
        $role = $this->role->findByName($roleName);
        //$user = $this->model->find($userId);
        $user = $this->find($userId);

        $user->roles()->detach($role);
    }

    public static function getBannedId($except = null){
        $banned = \App\Models\SimpleTables\banned_users::select('member_id AS user_id')->get();
        if($except){
            $implicitlyBanned = \App\Models\BannedUsersImplicitly::select('target AS user_id')->where('target' , '<>', $except)->get();
        }
        else{
            $implicitlyBanned = \App\Models\BannedUsersImplicitly::select('target AS user_id')->get();
        }

        return $implicitlyBanned->toBase()->merge($banned);
    }

    /**
     * Unassign all roles from the user
     *
     * @param  string $roleName
     * @param  integer $userId
     * @return void
     */
    public function unassignAllRoles($userId)
    {
        //$user = $this->model->find($userId);
        $user = $this->find($userId);
        $user->roles()->detach();
    }
    
    /**
     * Message is replied from reciever
     *
     * @param int msg_id
     *
     * @return bool
     */
    public function beenRepliedMessage($msg_id)
    {
        $msg = Message::where('id', $msg_id)->first();
        if($msg)
        {
            $replied = Message::where('id', '>', $msg->id)
                ->where('from_id', $msg->to_id)
                ->where('to_id', $msg->from_id)
                ->where('content', 'NOT LIKE', '系統通知%');
            return $replied->count() == 0 ? true : false;
        }
        return false;
    }

    /**
     * 有回覆車馬費邀請的訊息
     *
     * @param date start
     * @param date end
     * @return array result
     */

    public function selectTipMessagesReplied($start, $end)
    {
        $tipMessages = Tip::selectTipMessage($start, $end);
        $result = array();
        foreach($tipMessages as $message)
        {
            // from_id 邀請 to_id
            if($message->to_id != NULL)
                $isReply = Message::isReplied($message->from_id, $message->to_id, $message->created_at);
            if($isReply)
                array_push($result, $message);
        }
        return $result;
    }

    public function averageReceiveMessages($city, $isVip, $engroup)
    {
        $users = User::where('engroup', $engroup);

        $users = $users->join('user_meta', 'user_meta.user_id', '=', 'users.id');
        if(count($city)>0)
        {
            $users->whereIn('city', $city);
        }
        
        if($isVip)
        {
            $users->join('member_vip', 'member_vip.member_id','=','user_meta.user_id');
        }

        $users = $users->get()->keyBy('user_id')->keys();
// dd($users);
        if($users->count() > 0){
            $messages = Message::whereIn('to_id', $users->all())->get()->count();
        }else{
            $messages = 0;
        }
            

        return ['users' => $users->count(), 'messages' => $messages];
    }

    /**
     * Get all recommended member
     *
     * @return collection
     */
    public function getRecommendMembers()
    {
        $members = Vip::leftjoin('member_tip', 'member_tip.member_id', '=', 'member_vip.member_id')
            ->where('active', 1)
            ->where('expiry', '!=', '0000-00-00 00:00:00')
            ->where(function($query){
                ///成為VIP超過三個月
                $query->where('member_vip.created_at', '<', \Carbon\Carbon::now()->subMonths(3))
                ->orWhere(function($query){
                    //或成為VIP超過一個月且有使用車馬費邀請過
                    $query->where('member_vip.created_at', '<', \Carbon\Carbon::now()->subMonths(1));
                });
            });
        return $members->get();
    }

    /**
     * Is recommend member
     *
     * @param int id
     *
     * @return bool
     */
    public function isRecommendMember($id)
    {
        $member = Vip::leftjoin('member_tip', 'member_tip.member_id', '=', 'member_vip.member_id')
            ->where('member_vip.member_id', $id)
            ->where('active', 1)
            ->where('expiry', '!=', '0000-00-00 00:00:00')
            ->where(function($query){
                ///成為VIP超過三個月
                $query->where('member_vip.created_at', '<', \Carbon\Carbon::now()->subMonths(3))
                ->orWhere(function($query){
                    //或成為VIP超過一個月且有使用車馬費邀請過
                    $query->where('member_vip.created_at', '<', \Carbon\Carbon::now()->subMonths(1));
                });
            });

        return $member->first() ? true : false;
    }
    /**
     * Grouping male member
     *
     * @param array users 
     *
     * @return array The keys are 'normal', 'vip', 'recommend'
     */
    public function groupingMale($userIds)
    {
        $results = array('Recommend'=>array(), 'Vip'=>array(), 'Normal'=>array());
        foreach($userIds as $id)
        {
            $isVip = Vip::select('active')->where('member_id', $id)->where('active', 1)->orderBy('created_at', 'desc')->first();

            if($this->isRecommendMember($id))
            {
                array_push($results['Recommend'], $id);
            }
            else if($isVip)
            {
                array_push($results['Vip'], $id);
            }
            else
            {
                array_push($results['Normal'], $id);
            }
        }
        return $results;
    }

    /**
     * 日期區間內, 所有男 or 女會員發送的訊息
     *
     * @param int gender
     * @param date start
     * @param date end
     *
     * @return collection
     */
    public function selectMessagesByGender($start, $end, $gender)
    {
        $query = Message::leftjoin('users', 'from_id', '=', 'users.id')
            ->where('engroup', $gender)
            ->whereBetween('message.created_at', [$start, $end]);

        return $query->get();
    }
    /**
     * 日期區間內, 男會員被回覆的訊息比
     *
     * @param date start
     * @param date end
     *
     * @return array 
     */
    public function repliedMessagesProportion($start, $end)
    {
        
        $query = Message::join('users', 'from_id', '=', 'users.id')
            ->where('engroup', 1)
            ->whereBetween('message.created_at', [$start, $end]);
        $girl_receive = $query->get()->keyBy('to_id')->keys()->toArray();

        $query = Message::join('users', 'to_id', '=', 'users.id')
            ->where('engroup', 1);
        $girl_reply = $query->get()->keyBy('from_id')->keys()->toArray();
        

        /*判斷是哪種會員*/

        $girl_intersect = array_intersect($girl_receive, $girl_reply);
        $data['girl_reply_ratio'] = count($girl_receive)!=0 ? count($girl_intersect)/count($girl_receive):0;
           
          
        // $messages = $this->selectMessagesByGender($start, $end, 1);
        // $replied = $messages->filter(function($msg){
        //     if($this->beenRepliedMessage($msg->id))
        //         return $msg;
        // });

        $groupingMsg = $messages->pluck('from_id');
        $groupingMsg = $this->groupingMale($groupingMsg);

        
        $groupingReplied = $replied->pluck('from_id');
        $groupingReplied = $this->groupingMale($groupingReplied);
        return ['messages' => $groupingMsg, 'replied' => $groupingReplied];
    }
}
