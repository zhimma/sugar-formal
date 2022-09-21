<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use \FileUploader;
use Storage;

class VvipOptionXref extends Model
{
    use HasFactory;
    protected $table = 'vvip_option_xref';

    /*
    option_type: 
    
    point_information
    date_trend
    background_and_assets
    extra_care
    assets_image
    quality_life_image
    expect_date
    */

    public static function reset($user_id)
    {
        VvipOptionXref::where('user_id', $user_id)->delete();
    }

    public static function getOptionInfo($type_name, $user)
    {
        $table_name = 'vvip_option_'.$type_name;
        return DB::table($table_name)
                    ->leftJoin('vvip_option_xref', function($join) use($user, $table_name, $type_name)
                    {
                        $join->on($table_name.'.id', '=', 'vvip_option_xref.option_id')
                            ->where('vvip_option_xref.user_id', '=', $user->id)
                            ->where('vvip_option_xref.option_type', '=', $type_name)
                            ;
                    })
                    ->select($table_name.'.*', 'vvip_option_xref.id as xref_id','vvip_option_xref.option_remark as option_remark','vvip_option_xref.option_second_remark as option_second_remark')
                    ->where('is_custom', 0)
                    ->orWhere(function ($query){
                        $query->where('is_custom', 1);
                        $query->whereNotNull('vvip_option_xref.id');
                    })
                    ->get();
    }

    public static function viewSelectOptionInfo($type_name, $user_id)
    {
        $table_name = 'vvip_option_'.$type_name;
        return VvipOptionXref::leftJoin($table_name, $table_name.'.id', '=', 'option_id')
                                ->where('user_id', $user_id)
                                ->where('option_type', $type_name)
                                ->get();
    }

    public static function update_multiple_option($user_id, $option_array, $option_array_other)
    {
        $type_list = array_keys($option_array);

        //插入新資料
        $now_time = Carbon::now();
        $insert_data = [];
        foreach($type_list as $type)
        {
            foreach($option_array[$type] as $option)
            {
                $insert_data[] = [
                    'user_id' => $user_id, 
                    'option_type' => $type, 
                    'option_id' => $option, 
                    'option_remark' =>'', 
                    'created_at' => $now_time,
                    'updated_at' => $now_time
                ];
            }
        }

        //插入自填資料
        foreach($type_list as $type)
        {
            foreach($option_array_other[$type .'_other'] as $option)
            {
                if($option != '')
                {
                    $custom_option = DB::table('vvip_option_' . $type)->where('option_name', $option)->first();
                    if(!($custom_option??false))
                    {
                        $custom_option_id = DB::table('vvip_option_' . $type)->insertGetId(['option_name' => $option, 'is_custom' => 1]);
                    }
                    else
                    {
                        $custom_option_id = $custom_option->id;
                    }

                    $insert_data[] = [
                        'user_id' => $user_id, 
                        'option_type' => $type, 
                        'option_id' => $custom_option_id, 
                        'option_remark' =>'', 
                        'created_at' => $now_time,
                        'updated_at' => $now_time
                    ];
                }
            }
        }
        
        VvipOptionXref::insert($insert_data);
    }

    public static function uploadImage($user_id, $type_name, $image_array, $image_detail_array, $image_content_array, $image_title_array = null)
    {
        $insert_data = [];
        $now_time = Carbon::now();
        foreach($image_content_array as $key => $content)
        {
            //處理標題
            $image_title = '';
            if($image_title_array ?? false)
            {
                $image_title = $image_title_array[$key];
            }

            $file_name = uniqid();
            $file_path = '';
            $image_detail = json_decode($image_detail_array[$key], true);

            //上傳圖片
            $rootPath = public_path('/img/vvipInfo');
            $tempPath = $rootPath . '/' . $now_time->format('Ymd') . '/';
            if(!is_dir($tempPath)) 
            {
                File::makeDirectory($tempPath, 0777, true);
            }

            $fileUploader = new FileUploader($type_name.'_'.$key, array(
                'extensions' => null,
                'required' => false,
                'uploadDir' => $tempPath,
                'title' => '{random}',
                'replace' => false,
                'editor' => $image_detail[0]["editor"],
                'listInput' => true
            ));

            $upload = $fileUploader->upload();
            

            if ($upload) {
                foreach ($fileUploader->getUploadedFiles() as $key => $pic) {
                    $path = substr($pic['file'], strlen($rootPath));
                    $file_path = '/img/vvipInfo' . $path;
                }
            }

            //上傳圖片相關資料
            $custom_option_id = DB::table('vvip_option_' . $type_name)->insertGetId(['option_name' => $file_path, 'is_custom' => 1]);

            //更新xref
            $insert_data[] = [
                'user_id' => $user_id, 
                'option_type' => $type_name, 
                'option_id' => $custom_option_id, 
                'option_remark' => $content, 
                'option_second_remark' => $image_title, 
                'created_at' => $now_time,
                'updated_at' => $now_time
            ];
        }
        VvipOptionXref::insert($insert_data);
    }

    public static function updateMultipleOptionAndRemark($user_id, $type_name, $option_array, $option_second_array = null)
    {
        $now_time = Carbon::now();
        $insert_data = [];

        foreach($option_array as $key => $option)
        {
            $option_second_remark = '';
            if($option_second_array ?? false)
            {
                $option_second_remark = $option_second_array[$key][1];
            }
            $insert_data[] = [
                'user_id' => $user_id, 
                'option_type' => $type_name, 
                'option_id' => $option[0], 
                'option_remark' =>$option[1], 
                'option_second_remark' =>$option_second_remark, 
                'created_at' => $now_time,
                'updated_at' => $now_time
            ];
        }

        VvipOptionXref::insert($insert_data);
    }
    
}
