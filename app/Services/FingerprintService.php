<?php

namespace App\Services;

use App\Models\Fingerprint;
use App\Models\Fingerprint2;
use App\Models\User;
use App\Observer\BadUserCommon;

class FingerprintService{

	// Fingerprint Model
	public $model;
    
    public function __construct(){
    }

    public static function isExist($fingerprint)
    {
    	$result = Fingerprint::where($fingerprint)->count();
        return $result > 0 ? true : false;
    }

    public function judgeUserFingerprintAll($userId, $fingerprint){
        $isFingerprintBanned = \DB::table('banned_fingerprints')->where('fp', $fingerprint['fp'])->get()->count();
        if($isFingerprintBanned > 0
            && !\DB::table('banned_users_implicitly')->where('target', $userId)->exists()){
             if(\DB::table('banned_users_implicitly')->insert(
                ['fp' => 'DirectlyBanned',
                    'user_id' => '0',
                    'target' => $userId,
                    'created_at' => \Carbon\Carbon::now()]
                )) 
                {
                    BadUserCommon::addRemindMsgFromBadId($userId);
                }
        }
        if(isset($fingerprint['audio'])){ unset($fingerprint['audio']); }
        if(isset($fingerprint['created_at'])){ unset($fingerprint['created_at']); }
        if(isset($fingerprint['batterylevel'])){ unset($fingerprint['batterylevel']); }
        if(isset($fingerprint['enumerateDevices'])){ unset($fingerprint['enumerateDevices']); }
        if(isset($fingerprint['ip'])){ unset($fingerprint['ip']); }
        if(isset($fingerprint['fp'])){ unset($fingerprint['fp']); }
        if(isset($fingerprint['pixelRatio'])){ unset($fingerprint['pixelRatio']); }
        if(isset($fingerprint['_token'])){ unset($fingerprint['_token']); }
        if(isset($fingerprint['user_id'])){ unset($fingerprint['user_id']); }
        if(isset($fingerprint['email'])){ unset($fingerprint['email']); }
        if(isset($fingerprint['password'])){ unset($fingerprint['password']); }
        $result = Fingerprint2::join('banned_users', 'banned_users.member_id', '=', 'fingerprint2.user_id')
            ->where('banned_users.expire_date', null)
            ->where($fingerprint)->get()->first();
        /*$final_result = array();
        foreach($result as $r){
            if($r->user_id != $userId){
                array_push($final_result, $r);
            }
        }*/
        if($result
            && !\DB::table('expected_banning_users')->where('target', $userId)->exists()
            && !User::isBanned($userId)){
            // $ids = array_map(function ($array) { return $array->user_id; }, $final_result);
            \DB::table('expected_banning_users')->insert(
                ['fp' => $result->fp,
                 'user_id' => $result->user_id,
                 'target' => $userId,
                 'created_at' => \Carbon\Carbon::now()]
            );
        }
    }

    public function judgeUserFingerprintCanvasOnly($userId, $fingerprint){
        $result = Fingerprint2::join('banned_users', 'banned_users.member_id', '=', 'fingerprint2.user_id')
            ->where('canvas', $fingerprint['canvas'])->get()->first();
        /*$final_result = array();
        foreach($result as $r){
            if($r->user_id != $userId){
                array_push($final_result, $r);
            }
        }*/
        if($result && !User::isBanned($userId)){
            $exist = \DB::table('warning_users')->where('user_id', $userId)->first();
            if(!$exist){
                // $ids = array_map(function ($array) { return $array->user_id; }, $final_result);
                \DB::table('warning_users')->insert(
                    ['user_id' => $result->user_id,
                     'target' => $userId,
                     'created_at' => \Carbon\Carbon::now()]
                );
            }
        }
    }
}

?>