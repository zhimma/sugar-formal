<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LogAdvAuthApi extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'log_adv_auth_api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = '';

    protected $guarded = ['id'];
    
    public static function isPauseApi() {
        $api_pause_during = config('memadvauth.api.pause_during');    
        $latest_pause_log = LogAdvAuthApi::where('pause_api',1)->orderBy('created_at','DESC')->first();
        if($latest_pause_log) {
            return Carbon::parse($latest_pause_log->created_at)->diffInMinutes(Carbon::now())<$api_pause_during;
        }
        else return false;
    }
    
    public static function getPauseQuery() {
        return LogAdvAuthApi::where('pause_api',1);
    }
    
    public static function getLatestPause() {
        return LogAdvAuthApi::getPauseQuery()->orderBy('created_at','DESC')->first();
    }
    
    public static function getLatestSmallNotify() {
        return LogAdvAuthApi::getPauseQuery()->orwhere('s_notify',1)->orderBy('created_at','DESC')->first();
    }
    
    public static function getLatestLargeNotify() {
        return LogAdvAuthApi::getPauseQuery()->orwhere('l_notify',1)->orderBy('created_at','DESC')->first(); 
    }   

    public static function countInInterval($range,$type=null) {
        $latest_log = null;
        switch(strtolower($range)) {         
            case 'small':
                $latest_log = LogAdvAuthApi::getLatestSmallNotify();
                $inteval = config('memadvauth.api.check.s.interval');
            break;
            case 'large':
                $latest_log = LogAdvAuthApi::getLatestLargeNotify();
                $inteval = config('memadvauth.api.check.l.interval');
            break;
        }
        
        if($type=='pause') {
            $latest_log = LogAdvAuthApi::getLatestPause();
        }
    
        $now_date = date('Y-m-d H:i:s');
        $latest_notify_date = $latest_log?Carbon::parse($latest_log->created_at):null;
        if($latest_notify_date && Carbon::parse($now_date)->subMinutes($inteval)->lt($latest_notify_date)) {
            $start_date = $latest_notify_date;
        }
        else {
            $start_date = Carbon::parse($now_date)->subMinutes($inteval);
        }

        return LogAdvAuthApi::where('created_at','>',$start_date)->count();
    }

}