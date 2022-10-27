<?php
namespace App\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Auth;

class MemberFav extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_fav';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'member_fav_id'
    ];

    public static function fav($member_id, $fav_id)
    {
        $fav = new MemberFav();
        $fav->member_id = $member_id;
        $fav->member_fav_id = $fav_id;
        $fav->save();
    }

    public static function findBySelf($uid)
    {
        return Visited::unique(MemberFav::where([['member_id', $uid],['member_fav_id', '!=', $uid]])->distinct()->orderBy('created_at', 'desc')->get(), "member_fav_id");
    }

    public static function showFav($uid)
    {
        $blocks = Blocked::select('blocked_id')->where('member_id', $uid)->get();
        $bannedUsers = \App\Services\UserService::getBannedId();
        $fav = Visited::unique(
            MemberFav::select('member_fav.*')->from('member_fav')
                ->selectRaw('users.last_login, users.is_hide_online')
                ->selectRaw('IF((select count(*) from member_value_added_service as p where p.member_id=users.id and p.service_name="hideOnline" and p.active=1 and (p.expiry = "0000-00-00 00:00:00" OR p.expiry >= "'.Carbon::now().'") and users.is_hide_online=1)>0 , (select login_time from hide_online_data  where hide_online_data.user_id=users.id and deleted_at is null order by id desc limit 1 ), users.last_login) as last_login_new')
                ->leftJoin('users', 'users.id', 'member_fav.member_fav_id')
                ->leftJoin('hide_online_data', 'hide_online_data.user_id', 'member_fav.member_fav_id')
                ->where('users.accountStatus', 1)
                ->where('users.account_status_admin', 1)
                ->where([['member_fav.member_id', $uid],['member_fav.member_fav_id', '!=', $uid]])
                ->whereNotIn('member_fav.member_fav_id',$blocks)
                ->whereNotIn('member_fav.member_fav_id',$bannedUsers)
                ->distinct()
                ->orderBy('last_login_new' ,'desc')
                ->get(),
            "member_fav_id");
        foreach ($fav as $k => $f) {
            $favUser = \App\Models\User::findById($f->member_fav_id);
            if(isset($favUser)){
                $fav[$k]['name'] = $favUser->name;
                $fav[$k]['pic'] = $favUser->meta_()->pic;
                $fav[$k]['blurry_avatar'] = $favUser->meta_()->blurryAvatar;
                if($fav[$k]['pic']==null||!file_exists('.'.$fav[$k]['pic'])){
                    $fav[$k]['pic'] =($favUser->engroup==1)? '/new/images/male.png':'/new/images/female.png';
                }
                $fav[$k]['pic_blur'] = $favUser->meta_()->pic_blur;
                $fav[$k]['age'] = $favUser->meta_()->age();
                if(isset($favUser->meta_()->city)){
                    $favUser->city = explode(",",$favUser->meta_()->city);
                    $favUser->area = explode(",",$favUser->meta_()->area);
                }
                $fav[$k]['city'] = (!empty($favUser->city))?$favUser->city:'';
                $fav[$k]['area'] = (!empty($favUser->area) && $favUser->meta_()->isHideArea == '0')?$favUser->area:'';
                $fav[$k]['vip'] = $favUser->isVipOrIsVvip();
                $fav[$k]['vvip'] = $favUser->VvipInfoStatus();
                $fav[$k]['isblur'] = \App\Services\UserService::isBlurAvatar($favUser, Auth::user());
            }
        }
        return $fav;
    }

    public function age($birthdate) {
        if (isset($birthdate) && $birthdate !== null && $birthdate != 'NULL')
        {
            $userDob = $birthdate;
            $dob = new DateTime($userDob);

            $now = new DateTime();

            $difference = $now->diff($dob);

            $age = $difference->y;
            return $age;
        }
        return 0;
    }

    public static function remove($member_id, $fav_id)
    {
        if($fav_id!='all'){
            $fav = MemberFav::where('member_id', $member_id)->where('member_fav_id', $fav_id)->get()->first();
            if(isset($fav)){
                $fav->delete();
            }
        }
        else{
            MemberFav::where('member_id', $member_id)->delete();
        }
    }
}
