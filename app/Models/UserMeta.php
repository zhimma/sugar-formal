<?php

namespace App\Models;

use App\Models\Blocked as blocked;
use App\Models\SimpleTables\banned_users;
use App\Models\SimpleTables\warned_users;
use App\Services\ImagesCompareService;
use App\Services\SearchIgnoreService;
use Carbon\Carbon;
use Datetime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserMeta extends Model
{
    use HasFactory;

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
        'available_time',
        'family_situation',
        'isHideCup',
        'body',
        'about',
        'style',
        'situation',
        'occupation',
        'education',
        'marriage',
        'is_pure_dating',
        'is_dating_other_county',
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
        'phone',
        'budget_per_month_max',
        'budget_per_month_min',
        'transport_fare_max',
        'transport_fare_min',
        'pic_blur',
    ];

    /*
    |--------------------------------------------------------------------------
    | relationships
    |--------------------------------------------------------------------------
    */

    public static function uploadUserHeader($uid, $fieldContent) {
        return DB::table('user_meta')->where('user_id', $uid)->update(['pic' => $fieldContent]);
    }

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
                                  $isBlocked = 2,
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
                                  $isAdvanceAuth = null,
                                  $tattoo = null,
                                  $city2 = null,
                                  $area2 = null,
                                  $city3 = null,
                                  $area3 = null,
                                  $weight = '',
                                  $registered_from_mobile = 0
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
            $area3,
            $weight,
            $registered_from_mobile
            ){

            if ($registered_from_mobile == 1) {
                $query->select('*');
            } else {
                $query->select('*')->where('user_meta.birthdate', '<', Carbon::now()->subYears(18));
            }

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

                    if ($city3) {
                        $q->orWhere(function ($qq) use ($city3, $area3) {
                            $qq->where('city', 'like', '%' . $city3 . '%');
                            if ($area3) {
                                $qq->where('area', 'like', '%' . $area3 . '%');
                            }
                        });
                    }

                });
            }

            if (isset($cup) && $cup!=''){
                if(count($cup) > 0){
                    $query->whereIn('cup', $cup)->where('isHideCup', 0);
                }
            }
            if (isset($agefrom) && isset($ageto) && strlen($agefrom) != 0 && strlen($ageto) != 0) {
                $agefrom = $agefrom < 18 ? 18 : $agefrom;
                $to = Carbon::now()->subYears($ageto + 1)->addDay(1)->format('Y-m-d');
                $from = Carbon::now()->subYears($agefrom)->format('Y-m-d');
                // 單純使用 whereBetween('birthdate', ... 的話會導致部分生日判斷錯誤
                $query->whereBetween(\DB::raw("STR_TO_DATE(birthdate, '%Y-%m-%d')"), [$to, $from]);
            }

            if (isset($weight) && strlen($weight) != 0) $query->where('weight', $weight)->where('isHideWeight', 0);
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
            //if (isset($education) && strlen($education) != 0) $query->where('education', $education);
            if (isset($education) && $education != ''){
                if(count($education) > 0){
                    $query->whereIn('education', $education);
                }
            }

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
                ->where('registered_from_mobile', $registered_from_mobile)
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
                ->where('registered_from_mobile', $registered_from_mobile)
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

        if($isBlocked==2 && $userIsVip){
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

    public static function searchApi($request)
    {
        //Log::Info($request->all()); //純測試用
        // $time_start = microtime(true);
        if (!$request->user) {
            $request->user = auth()->user();
            if (!$request->user) {
                return response()->json(['error' => '?'], 401);
            }
        }
        $city = $request->city;
        $area = $request->area;
        $cup = $request->cup;
        $marriage = $request->marriage;
        $budget = $request->budget;
        $income = $request->income;
        $smoking = $request->smoking;
        $drinking = $request->drinking;
        $pic = $request->pic;
        $agefrom = $request->agefrom;
        $ageto = $request->ageto;
        $engroup =  $request->user['engroup'];
        // $blockcity = $request->umeta['city'];
        // $blockarea = $request->umeta['area'];
        // $blockdomain = $request->umeta['blockdomain'];
        // $blockdomainType = $request->umeta['blockdomainType'];
        $seqtime = $request->seqtime;
        $body = $request->body;
        $userid = $request->user['id'];
        $exchange_period = $request->exchange_period ?? '';
        $isBlocked = $request->isBlocked ?? 2;
        $userIsVip = $request->userIsVip ?? '';
        $heightfrom = $request->heightfrom ?? '';
        $heightto = $request->heightto ?? '';
        $weightfrom = $request->weightfrom ?? '';
        $weightto = $request->weightto ?? '';
        $prRange_none = $request->prRange_none ?? '';
        $prRange = $request->prRange ?? '';
        $situation = $request->situation ?? '';
        $education = $request->education ?? '';
        $isVip = $request->isVip ?? '';
        $isWarned = $request->isWarned ?? 0;
        $isPhoneAuth = $request->isPhoneAuth ?? '';
        $isAdvanceAuth = $request->isAdvanceAuth ?? null;
        $page = $request->page;
        $tattoo = $request->tattoo ?? null;
        $city2 = $request->city2 ?? null;
        $area2 = $request->area2 ?? null;
        $city3 = $request->city3 ?? null;
        $area3 = $request->area3 ?? null;
        $city4 = $request->city4 ?? null;
        $area4 = $request->area4 ?? null;
        $city5 = $request->city5 ?? null;
        $area5 = $request->area5 ?? null;
        // 新增體重
        $weight = $request->weight ?? '';
        // 是否想進一步發展
        $is_pure_dating = $request->is_pure_dating ?? null;
        // 是否接受約外縣市
        $is_dating_other_county = $request->is_dating_other_county ?? null;
        $relationship_status = $request->relationship_status ?? false;
        $search_tag = $request->search_tag ?? false;

        $xref_option_search_switch = false;

        //如果xref type有在搜尋選項裡就開啟
        //type於option_type資料表內
        if ($relationship_status) {
            $xref_option_search_switch = true;
        }
        if ($search_tag) {
            $xref_option_search_switch = true;
        }

        if ($engroup == 1) {
            $engroup = 2;
        } else if ($engroup == 2) {
            $engroup = 1;
        }
        if (isset($seqtime) && $seqtime == 2) {
            $orderBy = 'users.created_at';
        } else {
            $orderBy = 'last_login';
        }

        $constraint = function ($query) use (
            $city,
            $area,
            $cup,
            $agefrom,
            $ageto,
            $marriage,
            $is_pure_dating,
            $is_dating_other_county,
            $budget,
            $income,
            $smoking,
            $drinking,
            $pic,
            $body,
            $userid,
            $userIsVip,
            $heightfrom,
            $heightto,
            $weightfrom,
            $weightto,
            $situation,
            $education,
            $isWarned,
            $city2,
            $area2,
            $city3,
            $area3,
            $city4,
            $area4,
            $city5,
            $area5,
            $weight){
            $query->select('*')->where('user_meta.birthdate', '<', Carbon::now()->subYears(18));
            if($city || $city2 || $city3 || $city4 || $city5) {
                $query->where(function($q) use ($city,$city2,$city3,$city4,$city5,$area,$area2,$area3,$area4,$area5) {
                    if($city) {
                        $q->orWhere(function($qq) use ($city,$area) {
                            if($area) {
                                $qq->whereRaw('SUBSTRING_INDEX(city,",", 1) like "%'.$city.'%" AND SUBSTRING_INDEX(area,",", 1) like "%'.$area.'%"');
                            }else{
                                $qq->where('city','like','%'.$city.'%');
                            }
                        });
                        $q->orWhere(function($qq) use ($city,$area) {
                            if($area) {
                                $qq->whereRaw('SUBSTRING_INDEX(SUBSTRING_INDEX(city,",", 2),",",-1) like "%'.$city.'%" AND SUBSTRING_INDEX(SUBSTRING_INDEX(area,",", 2),",",-1) like "%'.$area.'%"');
                            }else{
                                $qq->where('city','like','%'.$city.'%');
                            }
                        });
                        $q->orWhere(function($qq) use ($city,$area) {
                            if($area) {
                                $qq->whereRaw('SUBSTRING_INDEX(city,",", -1) like "%'.$city.'%" AND SUBSTRING_INDEX(area,",", -1) like "%'.$area.'%"');
                            }else{
                                $qq->where('city','like','%'.$city.'%');
                            }
                        });
                    }

                    if($city2) {
                        $q->orWhere(function($qq) use ($city2,$area2) {
                            if($area2) {
                                $qq->whereRaw('SUBSTRING_INDEX(city,",", 1) like "%'.$city2.'%" AND SUBSTRING_INDEX(area,",", 1) like "%'.$area2.'%"');
                            }else{
                                $qq->where('city','like','%'.$city2.'%');
                            }
                        });
                        $q->orWhere(function($qq) use ($city2,$area2) {
                            if($area2) {
                                $qq->whereRaw('SUBSTRING_INDEX(SUBSTRING_INDEX(city,",", 2),",",-1) like "%'.$city2.'%" AND SUBSTRING_INDEX(SUBSTRING_INDEX(area,",", 2),",",-1) like "%'.$area2.'%"');
                            }else{
                                $qq->where('city','like','%'.$city2.'%');
                            }
                        });
                        $q->orWhere(function($qq) use ($city2,$area2) {
                            if($area2) {
                                $qq->whereRaw('SUBSTRING_INDEX(city,",", -1) like "%'.$city2.'%" AND SUBSTRING_INDEX(area,",", -1) like "%'.$area2.'%"');
                            }else{
                                $qq->where('city','like','%'.$city2.'%');
                            }
                        });
                    }

                    if($city3) {
                        $q->orWhere(function($qq) use ($city3,$area3) {
                            if($area3) {
                                $qq->whereRaw('SUBSTRING_INDEX(city,",", 1) like "%'.$city3.'%" AND SUBSTRING_INDEX(area,",", 1) like "%'.$area3.'%"');
                            }else{
                                $qq->where('city','like','%'.$city3.'%');
                            }
                        });
                        $q->orWhere(function($qq) use ($city3,$area3) {
                            if($area3) {
                                $qq->whereRaw('SUBSTRING_INDEX(SUBSTRING_INDEX(city,",", 2),",",-1) like "%'.$city3.'%" AND SUBSTRING_INDEX(SUBSTRING_INDEX(area,",", 2),",",-1) like "%'.$area3.'%"');
                            } else {
                                $qq->where('city', 'like', '%' . $city3 . '%');
                            }
                        });
                        $q->orWhere(function ($qq) use ($city3, $area3) {
                            if ($area3) {
                                $qq->whereRaw('SUBSTRING_INDEX(city,",", -1) like "%' . $city3 . '%" AND SUBSTRING_INDEX(area,",", -1) like "%' . $area3 . '%"');
                            } else {
                                $qq->where('city', 'like', '%' . $city3 . '%');
                            }
                        });
                    }

                    if ($city4) {
                        $q->orWhere(function ($qq) use ($city4, $area4) {
                            if ($area4) {
                                $qq->whereRaw('SUBSTRING_INDEX(city,",", 1) like "%' . $city4 . '%" AND SUBSTRING_INDEX(area,",", 1) like "%' . $area4 . '%"');
                            } else {
                                $qq->where('city', 'like', '%' . $city4 . '%');
                            }
                        });
                        $q->orWhere(function ($qq) use ($city4,$area4) {
                            if($area4) {
                                $qq->whereRaw('SUBSTRING_INDEX(SUBSTRING_INDEX(city,",", 2),",",-1) like "%'.$city4.'%" AND SUBSTRING_INDEX(SUBSTRING_INDEX(area,",", 2),",",-1) like "%'.$area4.'%"');
                            } else {
                                $qq->where('city', 'like', '%' . $city4 . '%');
                            }
                        });
                        $q->orWhere(function ($qq) use ($city4, $area4) {
                            if ($area4) {
                                $qq->whereRaw('SUBSTRING_INDEX(city,",", -1) like "%' . $city4 . '%" AND SUBSTRING_INDEX(area,",", -1) like "%' . $area4 . '%"');
                            } else {
                                $qq->where('city', 'like', '%' . $city4 . '%');
                            }
                        });
                    }

                    if ($city5) {
                        $q->orWhere(function ($qq) use ($city5, $area5) {
                            if ($area5) {
                                $qq->whereRaw('SUBSTRING_INDEX(city,",", 1) like "%' . $city5 . '%" AND SUBSTRING_INDEX(area,",", 1) like "%' . $area5 . '%"');
                            } else {
                                $qq->where('city', 'like', '%' . $city5 . '%');
                            }
                        });
                        $q->orWhere(function ($qq) use ($city5,$area5) {
                            if($area5) {
                                $qq->whereRaw('SUBSTRING_INDEX(SUBSTRING_INDEX(city,",", 2),",",-1) like "%'.$city5.'%" AND SUBSTRING_INDEX(SUBSTRING_INDEX(area,",", 2),",",-1) like "%'.$area5.'%"');
                            }else{
                                $qq->where('city','like','%'.$city5.'%');
                            }
                        });
                        $q->orWhere(function($qq) use ($city5,$area5) {
                            if($area5) {
                                $qq->whereRaw('SUBSTRING_INDEX(city,",", -1) like "%'.$city5.'%" AND SUBSTRING_INDEX(area,",", -1) like "%'.$area5.'%"');
                            }else{
                                $qq->where('city','like','%'.$city5.'%');
                            }
                        });
                    }
                });
            }

            if (isset($cup) && $cup!=''){
                if(count($cup) > 0){
                    $query->whereIn('cup', $cup)->where('isHideCup', 0);
                }
            }
            if (isset($agefrom) && isset($ageto) && strlen($agefrom) != 0 && strlen($ageto) != 0) {
                $agefrom = $agefrom < 18 ? 18 : $agefrom;
                $to = Carbon::now()->subYears($ageto + 1)->addDay(1)->format('Y-m-d');
                $from = Carbon::now()->subYears($agefrom)->format('Y-m-d');
                // 單純使用 whereBetween('birthdate', ... 的話會導致部分生日判斷錯誤
                $query->whereBetween(\DB::raw("STR_TO_DATE(birthdate, '%Y-%m-%d')"), [$to, $from]);
            }


            if (isset($weight) && strlen($weight) != 0) $query->where('weight', $weight)->where('isHideWeight', 0);
            if (isset($marriage) && strlen($marriage) != 0) $query->where('marriage', $marriage);
            if (isset($is_pure_dating) && strlen($is_pure_dating) != 0)
            {
                if($is_pure_dating=="1") {
                    $query->where('is_pure_dating', 1);
                }
                else if($is_pure_dating=="0") {
                    $query->where(function($query){
                        $query->where('is_pure_dating', 0);
                        $query->orWhereNull('is_pure_dating');
                    });
                }
            }
            if (isset($is_dating_other_county) && strlen($is_dating_other_county) != 0)
            {
                if($is_dating_other_county=="1") {
                    $query->where('is_dating_other_county', 1);
                }
                else if($is_dating_other_county=="0") {
                    $query->where(function($query){
                        $query->where('is_dating_other_county', 0);
                        $query->orWhereNull('is_dating_other_county');
                    });
                }
            }
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
            if (isset($weightfrom) && isset($weightto) && strlen($weightfrom) != 0 && strlen($weightto) != 0) {
                $query->whereBetween('weight', [$weightfrom, $weightto]);
            }
            if (isset($situation) && strlen($situation) != 0) $query->where('situation', $situation);
            //if (isset($education) && strlen($education) != 0) $query->where('education', $education);
            if (isset($education) && $education != ''){
                if(is_array($education) && count($education) > 0){
                    $query->whereIn('education', $education);
                }
            }
            /*
            if($isWarned != 1 && $userIsVip){
                $query->where('isWarned', 0);
            }
            */
            $meta = UserMeta::select('city', 'area')->where('user_id', $userid)->get()->first();
            $user_city = explode(',', $meta->city);
            $user_area = explode(',', $meta->area);
            /* 判斷搜索者的 city 和 area 是否被被搜索者封鎖 */
             //foreach ($user_city as $key => $city) {
                 //$query->whereRaw('(blockarea not LIKE "%' . $city .$user_area[$key]  .'%"  AND blockarea not LIKE "%'.$city.'全區%")');
            //}

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
                                    $query->whereRaw('(blockarea not LIKE "%' . $city .($user_area[$key]??'')  .'%"  AND blockarea not LIKE "%'.$city.'全區%")')
                                    ->whereRaw('LENGTH(blockarea) <> 0');
                                });
                    });
            }

            return $query->where('is_active', 1);
        };

        $xref_constraint = function ($query) use ($relationship_status, $search_tag){
            if($relationship_status)
            {
                //$query->where('option_type', 2)->where('option_id', $relationship_status);
                if (isset($relationship_status) && $relationship_status != ''){
                    if(is_array($relationship_status) && count($relationship_status) > 0){
                        $query->where('option_type', 2)->whereIn('option_id', $relationship_status);
                    }
                }
            }
            if($search_tag)
            {
                if (isset($search_tag) && $search_tag != ''){
                    $query->where(function ($query) use ($search_tag){
                        $type_list = DB::table('option_type')->get();
                        $has_match_tag = false;
                        foreach($type_list as $type_item)
                        {
                            $option_item = DB::table('option_' . $type_item->type_name)->whereIn('option_name', $search_tag)->first();
                            if($option_item ?? false)
                            {
                                $has_match_tag = true;
                                $query->orWhere(function ($query) use ($type_item, $option_item){
                                    $query->where('option_type', $type_item->id)->where('option_id', $option_item->id);
                                });
                            }
                        }
                        //沒有符合的tag時不搜尋出東西
                        if(!$has_match_tag)
                        {
                            $query->where('option_type', 0)->where('option_id', 0);
                        }
                    });
                }
            }
        };

        /**
         * 為加速效能，此三句功能以 subquery 形式在下方被替換，並以註解形式保留以利後續維護。
         * $bannedUsers = \App\Services\UserService::getBannedId();
         * $blockedUsers = blocked::select('blocked_id')->where('member_id',$userid)->get();
         * $isBlockedByUsers = blocked::select('member_id')->where('blocked_id',$userid)->get();
         */
        // 效能調整：Eager Loading
        if($engroup==1) {
            $query = //User::with(['user_meta' => $constraint, 'vip', 'vas', 'aw_relation', 'fa_relation', 'pr_log'])
                User::with([ 'vip', 'vas', 'aw_relation', 'fa_relation', 'pr_log'])
                ->select('*', \DB::raw("IF(is_hide_online = 1, hide_online_time, last_login) as last_login"))
                //->whereHas('user_meta', $constraint)
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
                })
                ;
        }else {
            //$query = User::with(['user_meta' => $constraint, 'vip', 'vas', 'aw_relation', 'fa_relation'])
            $query = User::with(['user_meta', 'vip', 'vas', 'aw_relation', 'fa_relation'])
                ->select('*', \DB::raw("IF(is_hide_online = 1, hide_online_time, last_login) as last_login"))
                //->whereHas('user_meta', $constraint)
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
            if($xref_option_search_switch)
            {
                $query = $query->whereHas('user_options_xref', $xref_constraint);
            }
        }
        if (isset($exchange_period) && $exchange_period != '' && count($exchange_period)>0) {
                $query->whereIn('exchange_period', $exchange_period);
        }

        if($isBlocked==2 && $userIsVip){
            $query->whereNotIn('users.id', function($query) use ($userid){
                // $blockedUsers
                $query->select('blocked_id')
                    ->from(with(new blocked)->getTable())
                    ->where('member_id', $userid);
            });
        }

        if($isWarned !=1 && $userIsVip){
            $query->whereNotIn('users.id', function($query) use ($userid){
                // $blockedUsers
                $query->select('member_id')
                    ->from(with(new warned_users)->getTable())
                    ->where('expire_date','>=',Carbon::now())
                    ->orWhere('expire_date',null);
            });
            
            /*
            $query->where(function($q1){
                $q1->where('isWarned', 0);
                $q1->orWhere(function($q2){
                    $q2->where('isWarned', 1);
                    $q2->where(function($q3){
                        $q3->whereHas('vip');
                        $q3->orWhere(function($q4){
                            $q4->whereHas('working_VvipApplication_list');
                            $q4->whereHas('unexpired_VVIP_vas_list');
                        });
                    });
                    
                });
            }); 
            */
        }
        if ( $prRange != '' && $userIsVip) {
            $pieces = explode('-', $prRange);
            if(is_array($pieces)) {
                // try {
                    $from = (int)$pieces[0];
                    $to = (int)$pieces[1];
                // }
                // catch (\Exception $e) {
                //     logger("prRange: " . $prRange);
                //     $from = 0;
                //     $to = 100;
                // }
                $query->whereIn('users.id', function ($query) use ($from, $to, $prRange_none) {
                    $query->select('user_id')
                        ->from(with(new Pr_log)->getTable())
                        ->where('active', 1)
                        ->whereBetween("pr", [$from, $to]);
                    if($prRange_none != '' && isset($prRange_none)) {
                        $query->orWhere('pr', $prRange_none);
                    }else{
                        $query->where('pr', '<>', '無');
                    }
                    $query->get();
                });
            }

        }else if($prRange_none != ''){
            $query->whereIn('users.id', function ($query) {
                $query->select('user_id')
                    ->from(with(new Pr_log)->getTable())
                    ->where('active', 1)
                    ->where('pr', '無')->get();
            });

        }

        if(isset($isPhoneAuth) && $isPhoneAuth==1 && $userIsVip){
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
            $query->where(function($query){
                $query->whereIn('users.id', function($query){
                    $query->select('member_id')
                        ->from(with(new Vip)->getTable())
                        ->where('active', 1);
                });
                $query->orWhere(function($query){
                    $query->whereIn('users.id', function($query){
                        $query->select('member_id')
                            ->from(with(new ValueAddedService)->getTable())
                            ->where('active', 1)
                            ->where('service_name', 'VVIP')
                            ->where(function($query) {
                                $query->where('expiry', '0000-00-00 00:00:00')
                                    ->orWhere('expiry', '>=', Carbon::now());
                        });
                    });
                    $query->whereIn('users.id', function($query){
                        $query->select('user_id')
                            ->from(with(new VvipApplication)->getTable())
                            ->where('status',1);
                    });
                });
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
        // $time_end = microtime(true);
        if($isWarned !=1 && $userIsVip){
            /*
            $constraintVipWarned = clone $constraint;
            $constraintVVipWarned = clone $constraint;
            $queryVipWarned = clone $query;
            $queryVVipWarned = clone $query;
            $constraintVipWarned->where('isWarned', 1);
            $constraintVVipWarned->where('isWarned', 1);
            if(!($userIsVip && isset($isVip) && $isVip==1)) {
                 $queryVipWarned->whereHas('vip');
                 $queryVVipWarned->where('is_vvip',1);
            }
           
            $constraint->where('isWarned', 0); 
             */
            $query->where(function($q1){
                $q1->whereHas('user_meta',function($q1sub){$q1sub->where('isWarned', 0);});
                $q1->orWhere(function($q2){
                    $q2->whereHas('user_meta',function($q2_1){$q2_1->where('isWarned', 1);});
                    $q2->where(function($q2_2){
                        $q2_2->whereHas('vip');
                        $q2_2->orWhere('is_vvip',1);
                    });
                    
                });
            });             
        }

        //$query->with(['user_meta'=>$constraint])
        $query->whereHas('user_meta',$constraint);
           /* 
        $queryVipWarned->with(['user_meta'=>$constraintVipWarned])
            ->whereHas('user_meta',$constraintVipWarned);
            
        $queryVVipWarned->with(['user_meta'=>$constraintVVipWarned])
            ->whereHas('user_meta',$constraintVVipWarned);
*/
        $page = $page-1;
        $count = $request->perPageCount;
        $start = $page*$count;
      
        $allPageDataCount = $query->count();
        $DataQuery = $query->orderBy($orderBy, 'desc');

        // $execution_time = ($time_end - $time_start);
        // echo '<b>Total Execution Time:</b> '.($execution_time*1000).'Milliseconds';

        $VvipDataQuery = $DataQuery->clone()->where('users.is_vvip',1);
        $NormalDataQuery = $DataQuery->clone()->where('users.is_vvip',0);
        $VvipDataQueryCount = $DataQuery->clone()->where('users.is_vvip',1)->count();

        if($start < $VvipDataQueryCount && ($start + $count) > $VvipDataQueryCount)
        {
            $VvipPageData = $VvipDataQuery->skip($start)->take($VvipDataQueryCount - $start)->get();
            $NormalPageData = $NormalDataQuery->skip(0)->take($count - ($VvipDataQueryCount - $start))->get();
            $singlePageData = $VvipPageData->merge($NormalPageData);
        }
        else if($start < $VvipDataQueryCount)
        {
            $singlePageData = $VvipDataQuery->skip($start)->take($count)->get();
        }
        else
        {
            $singlePageData = $NormalDataQuery->skip($start - $VvipDataQueryCount)->take($count)->get();
        }

        // 隱藏非必要及敏感個人資料
        $singlePageData = $singlePageData->makeHidden([
            'email', 'fa_relation', 'meta', 'registered_from_mobile',
            'engroup_change', 'enstatus', 'password_updated', 'updated_at',
            'created_at', 'vip_record', 'noticeRead', 'isReadManual', 'isReadIntro',
            'is_read_female_manual_part1', 'is_read_female_manual_part2',
            'is_read_female_manual_part3', 'notice_has_new_evaluation', 'login_times',
            'intro_login_times', 'hide_online_hide_time',
            'line_notify_auth_code', 'line_notify_token', 'line_notify_switch',
            'line_notify_alert', 'can_message_alert', 'show_can_message',
            'is_admin_chat_channel_open', 'advance_auth_status', 'advance_auth_time',
            'advance_auth_identity_no', 'advance_auth_identity_encode', 'advance_auth_birth',
            'advance_auth_phone', 'advance_auth_email', 'advance_auth_email_token',
            'advance_auth_email_at'
        ]);


        //$singlePageDataQuery = $DataQuery->skip($start)->take($count);
        //$singlePageData = $singlePageDataQuery->get();
        $singlePageCount = $singlePageData->count();

        $output = array(
            'singlePageData'=> $singlePageData,
            'singlePageCount'=> $singlePageCount,
            'allPageDataCount' => $allPageDataCount
        );
        // dd($output);
        // var_dump($output['singlePageCOunt'], $output['allPageDataCount']);
        return $output;
    }

    public static function findByMemberId($memberId)
    {
        return UserMeta::where('user_id', $memberId)->first();
    }

    /**
     * Perform a search against the model's indexed data.
     *
     * @param string $query
     * @param \Closure $callback
     * @return \Laravel\Scout\Builder
     */
    public static function scoutSearch($query = '', $callback = null)
    {
        return app(\Laravel\Scout\Builder::class, [
            'model' => new static,
            'query' => $query,
            'callback' => $callback,
            'softDelete' => static::usesSoftDelete() && config('scout.soft_delete', false),
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // 包養關係預設值為空是為了避免有的使用者在舊的 view 下出現錯誤

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function age()
    {
        if (isset($this->birthdate) && $this->birthdate !== null && $this->birthdate != 'NULL') {
            $userDob = $this->birthdate;
            $dob = new DateTime($userDob);

            $now = new DateTime();

            $difference = $now->diff($dob);

            $age = $difference->y;
            return $age;
        }
        return 0;
    }
    
    public function isWarned()
    {
        if($this->user->isVipOrIsVvip()) return 0;
        return $this->isWarned;
    }

    public function isAllSet($engroup = 2)
    {
        if ($engroup == 1) {
            //return isset($this->smoking) && isset($this->drinking) && isset($this->marriage) && isset($this->education) && isset($this->about) && isset($this->style) && isset($this->birthdate) && isset($this->budget) && $this->height > 0 && isset($this->area) && isset($this->city) && isset($this->income) && isset($this->assets);
            return isset($this->smoking) && isset($this->drinking) && isset($this->marriage) && isset($this->education) && isset($this->about) && isset($this->style) && isset($this->birthdate) && $this->height > 0 && isset($this->area) && isset($this->city);
        } else {
            return isset($this->smoking) && isset($this->drinking) && isset($this->education) && isset($this->about) && isset($this->style) && isset($this->birthdate) && $this->height > 0 && isset($this->area) && isset($this->city);
        }

    }

    public function returnUnSet()
    {
        $string = '';
        if (!isset($this->smoking)) {
            $string .= '抽菸、';
        }
        if (!isset($this->drinking)) {
            $string .= '喝酒、';
        }
        if (!isset($this->marriage)) {
            $string .= '婚姻、';
        }
        if (!isset($this->education)) {
            $string .= '教育、';
        }
        if (!isset($this->about)) {
            $string .= '關於我、';
        }
        if (!isset($this->style)) {
            $string .= '期待的約會模式、';
        }
        if (!isset($this->birthdate)) {
            $string .= '生日、';
        }
        if (!isset($this->budget)) {
            $string .= '預算、';
        }
        if ($this->height <= 0) {
            $string = $string . '身高、';
        }
        if (!isset($this->area)) {
            $string .= '地區、';
        }
        if (!isset($this->city)) {
            $string .= '縣市、';
        }
        return substr($string, 0, -3) . '未填寫！';
    }

    public function getCompareStatus()
    {
        return ImagesCompareService::getCompareStatusByPic($this->pic);
    }

    public function getCompareEncode()
    {
        return ImagesCompareService::getCompareEncodeByPic($this->pic);
    }

    public function getCompareRsImg()
    {
        return ImagesCompareService::getCompareRsImgByPic($this->pic);

    }

    public function getSameImg()
    {
        return ImagesCompareService::getSameImgByPic($this->pic);

    }

    public function compareImages($encode_by = null, $delay = 0)
    {
        return ImagesCompareService::compareImagesByPic($this->pic, $encode_by, $delay);
    }

    public function isPicFileExists()
    {
        return ImagesCompareService::isFileExistsByPic($this->pic);
    }

    public function isPicNeedCompare()
    {
        return ImagesCompareService::isNeedCompareByEntry($this);
    }

    public function actual_unchecked_rau_modify_pic()
    {
        return $this->hasOne(RealAuthUserModifyPic::class, 'old_pic', 'pic')->whereHas('real_auth_user_modify',function($q){$q->where([['status',0],['apply_status_shot',1]])->whereHas('real_auth_user_apply',function($qq){$qq->where('status',1);});})->latest();
    }
    
    //Vip
    public function vip()
    {
        return $this->hasMany(Vip::class, 'member_id', 'user_id')->where('active', 1)->orderBy('id', 'desc');
    }    
}



