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
        'notifhistory',
        'adminNote'
    ];

    /*
    |--------------------------------------------------------------------------
    | relationships
    |--------------------------------------------------------------------------
    */

    public function user(){
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

    // 包養關係預設值為空是為了避免有的使用者在舊的 view 下出現錯誤
    public static function search($city, $area, $cup, $marriage, $budget, $income, $smoking, $drinking, $photo, $agefrom, $ageto, $engroup, $blockcity, $blockarea, $blockdomain, $blockdomainType, $seqtime, $body, $userid, $exchange_period = '')
    {
        if ($engroup == 1)
        {
            $engroup = 2;

        }
        else if ($engroup == 2) { $engroup = 1; }

        // 效能調整：Lazy Loading
        $query = User::with(array('user_meta' => function($query) use ($city, $area, $cup, $marriage, $budget, $income, $smoking, $drinking, $photo, $agefrom, $ageto, $engroup, $blockcity, $blockarea, $blockdomain, $blockdomainType, $seqtime, $body, $userid, $exchange_period)
        {
            $query->join('users', 'user_id', '=', 'users.id')->where('users.engroup', $engroup);
            if (isset($exchange_period)&&$exchange_period!=''){
                if(count($exchange_period) > 0){
                    //                $query = $query->whereIn('exchange_period', $exchange_period);
                    $query = $query->whereIn('users.exchange_period', $exchange_period)->where('engroup', $engroup);
                }
                if (isset($city) && strlen($city) != 0) $query = $query->where('city','like', '%'.$city.'%');
                if (isset($area) && strlen($area) != 0) $query = $query->where('area','like', '%'.$area.'%');
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
                    // dd(date('Y-01-01', strtotime("-30 year")));
                    try{
                        if(strtotime(Carbon::now()->subYears($ageto))===strtotime(Carbon::now()->subYears($agefrom))){
                            $to = Carbon::now()->subYears($ageto+1)->addDay(1);
                            $from = Carbon::now()->subYears($agefrom);
                            $query = $query->whereBetween('birthdate', [$to, $from]);
                        }else{
                            $to = Carbon::now()->subYears($ageto+1)->addDay(1);
                            $from = Carbon::now()->subYears($agefrom);
                            // dd($to, $from);
                            $query = $query->whereBetween('birthdate', [$to, $from]);

                        }
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
                    $query->whereNotIn('user_id', $bannedUsers)->whereNotIn('user_id', $block_user)->whereNotIn('user_id', $blockedUsers)->whereNotIn('user_id', $beBlockedUsers)->where('is_active', 1)->orderBy('users.created_at', 'desc');
                else
                    $query->whereNotIn('user_id', $bannedUsers)->whereNotIn('user_id', $block_user)->whereNotIn('user_id', $blockedUsers)->whereNotIn('user_id', $beBlockedUsers)->where('is_active', 1)->orderBy('users.last_login', 'desc');
            }
        }))->paginate(12);

        return $query;
    }
    public static function findByMemberId($memberId)
    {
        return UserMeta::where('user_id', $memberId)->first();
    }
}
