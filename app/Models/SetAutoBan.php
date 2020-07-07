<?php

namespace App\Models;

use App\Models\User;
use App\Models\UserMeta;
use App\Models\Message;
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
    public static function auto_ban_old2($uid)
    {
        $auto_ban = SetAutoBan::select('type', 'set_ban', 'id', 'content')->orderBy('id', 'desc')->get();
        // Log::info('SetAutoBan::select data ' . $auto_ban);
        foreach ($auto_ban as $ban_set) {
            // Log::info('SetAutoBan::select foreach ' . $ban_set->type);
            switch ($ban_set->type) {
            	case 'name':
            		$udate = User::where('id', $uid)->where('name','like','%'.$ban_set->content.'%')->get()->first();
            		break;
            	case 'email':
            		$udate = User::where('id', $uid)->where('email','like','%'.$ban_set->content.'%')->get()->first();
            		break;
            	case 'title':
            		$udate = User::where('id', $uid)->where('title','like','%'.$ban_set->content.'%')->get()->first();
            		break;
            	case 'about':
            		$udate = UserMeta::where('user_id',$uid)->where('about','like','%'.$ban_set->content.'%')->get()->first();
            		break;
            	case 'style':
            		$udate = UserMeta::where('user_id',$uid)->where('style','like','%'.$ban_set->content.'%')->get()->first();
            		break;
            	default:
            		break;
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

    //自動封鎖 用後台設定的關鍵字查詢
    public static function auto_ban($uid)
    {
        $auto_ban = SetAutoBan::select('type', 'set_ban', 'id', 'content')->orderBy('id', 'desc')->get();
        foreach ($auto_ban as $ban_set) {
            $content = $ban_set->content;
            $violation = false;
            switch ($ban_set->type) {
                case 'name':
                    if(User::where('id', $uid)->where('name','like','%'.$content.'%')->first() != null) $violation = true;
                    break;
                case 'email':
                    if(User::where('id', $uid)->where('email','like','%'.$content.'%')->first() != null) $violation = true;
                    break;
                case 'title':
                    if(User::where('id', $uid)->where('title','like','%'.$content.'%')->first() != null) $violation = true;
                    break;
                case 'about':
                    if(UserMeta::where('user_id',$uid)->where('about','like','%'.$content.'%')->first() != null) $violation = true;
                    break;
                case 'style':
                    if(UserMeta::where('user_id',$uid)->where('style','like','%'.$content.'%')->first() != null) $violation = true;
                    break;
                case 'allcheck':
                    //全檢查判斷user與user_meta的內容 若有一個違規 就設定true
                    if( (User::where('id', $uid)->where(function($query)use($content){
                            $query->where('name', 'like', '%'.$content.'%')
                            ->orwhere('email', 'like', '%'.$content.'%')
                            ->orwhere('title', 'like', '%'.$content.'%');
                        })->first() != null ) 
                        OR (UserMeta::where('user_id', $uid)->where(function($query)use($content){
                            $query->where('about', 'like', '%'.$content.'%')
                            ->orwhere('style', 'like', '%'.$content.'%');
                        })->first() != null ) ){
                        $violation = true;
                    }
                    break;
                default:
                    break;
            }

            if($violation){
                // Log::info('ban_set->set_ban ' . $ban_set->set_ban);
                if($ban_set->set_ban==1){
                    //直接封鎖
                    $userBanned = new banned_users;
                    $userBanned->member_id = $uid;
                    $userBanned->reason = '自動封鎖';
                    $userBanned->save();
                }elseif($ban_set->set_ban==2){
                    //隱性封鎖 新增測試
                    if(BannedUsersImplicitly::where('target', $uid)->first() == null){
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

    //發訊後的自動封鎖
    public static function msg_auto_ban_old($uid, $toid, $msg)
    {
        $auto_ban = SetAutoBan::select('type', 'set_ban', 'id', 'content')->where('type', 'msg')->orderBy('id', 'desc')->get();
        foreach ($auto_ban as $ban_set) {
            $udate = Message::where('from_id', $uid)
            		->where('to_id', $toid)->where('content', $msg)
            		->where('content','like','%'.$ban_set->content.'%')->get()->first();
            if(!empty($udate)){
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

    //發訊後的自動封鎖
    public static function msg_auto_ban($uid, $toid, $msg)
    {
        $auto_ban = SetAutoBan::select('type', 'set_ban', 'id', 'content')->where('type', 'msg')->orwhere('type', 'allcheck')->orderBy('id', 'desc')->get();
        foreach ($auto_ban as $ban_set) {
            $violation = false;
            if(Message::where('from_id', $uid)->where('to_id', $toid)->where('content', $msg)->where('content','like','%'.$ban_set->content.'%')->first() != null){
                $violation = true;
            }
            if($violation){
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
