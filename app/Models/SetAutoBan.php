<?php

namespace App\Models;

use App\Models\User;
use App\Models\UserMeta;
use App\Models\Message;
use App\Models\SimpleTables\warned_users;
use App\Models\SimpleTables\banned_users;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SetAutoBan extends Model
{
    //
    protected $table = 'set_auto_ban';

    //自動封鎖 用後台設定的關鍵字查詢
    public static function auto_ban($uid)
    {
        $user = User::findById($uid);
        try {
            if(isset($user) && $user->can('admin')){
                return;
            }
        }
        catch (\Exception $e){

        }
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
                Log::info('ban_set type='.$ban_set->set_ban.' id='.$ban_set->id);
                if($ban_set->set_ban==1){
                    //直接封鎖
                    $userBanned = new banned_users;
                    $userBanned->member_id = $uid;
                    $userBanned->reason = "系統原因($ban_set->id)";
                    $userBanned->save();
                }elseif($ban_set->set_ban==2){
                    //隱性封鎖 新增測試
                    if(BannedUsersImplicitly::where('target', $uid)->first() == null){
                        BannedUsersImplicitly::insert(['fp' => 'BannedInUserInfo','user_id' => 0,'target' => $uid]);
                    }
                }elseif($ban_set->set_ban==3){
                    //警示會員
                    $userWarned = new warned_users;
                    $userWarned->member_id = $uid;
                    $userWarned->reason = "系統原因($ban_set->id)";
                    $userWarned->save();
                    // UserMeta::where('user_id', $uid)->update(['isWarned' => 1]);
                }
                return;
            }
        }
    }

    //發訊後的自動封鎖
    public static function msg_auto_ban($uid, $toid, $msg)
    {
        $user = User::findById($uid);
        try {
            if(isset($user) && $user->can('admin')){
                return;
            }
        }
        catch (\Exception $e){

        }
        $auto_ban = SetAutoBan::select('type', 'set_ban', 'id', 'content')->where('type', 'msg')->orwhere('type', 'allcheck')->orderBy('id', 'desc')->get();
        foreach ($auto_ban as $ban_set) {
            $violation = false;
            if (Message::where('from_id', $uid)->where('to_id', $toid)->where('content', $msg)->where('content', 'like', '%' . $ban_set->content . '%')->first() != null) {
                $violation = true;
            }
            if ($violation) {
                if ($ban_set->set_ban == 1) {
                    //直接封鎖
                    $userBanned = new banned_users;
                    $userBanned->member_id = $uid;
                    $userBanned->reason = "系統原因($ban_set->id)";
                    $userBanned->save();
                } elseif ($ban_set->set_ban == 2) {
                    //隱性封鎖 新增測試
                    $idch = BannedUsersImplicitly::where('target', $uid)->first();
                    if (empty($idch)) {
                        BannedUsersImplicitly::insert(['fp' => 'BannedInUserInfo', 'user_id' => 0, 'target' => $uid]);
                    }
                } elseif ($ban_set->set_ban == 3) {
                    //警示會員
                    $userWarned = new warned_users;
                    $userWarned->member_id = $uid;
                    $userWarned->reason = "系統原因($ban_set->id)";
                    $userWarned->save();
                    // UserMeta::where('user_id', $uid)->update(['isWarned' => 1]);
                }
                return;
            }
        }
    }

    //登出後的警示
    public static function logout_warned($uid)
    {
        $user = User::findById($uid);
        try {
            if(isset($user) && $user->can('admin')){
                return;
            }
        }
        catch (\Exception $e){

        }
        $auto_ban = SetAutoBan::select('type', 'set_ban', 'id', 'content')->where('set_ban', '3')->orderBy('id', 'desc')->get();
        foreach ($auto_ban as $ban_set) {
            $content = $ban_set->content;
            $violation = false;
            switch ($ban_set->type) {
                case 'name':
                    if (User::where('id', $uid)->where('name', 'like', '%' . $content . '%')->first() != null) $violation = true;
                    break;
                case 'email':
                    if (User::where('id', $uid)->where('email', 'like', '%' . $content . '%')->first() != null) $violation = true;
                    break;
                case 'title':
                    if (User::where('id', $uid)->where('title', 'like', '%' . $content . '%')->first() != null) $violation = true;
                    break;
                case 'about':
                    if (UserMeta::where('user_id', $uid)->where('about', 'like', '%' . $content . '%')->first() != null) $violation = true;
                    break;
                case 'style':
                    if (UserMeta::where('user_id', $uid)->where('style', 'like', '%' . $content . '%')->first() != null) $violation = true;
                    break;
                case 'allcheck':
                    //全檢查判斷user與user_meta的內容 若有一個違規 就設定true
                    if ((User::where('id', $uid)->where(function ($query) use ($content) {
                                $query->where('name', 'like', '%' . $content . '%')
                                    ->orwhere('title', 'like', '%' . $content . '%');
                            })->first() != null)
                        or (UserMeta::where('user_id', $uid)->where(function ($query) use ($content) {
                                $query->where('about', 'like', '%' . $content . '%')
                                    ->orwhere('style', 'like', '%' . $content . '%');
                            })->first() != null)) {
                        $violation = true;
                    }
                    break;
                default:
                    break;
            }

            if ($violation) {
                // Log::info('ban_set->set_ban ' . $ban_set->set_ban);
                //警示會員
                $userWarned = new warned_users;
                $userWarned->member_id = $uid;
                $userWarned->reason = "系統原因($ban_set->id)";
                $userWarned->save();
                // UserMeta::where('user_id', $uid)->update(['isWarned' => 1]);
                return;
            }
        }
    }
}
