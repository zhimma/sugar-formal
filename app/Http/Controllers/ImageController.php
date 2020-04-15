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
use Image;
use File;
use Storage;
use Carbon\Carbon;
use \FileUploader;

class ImageController extends Controller
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
            return redirect("/dashboard?img");
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
        $umeta->save();

        if(!$admin){
            return redirect()->to('/dashboard?img')
                   ->with('success','照片上傳成功')
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

        if($files = $request->file('images')) {
            foreach ($files as $file) {
                //dd($files);
                $now = Carbon::now()->format('Ymd');
                $input['imagename'] = $now . rand(100000000,999999999) . '.' . $file->getClientOriginalExtension();

                $rootPath = public_path('/img/Member');
                $tempPath = $rootPath . '/' . substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/';

                if(!is_dir($tempPath)) {
                    File::makeDirectory($tempPath, 0777, true);
                }

                $destinationPath = '/img/Member/'. substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/' . $input['imagename'];

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
                $memberPic->save();
            }
        }

        if(!$admin){
            return redirect()->to('/dashboard?img')
                   ->with('success','照片上傳成功');
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
                "local" => $avatarPath,
                "data" => array(
                    "readerForce" => true
                )
            );

            return response()->json($avatar);
        }
    }

    public function uploadAvatar(Request $request)
    {
        $userId = $request->userId;
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
            'files' => $preloadedFiles
        ));

        /*$file = public_path($meta->pic);
        if(is_file($file) and file_exists($file))
            unlink($file);*/

        $upload = $fileUploader->upload();
        if($upload['hasWarnings'])
            return redirect()->back()->withErrors($upload['warnings']);
        else
        {
            //remove origin avator
            $removeFiles = $fileUploader->getRemovedFiles('avatar');
            if($removeFiles)
            {
                $file = public_path($removeFiles[0]['file']);
                if(File::exists($file))
                {
                    $user = UserMeta::where('user_id', $userId)->first();
                    $user->pic = NULL;
                    $user->save();
                    unlink($file);
                }
            }


            //upload new avator
            $avatar = $fileUploader->getUploadedFiles();
            if($avatar)
            {
                $filePath = $avatar[0]['file'];
                if( is_file($filePath) and file_exists($filePath)){
                    $path = substr($filePath, strlen(public_path(DIRECTORY_SEPARATOR))-1);
                    $path[0] = '/';
                    UserMeta::where('user_id', $userId)->update(['pic' => $path]);
                }
            }
            return redirect()->back();
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
            $paths[] = array(
                "name" => end($path_slice), //filename
                "type" => FileUploader::mime_content_type($path),
                "size" => filesize(public_path($path)), //filesize需完整路徑
                "file" => $path,
                "local" => $path,
                "data" => array(
                    "readerForce" => true
                )
            );
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
            'files' => $preloadedFiles
        ));
    
        //選擇移除的照片
        foreach($fileUploader->getRemovedFiles() as $key => $value)
        {
            $file = public_path($value['file']); //full path of removed file 
            if(File::exists($file)){
                unlink($file);
                MemberPic::where('pic', $value['file'])->delete();
            }
        }

        $upload = $fileUploader->upload();
        if($upload)
        {
            $publicPath = public_path().DIRECTORY_SEPARATOR;
            foreach($fileUploader->getUploadedFiles() as $uploadedFile)
            {
                $path = substr($uploadedFile['file'], strlen($publicPath));
                $addPicture = new MemberPic;
                $addPicture->member_id = $userId;
                $addPicture->pic = $path;
                $addPicture->save();
            }
        }
        
        $previous = redirect()->back();
        return $upload['isSuccess'] ? $previous : $previous->withErrors($upload['warnings']);
    }
}
