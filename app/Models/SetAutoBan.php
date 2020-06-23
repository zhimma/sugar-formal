<?php

namespace App\Models;

use App\Models\User;
use App\Models\UserMeta;
use App\Models\BannedUsersImplicitly;
use App\Models\SimpleTables\banned_users;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SetAutoBan extends Model
{
    //
    protected $table = 'set_auto_ban';

    public static function auto_ban($aid)
    {
        //測試自動封鎖
        $user = User::findById($aid);
        $userMeta = UserMeta::where('user_id', 'like', $user->id)->get()->first();
        $auto_ban = SetAutoBan::select('type', 'set_ban', 'id', 'content')
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
                $idch = BannedUsersImplicitly::where('target', $user->id)->first();
                if(empty($idch)){
                    BannedUsersImplicitly::insert(
                        ['fp' => 'BannedInUserInfo',
                        'user_id' => 0,
                        'target' => $user->id]
                    );
                }
            }elseif($auto_ban->set_ban==3){
                //警示會員
                UserMeta::where('user_id', $aid)->update(['isWarned' => 1]);
            }
        }
    }
}
