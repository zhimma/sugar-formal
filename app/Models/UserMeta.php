<?php

namespace App\Models;

use \Datetime;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use App\Models\SimpleTables\banned_users;
use App\Models\Blocked as blocked;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        'notifhistory'
    ];

    /*
    |--------------------------------------------------------------------------
    | relationships
    |--------------------------------------------------------------------------
    */
    
    public function user() 
    {
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

    public function isAllSet()
    {
        return isset($this->smoking) && isset($this->drinking) && isset($this->marriage) && isset($this->education) && isset($this->about) && isset($this->style) && isset($this->birthdate) && isset($this->budget) && $this->height > 0 && isset($this->area) && isset($this->city);
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

    public static function search($city, $area, $cup, $marriage, $budget, $income, $smoking, $drinking, $photo, $agefrom, $ageto, $engroup, $blockcity, $blockarea, $blockdomain, $blockdomainType, $seqtime, $body, $userid)
    {
        if ($engroup == 1)
        {
            $engroup = 2;

        }
        else if ($engroup == 2) { $engroup = 1; }

        $query = UserMeta::where('users.engroup', $engroup)->join('users', 'user_id', '=', 'users.id');

         if (isset($city) && strlen($city) != 0) $query = $query->where('city','like', '%'.$city.'%');
         if (isset($area) && strlen($area) != 0) $query = $query->where('area','like', '%'.$area.'%');
        // if ($engroup == 2){
        //     if (isset($blockcity)){
        //         foreach ($blockcity as $k => $v) {
        //             $nn=$k;
        //             $area = $blockarea[$k];
        //             $query->where(function ($query)use ($v,$area) {
        //                 $query->where(function ($query)use ($v,$area){
        //                     $query->where('blockarea', '<>', $area);
        //                     $query->where('blockcity', '=', $v);
        //                 });
        //                 $query->orWhere('blockcity', '<>', $v);
        //                 $query->orWhere('blockcity', NULL);
        //                 $query->orWhere('blockarea', NULL);
        //             });
        //             //判定全區 不搜尋
        //             $blocked_city_user = UserMeta::select('user_id')->where(['blockcity'=>$v,'blockarea'=>null])->get();
        //             if($blocked_city_user)$query->whereNotIn('user_id', $blocked_city_user);
        //         }
        //     }
        // }
        
//        if ($engroup == 1)
//        {
//            //if (isset($blockdomain) && strlen($blockdomain) != 0) $query->where('blockdomain', '<>', $blockdomain);
//            //if (isset($blockdomainType) && strlen($blockdomainType) != 0) $query->where('blockdomainType', '<>', $blockdomainType);
//        }
        // dd($cup);
        if (isset($cup)&&$cup!=''){
            if(count($cup) > 0){
                $query = $query->whereIn('cup', $cup);
            }
        }
        if (isset($marriage) && strlen($marriage) != 0) $query = $query->where('marriage', $marriage);
        if (isset($budget) && strlen($budget) != 0) $query = $query->where('budget', $budget);
        if (isset($income) && strlen($income) != 0) $query = $query->where('income', $income);
        if (isset($smoking) && strlen($smoking) != 0) $query = $query->where('smoking', $smoking);
        if (isset($drinking) && strlen($drinking) != 0) $query = $query->where('drinking', $drinking);
        if (isset($body)&&$body!=''){
            if(count($body) > 0){
                $query = $query->whereIn('body', $body);
            }
        }
        if (isset($photo) && strlen($photo) != 0) $query = $query->whereNotNull('pic')->where('pic', '<>', 'NULL');
        if (isset($agefrom) && isset($ageto) && strlen($agefrom) != 0 && strlen($ageto) != 0) {
            $agefrom = $agefrom < 18 ? 18 : $agefrom;
            try{
                $query = $query->whereBetween('birthdate', [Carbon::now()->subYears($ageto), Carbon::now()->subYears($agefrom)]);
            }
            catch(\Exception $e){
                Log::info('Searching function exception occurred, user id: ' . $userid . ', $agefrom: ' . $agefrom . ', $ageto: ' . $ageto);
                Log::info('Useragent: ' . $_SERVER['HTTP_USER_AGENT']);
            }
        }

        try{
            $query = $query->where('birthdate', '<', Carbon::now()->subYears(18));
        }
        catch(\Exception $e){
            Log::info('Searching function exception occurred, user id: ' . $userid);
            Log::info('Useragent: ' . $_SERVER['HTTP_USER_AGENT']);
        }

        $bannedUsers = \App\Services\UserService::getBannedId();
        $blockedUsers = blocked::select('blocked_id')->where('member_id',$userid)->get();
        //if($blockedUsers)$query->whereNotIn('user_id', $blockedUsers);
        $beBlockedUsers = blocked::select('member_id')->where('blocked_id',$userid)->get();


        $block = UserMeta::where('users.id', $userid)->join('users', 'user_id', '=', 'users.id')->get()->first();
        $user_city = explode(',',$block->city);
        $user_area = explode(',',$block->area);
       

        /*判斷搜尋的使用者的blockcity, blockarea是否為搜索者的city, area*/
        $block_user = [];
        $user_filter = $query->get();
        foreach($user_filter as $user_filter){
            $block_c = explode(',',$user_filter->blockcity);
            $block_a = explode(',',$user_filter->blockarea);
            if(array_intersect($block_c, $user_city) && array_intersect($block_a, $user_area) ){
                array_push($block_user,$user_filter->user_id);
            }
        }


        if(isset($seqtime) && $seqtime == 2)
            return $query->whereNotIn('user_id', $bannedUsers)->whereNotIn('user_id', $block_user)->whereNotIn('user_id', $blockedUsers)->whereNotIn('user_id', $beBlockedUsers)->orderBy('users.created_at', 'desc')->paginate(12);
        else
            return $query->whereNotIn('user_id', $bannedUsers)->whereNotIn('user_id', $block_user)->whereNotIn('user_id', $blockedUsers)->whereNotIn('user_id', $beBlockedUsers)->orderBy('users.last_login', 'desc')->paginate(12);
    }
    public static function findByMemberId($memberId)
    {
        return UserMeta::where('user_id', $memberId)->first();
    }
}
