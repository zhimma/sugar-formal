<?php

namespace App\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class Reported extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reported';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'reported_id',
        'pic'
    ];

    /**
     * Find a role by name
     *
     * @param  string $name
     * @return Role
     */
    public static function cntr($uid)
    {
        $reported = Reported::select('id')->where('reported_id', $uid)->count();
        $reported_pic = ReportedPic::select('reported_pic.id')
            ->join('member_pic','member_pic.id','=','reported_pic.reported_pic_id')
            ->where('member_pic.member_id',$uid)
            ->count();
        $reported_avatar = ReportedAvatar::select('id')->where('reported_user_id',$uid)->count();
        $reported_message = Message::select('id')->where('from_id',$uid)->where('isReported',1)->count();
        return $reported + $reported_pic + $reported_avatar + $reported_message;
    }

    public static function report($member_id, $reported_id, $content = null, $images = null)
    {
        $reported = new Reported;
        $reported->member_id = $member_id;
        $reported->reported_id = $reported_id;
        $reported->content = $content;

        //上傳檢舉照片
        if($files = $images)
        {
            $images_ary=array();
            foreach ($files as $key => $file) {
                $now = Carbon::now()->format('Ymd');
                $input['imagename'] = $now . rand(100000000,999999999) . '.' . $file->getClientOriginalExtension();

                $rootPath = public_path('/img/Reported');
                $tempPath = $rootPath . '/' . substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/';

                if(!is_dir($tempPath)) {
                    File::makeDirectory($tempPath, 0777, true);
                }

                $destinationPath = '/img/Reported/'. substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/' . $input['imagename'];

                $img = Image::make($file->getRealPath());
                $img->resize(400, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($tempPath . $input['imagename']);

                //整理images
                $images_ary[$key]= $destinationPath;
            }
            $reported->pic = json_encode($images_ary);
        }
        $reported->save();
    }

    public static function findMember($member_id, $reported_id){
        $query = Reported::where('member_id', $member_id)
                 ->where('reported_id', $reported_id)
                 ->get();
        if(count($query)){
            return true;
        }
        else{
            return false;
        }
    }
}
