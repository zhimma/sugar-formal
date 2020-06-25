<?php

namespace App\Models;

use App\Models\User;
use App\Models\UserMeta;
use App\Models\SimpleTables\banned_users;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SetAutoBan extends Model
{
    //
    protected $table = 'set_auto_ban';

    public static function auto_ban_old($aid)
    {
        //測試自動封鎖
        $user = User::findById($aid);
        $userMeta = UserMeta::where('user_id', 'like', $user->id)->get()->first();
        $auto_ban = DB::table('set_auto_ban')->select('type', 'set_ban', 'id', 'content')
            ->where(function($query)use($user, $userMeta){
                $query->where(['type' => 'name','content' => $user->name])
                ->orWhere(function($query)use($user, $userMeta){
                    $query->where(['type' => 'about','content' => $userMeta->about]);
                })
                ->orWhere(function($query)use($user, $userMeta){
                    $query->where(['type' => 'email','content' => $user->email]);
                })
                ->orWhere(function($query)use($user, $userMeta){
                    $query->where(['type' => 'title','content' => $user->title]);
                })
                ->orWhere(function($query)use($user, $userMeta){
                    $query->where(['type' => 'style','content' => $userMeta->style]);
                });
            })->orderBy('id', 'desc')->first();
        if(!empty($auto_ban)){
            if($auto_ban->set_ban==1){
                //直接封鎖
                $userBanned = new banned_users;
                $userBanned->member_id = $aid;
                $userBanned->reason = '自動封鎖';
                $userBanned->save();
            }elseif($auto_ban->set_ban==2){
                //隱性封鎖 新增測試
                $idch = DB::table('banned_users_implicitly')->where('target', $user->id)->first();
                if(empty($idch)){
                    DB::table('banned_users_implicitly')->insert(
                        ['fp' => 'BannedInUserInfo',
                        'user_id' => 0,
                        'target' => $user->id]
                    );
                }
            }
        }
    }

    //自動封鎖 用後台設定的關鍵字查詢
    public static function auto_ban($uid)
    {
        $auto_ban = SetAutoBan::select('type', 'set_ban', 'id', 'content')->orderBy('id', 'desc')->get();
        // Log::info('SetAutoBan::select data ' . $auto_ban);
        foreach ($auto_ban as $ban_set) {
            // Log::info('SetAutoBan::select foreach ' . $ban_set->type);
            if($ban_set->type=='name'){
                $udate = User::where('id', $uid)->where('name','like','%'.$ban_set->content.'%')->get()->first();
            }elseif ($ban_set->type=='email') {
                $udate = User::where('id', $uid)->where('email','like','%'.$ban_set->content.'%')->get()->first();
            }elseif ($ban_set->type=='title') {
                $udate = User::where('id', $uid)->where('title','like','%'.$ban_set->content.'%')->get()->first();
            }elseif ($ban_set->type=='about') {
                $udate = UserMeta::where('user_id',$uid)->where('about','like','%'.$ban_set->content.'%')->get()->first();
            }elseif ($ban_set->type=='style') {
                $udate = UserMeta::where('user_id',$uid)->where('style','like','%'.$ban_set->content.'%')->get()->first();
            }

            if(!empty($udate)){
                // Log::info('ban_set->set_ban ' . $ban_set->set_ban);
                if($ban_set->set_ban==1){
                    //直接封鎖
                    $userBanned = new banned_users;
                    $userBanned->member_id = $uid;
                    $userBanned->reason = '自動封鎖';
                    $userBanned->save();
                }elseif($ban_set->set_ban==2){
                    //隱性封鎖 新增測試
                    $idch = BannedUsersImplicitly::where('target', $uid)->first();
                    if(empty($idch)){
                        BannedUsersImplicitly::insert(['fp' => 'BannedInUserInfo','user_id' => 0,'target' => $uid]);
                    }
                }elseif($ban_set->set_ban==3){
                    //警示會員
                    UserMeta::where('user_id', $uid)->update(['isWarned' => 1]);
                }
                return;
            }
        }
    }
}
