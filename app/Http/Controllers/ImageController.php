<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\ImageRequest;
use App\Http\Requests\MultipleImageRequest;
use App\Http\Requests;
use App\Services\ImageService;
use App\Models\User;
use App\Models\MemberPic;
use App\Models\UserMeta;
use App\Models\LogFreeVipPicAct;
use Image;
use File;
use Storage;
use Carbon\Carbon;
use Session;
use \FileUploader;
use App\Models\Vip;
use App\Models\AdminCommonText;
use Illuminate\Support\Facades\Log;

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

    public function uploadAvatar(Request $request)
    {
        $userId = $request->userId;
        $user = $request->user();
        $preloadedFiles = $this->getAvatar($request)->content();        
        $preloadedFiles = json_decode($preloadedFiles, true);
            
        $fileUploader = new FileUploader('avatar', array(
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

        if($upload['hasWarnings']??false) {
            if($request->ajax()) {
                echo is_array($upload['warnings'])?implode("\r\r",$upload['warnings']):$upload['warnings'];
                exit;
            }            
            return redirect()->back()->withErrors($upload['warnings']);
        }else{
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
            //upload new avator
            $avatar = $fileUploader->getUploadedFiles();

            if($avatar)
            {
                $path = substr($avatar[0]['file'], strlen(public_path()));                 
                $path[0] = '/';
                UserMeta::where('user_id', $userId)->update(['pic' => $path, 'pic_original_name'=>$avatar[0]['old_name']]);
            }
            $msg="上傳成功";

            $girl_to_vip = AdminCommonText::where('alias', 'girl_to_vip')->get()->first();

            $user->load('meta','pic');
            $log_pic_acts_count = $user->log_free_vip_pic_acts->count();  
            $last_avatar_act_log = $user->log_free_vip_avatar_acts->first();
            $last_avatar_sys_react = $last_avatar_act_log->sys_react??'';
            $last_avatar_act_time =  isset($last_avatar_act_log->created_at)?Carbon::parse($last_avatar_act_log->created_at):'0000-00-00 00:00:00';
            if($user->existHeaderImage() && $user->engroup==2){
                if(!$user->isVip()) {
                    $vip_record = Carbon::parse($user->vip_record);
                    if( $log_pic_acts_count>0){
                        $msg = "照片上傳成功，24H後升級為VIP會員";
                        $shot_vip_record = $vip_record;
                        $sys_react = 'recovering';
                        
                    }else{
                        $msg = $girl_to_vip->content;

                        $shot_vip_record = '';
                        $sys_react = 'upgrade';
                    
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

                    if($checkFreeVipLog) {
                        if($checkFreeVipLog->user_operate=='delete') {
                            $last_del_time = Carbon::parse($checkFreeVipLog->created_at);
                            if($last_del_time->diffInSeconds(Carbon::now()) <= 1800) {
                                $sys_react = 'remain';
                            }  
                            else {
                                $sys_react = 'error：delete pics but still free vip after 30 min ';
                            }
                            
                            LogFreeVipPicAct::create(['user_id'=> $user->id
                                      ,'user_operate'=>'upload'
                                      ,'img_remain_num'=>isset($user->meta->pic)
                                      ,'pic_type'=>'avatar'
                                      ,'sys_react'=>$sys_react
                                      ,'shot_vip_record'=>$user->vip_record
                                     ,'shot_is_free_vip'=>$user->isFreeVip()
                                    ]);                              
                        }

                    }                   
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
            if($request->ajax()) {
                if( $msg) {
                    echo $msg;
                }
                else echo '1';
                exit;
            }
            return redirect()->back()->with('message', $msg);
        }
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
            $meta->pic = NULL;
            $meta->save();
            $msg="刪除成功";
            if($user->log_free_vip_pic_acts->count()>0) {
                if($user->engroup==2 ){
                    $user->load('meta');
                    if($user->isFreeVip()){
                        $msg="您大頭照已刪除，需於30分鐘內補上，若超過30分鐘才補上，須等24hr才會恢復vip資格喔。";
                        $log_sys_react = 'reminding';
                    }
                    else if(LogFreeVipPicAct::where(['user_id'=> $user->id])->count()>0){
                        $log_sys_react = 'not_vip_not_ok';
                    }

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
        
        $picturePaths = MemberPic::getSelf($id)->pluck('pic');
        $paths = array();
        foreach($picturePaths as $path)
        {
            $path_slice = explode('/', $path);
            if(!file_exists(public_path($path))){
                $paths[] = array(
                    "name" => end($path_slice), //filename
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
                    "name" => end($path_slice), //filename
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

    public function uploadPictures(Request $request)
    {
        $userId = $request->userId;
        $user=$request->user();
        $preloadedFiles = $this->getPictures($request)->content();
        $preloadedFiles = json_decode($preloadedFiles, true);

        $fileUploader = new FileUploader('pictures', array(
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
        
        if($upload['hasWarnings']??false) {
            if($request->ajax()) {
                echo is_array($upload['warnings'])?implode("\r\r",$upload['warnings']):$upload['warnings'];
                exit;
            }            
            return redirect()->back()->withErrors($upload['warnings']);
        }        
        
        if($upload)
        {
            $publicPath = public_path();

            foreach($fileUploader->getUploadedFiles() as  $uploadIndex=>$uploadedFile)
            {
                $path = substr($uploadedFile['file'], strlen($publicPath));

                $path[0] = "/";
                $addPicture = new MemberPic;
                $addPicture->member_id = $userId;
                $addPicture->pic = $path;
                $addPicture->original_name = $uploadedFile['old_name'];
                $addPicture->save();
            }
        }
        $msg="上傳成功";

        $girl_to_vip = AdminCommonText::where('alias', 'girl_to_vip')->get()->first();

        $user->load('pic','meta'); 
        $log_pic_acts_count = $user->log_free_vip_pic_acts->count();  
        $last_mempic_act_log = $user->log_free_vip_member_pic_acts->first();
        $last_mempic_sys_react = $last_mempic_act_log->sys_react??'';
        $last_mempic_act_time =  isset($last_mempic_act_log->created_at)?Carbon::parse($last_mempic_act_log->created_at):'0000-00-00 00:00:00';        

        if($user->existHeaderImage() && $user->engroup==2 ){

            if(!$user->isVip()) {
                $vip_record = Carbon::parse($user->vip_record);

                if($user->log_free_vip_pic_acts->count()>0 ){
                    if($last_mempic_sys_react!='recovering' 
                            && $last_mempic_sys_react!='upgrade'
                            && $last_mempic_sys_react!='member_pic_ok'
                            && $last_mempic_sys_react!='remain'  //不可能發生但為以防萬一列入判斷
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

                    LogFreeVipPicAct::create(['user_id'=> $user->id
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
                if($checkFreeVipLog) {
                    if($checkFreeVipLog->user_operate=='delete') {
                        $last_del_time = Carbon::parse($checkFreeVipLog->created_at);
                        if($last_del_time->diffInSeconds(Carbon::now()) <= 1800) {
                            $sys_react = 'remain';
                        }  
                        else {
                            $sys_react = 'error：delete pics under rule but still free vip after 30 min ';
                        }

                        LogFreeVipPicAct::create(['user_id'=> $user->id
                                  ,'user_operate'=>'upload'
                                  ,'img_remain_num'=>$user->pic->count()
                                  ,'pic_type'=>'member_pic'
                                  ,'sys_react'=>$sys_react
                                  ,'shot_vip_record'=>$user->vip_record
                                 ,'shot_is_free_vip'=>$user->isFreeVip()
                                ]);                              
                    }

                }                   
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
        $previous = redirect()->back()->with('message', $msg);
        return $upload['isSuccess'] ? $previous : $previous->withErrors($upload['warnings']);
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
            $pictures = MemberPic::where('pic', $request->picture)->get();
        }

        foreach($pictures as $picture)
        {
            $fullPath = public_path($picture->pic);
            
            if(File::exists($fullPath))
                unlink($fullPath);

            $picture->delete();
        }
        
        $msg="刪除成功";

        $user->load('pic','meta');
        
        if($user->log_free_vip_pic_acts->count()>0) {

            if(!$user->existHeaderImage() && $user->engroup==2){
                if( $user->isFreeVip()) {
                    $msg="您的照片低於四張，需於30分鐘內補上，若超過30分鐘才補上，須等24hr才會恢復vip資格喔。";
                    $log_sys_react = 'reminding';
                }
                else {
                    $log_sys_react = 'not_vip_not_ok';
                }

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
}
