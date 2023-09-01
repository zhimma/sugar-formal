<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserOptionsXref;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class TempOptionsXrefCount extends Model
{
    use HasFactory;

    protected $table = 'temp_options_xref_count';

    public static function compute_options_xref_count()
    {
        //會員自定義的標籤清單option_list[type_id][option_id]
        $option_list = [];
        foreach(DB::table('option_type')->get() as $type)
        {
            $table_name = 'option_' . $type->type_name;
            $exclude_type_array = ['occupation'];
            if(!in_array($type->type_name, $exclude_type_array))
            {
                if(Schema::hasColumn($table_name, 'is_custom')) {
                    $temp_option_list = DB::table($table_name)->where('is_custom', 1);
                    $temp_option_list = $temp_option_list->get();
                    foreach($temp_option_list as $option)
                    {
                        $option_list[$type->id][$option->id] = $option->option_name;
                    }
                }
            }
        }

        $xref_list = UserOptionsXref::leftJoin('users', 'user_options_xref.user_id', '=', 'users.id')
                                    ->leftJoin('banned_users', 'users.id', '=', 'banned_users.member_id')
                                    ->leftJoin('banned_users_implicitly', 'users.id', '=', 'banned_users_implicitly.user_id')
                                    ->where('users.last_login', '>=', Carbon::now()->subDays(7)) //篩選出7天內登入的會員
                                    ->where('users.accountStatus', 1) //排除關閉帳號的用戶
                                    ->where('account_status_admin', 1) //排除站方關閉帳號的用戶
                                    ->where('users.id', '!=', 1049) //排除站長
                                    ->whereNull('banned_users.id') //排除封鎖
                                    ->whereNull('banned_users_implicitly.id') //排除隱性封鎖
                                    ->where(function($query) use($option_list){
                                        foreach($option_list as $type_id => $option_id_list)
                                        {
                                            $query->orWhere(function($query) use($type_id, $option_id_list){
                                                $query->where('option_type', $type_id)->whereIn('option_id', array_keys($option_id_list));
                                            });
                                        }
                                    })
                                    ->get()
                                    ;

        //排序
        $count_list = [];
        foreach($xref_list as $xref_item)
        {
            $count_list[$option_list[$xref_item->option_type][$xref_item->option_id]] = ($count_list[$option_list[$xref_item->option_type][$xref_item->option_id]] ?? 0) + 1;
        }
        arsort($count_list); //由小至大排序

        $insert_data = [];
        $now_time = Carbon::now();
        foreach($count_list as $option_name => $count)
        {
            $insert_data[] = [
                'option_name' => $option_name, 
                'count' => $count, 
                'created_at' => $now_time, 
                'updated_at' => $now_time
            ];
        }
        TempOptionsXrefCount::truncate();
        TempOptionsXrefCount::insert($insert_data);
    }
}
