<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SpecialIndustriesTestSetup extends Model
{
    protected $table = 'special_industries_test_setup';

    public static function generate_setup($request){
        
        $test_setup = new SpecialIndustriesTestSetup;
        $test_setup->title = $request->test_title ?? '未命名';
        $test_setup->is_banned = $request->is_banned ?? 0;
        $test_setup->is_warned = $request->is_warned ?? 0;
        $test_setup->is_ever_banned = $request->is_ever_banned ?? 0;
        $test_setup->is_ever_warned = $request->is_ever_warned ?? 0;
        $test_setup->start_time = Carbon::parse($request->date_start ?? "0000-00-00 00:00:00");
        $test_setup->end_time = Carbon::parse($request->date_end." 23:59:59" ?? "0000-00-00 00:00:00");
        $test_setup->gender = $request->en_group ?? 0;
        $test_setup->select_member_count = $request->select_member_count * ($request->select_count ?? 0);
        $test_setup->normal_member_count = $request->normal_member_count * ($request->member_count ?? 0);
        $test_setup->save();

        return $test_setup->id;
    }
}
