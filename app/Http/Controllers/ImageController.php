<?php

namespace App\Http\Controllers;

use App\Models\AdminDeleteImageLog;
use App\Models\VvipProveImg;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\ImageRequest;
use App\Http\Requests\MultipleImageRequest;
use App\Http\Requests;
use App\Services\ImageService;
use App\Services\RealAuthPageService;
//use App\Services\ImagesCompareService;
use App\Models\User;
use App\Models\MemberPic;
use App\Models\UserMeta;
use App\Models\LogFreeVipPicAct;
use App\Models\CheckPointUser;
use Image;
use File;
use Storage;
use Carbon\Carbon;
use Session;
use \FileUploader;
use App\Models\Vip;
use App\Models\VipLog;
use App\Models\AdminCommonText;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ImageController extends BaseController
{
    private $imageBasePath;
    private $uploadDir;
    //private $service;

    public function __construct()
    {
        /*
        * !important
        * 為了維持資料庫格式一致, 請避免使用 public_path('/img/Member'), 
        * 在 Linux 和 Windows 顯示上有所差異
        */
        $this->imageBasePath = public_path().'/img/Member/';
        $this->uploadDir = $this->imageBasePath . Carbon::now()->format('Y/m/d/');
        if(!File::exists($this->uploadDir))
            File::makeDirectory($this->uploadDir, 0777, true);
    }

    public function deleteImage(Request $request, $admin = false)
    {
        $payload = $request->all();
        MemberPic::destroy($payload['imgId']);
        if($admin){
            // 操作紀錄
            \App\Models\AdminPicturesSimilarActionLog::insert([
                'operator_id'   => Auth::user()->id,
                'operator_role' => Auth::user()->roles->first()->id,
                'target_id'     => MemberPic::withTrashed()->find($payload['imgId'])->member_id,
                'act'           => '刪除生活照',
                'pic'           => MemberPic::withTrashed()->find($payload['imgId'])->pic,
                'ip'            => $request->ip(),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            $imageInfo=MemberPic::withTrashed()->find($payload['imgId']);
            // 由後台刪除的生活照,寫入log紀錄
            AdminDeleteImageLog::create([
                'member_id'=>$imageInfo->member_id,
                'member_pic_id'=>$imageInfo->id,
            ]);
        }

        if(!$admin){
            // return redirect("/dashboard?img");
            return back()->with('message','照片刪除成功');
        }
        else{
            return back()->with('message', '成功刪除照片');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function resizeImagePostHeader(Request $request, ImageRequest $imageRequest, $admin = false)
    {
	    // $this->validate($request, [
        //     'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:22000',
        // ]);
        $payload = $request->all();

        $userId = $payload['userId'];

        $image = $request->file('image');
        $now = Carbon::now()->format('Ymd');

        $input['imagename'] = $now . rand(100000000,999999999) . '.' . $image->getClientOriginalExtension();

        $rootPath = public_path('/img/Member');
        $tempPath = $rootPath . '/' . substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/';

        if(!is_dir($tempPath)) {
            File::makeDirectory($tempPath, 0777, true);
        }

        $destinationPath = '/img/Member/'. substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/' . $input['imagename'];

        $img = Image::make($image->getRealPath());
        $img->resize(400, 600, function ($constraint) {
		    $constraint->aspectRatio();
		})->save($tempPath . $input['imagename']);

        // $destinationPath = public_path('/img/thumb');
        // $img->resize(800, 1200, function ($constraint) {
		//     $constraint->aspectRatio();
		// })->save($destinationPath.'/'.$userId.'.'.$input['imagename']);

        //'/img/thumb/'.$userId.'.'.$input['headername']

        $umeta = User::id_($userId)->meta_();
        $umeta->pic = $destinationPath;
        $umeta->pic_original_name = $image->getClientOriginalName();
        $umeta->save();
        $umeta->compareImages('ImageController@resizeImagePostHeader');
        if(!$admin){
            // return redirect()->to('/dashboard?img')
            return back()->with('success','照片上傳成功')
                   ->with('imageName',$input['imagename']);
        }
        else if($admin){
            return back()->with('message', '照片上傳成功');
        }
        else{
            return back()->withErrors(['出現預期外的錯誤']);
        }
    }

    public function resizeImagePostHeader2(Request $request, $admin = false)
    {
        $payload = $request->all();
        $userId = $payload['userId'];
        $umeta = User::id_($userId)->meta_();

        $images_arr = $payload['slim'];

        foreach ($images_arr as $key => $value) {
            $img_data = json_decode($value,true);
            if ($img_data && sizeof($img_data) > 0) {
                preg_match('/^(data:\s*image\/(\w+);base64,)/', $img_data['output']['image'], $result);
                $image_type = $result[2];
                if (in_array($image_type,array('pjpeg','jpeg','jpg','gif','bmp','png'))) {
                    if (in_array($image_type, ['pjpeg','jpeg','jpg'])) $image_type = 'jpg';
                    $image      = str_replace($result[1], '', $img_data['output']['image']);
                    $image      = str_replace(' ', '+', $image);

                    $now = Carbon::now()->format('Ymd');
                    $input['imagename'] = $now . rand(100000000,999999999).'.'.$image_type ;

                    $rootPath = public_path('/img/Member');
                    $tempPath = $rootPath . '/' . substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/';

                    if(!is_dir($tempPath)) {
                        File::makeDirectory($tempPath, 0777, true);
                    }

                    $destinationPath = '/img/Member/'. substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/' . $input['imagename'];

                    if (file_put_contents('.'.$destinationPath, base64_decode($image))) {
                        $umeta = User::id_($userId)->meta_();
                        if(file_exists('.'.$umeta->pic)){
                            unlink('.'.$umeta->pic);//將檔案刪除
                        }
                        $umeta->pic = $destinationPath;
                        $umeta->save();                       
                        $umeta->compareImages('ImageController@resizeImagePostHeader2');
                        return redirect()->to('/dashboard?img')
                        ->with('success','照片上傳成功');
                    }
                }
            }
        }

        if(!$admin){
            return redirect()->to('/dashboard?img')
                   ->with('success','照片上傳成功')
                   ;
        }
        else if($admin){
            return back()->with('message', '照片上傳成功');
        }
        else{
            return back()->withErrors(['出現預期外的錯誤']);
        }
    }

    public function resizeImagePost(Request $request, MultipleImageRequest $multipleImage, $admin = false)
    {
	    // $this->validate($request, [
        //     'images' => ['required', 'upload-image-limit'],
        //     'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:22000', 'upload-image-limit']
        // ]);

        $payload = $request->all();
        $userId = $payload['userId'];

        $rootPath = public_path('/img/Member'); //生活照
        $picPath = '/img/Member/';
        //證件照
        if($request->get('picType') == 'IDPhoto'){
            $rootPath = public_path('/img/Member/IDPhoto');
            $picPath = '/img/Member/IDPhoto/';
        }
        if($files = $request->file('images')) {
            foreach ($files as $file) {
                $now = Carbon::now()->format('Ymd');
                $input['imagename'] = $now . rand(100000000,999999999) . '.' . $file->getClientOriginalExtension();
                $tempPath = $rootPath . '/' . substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/';

                if(!is_dir($tempPath)) {
                    File::makeDirectory($tempPath, 0777, true);
                }

                $destinationPath = $picPath. substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/' . $input['imagename'];

                $img = Image::make($file->getRealPath());
                $img->resize(400, 600, function ($constraint) {
        		    $constraint->aspectRatio();
        		})->save($tempPath . $input['imagename']);

                // $destinationPath = public_path('/img');
                // $img->resize(800, 1200, function ($constraint) {
        		//     $constraint->aspectRatio();
        		// })->save($destinationPath.'/'.$userId.'.'.$input['imagename']);

                $memberPic = new MemberPic;
                $memberPic->member_id = $userId;
                $memberPic->pic = $destinationPath;
                $memberPic->original_name = $file->getClientOriginalName();
                $memberPic->save();
                $memberPic->compareImages('ImageController@resizeImagePost');
            }
        }

        if(!$admin){
            // return redirect()->to('/dashboard?img')
                   return back()->with('success','照片上傳成功');
        }
        else if($admin){
            return back()->with('message', '照片上傳成功');
        }
        else{
            return back()->withErrors(['出現預期外的錯誤']);
        }
    }

    public function getAvatar(Request $request)
    {
        $id = $request->userId;
        $avatarPath = UserMeta::where('user_id', $id)->first()->pic;
        if(!file_exists(public_path($avatarPath))){
            $uMeta = UserMeta::where('user_id', $id)->first();
            $uMeta->pic = null;
            $uMeta->save();
            $avatarPath = null;
        }

        if(is_null($avatarPath))
            return response()->json("");
        else
        {
            $path_slice = explode('/', $avatarPath);
            //必須為list
            $avatar[] = array(
                "name" => end($path_slice),
                "type" => FileUploader::mime_content_type($avatarPath),
                "size" => filesize(public_path($avatarPath)),
                "file" => $avatarPath,
                "relative_file" => public_path($avatarPath),
                "local" => $avatarPath,
                "data" => array(
                    "readerForce" => true,
                    "isPreload" => true //為預先載入的圖片
                )
            );

            return response()->json($avatar);
        }
    }

    public function uploadAvatar(Request $request,RealAuthPageService $rap_service)
    {
        $msg='';
        $userId = $request->userId;
        $user = $request->user();
        $rap_service->riseByUserEntry($user);
        $preloadedFiles = $this->getAvatar($request)->content();                
        $preloadedFiles = json_decode($preloadedFiles, true);

        CheckPointUser::where('user_id', auth()->id())->delete();
        $user_meta = UserMeta::where('user_id', auth()->id())->first();
        $user_meta->updated_at = Carbon::now();
        $user_meta->save();
        
        $file_input_name = 'avatar';
        
        if($rap_service->isPassedByAuthTypeId(1) && $user_meta->pic ) {
            $file_input_name = 'apply_replace_pic';
        }
            
        $fileUploader = new FileUploader($file_input_name, array(
            'fileMaxSize' => 8,
            'extensions' => ['jpg', 'jpeg', 'png', 'gif'],
            'required' => true,
            'uploadDir' => $this->uploadDir,
            'title' => function(){
                $now = Carbon::now()->format('Ymd');
                return $now . rand(100000000,999999999);
            },
            'replace' => false,
            'editor' => true,
            'files' => $preloadedFiles
        ));

        $upload = $fileUploader->upload();

        if(($upload['hasWarnings']??false) || !$upload['files']) {
            if(!$upload['files'])  {
                if(is_array($upload['warnings'])) $upload['warnings'][] = '您沒有選擇檔案。請重新選擇一個。';
                else $upload['warnings'] = '您沒有選擇檔案。請重新選擇一個。';
            }
            if($request->ajax()) {
                echo is_array($upload['warnings'])?implode("\r\r",$upload['warnings']):$upload['warnings'];
                exit;
            }            
            return redirect()->back()->with(['real_auth'=>request()->real_auth ?? 0])->withErrors($upload['warnings']);
        }else{
            //upload new avator
            $avatar = $fileUploader->getUploadedFiles();
            
            if($avatar)
            {
                
                $path = substr($avatar[0]['file'], strlen(public_path()));                 
                $path[0] = '/';
                if(!$rap_service->isPassedByAuthTypeId(1)) {
                    
                    //remove origin avator
                    $removeFiles = $fileUploader->getRemovedFiles();
                    if($removeFiles)
                    {
                        $file = public_path($removeFiles[0]['file']);
                        if(File::exists($file))
                        {
                            $UserMeta = UserMeta::where('user_id', $userId)->first();
                            $UserMeta->pic = NULL;
                            $UserMeta->save();
                            unlink($file);
                        }
                    }                
                    
                    
                    $this->handleAvatarUploadedFile($user,$path,$avatar[0]['old_name']);
                    
                    if($rap_service->isApplyEffectByAuthTypeId(1)) {
                        $rap_service->savePicModifyByReq($request);
                        $arr['pic'] = $path;
                        $arr['operate'] = 1;
                        $arr['original_name'] = $avatar[0]['old_name'];
                        $arr['pic_cat'] = 'avatar';
                        $this->handleUploadedFileForRealAuth($rap_service,$arr);
                    }
                }
                else {
                   $rap_service->savePicModifyByReq($request);
                   $arr['old_pic'] = $user->meta->pic??null;
                   $arr['operate'] = 1;
                   $arr['pic'] = $path;
                    $arr['original_name'] = $avatar[0]['old_name'];
                    $arr['pic_cat'] = 'avatar';
                   $this->handleUploadedFileForRealAuth($rap_service,$arr);
                }

                //更新大頭照模糊照片路徑
                $avatarOriginPath=UserMeta::where('user_id', $userId)->first();
                if(!is_null($avatarOriginPath) && $path){
                    $blurPic=$this->createBlurPhoto($path);
                    $avatarOriginPath->pic_blur=$blurPic;
                    $avatarOriginPath->save();
                }

               // UserMeta::where('user_id', $userId)->update(['pic' => $path, 'pic_original_name'=>$avatar[0]['old_name']]);
                //if($user->engroup==2)
               //     \App\Jobs\SimilarImagesSearcher::dispatch($path);                
            }
            
            $msg="上傳成功";

            $handled_msg = $this->handleAvatarLogFreeVipPicAct($user);
            
            if($handled_msg) $msg = $handled_msg;
            
            if($request->ajax()) {
                if( $msg) {
                    echo $msg;
                }
                else echo '1';
                exit;
            }
            return redirect()->back()->with(['message'=> $msg, 'real_auth'=>request()->real_auth ?? 0]);
        }
    }
    
    public static function handleAvatarUploadedFile($user,$path,$pic_original_name)
    {
            $userId = $user->id;
            $msg = '';    
            $path[0] = '/';
            UserMeta::where('user_id', $userId)->update(['pic' => $path, 'pic_original_name'=>$pic_original_name]);
            if($user->engroup==2)
                \App\Jobs\SimilarImagesSearcher::dispatch($path);                
    }
    
    public static function handleUploadedFileForRealAuth($rap_service,$arr)
    {
            $rap_service->saveRealAuthUserModifyPicByArr($arr);            
     }    
    
    public static function handleAvatarLogFreeVipPicAct($user)
    {
        $girl_to_vip = AdminCommonText::where('alias', 'girl_to_vip')->get()->first();

        $msg = '';
        $user->load('meta','pic');
        $user->meta->compareImages('ImageController@uploadAvatar');
        $log_pic_acts_count = $user->log_free_vip_pic_acts->count();  
        $last_avatar_act_log = $user->log_free_vip_avatar_acts()->orderBY('created_at','DESC')->first();
        $last_avatar_sys_react = $last_avatar_act_log->sys_react??'';
        $last_avatar_act_time =  isset($last_avatar_act_log->created_at)?Carbon::parse($last_avatar_act_log->created_at):'0000-00-00 00:00:00';
        if($user->existHeaderImage() && $user->engroup==2){
            if(!$user->isVip()) {
                $vip_record = Carbon::parse($user->vip_record);
                $freeVipCount =  VipLog::where('member_id', $user->id)->where('free',1)->where('action',1)->count();
                if(!$freeVipCount) {
                    $msg = $girl_to_vip->content;
                    $shot_vip_record = '';
                    $sys_react = 'upgrade';                        
                }                    
                else {
                    $msg = "照片上傳成功，24H後升級為VIP會員";
                    $shot_vip_record = $vip_record;
                    $sys_react = 'recovering';
                    
                }
                
                LogFreeVipPicAct::create(['user_id'=> $user->id
                     ,'user_operate'=>'upload'
                     ,'img_remain_num'=>isset($user->meta->pic)
                     ,'pic_type'=>'avatar'
                     ,'sys_react'=>$sys_react
                     ,'shot_vip_record'=>$shot_vip_record
                      ,'shot_is_free_vip'=>$user->isFreeVip()    
                         ]);                  
            }
            else {  //is still in free vip
                $checkFreeVipLog = LogFreeVipPicAct::where([['user_id',$user->id],['pic_type','avatar']])->orderBy('created_at', 'DESC')->first();
                $sys_react = "";
                if($checkFreeVipLog) {
                    if($checkFreeVipLog->user_operate=='delete') {
                        $last_del_time = Carbon::parse($checkFreeVipLog->created_at);
                        if($last_del_time->diffInSeconds(Carbon::now()) <= 1800) {
                            $sys_react = 'remain';
                        }  
                        else {
                            $sys_react = 'error：delete pics but still free vip after 30 min ';
                        }
                    }
                }
                else{
                    $sys_react = 'remain_init';
                }
                LogFreeVipPicAct::create([
                    'user_id'=> $user->id,
                    'user_operate' => 'upload',
                    'img_remain_num' => isset($user->meta->pic),
                    'pic_type' => 'avatar',
                    'sys_react' => $sys_react,
                    'shot_vip_record' => $user->vip_record,
                    'shot_is_free_vip' => $user->isFreeVip()
                ]);
            }
        }
        else if($user->meta->pic && $user->pic->count()<3  && $user->engroup==2) {
            LogFreeVipPicAct::create(['user_id'=> $user->id
                       ,'user_operate'=>'upload'
                       ,'img_remain_num'=>isset($user->meta->pic)
                       ,'pic_type'=>'avatar'
                       ,'sys_react'=>'avatar_ok'
                       ,'shot_vip_record'=>$user->vip_record
                       ,'shot_is_free_vip'=>$user->isFreeVip()
                    ]);              
        }

        return $msg;
    }

    public function deleteAvatar(Request $request)
    {
        $user=$request->user();
        $meta = UserMeta::findByMemberId($request->userId);
        if(is_null($meta))
            return response("此會員不存在", 200);
        $fullPath = public_path($meta->pic);
        if(File::exists($fullPath) and unlink($fullPath))
        {
            // 標記刪除
            \App\Models\AvatarDeleted::insert([
                'user_id'     => $meta->user_id,
                'operator'    => $meta->user_id,
                'pic'         => $meta->pic,
                'created_at'  => now(),
                'updated_at'  => now(),
                'uploaded_at' => $meta->updated_at,
            ]);

            $meta->pic = NULL;
            $meta->save();
            CheckPointUser::where('user_id', auth()->id())->delete();
            $msg="刪除成功";
           // if($user->log_free_vip_pic_acts->count()>0) {
            if($user->engroup==2 ){
                $user->load('meta');
                if($user->isFreeVip()){
                    $msg="您大頭照已刪除，需於30分鐘內補上，若超過30分鐘才補上，須等24hr才會恢復vip資格喔。";
                    $log_sys_react = 'reminding';
                }
                else if($user->log_free_vip_pic_acts->count()>0){
                    $log_sys_react = 'not_vip_not_ok';
                }
                else {
                    $log_sys_react = null;
                }

                if($log_sys_react) {
                    LogFreeVipPicAct::create(['user_id'=> $user->id
                        ,'user_operate'=>'delete'
                        ,'img_remain_num'=>isset($user->meta->pic)
                        ,'pic_type'=>'avatar'
                        ,'sys_react'=>$log_sys_react
                        ,'shot_vip_record'=>$user->vip_record
                        ,'shot_is_free_vip'=>$user->isFreeVip()    
                            ]);  
                }
            }
            return response($msg);
        }   
        else
        {
            return response("大頭照不存在或刪除失敗", 500);
        }
    }

    public function getPictures(Request $request)
    {
        //$id = 41759;
        $id = $request->userId;
        
        // $picturePaths = MemberPic::getSelf($id)->pluck('pic');
        $picturePaths = MemberPic::withTrashed()->where('member_id', $id)->where('self_deleted', 0)->orderByDesc('created_at')->whereNull('deleted_at')->get()->take(6)->pluck('pic');
        $paths = array();
        foreach($picturePaths as $path)
        {
            $targetPic = MemberPic::withTrashed()->where('pic', $path)->first();

            if ($targetPic->deleted_at && $targetPic->self_deleted == 0) {
                $admin_deleted_name = '不合規定的照片';
            }

            $path_slice = explode('/', $path);
            if(!file_exists(public_path($path))){
                $paths[] = array(
                    "name" => $admin_deleted_name??end($path_slice), //filename
                    "type" => FileUploader::mime_content_type($path),
                    "size" => 0, //filesize需完整路徑
                    "file" => $path,
                    "relative_file" => public_path($path), // full path for editing files
                    "local" => $path,
                    "data" => array(
                        "readerForce" => true,
                        "isPreload" => true //為預先載入的圖片
                    )
                );
            }
            else{
                $paths[] = array(
                    "name" => $admin_deleted_name??end($path_slice), //filename
                    "type" => FileUploader::mime_content_type($path),
                    "size" => filesize(public_path($path)), //filesize需完整路徑
                    "file" => $path,
                    "relative_file" => public_path($path), // full path for editing files
                    "local" => $path,
                    "data" => array(
                        "readerForce" => true,
                        "isPreload" => true //為預先載入的圖片
                    )
                );
            }
        }
        $responseJSON = response()->json($paths);
        return $responseJSON;
    }

    /**
    * 上傳生活照
    *
    * @param Request request 
    */

    public function uploadPictures(Request $request,RealAuthPageService $rap_service)
    {
        $userId = $request->userId;
        $user=$request->user();
        $rap_service->riseByUserEntry($user);
        $preloadedFiles = $this->getPictures($request)->content();
        $preloadedFiles = json_decode($preloadedFiles, true);
        $before_uploaded_existHeaderImage = $user->existHeaderImage();

        CheckPointUser::where('user_id', auth()->id())->delete();
        $user_meta = UserMeta::where('user_id', auth()->id())->first();
        $user_meta->updated_at = Carbon::now();
        $user_meta->save();
        
        $file_input_name = 'pictures';
        
        if($rap_service->isPassedByAuthTypeId(1) && $request->pic_kind) {
            $file_input_name = 'apply_replace_pic';
        }        

        $fileUploader = new FileUploader($file_input_name, array(
            'fileMaxSize' => 8,
            'extensions' => ['jpg', 'jpeg', 'png', 'gif'],
            'required' => true,
            'uploadDir' => $this->uploadDir,
            'title' => function(){
                $now = Carbon::now()->format('Ymd');
                return $now . rand(100000000,999999999);
            },
            'replace' => false,
            'editor' => true,
            'files' => $preloadedFiles
        ));
        //選擇移除的照片
//        try{
//            foreach($fileUploader->getRemovedFiles() as $key => $value)
//            {
//                $file = public_path($value['file']); //full path of removed file
//                if(File::exists($file)){
//                    unlink($file);
//                    MemberPic::where('pic', $value['file'])->delete();
//                }
//            }
//        }
//        catch (\Exception $e){
//            Session::flash('message', '照片上傳失敗，某些 iPhone 機型會鎖住上傳照片權限，請改用安卓系統或電腦上傳即可。若不方便或者還是上傳失敗，請點右下【聯絡我們】和站長聯繫處理。');
//            Log::info('Image upload failed, user id: ' . $userId . ', useragent: ' . $_SERVER['HTTP_USER_AGENT']);
//            return redirect()->back();
//        }

        $upload = $fileUploader->upload();
        
        if(($upload['hasWarnings']??false) || !$upload['files']) {
            if(!$upload['files'])  {
                if(is_array($upload['warnings'])) $upload['warnings'][] = '您沒有選擇檔案。請重新選擇一個。';
                else $upload['warnings'] = '您沒有選擇檔案。請重新選擇一個。';
            }            
            
            if($request->ajax()) {
                echo is_array($upload['warnings'])?implode("\r\r",$upload['warnings']):$upload['warnings'];
                exit;
            }            
            return redirect()->back()->with(['real_auth'=>request()->real_auth ?? 0])->withErrors($upload['warnings']);
        }        
        
        if($upload)
        {
            if($rap_service->isApplyEffectByAuthTypeId(1)) {
                $rap_service->savePicModifyByReq($request);
            }
            
            $publicPath = public_path();

            foreach($fileUploader->getUploadedFiles() as  $uploadIndex=>$uploadedFile)
            {
                $path = substr($uploadedFile['file'], strlen($publicPath));

                $path[0] = "/";

                if(!$rap_service->isPassedByAuthTypeId(1)) {
                    $this->handlePicturesUploadedFile($user,$path,$uploadedFile['old_name']);
                    
                    if($rap_service->isSelfAuthWaitingCheck()) {

                        $arr['pic'] = $path;
                        $arr['operate'] = 1;
                        $arr['original_name'] = $uploadedFile['old_name'];
                        $arr['pic_cat'] = 'member_pic';
                        $this->handleUploadedFileForRealAuth($rap_service,$arr);
                    }
                }
                else {
                    $arr['pic'] = $path;
                    $arr['old_pic'] = $request->org_pic;
                    $arr['operate'] = 1;
                    $arr['original_name'] = $uploadedFile['old_name'];
                    $arr['pic_cat'] = 'member_pic';
                    $this->handleUploadedFileForRealAuth($rap_service,$arr);                    
                }
            }
            
            if($rap_service->isApplyEffectByAuthTypeId(1)) {
                $rap_service->updateModifyNewMemPicNum();
            }            

            //更新生活照模糊照片路徑
            $lifePhotoList=MemberPic::where('member_id', $userId)->get();
            foreach ($lifePhotoList as $lifePhoto){
                if($lifePhoto->pic){
                    $blurPic=$this->createBlurPhoto($lifePhoto->pic);
                    $lifePhoto->pic_blur=$blurPic;
                    $lifePhoto->save();
                }
                else {
                    logger('ImageController no path.');
                    \Sentry\captureMessage("ImageController no path.");
                }
            }
        }
        
        $msg="上傳成功";
    
        $handled_msg = $this->handlePicturesLogFreeVipPicAct($user);
        
        if($handled_msg) $msg=$handled_msg;
        
        if($request->ajax()) {
            $no_react = true;
            if( $msg) {
                echo $msg;
                $no_react=false;
            }
            
            if(!($upload['isSuccess']??false) ) {
                if($upload['warnings']??false) {
                    echo $upload['warnings'];
                    $no_react=false;
                }
            }
            if($no_react) echo '1';
            exit;
        }
        $previous = redirect()->back()->with(['message'=>$msg, 'real_auth' =>request()->real_auth ?? 0]);
        return $upload['isSuccess'] ? $previous : $previous->withErrors($upload['warnings']);
    }
    
    public function handlePicturesUploadedFile($user,$path,$pic_original_name)
    {
        $userId = $user->id;
       $addPicture = new MemberPic;
        $addPicture->member_id = $userId;
        $addPicture->pic = $path;
        $addPicture->original_name = $pic_original_name;
        $blurPic=$this->createBlurPhoto($path);
        $addPicture->pic_blur=$blurPic;
        $addPicture->save();

        // 新增生活照時,刪除AdminDeleteImageLog紀錄
        AdminDeleteImageLog::where('member_id', $userId)->orderBy('id')->take(1)->delete();
        if($user->engroup==2)
            \App\Jobs\SimilarImagesSearcher::dispatch($path);
        $addPicture->compareImages('ImageController@uploadPictures');
        $addPicture = null;        
    }
    
    public static function handlePicturesLogFreeVipPicAct($user) 
    {
        $girl_to_vip = AdminCommonText::where('alias', 'girl_to_vip')->get()->first();

        $msg = '';
        $user->load('pic','meta');         
        $log_pic_acts_count = $user->log_free_vip_pic_acts->count();  
        $last_mempic_act_log = $user->log_free_vip_member_pic_acts()->orderBy('created_at','DESC')->first();
        $last_mempic_sys_react = $last_mempic_act_log->sys_react??'';
        $last_mempic_act_time =  isset($last_mempic_act_log->created_at)?Carbon::parse($last_mempic_act_log->created_at):'0000-00-00 00:00:00';    
        $before_uploaded_existHeaderImage = $user->existHeaderImage();    

        if($user->existHeaderImage() && $user->engroup==2 ){

            if(!$user->isVip()) {
                $vip_record = Carbon::parse($user->vip_record);
                $freeVipCount =  VipLog::where('member_id', $user->id)->where('free',1)->where('action',1)->count();
                if($freeVipCount){
                    if($last_mempic_sys_react!='recovering' 
                            && $last_mempic_sys_react!='upgrade'
                            && $last_mempic_sys_react!='member_pic_ok'
                            && $last_mempic_sys_react!='remain'  //不可能發生但為以防萬一列入判斷
                            || (!$before_uploaded_existHeaderImage && $freeVipCount)
                            ) {
                        $msg = "照片上傳成功，24H後升級為VIP會員";
                        LogFreeVipPicAct::create(['user_id'=> $user->id
                            ,'user_operate'=>'upload'
                            ,'img_remain_num'=>$user->pic->count()
                            ,'pic_type'=>'member_pic'
                            ,'sys_react'=>'recovering'
                            ,'shot_vip_record'=>$vip_record
                             ,'shot_is_free_vip'=>$user->isFreeVip()    
                                ]);     
                    }
                }else{
                    $msg = $girl_to_vip->content;                    
                    $shot_vip_record = '';

                    $sys_react = 'upgrade';

                    LogFreeVipPicAct::create([
                        'user_id'=> $user->id
                        ,'user_operate'=>'upload'
                        ,'img_remain_num'=>$user->pic->count()
                        ,'pic_type'=>'member_pic'
                        ,'sys_react'=>$sys_react
                        ,'shot_vip_record'=>$shot_vip_record
                         ,'shot_is_free_vip'=>$user->isFreeVip()    
                    ]);
                }
            } 
            else {  //is still in free vip
                $checkFreeVipLog = LogFreeVipPicAct::where([['user_id',$user->id],['pic_type','member_pic']])->orderBy('created_at', 'DESC')->first();
                $sys_react = '';
                if($checkFreeVipLog) {
                    if($checkFreeVipLog->user_operate=='delete') {
                        $last_del_time = Carbon::parse($checkFreeVipLog->created_at);
                        if($last_del_time->diffInSeconds(Carbon::now()) <= 1800) {
                            $sys_react = 'remain';
                        }  
                        else {
                            $sys_react = 'error：delete pics under rule but still free vip and satisfy image free vip rule  after 30 min ';
                        }
                    }
                }
                else{
                    $sys_react = 'remain_init';
                }
                LogFreeVipPicAct::create(['user_id'=> $user->id,
                    'user_operate' => 'upload',
                    'img_remain_num' => $user->pic->count(),
                    'pic_type' => 'member_pic',
                    'sys_react' => $sys_react,
                    'shot_vip_record' => $user->vip_record,
                    'shot_is_free_vip' => $user->isFreeVip()
                ]);
            }            
        }
        else if(!$user->meta->pic && $user->pic->count()>=3 && $user->engroup==2) {
            LogFreeVipPicAct::create(['user_id'=> $user->id
                      ,'user_operate'=>'upload'
                      ,'img_remain_num'=>$user->pic->count()
                      ,'pic_type'=>'member_pic'
                      ,'sys_react'=>'member_pic_ok'
                      ,'shot_vip_record'=>$user->vip_record
                     ,'shot_is_free_vip'=>$user->isFreeVip()
                    ]);              
        }

        return $msg;
    }

    /**
    * 如果是 userId 則刪除此使用者的全部生活照, 否則只刪除指定的 picturesPath
    * 
    * @param Request request
    *
    * @return Response 
    */
    public function deletePictures(Request $request)
    {
        $pictures = collect();
        $user=$request->user();
        if($request->userId)
            $picutres = MemberPic::getSelf($request->userId)->get();
        else{
            $pictures = MemberPic::withTrashed()->where('pic', $request->picture)->get();
        }
        
        return $this->handleDeletePictures($pictures,$user);

    }
    
    public function handleDeletePictures($pictures,$user)
    {

        foreach($pictures as $picture)
        {
            // 後台需要管理紀錄，故取消刪除照片實體。
            // $fullPath = public_path($picture->pic);
            
            // if(File::exists($fullPath))
            //     unlink($fullPath);

            $picture->delete();
            
            // 由於 Admin 刪除與 User 刪除是共用 deleted_at 欄位，故 User 刪除時應添加此筆紀錄以記錄使用者自行刪除。
            $picture->self_deleted = 1;
            $picture->save();
        }

        CheckPointUser::where('user_id', auth()->id())->delete();
        $user_meta = UserMeta::where('user_id', auth()->id())->first();
        $user_meta->updated_at = Carbon::now();
        $user_meta->save();
        
        $msg="刪除成功";

        $user->load('pic','meta');

        if(!$user->existHeaderImage() && $user->pic()->count() <= 3  && $user->engroup==2){
            if( $user->isFreeVip()) {
                $msg="您的照片低於四張，需於30分鐘內補上，若超過30分鐘才補上，須等24hr才會恢復vip資格喔。";
                $log_sys_react = 'reminding';
            }
            else if($user->log_free_vip_pic_acts->count()>0) {
                $log_sys_react = 'not_vip_not_ok';
            }
            else {
                $log_sys_react = null;
            }
            if($log_sys_react) {
                LogFreeVipPicAct::create(['user_id'=> $user->id
                    ,'user_operate'=>'delete'
                    ,'img_remain_num'=>$user->pic->count()
                    ,'pic_type'=>'member_pic'
                    ,'sys_react'=>$log_sys_react 
                    ,'shot_vip_record'=>$user->vip_record
                    ,'shot_is_free_vip'=>$user->isFreeVip()
                        ]); 
            }
        }
        
        return response($msg);        
    }

    public function admin_user_image_delete(Request $request)
    {
        DB::beginTransaction();
        
        try {

            // 軟刪除
            MemberPic::destroy($request->imgId);

            // 操作紀錄
            \App\Models\AdminPicturesSimilarActionLog::insert([
                'operator_id'   => Auth::user()->id,
                'operator_role' => Auth::user()->roles->first()->id,
                'target_id'     => $request->userId,
                'act'           => '刪除生活照',
                'pic'           => MemberPic::withTrashed()->find($request->imgId)->pic,
                'ip'            => $request->ip(),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            DB::commit();

            $msg_type    = 'message';
            $msg_content = '生活照刪除成功';

        } catch (\Throwable $th) {

            // throw $th;
            DB::rollback();

            $msg_type    = 'error';
            $msg_content = '生活照刪除失敗';
        }

        return back()->with($msg_type, $msg_content);
    }

    public function admin_user_avatar_delete(Request $request)
    {

        DB::beginTransaction();

        try {

            $user_id = $request->input('userId');
            $usermeta = UserMeta::where('user_id', $user_id)->first();

            // 標記刪除
            \App\Models\AvatarDeleted::insert([
                'user_id'     => $usermeta->user_id,
                'operator'    => Auth::user()->id,
                'pic'         => $usermeta->pic,
                'created_at'  => now(),
                'updated_at'  => now(),
                'uploaded_at' => $usermeta->updated_at,
            ]);

            // 操作記錄
            \App\Models\AdminPicturesSimilarActionLog::insert([
                'operator_id'   => Auth::user()->id,
                'operator_role' => Auth::user()->roles->first()->id,
                'target_id'     => $usermeta->user_id,
                'act'           => '刪除頭像',
                'pic'           => $usermeta->pic,
                'ip'            => $request->ip(),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            // 設為空值 (軟刪除)
            $usermeta->pic = null;
            $usermeta->save();
            
            DB::commit();
            
            $msg_type    = 'message';
            $msg_content = '頭像刪除成功';

        } catch (\Throwable $th) {

            // throw $th;
            DB::rollback();
            
            $msg_type    = 'error';
            $msg_content = '頭像刪除失敗';
        }

        return back()->with($msg_type, $msg_content);
    }

    public function admin_user_pictures_all_delete(Request $request){

        $userId = $request->target_uid;
        $user = User::where('id', $userId)->first();

        if (!$user) {
            return back()->with('errpr', '沒有找到該會員的資料');
        }

        DB::beginTransaction();

        try {

            // 刪除生活照
            $member_pics = MemberPic::where('member_id', $user->id)->get();

            foreach ($member_pics as $member_pic) {

                // 操作紀錄
                \App\Models\AdminPicturesSimilarActionLog::insert([
                    'operator_id'   => Auth::user()->id,
                    'operator_role' => Auth::user()->roles->first()->id,
                    'target_id'     => $user->id,
                    'act'           => '刪除生活照',
                    'pic'           => $member_pic->pic,
                    'ip'            => $request->ip(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
                // 由後台刪除的生活照,寫入log紀錄
                AdminDeleteImageLog::create([
                    'member_id'=>$member_pic->member_id,
                    'member_pic_id'=>$member_pic->id,
                ]);

                $member_pic->delete();

            }

            // 刪除頭像

            $usermeta = UserMeta::where('user_id', $user->id)->first();

            if ($usermeta->pic) {
                // 標記刪除
                \App\Models\AvatarDeleted::insert([
                    'user_id'     => $usermeta->user_id,
                    'operator'    => Auth::user()->id,
                    'pic'         => $usermeta->pic,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                    'uploaded_at' => $usermeta->updated_at,
                ]);
                
                // 操作記錄
                \App\Models\AdminPicturesSimilarActionLog::insert([
                    'operator_id'   => Auth::user()->id,
                    'operator_role' => Auth::user()->roles->first()->id,
                    'target_id'     => $usermeta->user_id,
                    'act'           => '刪除頭像',
                    'pic'           => $usermeta->pic,
                    'ip'            => $request->ip(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                // 設為空值 (軟刪除)
                $usermeta->pic = null;
                $usermeta->save();
            }

            // 女性會員刪除VIP
            if ($user->isVip() && $user->engroup == 2) {
                Vip::where('member_id', $user->id)->update([
                    'active' => 0,
                    'expiry' => date('Y-m-d H:i:s'),
                ]);
                // Vip::where('member_id', $user->id)->first()->removeVIP();
            }

            DB::commit();

            $msg_type    = 'message';
            $msg_content = '刪除成功';
        } catch (\Throwable $th) {
            throw $th;
            DB::rollback();
            $msg_type    = 'error';
            $msg_content = '刪除失敗';
        }

        return back()->with($msg_type, $msg_content);

    }

    public function uploadImages_VVIP(Request $request)
    {
        $user=$request->user();
        $preloadedFiles = $this->getPictures($request)->content();
        $preloadedFiles = json_decode($preloadedFiles, true);
        $hash = $request->input('mode');

        $fileUploader = new FileUploader('files', array(
            'fileMaxSize' => 8,
            'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
            'required' => true,
            'uploadDir' => $this->uploadDir,
            'title' => function(){
                $now = Carbon::now()->format('Ymd');
                return $now . rand(100000000,999999999);
            },
            'replace' => false,
            'editor' => true,
            'files' => $preloadedFiles
        ));

        $upload = $fileUploader->upload();

        if($upload)
        {
            $publicPath = public_path();
            foreach($fileUploader->getUploadedFiles() as $uploadedFile)
            {
                $path = substr($uploadedFile['file'], strlen($publicPath));
                $path[0] = "/";
                //存入VVIP證明文件資料表
                $addImages = new VvipProveImg;
                $addImages->user_id = $user->id;
                $addImages->path = $path;
                $addImages->created_at = Carbon::now();
                $addImages->save();
            }
        }

        if($hash=='refill') {
            $msg = "補件上傳成功";
        }else{
            $msg = "";
        }

        //$previous = redirect()->back('#refill')->with('message', $msg);
        //$previous = redirect()->route('vvipSelectA', [ '#refill' ])->with('message', '補件上傳成功');
        //非補件 導向付款提示
        if($hash =='refill'){
            $previous = redirect()->route('vvipSelectA', [ '#'.$hash ])->with('message', $msg);
        }elseif($hash =='pay'){
            if($upload['isSuccess'] ?? false)
            {$previous = redirect()->route('vvipSelectA', [ '#'.$hash ]);}
            else
            {$previous = redirect()->route('vvipSelectA', [ '#file_error' ]);}
        }else{
            $previous = redirect()->route('vvipSelectA');
        }


        return $upload['isSuccess'] ? $previous : $previous->withErrors($upload['warnings']);
    }

    //大頭照or生活照照片模糊處理
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
