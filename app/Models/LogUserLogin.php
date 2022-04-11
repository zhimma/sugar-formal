<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class LogUserLogin extends Model
{
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
    protected $fillable = ['user_id', 'cfp_id', 'visitor_id', 'userAgent', 'ip', 'created_date', 'created_at'];

    public function setReadOnly() {
        $this->guarded =  ['*'];
    }
	
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
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

	public static function queryOfVisitorIdUsedByOtherUserId($cfp_id,$user_id=null) {
		if(!$cfp_id) return null;
		$query = LogUserLogin::where('visitor_id',$cfp_id);
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
}
