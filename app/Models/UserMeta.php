<?php

namespace App\Models;

use App\Models\SimpleTables\warned_users;
use \Datetime;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use App\Models\SimpleTables\banned_users;
use App\Models\Blocked as blocked;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Pr_log;
use App\Models\Vip;
use App\Services\ImagesCompareService;
use App\Models\SearchIgnore;
use App\Services\SearchIgnoreService;

class UserMeta extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_meta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //如果有做進階驗證，通過驗證的生日資料會同時更新此生日資料欄位
    //如果有做進階驗證，通過驗證的手機資料會同時更新此手機資料欄位
    protected $fillable = [
        'user_id',
        'terms_and_cond',
        'is_active',
        'activation_token',
        'title',
        'city',
        'blockcity',
        'area',
        'blockarea',
        'isHideArea',
        'budget',        
        'birthdate',
        'height',
        'weight',
        'isHideWeight',
        'cup',
        'isHideCup',
        'body',
        'about',
        'style',
        'situation',
        'occupation',
        'education',
        'marriage',
        'drinking',
        'smoking',
        'isHideOccupation',
        'country',
        'memo',
        'pic',
        'domainType',
        'blockdomainType',
        'domain',
        'blockdomain',
        'job',
        'realName',
        'assets',
        'income',
        'notifmessage',
        'notifhistory',
        'adminNote',
        'blurryLifePhoto',
        'blurryAvatar',
        'phone' 
        
    ];

    /*
    |--------------------------------------------------------------------------
    | relationships
    |--------------------------------------------------------------------------
    */

    public function user(){
         return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function users(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function age() {
        if (isset($this->birthdate) && $this->birthdate !== null && $this->birthdate != 'NULL')
        {
            $userDob = $this->birthdate;
            $dob = new DateTime($userDob);

            $now = new DateTime();

            $difference = $now->diff($dob);

            $age = $difference->y;
            return $age;
        }
        return 0;
    }

    public function isAllSet($engroup = 2)
    {
        if($engroup == 1) {
            return isset($this->smoking) && isset($this->drinking) && isset($this->marriage) && isset($this->education) && isset($this->about) && isset($this->style) && isset($this->birthdate) && isset($this->budget) && $this->height > 0 && isset($this->area) && isset($this->city) && isset($this->income) && isset($this->assets);
        }else{
            return isset($this->smoking) && isset($this->drinking) && isset($this->marriage) && isset($this->education) && isset($this->about) && isset($this->style) && isset($this->birthdate) && isset($this->budget) && $this->height > 0 && isset($this->area) && isset($this->city);
        }
        
    }

    public function returnUnSet()
    {
        $string = '';
        if(!isset( $this->smoking)){
            $string .= '抽菸、';
        }
        if(!isset($this->drinking)){
            $string .= '喝酒、';
        }
        if(!isset($this->marriage)){
            $string .= '婚姻、';
        }
        if(!isset($this->education)){
            $string .= '教育、';
        }
        if(!isset($this->about)){
            $string .= '關於我、';
        }
        if(!isset($this->style)){
            $string .= '期待的約會模式、';
        }
        if(!isset($this->birthdate)){
            $string .= '生日、';
        }
        if(!isset($this->budget)){
            $string .= '預算、';
        }
        if($this->height <= 0){
            $string = $string .'身高、';
        }
        if(!isset($this->area)){
            $string .= '地區、';
        }
        if(!isset($this->city)){
            $string .= '縣市、';
        }
        return substr($string, 0, -3).'未填寫！';
    }


    public static function uploadUserHeader($uid, $fieldContent) {
        return DB::table('user_meta')->where('user_id', $uid)->update(['pic' => $fieldContent]);
    }

    // 包養關係預設值為空是為了避免有的使用者在舊的 view 下出現錯誤
    public static function search($city,
                                  $area,
                                  $cup,
                                  $marriage,
                                  $budget,
                                  $income,
                                  $smoking,
                                  $drinking,
                                  $pic,
                                  $agefrom,
                                  $ageto,
                                  $engroup,
                                  $blockcity,
                                  $blockarea,
                                  $blockdomain,
                                  $blockdomainType,
                                  $seqtime,
                                  $body,
                                  $userid,
                                  $exchange_period = '',
                                  $isBlocked = 1,
                                  $userIsVip = '',
                                  $heightfrom = '',
                                  $heightto = '',
                                  $prRange_none = '',
                                  $prRange = '',
                                  $situation = '',
                                  $education = '',
                                  $isVip = '',
                                  $isWarned = 2,
                                  $isPhoneAuth = '',
                                  $isAdvanceAuth=null,
                                  $tattoo=null,
                                    $city2=null,
                                    $area2=null, 
                                    $city3=null,
                                    $area3=null                               
                                  )
    {
        if ($engroup == 1) { $engroup = 2; }
        else if ($engroup == 2) { $engroup = 1; }
        if(isset($seqtime) && $seqtime == 2){ $orderBy = 'users.created_at'; }
        else{ $orderBy = 'last_login'; }
        $constraint = function ($query) use (
            $city,
            $area,
            $cup,
            $agefrom,
            $ageto,
            $marriage,
            $budget,
            $income,
            $smoking,
            $drinking,
            $pic,
            $engroup,
            $blockcity,
            $blockarea,
            $blockdomain,
            $blockdomainType,
            $seqtime, $body,
            $userid,
            $exchange_period,
            $isBlocked,
            $userIsVip,
            $heightfrom,
            $heightto,
            $prRange_none,
            $prRange,
            $situation,
            $education,
            $isVip,
            $isWarned,
            $isPhoneAuth,
            $city2,
            $area2,
            $city3,
            $area3            
            ){
            $query->select('*')->where('user_meta.birthdate', '<', Carbon::now()->subYears(18));      
            
            if($city || $city2 || $city3) {
                $query->where(function($q) use ($city,$city2,$city3,$area,$area2,$area3) {
                    if($city) {
                        $q->orWhere(function($qq) use ($city,$area) {
                            $qq->where('city','like','%'.$city.'%');
                            if($area) {
                                $qq->where('area','like','%'.$area.'%');
                            }
                        });
                    }
                    
                    
                    if($city2) {
                        $q->orWhere(function($qq) use ($city2,$area2) {
                            $qq->where('city','like','%'.$city2.'%');
                            if($area2) {
                                $qq->where('area','like','%'.$area2.'%');
                            }
                        });
                    }

                    if($city3) {
                        $q->orWhere(function($qq) use ($city3,$area3) {
                            $qq->where('city','like','%'.$city3.'%');
                            if($area3) {
                                $qq->where('area','like','%'.$area3.'%');
                            }
                        });
                    }                    
                    
                });
            }
            
            if (isset($cup) && $cup!=''){
                if(count($cup) > 0){
                    $query->whereIn('cup', $cup);
                }
            }
            if (isset($agefrom) && isset($ageto) && strlen($agefrom) != 0 && strlen($ageto) != 0) {
                $agefrom = $agefrom < 18 ? 18 : $agefrom;
                $to = Carbon::now()->subYears($ageto + 1)->addDay(1)->format('Y-m-d');
                $from = Carbon::now()->subYears($agefrom)->format('Y-m-d');
                // 單純使用 whereBetween('birthdate', ... 的話會導致部分生日判斷錯誤
                $query->whereBetween(\DB::raw("STR_TO_DATE(birthdate, '%Y-%m-%d')"), [$to, $from]);
            }


            if (isset($marriage) && strlen($marriage) != 0) $query->where('marriage', $marriage);
            if (isset($budget) && strlen($budget) != 0) $query->where('budget', $budget);
            if (isset($income) && strlen($income) != 0) $query->where('income', $income);
            if (isset($smoking) && strlen($smoking) != 0) $query->where('smoking', $smoking);
            if (isset($drinking) && strlen($drinking) != 0) $query->where('drinking', $drinking);
            if (isset($body) && $body != ''){
                if(count($body) > 0){
                    $query->whereIn('body', $body);
                }
            }
            if (isset($pic) && $pic == 1) $query->whereNotNull('pic');
            //->where('pic', '<>', 'NULL')->where('pic', '<>', '');
            if (isset($heightfrom) && isset($heightto) && strlen($heightfrom) != 0 && strlen($heightto) != 0) {
                $query->whereBetween('height', [$heightfrom, $heightto]);
            }
            if (isset($situation) && strlen($situation) != 0) $query->where('situation', $situation);
            if (isset($education) && strlen($education) != 0) $query->where('education', $education);

            if($isWarned != 2 && $userIsVip){
                $query->where('isWarned', '<>', 1);
            }
            $meta = UserMeta::select('city', 'area')->where('user_id', $userid)->get()->first();
            $user_city = explode(',', $meta->city);
            $user_area = explode(',', $meta->area);
            /* 判斷搜索者的 city 和 area 是否被被搜索者封鎖 */
//            foreach ($user_city as $key => $city) {
//                 $query->whereRaw('(blockarea not LIKE "%' . $city .$user_area[$key]  .'%"  AND blockarea not LIKE "%'.$city.'全區%")');
//            }

            foreach ($user_city as $key => $city){
                $query->where(
                    function ($query) use ($city, $user_area, $key){
                        $query->where(
                        // 未設定封鎖城市地區
                            function ($query) use ($city, $user_area, $key){
                                $query->where(\DB::raw('LENGTH(blockcity) = 0'))
                                    ->where(\DB::raw('LENGTH(blockarea) = 0'));
                            })
                            // 設定封鎖城市地區
                            ->orWhere(
                                function ($query) use ($city, $user_area, $key){
                                    $query->whereRaw('(blockarea not LIKE "%' . $city .$user_area[$key]  .'%"  AND blockarea not LIKE "%'.$city.'全區%")')
                                    ->whereRaw('LENGTH(blockarea) <> 0');
                                });
                    });
            }
            return $query->where('is_active', 1);
        };

        /**
         * 為加速效能，此三句功能以 subquery 形式在下方被替換，並以註解形式保留以利後續維護。
         * $bannedUsers = \App\Services\UserService::getBannedId();
         * $blockedUsers = blocked::select('blocked_id')->where('member_id',$userid)->get();
         * $isBlockedByUsers = blocked::select('member_id')->where('blocked_id',$userid)->get();
         */
        // 效能調整：Eager Loading
        if($engroup==1) {
            $query = User::with(['user_meta' => $constraint, 'vip', 'vas', 'aw_relation', 'fa_relation', 'pr_log'])
                ->select('*', \DB::raw("IF(is_hide_online = 1, hide_online_time, last_login) as last_login"))
                ->whereHas('user_meta', $constraint)
                ->where('engroup', $engroup)
                ->where('accountStatus', 1)
                ->where('account_status_admin', 1)
                ->where('is_hide_online', '<>', 2)
                ->whereNotIn('users.id', function ($query) {
                    // $bannedUsers
                    $query->select('target')
                        ->from(with(new BannedUsersImplicitly)->getTable());
                })
                ->whereNotIn('users.id', function ($query) {
                    // $bannedUsers
                    $query->select('member_id')
                        ->from(with(new banned_users)->getTable());
                });
        }else {
            $query = User::with(['user_meta' => $constraint, 'vip', 'vas', 'aw_relation', 'fa_relation'])
                ->select('*', \DB::raw("IF(is_hide_online = 1, hide_online_time, last_login) as last_login"))
                ->whereHas('user_meta', $constraint)
                ->where('engroup', $engroup)
                ->where('accountStatus', 1)
                ->where('account_status_admin', 1)
                ->where('is_hide_online', '<>', 2)
                ->whereNotIn('users.id', function ($query) {
                    // $bannedUsers
                    $query->select('target')
                        ->from(with(new BannedUsersImplicitly)->getTable());
                })
                ->whereNotIn('users.id', function ($query) {
                    // $bannedUsers
                    $query->select('member_id')
                        ->from(with(new banned_users)->getTable());
                })/*->whereNotIn('users.id', function($query) use ($userid){
                // $blockedUsers
                $query->select('blocked_id')
                    ->from(with(new blocked)->getTable())
                    ->where('member_id', $userid);})
            ->whereNotIn('users.id', function($query) use ($userid){
                // $isBlockedByUsers
                $query->select('member_id')
                    ->from(with(new blocked)->getTable())
                    ->where('blocked_id', $userid);}) */
            ;
        }
        if (isset($exchange_period) && $exchange_period != '' && count($exchange_period)>0) {
                $query->whereIn('exchange_period', $exchange_period);
        }

        if($isBlocked==1 && $userIsVip){
            $query->whereNotIn('users.id', function($query) use ($userid){
                // $blockedUsers
                $query->select('blocked_id')
                    ->from(with(new blocked)->getTable())
                    ->where('member_id', $userid);
            });
        }

        if($isWarned !=2 && $userIsVip){
            $query->whereNotIn('users.id', function($query) use ($userid){
                // $blockedUsers
                $query->select('member_id')
                    ->from(with(new warned_users)->getTable())
                    ->where('expire_date','>=',Carbon::now())
                    ->orWhere('expire_date',null);
            });
        }
        if ( $prRange != '' && $userIsVip) {
            $pieces = explode('-', $prRange);
            if(is_array($pieces)) {
                $from = $pieces[0];
                $to = $pieces[1];
                $query->whereIn('users.id', function ($query) use ($from, $to, $prRange_none) {
                    $query->select('user_id')
                        ->from(with(new Pr_log)->getTable())
                        ->where('active', 1)
                        ->whereBetween(DB::raw("CAST(pr AS INT)"), [$from, $to]);
                    if($prRange_none != '' && isset($prRange_none)) {
                        $query->orWhere('pr', $prRange_none);
                    }else{
                        $query->where('pr', '<>', '無');
                    }

                });
            }

        }else if($prRange_none != ''){
            $query->whereIn('users.id', function ($query) {
                $query->select('user_id')
                    ->from(with(new Pr_log)->getTable())
                    ->where('active', 1)
                    ->where('pr', '無');
            });

        }

        if(isset($isPhoneAuth) && $isPhoneAuth==2 && $userIsVip){
            $query->whereIn('users.id', function($query) use ($userid){
                // $blockedUsers
                $query->select('member_id')
                    ->from('short_message')->where('active',1);
            });
        }

        if($isAdvanceAuth && isset($isAdvanceAuth) && $isAdvanceAuth==1){
                $query->where('users.advance_auth_status',$isAdvanceAuth);
        }
	

        if($userIsVip && isset($isVip) && $isVip==1){
            $query->whereIn('users.id', function($query) use ($userid, $isVip){
                // $blockedUsers
                $query->select('member_id')
                    ->from(with(new Vip)->getTable())
                    ->where('active', $isVip);
            });
        }
        
        if($tattoo==1) {
            $query->has('tattoo');
        }
        else if($tattoo==-1) {
            $query->doesntHave('tattoo');
        }
            
        if($userIsVip) {
            $siService = new SearchIgnoreService(new \App\Services\UserService(new User,new UserMeta));
            $ignore_user_ids = $siService->member_query()->get()->pluck('ignore_id')->all();
            $query->whereNotIn('users.id',$ignore_user_ids);
        }
        return $query->orderBy($orderBy, 'desc')->paginate(12);
    }

    public static function searchApi($city,
                                  $area,
                                  $cup,
                                  $marriage,
                                  $budget,
                                  $income,
                                  $smoking,
                                  $drinking,
                                  $pic,
                                  $agefrom,
                                  $ageto,
                                  $engroup,
                                  $blockcity,
                                  $blockarea,
                                  $blockdomain,
                                  $blockdomainType,
                                  $seqtime,
                                  $body,
                                  $userid,
                                  $exchange_period = '',
                                  $isBlocked = 1,
                                  $userIsVip = '',
                                  $heightfrom = '',
                                  $heightto = '',
                                  $prRange_none = '',
                                  $prRange = '',
                                  $situation = '',
                                  $education = '',
                                  $isVip = '',
                                  $isWarned = 2,
                                  $isPhoneAuth = '',
                                  $isAdvanceAuth=null,
                                  $page,
                                  $tattoo=null,
                                  $city2=null,
                                  $area2=null, 
                                  $city3=null,
                                  $area3=null,
                                  //新增體重
                                  $weight = '',  )
    {
        if ($engroup == 1) { $engroup = 2; }
        else if ($engroup == 2) { $engroup = 1; }
        if(isset($seqtime) && $seqtime == 2){ $orderBy = 'users.created_at'; }
        else{ $orderBy = 'last_login'; }
        $constraint = function ($query) use (
            $city,
            $area,
            $cup,
            $agefrom,
            $ageto,
            $marriage,
            $budget,
            $income,
            $smoking,
            $drinking,
            $pic,
            $engroup,
            $blockcity,
            $blockarea,
            $blockdomain,
            $blockdomainType,
            $seqtime, $body,
            $userid,
            $exchange_period,
            $isBlocked,
            $userIsVip,
            $heightfrom,
            $heightto,
            $prRange_none,
            $prRange,
            $situation,
            $education,
            $isVip,
            $isWarned,
            $isPhoneAuth,
            $city2,
            $area2,
            $city3,
            $area3,
            $weight){
            $query->select('*')->where('user_meta.birthdate', '<', Carbon::now()->subYears(18));
            if($city || $city2 || $city3) {
                $query->where(function($q) use ($city,$city2,$city3,$area,$area2,$area3) {
                    if($city) {
                        $q->orWhere(function($qq) use ($city,$area) {
                            $qq->where('city','like','%'.$city.'%');
                            if($area) {
                                $qq->where('area','like','%'.$area.'%');
                            }
                        });
                    }
                    
                    
                    if($city2) {
                        $q->orWhere(function($qq) use ($city2,$area2) {
                            $qq->where('city','like','%'.$city2.'%');
                            if($area2) {
                                $qq->where('area','like','%'.$area2.'%');
                            }
                        });
                    }

                    if($city3) {
                        $q->orWhere(function($qq) use ($city3,$area3) {
                            $qq->where('city','like','%'.$city3.'%');
                            if($area3) {
                                $qq->where('area','like','%'.$area3.'%');
                            }
                        });
                    }                    
                    
                });
            }            

            if (isset($cup) && $cup!=''){
                if(count($cup) > 0){
                    $query->whereIn('cup', $cup);
                }
            }
            if (isset($agefrom) && isset($ageto) && strlen($agefrom) != 0 && strlen($ageto) != 0) {
                $agefrom = $agefrom < 18 ? 18 : $agefrom;
                $to = Carbon::now()->subYears($ageto + 1)->addDay(1)->format('Y-m-d');
                $from = Carbon::now()->subYears($agefrom)->format('Y-m-d');
                // 單純使用 whereBetween('birthdate', ... 的話會導致部分生日判斷錯誤
                $query->whereBetween(\DB::raw("STR_TO_DATE(birthdate, '%Y-%m-%d')"), [$to, $from]);
            }


            if (isset($weight) && strlen($weight) != 0) $query->where('weight', $weight);
            if (isset($marriage) && strlen($marriage) != 0) $query->where('marriage', $marriage);
            if (isset($budget) && strlen($budget) != 0) $query->where('budget', $budget);
            if (isset($income) && strlen($income) != 0) $query->where('income', $income);
            if (isset($smoking) && strlen($smoking) != 0) $query->where('smoking', $smoking);
            if (isset($drinking) && strlen($drinking) != 0) $query->where('drinking', $drinking);
            if (isset($body) && $body != ''){
                if(count($body) > 0){
                    $query->whereIn('body', $body);
                }
            }
            if (isset($pic) && $pic == 1) $query->whereNotNull('pic');
                //->where('pic', '<>', 'NULL')->where('pic', '<>', '');
            if (isset($heightfrom) && isset($heightto) && strlen($heightfrom) != 0 && strlen($heightto) != 0) {
                $query->whereBetween('height', [$heightfrom, $heightto]);
            }
            if (isset($situation) && strlen($situation) != 0) $query->where('situation', $situation);
            if (isset($education) && strlen($education) != 0) $query->where('education', $education);

            if($isWarned != 2 && $userIsVip){
                $query->where('isWarned', '<>', 1);
            }
            $meta = UserMeta::select('city', 'area')->where('user_id', $userid)->get()->first();
            $user_city = explode(',', $meta->city);
            $user_area = explode(',', $meta->area);
            /* 判斷搜索者的 city 和 area 是否被被搜索者封鎖 */
//            foreach ($user_city as $key => $city) {
//                 $query->whereRaw('(blockarea not LIKE "%' . $city .$user_area[$key]  .'%"  AND blockarea not LIKE "%'.$city.'全區%")');
//            }

            foreach ($user_city as $key => $city){
                $query->where(
                    function ($query) use ($city, $user_area, $key){
                        $query->where(
                        // 未設定封鎖城市地區
                            function ($query) use ($city, $user_area, $key){
                                $query->where(\DB::raw('LENGTH(blockcity) = 0'))
                                    ->where(\DB::raw('LENGTH(blockarea) = 0'));
                            })
                            // 設定封鎖城市地區
                            ->orWhere(
                                function ($query) use ($city, $user_area, $key){
                                    $query->whereRaw('(blockarea not LIKE "%' . $city .$user_area[$key]  .'%"  AND blockarea not LIKE "%'.$city.'全區%")')
                                    ->whereRaw('LENGTH(blockarea) <> 0');
                                });
                    });
            }



            return $query->where('is_active', 1);
        };

        /**
         * 為加速效能，此三句功能以 subquery 形式在下方被替換，並以註解形式保留以利後續維護。
         * $bannedUsers = \App\Services\UserService::getBannedId();
         * $blockedUsers = blocked::select('blocked_id')->where('member_id',$userid)->get();
         * $isBlockedByUsers = blocked::select('member_id')->where('blocked_id',$userid)->get();
         */
        // 效能調整：Eager Loading
        if($engroup==1) {
            $query = User::with(['user_meta' => $constraint, 'vip', 'vas', 'aw_relation', 'fa_relation', 'pr_log'])
                ->select('*', \DB::raw("IF(is_hide_online = 1, hide_online_time, last_login) as last_login"))
                ->whereHas('user_meta', $constraint)
                ->where('engroup', $engroup)
                ->where('accountStatus', 1)
                ->where('account_status_admin', 1)
                ->where('is_hide_online', '<>', 2)
                ->whereNotIn('users.id', function ($query) {
                    // $bannedUsers
                    $query->select('target')
                        ->from(with(new BannedUsersImplicitly)->getTable());
                })
                ->whereNotIn('users.id', function ($query) {
                    // $bannedUsers
                    $query->select('member_id')
                        ->from(with(new banned_users)->getTable());
                });
        }else {
            $query = User::with(['user_meta' => $constraint, 'vip', 'vas', 'aw_relation', 'fa_relation'])
                ->select('*', \DB::raw("IF(is_hide_online = 1, hide_online_time, last_login) as last_login"))
                ->whereHas('user_meta', $constraint)
                ->where('engroup', $engroup)
                ->where('accountStatus', 1)
                ->where('account_status_admin', 1)
                ->where('is_hide_online', '<>', 2)
                ->whereNotIn('users.id', function ($query) {
                    // $bannedUsers
                    $query->select('target')
                        ->from(with(new BannedUsersImplicitly)->getTable());
                })
                ->whereNotIn('users.id', function ($query) {
                    // $bannedUsers
                    $query->select('member_id')
                        ->from(with(new banned_users)->getTable());
                })/*->whereNotIn('users.id', function($query) use ($userid){
                // $blockedUsers
                $query->select('blocked_id')
                    ->from(with(new blocked)->getTable())
                    ->where('member_id', $userid);})
            ->whereNotIn('users.id', function($query) use ($userid){
                // $isBlockedByUsers
                $query->select('member_id')
                    ->from(with(new blocked)->getTable())
                    ->where('blocked_id', $userid);}) */
            ;
        }
        if (isset($exchange_period) && $exchange_period != '' && count($exchange_period)>0) {
                $query->whereIn('exchange_period', $exchange_period);
        }

        if($isBlocked==1 && $userIsVip){
            $query->whereNotIn('users.id', function($query) use ($userid){
                // $blockedUsers
                $query->select('blocked_id')
                    ->from(with(new blocked)->getTable())
                    ->where('member_id', $userid);
            });
        }

        if($isWarned !=2 && $userIsVip){
            $query->whereNotIn('users.id', function($query) use ($userid){
                // $blockedUsers
                $query->select('member_id')
                    ->from(with(new warned_users)->getTable())
                    ->where('expire_date','>=',Carbon::now())
                    ->orWhere('expire_date',null);
            });
        }
        if ( $prRange != '' && $userIsVip) {
            $pieces = explode('-', $prRange);
            if(is_array($pieces)) {
                $from = $pieces[0];
                $to = $pieces[1];
                $query->whereIn('users.id', function ($query) use ($from, $to, $prRange_none) {
                    $query->select('user_id')
                        ->from(with(new Pr_log)->getTable())
                        ->where('active', 1)
                        ->whereBetween(DB::raw("CAST(pr AS INT)"), [$from, $to]);
                    if($prRange_none != '' && isset($prRange_none)) {
                        $query->orWhere('pr', $prRange_none);
                    }else{
                        $query->where('pr', '<>', '無');
                    }

                });
            }

        }else if($prRange_none != ''){
            $query->whereIn('users.id', function ($query) {
                $query->select('user_id')
                    ->from(with(new Pr_log)->getTable())
                    ->where('active', 1)
                    ->where('pr', '無');
            });

        }

        if(isset($isPhoneAuth) && $isPhoneAuth==2 && $userIsVip){
            $query->whereIn('users.id', function($query) use ($userid){
                // $blockedUsers
                $query->select('member_id')
                    ->from('short_message')->where('active',1);
            });
        }

        if($isAdvanceAuth && isset($isAdvanceAuth) && $isAdvanceAuth==1){
                $query->where('users.advance_auth_status',$isAdvanceAuth);
        }
	

        if($userIsVip && isset($isVip) && $isVip==1){
            $query->whereIn('users.id', function($query) use ($userid, $isVip){
                // $blockedUsers
                $query->select('member_id')
                    ->from(with(new Vip)->getTable())
                    ->where('active', $isVip);
            });
        }
    
        if($tattoo==1) {
            $query->has('tattoo');
        }
        else if($tattoo==-1) {
            $query->doesntHave('tattoo');
        }
            
        if($userIsVip) {
            $siService = new SearchIgnoreService(new \App\Services\UserService(new User,new UserMeta));
            $ignore_user_ids = $siService->member_query()->get()->pluck('ignore_id')->all();
            $query->whereNotIn('users.id',$ignore_user_ids);
        }    

        $page = $page-1;
        $count = 12;
        $start = $page*$count;
        $DataQuery = $query->orderBy($orderBy, 'desc');
        $allPageDataCount = $DataQuery->count();
        $singlePageDataQuery = $DataQuery->skip($start)->take($count);

        $singlePageData = $singlePageDataQuery->get();
        $singlePageCount = count($singlePageData);
        
        $output = array(
            'singlePageData'=> $singlePageData,
            'singlePageCount'=> $singlePageCount,
            'allPageDataCount'=>$allPageDataCount 
        );

        return $output;
    }
    
    public static function findByMemberId($memberId)
    {
        return UserMeta::where('user_id', $memberId)->first();
    }
    
    public function getCompareStatus() {
        return ImagesCompareService::getCompareStatusByPic($this->pic);
    }      
    
    public function getCompareEncode() {
        return ImagesCompareService::getCompareEncodeByPic($this->pic);
    }    
    
    public function getCompareRsImg() {
        return ImagesCompareService::getCompareRsImgByPic($this->pic);
 
    }
 
    public function getSameImg() {
        return ImagesCompareService::getSameImgByPic($this->pic);
 
    } 

    public function compareImages($encode_by=null) {
        ImagesCompareService::compareImagesByPic($this->pic,$encode_by);
    }  

    public function isPicFileExists() {
        return ImagesCompareService::isFileExistsByPic($this->pic);
    }
    
    public function isPicNeedCompare() {
        return ImagesCompareService::isNeedCompareByEntry($this);
    }        
}



