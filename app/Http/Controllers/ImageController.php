<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ImageRequest;
use App\Http\Requests\MultipleImageRequest;
use App\Http\Requests;
use App\Models\User;
use App\Models\MemberPic;
use App\Models\UserMeta;
use Image;
use File;
use Storage;
use Carbon\Carbon;

class ImageController extends Controller
{
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

    public function fileuploader_image_upload()
    {
        // 確保上傳資料夾存在
        @mkdir($this->img_dir, 0777, true);
        include( public_path(). '/plugins/fileuploader2/src/class.fileuploader.php');
        // 上傳圖片
        $fileUploader = new MyFileUploader('files', array(
            'fileMaxSize' => 8,
            'extensions' => ['jpg', 'jpeg', 'png', 'gif'],
            'required' => true,
            'uploadDir' => $this->img_dir,
            'title' => time(),
            'replace' => false,
        ));
        $upload = $fileUploader->upload();

        if ($upload['isSuccess']) {
            // 製作縮圖
            // 縮圖檔名: 原檔名後綴 "_thumbnail"
            $img = $upload['files'][0];
            $thumbnail = $this->img_dir.basename($img['name'], '.'.$img['extension']).'_thumbnail.'.$img['extension'];
            if (MyFileUploader::resize($img['file'], 200, 200, $thumbnail, null, 100)) {
                $return = $this->ReturnHandle(true, '/'.$img['file']);
            } else {
                $return = $this->ReturnHandle(false, $this->lang->line('Update_Info_fail'));
            }
        } else {
            $return = $this->ReturnHandle(false, $this->lang->line('Update_Info_fail'));
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($return));
    }
}
