<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OptionOccupation;
use App\Models\OptionPersonalityTraits;
use App\Models\OptionLifeStyle;
use Illuminate\Support\Facades\Log;
use DB;
use Carbon\Carbon;

class UserOptionsXref extends Model
{
    protected $table = 'user_options_xref';

    protected $fillable = [
        'option_id'
    ];

    public static function update_occupation($user_id, $option, $other_option_name)
    {
        $data = UserOptionsXref::firstOrNew(['user_id' => $user_id], ['option_type' => 1]);
        $data->user_id = $user_id;
        $data->option_type = 1;

        if($option)
        {
            if($option == 'other')
            {
                $option_data = OptionOccupation::firstOrNew(['option_name' => $other_option_name], ['is_custom' => 1]);
                $option_data->option_name = $other_option_name;
                $option_data->is_custom = 1;
                $option_data->save();

                
                $data->option_id = $option_data->id;
            }
            else
            {
                $data->option_id = $option;
            }
            $data->save();
        }
        else
        {
            $data->delete();
        }
        
    }

    public function occupation()
    {
        return $this->hasOne(OptionOccupation::class, 'id', 'option_id');
    }

    public static function update_multiple_option($user_id, $option_array)
    {
        $type_list = array_keys($option_array);
        $type_list_id = [];
        foreach(DB::table('option_type')->whereIn('type_name', $type_list)->get()->toArray() as $type)
        {
            $type_list_id[$type->type_name] = $type->id;
        }
        //把所有選項刪除重置
        UserOptionsXref::where('user_id', $user_id)->whereIn('option_type', $type_list_id)->delete();

        //插入新資料
        $now_time = Carbon::now();
        $insert_data = [];
        foreach($type_list as $type)
        {
            foreach($option_array[$type] as $option)
            {
                $insert_data[] = [
                    'user_id' => $user_id, 
                    'option_type' => $type_list_id[$type], 
                    'option_id' => $option, 
                    'created_at' => $now_time,
                    'updated_at' => $now_time
                ];
            }
        }
        UserOptionsXref::insert($insert_data);
    }

    public static function update_option_user_defined($user_id, $type_name, $tag_option_name)
    {
        switch ($type_name){
            case 'personality_traits' :
                $option_model = new OptionPersonalityTraits();
                break;
            case 'life_style' :
                $option_model = new OptionLifeStyle();
                break;
        }
        foreach (json_decode($tag_option_name) as $option_name){
            $option_data = $option_model->firstOrNew(['option_name' => $option_name], ['is_custom' => 1]);
            $option_data->option_name = $option_name;
            $option_data->is_custom = 1;
            $option_data->save();


            $option_type=OptionType::where('type_name', $type_name)->first();
            if($option_type){
                //插入新資料
                $now_time = Carbon::now();
                $insert_data= [
                    'user_id' => $user_id,
                    'option_type' => $option_type->id,
                    'option_id' => $option_data->id,
                    'created_at' => $now_time,
                    'updated_at' => $now_time
                ];
                UserOptionsXref::insert($insert_data);
            }
        }
    }

    public static function get_user_option($user_id, $type_name)
    {
        $option_type=OptionType::where('type_name', $type_name)->first();
        if($option_type){
            $table='option_'.$type_name;
            return UserOptionsXref::leftJoin($table, $table.'.id', 'user_options_xref.option_id')
                ->leftJoin('option_type', 'option_type.id', 'user_options_xref.option_type')
                ->where('user_options_xref.user_id', $user_id)
                ->where('user_options_xref.option_type', $option_type->id)
                ->where($table.'.is_custom', 1)
                ->get();
        }else{
            return null;
        }
    }
}
