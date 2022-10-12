<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpecialIndustriesTestSetup;
use App\Models\SpecialIndustriesTestTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminController extends \App\Http\Controllers\BaseController
{
    public function special_industries_judgment_training_setup()
    {
        return view('admin.special_industries_judgment_training_setup');
    }

    public function special_industries_judgment_training_setup_set(Request $request)
    {
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

        $test_topic = new SpecialIndustriesTestTopic;
        $test_topic->test_setup_id = $test_setup->id;
        $test_topic->test_topic = '';
        $test_topic->correct_answer = '';
        $test_topic->save();

        return back()->with('message', '新增成功');
    }

    public function special_industries_judgment_training_select()
    {
        $test_topic = SpecialIndustriesTestTopic::get();
        return view('admin.special_industries_judgment_training_select')
                ->with('test_topic', $test_topic);
    }

    public function special_industries_judgment_training_test(Request $request)
    {
        if($request->setup_id)
        {
            $setup = SpecialIndustriesTestSetup::where('id', $request->setup_id)->first();

        }
        else
        {

        }
        return view('admin.special_industries_judgment_training_test');
    }
}
