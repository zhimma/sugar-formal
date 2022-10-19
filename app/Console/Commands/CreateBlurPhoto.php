<?php
namespace App\Console\Commands;

use App\Models\MemberPic;
use App\Models\User;
use App\Models\UserMeta;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use File;
use Image;


class CreateBlurPhoto extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'CreateBlurPhoto';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '針對會員大頭照＆生活照, 另存模糊照片';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	    parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{

        $getUserMetaList = UserMeta::whereNull('pic_blur')->where('user_id', 15600)->get();
        foreach ($getUserMetaList as $user_meta) {
            DB::beginTransaction();
            try {

                //另存大頭照模糊照片
                $blurPhotoPath_avatar=$this->createBlurPhoto($user_meta->pic);
                $user_meta->pic_blur=$blurPhotoPath_avatar;
                $user_meta->save();

                //另存生活照模糊照片
                $lifePicList=MemberPic::where('member_id', $user_meta->user_id)->whereNull('pic_blur')->get();
                foreach ($lifePicList as $lifePic){
                    $blurPhotoPath_life=$this->createBlurPhoto($lifePic->pic);
                    $lifePic->pic_blur=$blurPhotoPath_life;
                    $lifePic->save();
                }

                $this->info('模糊照片 欄位更新完成。');
                DB::commit();
            } catch (\Exception $e) {
                Log::info('模糊照片檔案建立失敗'.$e->getMessage() .' LINE:'.$e->getLine());
                DB::rollBack();
            }
        }
	}

	public function createBlurPhoto($originPath){

        $blurPhotoPath='';
	    if(!empty($originPath))
	    {
            $pic_path = public_path($originPath);
            if(file_exists($pic_path)){
                $file_name_string_start=strripos($originPath, '/');
                $origin_file_name=str_replace('/', '',substr($originPath, -($file_name_string_start)));
                $origin_file_extension=explode('.',$origin_file_name)[1];

                $uploadDir_Blur= '/img/Blur/Member/'. substr($origin_file_name, 0, 4) .'/'. substr($origin_file_name, 4, 2) .'/'. substr($origin_file_name, 6, 2) .'/';
                $blur_file_name=substr($origin_file_name, 0, 4).substr($origin_file_name, 4, 2). substr($origin_file_name, 6, 2).rand(100000000,999999999).'.'.$origin_file_extension;
                $blurPhotoPath=$uploadDir_Blur.$blur_file_name;


                if(!File::exists(public_path($uploadDir_Blur)))
                    File::makeDirectory(public_path($uploadDir_Blur), 0777, true);

                // 建立圖片實例
                $img = Image::make(public_path($originPath));
                $img->resize(400, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->blur(30)->save(public_path($blurPhotoPath));
            }
        }
        return $blurPhotoPath;
    }
}
