<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

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
                    ->select($sub_type_table_name.'.*', 'vvip_sub_option_xref.id as xref_id')
                    ->get();
    }
}
