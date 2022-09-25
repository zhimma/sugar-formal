<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Laravel\Scout\Searchable;

class LogUserLogin extends Model
{
    use Searchable;
    
    public $timestamps = false;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'log_user_login';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'cfp_id', 'userAgent', 'ip', 'created_date', 'created_at', 'log_hide'];

    public function setReadOnly() {
        $this->guarded =  ['*'];
    }
	
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

	public function visitor(){
		return $this->belongsTo(Visitor::class, 'visitor_id', 'id');
	}

	public function cfp(){
        return $this->hasMany(CustomFingerPrint::class, 'id', 'cfp_id');
    }
	
	public static function queryOfIpUsedByOtherUserId($ip,$user_id=null,$d=3) {
		if(!$ip) return null;
		$start_date =  \Carbon\Carbon::now()->subDays($d)->format('Y-m-d H:i:s');
		$end_date =  \Carbon\Carbon::now()->format('Y-m-d H:i:s');
		$query = LogUserLogin::where('ip',$ip)
			->where('created_at','>=',$start_date)
			->where('created_at','<=',$end_date);
		if($user_id) $query->where('user_id','<>',$user_id);
		return $query;
	}
	
	public static function isIpUsedByOtherUserId($ip,$user_id=null,$d=3) {
		$query = LogUserLogin::queryOfIpUsedByOtherUserId($ip,$user_id,$d);
		
		return $query->count()?true:false;		
	}
	
	public static function queryOfCfpIdUsedByOtherUserId($cfp_id,$user_id=null) {
		if(!$cfp_id) return null;
		$query = LogUserLogin::where('cfp_id',$cfp_id);
		if($user_id) $query->where('user_id','<>',$user_id);
		return $query;
	}
	
	public static function isCfpIdUsedByOtherUserId($cfp_id,$user_id=null) {
		$query = LogUserLogin::queryOfCfpIdUsedByOtherUserId($cfp_id,$user_id);
		return $query->count()?true:false;
	}
	
	public function isCfpIdExist() {
		return LogUserLogin::isCfpIdUsedByOtherUserId($this->cfp_id,$this->user_id);
	}	

	public static function countOfUser($user_id) {
		return LogUserLogin::where('user_id', $user_id)->count();
	}	

	public static function recordLoginData($user, $cfp_hash) 
	{
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
        $ip = $_SERVER['REMOTE_ADDR'];
		$now_time = Carbon::now();

		//更新最後登入時間
		$user->last_login = $now_time;

		//更新登入次數
		$user->login_times = $user->login_times +1;

		$user->save();

		if($cfp_hash && strlen($cfp_hash) == 50)
        {
            $cfp = \App\Services\UserService::checkcfp($cfp_hash, $user->id);
            //新增登入紀錄
            $logUserLogin = LogUserLogin::create([
                    'user_id' => $user->id,
                    'cfp_id' => $cfp->id,
                    'userAgent' => $userAgent,
                    'ip' => $ip,
                    'created_date' =>  $now_time->toDateString(),
                    'created_at' =>  $now_time]
            );
        }
        else
        {
            $logUserLogin = LogUserLogin::create([
                    'user_id' => $user->id,
                    'userAgent' => $userAgent,
                    'ip' => $ip,
                    'created_date' =>  $now_time->toDateString(),
                    'created_at' =>  $now_time]
            );
        }
        try
        {
            $country = null;
            // 先檢查 IP 是否有記錄
            $ip_record = LogUserLogin::where('ip', $ip)->first();
            if($ip_record && $ip_record->country && $ip_record->country != "??")
            {
                $country = $ip_record->country;
            }
            // 否則從 API 查詢
            else
            {
                $client = new \GuzzleHttp\Client();
                $response = $client->get('http://ipinfo.io/' . $ip . '?token=27fc624e833728');
                $content = json_decode($response->getBody());
                if(isset($content->country))
                {
                    $country = $content->country;
                }
                else
                {
                    $country = "??";
                }
            }

            if(isset($country))
            {
                $logUserLogin->country = $country;
                $logUserLogin->save();
                $whiteList = [
                    "pig820827@yahoo.com.tw",
                    "henyanyilily@gmail.com",
                    "chenyanyilily@gmail.com",
                    "sa83109@gmail.com",
                    "frebert456@gmail.com",
                    "sagitwang@gmail.com",
                    "nathan7720757@gmail.com",
                ];
                if(!in_array($user->email, $whiteList))
                {
                    if($country != "TW" && $country != "??") 
                    {
                        logger("None TW login, user id: " . $user->id);
                        //if($event->user->engroup == 2)
                        //{
                        //    Auth::logout();
                        //    return back()->withErrors('Forbidden.');
                        //}
                    }
                }
            }
        }
        catch (\Exception $e)
        {
            logger($e);
        }
    }
}
