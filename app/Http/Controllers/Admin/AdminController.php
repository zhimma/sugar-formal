<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpecialIndustriesTestAnswer;
use App\Models\SpecialIndustriesTestSetup;
use App\Models\SpecialIndustriesTestTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;

class AdminController extends \App\Http\Controllers\BaseController
{
    public function special_industries_judgment_training_setup()
    {
        $test_topic = SpecialIndustriesTestTopic::select('special_industries_test_topic.id as topic_id','special_industries_test_topic.*','special_industries_test_setup.*')
                                                ->leftJoin('special_industries_test_setup','special_industries_test_setup.id','special_industries_test_topic.test_setup_id')
                                                ->where('is_hide',false)
                                                ->orderByDesc('special_industries_test_topic.updated_at')
                                                ->get();
        return view('admin.special_industries_judgment_training_setup')
                ->with('test_topic', $test_topic);
    }

    public function special_industries_judgment_training_setup_set(Request $request)
    {
        $setup_id = SpecialIndustriesTestSetup::generate_setup($request);

        $test_topic_id = SpecialIndustriesTestTopic::generate_topic($setup_id);

        return back()->with('message', '新增成功');
    }

    public function special_industries_judgment_training_select()
    {
        $test_topic = SpecialIndustriesTestTopic::select('special_industries_test_topic.id as topic_id','special_industries_test_topic.*','special_industries_test_setup.*')
                                                ->leftJoin('special_industries_test_setup','special_industries_test_setup.id','special_industries_test_topic.test_setup_id')
                                                ->where('is_hide',false)
                                                ->orderByDesc('special_industries_test_topic.updated_at')
                                                ->get();
        return view('admin.special_industries_judgment_training_select')
                ->with('test_topic', $test_topic);
    }

    public function special_industries_judgment_training_test(Request $request)
    {
        $test_topic = SpecialIndustriesTestTopic::where('id',$request->topic_id)
                                            ->first();
        $topic_user = User::whereIn('users.id',json_decode($test_topic->test_topic))
                            ->inRandomOrder()
                            ->get();
        $correct_answer = json_decode($test_topic->correct_answer, true);

        return view('admin.special_industries_judgment_training_test')
                ->with('test_topic', $test_topic)
                ->with('topic_user', $topic_user)
                ->with('correct_answer', $correct_answer)
                ;
    }

    public function special_industries_judgment_training_hide(Request $request)
    {
        $test_topic = SpecialIndustriesTestTopic::where('id',$request->topic_id)->first();
        $test_topic->is_hide = true;
        $test_topic->save();

        return back()->with('message', '刪除成功');
    }

    public function special_industries_judgment_answer_send(Request $request)
    {
        $user = $request->user();
        $topic_id = $request->topic_id;
        $test_topic = SpecialIndustriesTestTopic::where('id',$topic_id)->first();

        $answer_array = [];
        $user_id_array = json_decode($request->answer_user_id);
        $answer_choose_array = json_decode($request->answer_choose);
        
        for ($i=0; $i<$test_topic->topic_count; $i++)
        {
            $answer_array[$user_id_array[$i]] = $answer_choose_array[$i];
        }

        //儲存結果
        $answer = new SpecialIndustriesTestAnswer();
        $answer->test_topic_id = $topic_id;
        $answer->test_user = $user->id;
        $answer->user_answer = json_encode($answer_array);
        $answer->save();

        return response()->json([], 200);
    }

    public function special_industries_judgment_result(Request $request)
    {
        $answer_detail = SpecialIndustriesTestAnswer::leftJoin('special_industries_test_topic','special_industries_test_topic.id','special_industries_test_answer.test_topic_id')
                                                    ->where('special_industries_test_answer.id',$request->answer_id)
                                                    ->first();
        $topic_user = User::whereIn('users.id',json_decode($answer_detail->test_topic))
                            ->get();
        $user_answer = json_decode($answer_detail->user_answer,true);
        $correct_answer = json_decode($answer_detail->correct_answer,true);
        return response()->json(['answer_id' => $request->answer_id,'topic_user' => $topic_user,'user_answer' => $user_answer,'correct_answer' => $correct_answer], 200);
    }

}
