<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

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
                    ->select($table_name.'.*', 'vvip_option_xref.id as xref_id')
                    ->get();
    }

    public static function update_multiple_option($user_id, $option_array)
    {
        $type_list = array_keys($option_array);
        //把所有選項刪除重置
        VvipOptionXref::where('user_id', $user_id)->whereIn('option_type', $type_list)->delete();

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
        VvipOptionXref::insert($insert_data);
    }
}
