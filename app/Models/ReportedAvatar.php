<?php

namespace App\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ReportedAvatar extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reported_avatar';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'reporter_id',
        'reported_user_id',
        'pic'
    ];

    public static function report($reporter_id, $reported_user_id, $content = null, $images = null)
    {
        $reported = new ReportedAvatar;
        $reported->reporter_id = $reporter_id;
        $reported->reported_user_id = $reported_user_id;
        $reported->content = $content;

        //上傳檢舉照片
        if($files = $images)
        {
            $images_ary=array();
            foreach ($files as $key => $file) {
                $now = Carbon::now()->format('Ymd');
                $input['imagename'] = $now . rand(100000000,999999999) . '.' . $file->getClientOriginalExtension();

                $rootPath = public_path('/img/ReportedAvatar');
                $tempPath = $rootPath . '/' . substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/';

                if(!is_dir($tempPath)) {
                    File::makeDirectory($tempPath, 0777, true);
                }

                $destinationPath = '/img/ReportedAvatar/'. substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/' . $input['imagename'];

                $pathname = $file->getRealPath();
                $imagesize = getimagesize($pathname);
                $width = $imagesize[0] ?? 1200;
                $height = $imagesize[1] ?? null;

                $img = Image::make($pathname);
                $img->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    // 若圖片較小，則不需放大圖片
                    $constraint->upsize();
                })->save($tempPath . $input['imagename']);

                //整理images
                $images_ary[$key]= $destinationPath;
            }
            $reported->pic = json_encode($images_ary);
        }
        $reported->save();
        event(new \App\Events\CheckWarnedOfReport($reported_user_id));
    }

    public static function findMember($reporter_id, $reported_user_id){
        $query = ReportedAvatar::where('reporter_id', $reporter_id)
                 ->where('reported_user_id', $reported_user_id)
                 ->get();
        if(count($query)){
            return true;
        }
        else{
            return false;
        }
    }
}
