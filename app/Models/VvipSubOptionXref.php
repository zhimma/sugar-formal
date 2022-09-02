<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class VvipSubOptionXref extends Model
{
    use HasFactory;
    protected $table = 'vvip_sub_option_xref';

    /*
    sub_option_type: 
    
    point_information:
        high_assets
        ceo_title

    background_and_assets:
        professional
        high_net_worth
        entrepreneur

    extra_care:
        professional_network
        life_care
        special_problem_handling
    */

    public static function getSubOptionInfo($sub_type_name, $user)
    {
        $sub_type_table_name = 'vvip_sub_option_'.$sub_type_name;
        return DB::table($sub_type_table_name)
                    ->leftJoin('vvip_sub_option_xref', function($join) use($user, $sub_type_table_name, $sub_type_name)
                    {
                        $join->on($sub_type_table_name.'.id', '=', 'vvip_sub_option_xref.option_id')
                            ->where('vvip_sub_option_xref.user_id', '=', $user->id)
                            ->where('vvip_sub_option_xref.option_type', '=', $sub_type_name)
                            ;
                    })
                    ->select($sub_type_table_name.'.*', 'vvip_sub_option_xref.id as xref_id','vvip_sub_option_xref.option_remark as option_remark')
                    ->where('is_custom', 0)
                    ->orWhere(function ($query){
                        $query->where('is_custom', 1);
                        $query->whereNotNull('vvip_sub_option_xref.id');
                    })
                    ->get();
    }

    public static function reset($user_id)
    {
        VvipSubOptionXref::where('user_id', $user_id)->delete();
    }

    public static function updateHighAssets($user_id, $option, $other_option)
    {
        $now_time = Carbon::now();
        $insert_data = [];
        if($option ?? false)
        {
            $insert_data[] = [
                'user_id' => $user_id, 
                'option_type' => 'high_assets', 
                'option_id' => $option, 
                'option_remark' =>'', 
                'created_at' => $now_time,
                'updated_at' => $now_time
            ];
        }
        elseif($other_option ?? false)
        {
            $custom_option = DB::table('vvip_sub_option_high_assets')->where('option_name', $other_option)->first();
                    if(!($custom_option??false))
                    {
                        $custom_option_id = DB::table('vvip_sub_option_high_assets')->insertGetId(['option_name' => $other_option, 'is_custom' => 1]);
                    }
                    else
                    {
                        $custom_option_id = $custom_option->id;
                    }

                    $insert_data[] = [
                        'user_id' => $user_id, 
                        'option_type' => 'high_assets', 
                        'option_id' => $custom_option_id, 
                        'option_remark' =>'', 
                        'created_at' => $now_time,
                        'updated_at' => $now_time
                    ];
        }

        VvipSubOptionXref::insert($insert_data);
    }

    public static function updateCeoTitle($user_id, $option)
    {
        $now_time = Carbon::now();
        $insert_data = [];
        if($option ?? false)
        {
            $insert_data[] = [
                'user_id' => $user_id, 
                'option_type' => 'ceo_title', 
                'option_id' => $option, 
                'option_remark' =>'', 
                'created_at' => $now_time,
                'updated_at' => $now_time
            ];
        }

        VvipSubOptionXref::insert($insert_data);
    }

    public static function updateMultipleOption($user_id, $option_array, $type_name)
    {
        $now_time = Carbon::now();
        $insert_data = [];

        foreach($option_array as $option)
        {
            $insert_data[] = [
                'user_id' => $user_id, 
                'option_type' => $type_name, 
                'option_id' => $option, 
                'option_remark' =>'', 
                'created_at' => $now_time,
                'updated_at' => $now_time
            ];
        }

        VvipSubOptionXref::insert($insert_data);
    }

    public static function updateMultipleOptionAndRemark($user_id, $option_array, $type_name)
    {
        $now_time = Carbon::now();
        $insert_data = [];

        foreach($option_array as $option)
        {
            $insert_data[] = [
                'user_id' => $user_id, 
                'option_type' => $type_name, 
                'option_id' => $option[0], 
                'option_remark' =>$option[1], 
                'created_at' => $now_time,
                'updated_at' => $now_time
            ];
        }

        VvipSubOptionXref::insert($insert_data);
    }

    public static function updateOptionAndRemark($user_id, $option, $type_name)
    {
        $now_time = Carbon::now();
        $insert_data = [];
        if($option != [])
        {
            $insert_data[] = [
                'user_id' => $user_id, 
                'option_type' => $type_name, 
                'option_id' => $option[0], 
                'option_remark' => $option[1], 
                'created_at' => $now_time,
                'updated_at' => $now_time
            ];
        }

        VvipSubOptionXref::insert($insert_data);
    }

    public static function updateOptionAndCustomAndRemark($user_id, $option, $type_name)
    {
        $now_time = Carbon::now();
        $insert_data = [];
        if($option[0] ?? false)
        {
            $insert_data[] = [
                'user_id' => $user_id, 
                'option_type' => $type_name, 
                'option_id' => $option[0], 
                'option_remark' => $option[2], 
                'created_at' => $now_time,
                'updated_at' => $now_time
            ];
        }
        elseif($option[1] ?? false)
        {
            $custom_option = DB::table('vvip_sub_option_' . $type_name)->where('option_name', $option[1])->first();
            if(!($custom_option??false))
            {
                $custom_option_id = DB::table('vvip_sub_option_' . $type_name)->insertGetId(['option_name' => $option[1], 'is_custom' => 1]);
            }
            else
            {
                $custom_option_id = $custom_option->id;
            }

            $insert_data[] = [
                'user_id' => $user_id, 
                'option_type' => $type_name, 
                'option_id' => $custom_option_id, 
                'option_remark' =>$option[2], 
                'created_at' => $now_time,
                'updated_at' => $now_time
            ];
        }

        VvipSubOptionXref::insert($insert_data);
    }
}
