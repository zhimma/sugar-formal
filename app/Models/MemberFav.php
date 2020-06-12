<?php
namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

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
        $fav = Visited::unique(MemberFav::where([['member_id', $uid],['member_fav_id', '!=', $uid]])->whereNotIn('member_fav_id',$blocks)->whereNotIn('member_fav_id',$bannedUsers)->distinct()->orderBy('created_at', 'desc')->get(), "member_fav_id");
        foreach ($fav as $k => $f) {
            $favUser = \App\Models\User::findById($f->member_fav_id);
            if(isset($favUser)){
                $fav[$k]['name'] = $favUser->name;
                $fav[$k]['pic'] = $favUser->meta_()->pic;
                if($fav[$k]['pic']==null||!file_exists('.'.$fav[$k]['pic'])){
                    $fav[$k]['pic'] =($favUser->engroup==1)? '/new/images/male.png':'/new/images/female.png';
                }
                $fav[$k]['age'] = $favUser->meta_()->age();
                if(isset($favUser->meta_()->city)){
                    $favUser->city = explode(",",$favUser->meta_()->city);
                    $favUser->area = explode(",",$favUser->meta_()->area);
                }
                $fav[$k]['city'] = (!empty($favUser->city))?$favUser->city:'';
                $fav[$k]['area'] = (!empty($favUser->area))?$favUser->area:'';
                $fav[$k]['vip'] = $favUser->isVip();
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
