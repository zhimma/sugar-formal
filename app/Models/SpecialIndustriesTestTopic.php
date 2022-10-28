<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class SpecialIndustriesTestTopic extends Model
{
    protected $table = 'special_industries_test_topic';

    protected $fillable = [
        'is_hide',
    ];

    public static function generate_topic($setup_id){

        //讀取題目設定
        $setup = SpecialIndustriesTestSetup::where('id', $setup_id)->first();

        //產生題目
        $test_topic_array = [];
        $correct_answer_array = [];

        //隨機挑選異常會員
        $warn_ban_topic = User::select('users.id as topic_user_id');
        
        if($setup->is_banned)
        {
            $warn_ban_topic = $warn_ban_topic->addSelect('banned_users.id as banned_id','banned_users.reason as banned_reason');
        }
        if($setup->is_warned)
        {
            $warn_ban_topic = $warn_ban_topic->addSelect('warned_users.id as warned_id','warned_users.reason as warned_reason');
        }
        if($setup->is_ever_banned)
        {
            $warn_ban_topic = $warn_ban_topic->addSelect('is_banned_log.id as is_ever_banned_id','is_banned_log.reason as is_ever_banned_reason');
        }
        if($setup->is_ever_warned)
        {
            $warn_ban_topic = $warn_ban_topic->addSelect('is_warned_log.id as is_ever_warned_id','is_warned_log.reason as is_ever_warned_reason');
        }
        
        if($setup->is_banned)
        {
            $warn_ban_topic = $warn_ban_topic->leftJoin('banned_users','banned_users.member_id','users.id');
        }
        if($setup->is_warned)
        {
            $warn_ban_topic = $warn_ban_topic->leftJoin('warned_users','warned_users.member_id','users.id');
        }
        if($setup->is_ever_banned)
        {
            $warn_ban_topic = $warn_ban_topic->leftJoin('is_banned_log','is_banned_log.user_id','users.id');
        }
        if($setup->is_ever_warned)
        {
            $warn_ban_topic = $warn_ban_topic->leftJoin('is_warned_log','is_warned_log.user_id','users.id');
        }
        
        if($setup->start_time != '0000-00-00 00:00:00')
        {
            $warn_ban_topic = $warn_ban_topic->where('users.last_login','>=',$setup->start_time);
        }
        if($setup->end_time != '0000-00-00 00:00:00')
        {
            $warn_ban_topic = $warn_ban_topic->where('users.last_login','<=',$setup->end_time);
        }
        if($setup->gender)
        {
            $warn_ban_topic = $warn_ban_topic->where('users.engroup',$setup->gender);
        }

        $warn_ban_topic = $warn_ban_topic->where(function($query) use($setup){
            if($setup->is_banned)
            {
                $query = $query->whereNotNull('banned_users.id');
            }
            if($setup->is_warned)
            {
                $query = $query->orWhereNotNull('warned_users.id');
            }
            if($setup->is_ever_banned)
            {
                $query = $query->orWhereNotNull('is_banned_log.id');
            }
            if($setup->is_ever_warned)
            {
                $query = $query->orWhereNotNull('is_warned_log.id');
            }
        });

        $warn_ban_topic = $warn_ban_topic->groupBy('users.id')->inRandomOrder();
        if($setup->select_member_count)
        {
            $warn_ban_topic = $warn_ban_topic->take($setup->select_member_count);
        }
        $warn_ban_topic = $warn_ban_topic->get();

        //題目插入異常會員
        foreach($warn_ban_topic as $topic)
        {
            if($topic->banned_id)
            {
                $test_topic_array[] = $topic->topic_user_id;
                $correct_answer_array[$topic->topic_user_id] = ['banned',$topic->banned_reason];
            }
            elseif($topic->warned_id)
            {
                $test_topic_array[] = $topic->topic_user_id;
                $correct_answer_array[$topic->topic_user_id] = ['warned',$topic->warned_reason];
            }
            elseif($topic->is_ever_banned_id)
            {
                $test_topic_array[] = $topic->topic_user_id;
                $correct_answer_array[$topic->topic_user_id] = ['banned',$topic->is_ever_banned_reason];
            }
            elseif($topic->is_ever_warned_id)
            {
                $test_topic_array[] = $topic->topic_user_id;
                $correct_answer_array[$topic->topic_user_id] = ['warned',$topic->is_ever_warned_reason];
            }
        }
        
        //正常會員
        $normal_topic = User::leftJoin('banned_users','banned_users.member_id','users.id')
                                            ->leftJoin('is_banned_log','is_banned_log.user_id','users.id')
                                            ->leftJoin('warned_users','warned_users.member_id','users.id')
                                            ->leftJoin('is_warned_log','is_warned_log.user_id','users.id');
        
        if($setup->start_time != '0000-00-00 00:00:00')
        {
            $normal_topic = $normal_topic->where('users.last_login','>=',$setup->start_time);
        }
        if($setup->end_time != '0000-00-00 00:00:00')
        {
            $normal_topic = $normal_topic->where('users.last_login','<=',$setup->end_time);
        }
        if($setup->gender)
        {
            $normal_topic = $normal_topic->where('users.engroup',$setup->gender);
        }

        $normal_topic = $normal_topic->whereNull('banned_users.id')
                                        ->whereNull('is_banned_log.id')
                                        ->whereNull('warned_users.id')
                                        ->whereNull('is_warned_log.id');
        $normal_topic = $normal_topic->select('users.id as topic_user_id');
        $normal_topic = $normal_topic->inRandomOrder()->take(count($test_topic_array) + $setup->normal_member_count)->get();

        //題目插入正常會員
        $normal_count = 0;
        foreach($normal_topic as $topic)
        {
            $test_topic_array[] = $topic->topic_user_id;
            $correct_answer_array[$topic->topic_user_id] = ['pass',''];
            $normal_count = $normal_count + 1;
        }

        //儲存題目
        $test_topic = new SpecialIndustriesTestTopic;
        $test_topic->test_setup_id = $setup_id;
        $test_topic->topic_count = count($test_topic_array);
        $test_topic->normal_count = $normal_count;
        $test_topic->test_topic = json_encode($test_topic_array);
        $test_topic->correct_answer = json_encode($correct_answer_array);
        $test_topic->save();

        return $test_topic->id;

    }
}
