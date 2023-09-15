<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\LogSystemDayStatistic;
use Illuminate\Support\Facades\Http;
use App\Services\LineNotifyService as LineNotify;

class send_registed_users_statistics_by_LineNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_registed_users_statistics_by_LineNotify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '寄送Line資訊';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date_yesterday = Carbon::yesterday()->toDateString();

        // 昨日男會員數
        $yesterdayMaleCount = 
        LogSystemDayStatistic::where('date', $date_yesterday)->first()->number_of_male_registrants
        ??
        \App\Models\User::without(['user_meta', 'vip'])
            ->select('id')
            ->where('engroup', 1)
            ->whereBetween('created_at', [$date_yesterday, $date_yesterday . ' 23:59:59'])
            ->count();

        // 昨日女會員數
        $yesterdayWomaleCount = 
        LogSystemDayStatistic::where('date', $date_yesterday)->first()->number_of_female_registrants
        ??
        \App\Models\User::without(['user_meta', 'vip'])
            ->select('id')
            ->where('engroup', 2)
            ->whereBetween('created_at', [$date_yesterday, $date_yesterday . ' 23:59:59'])
            ->count();



        $date_2days_ago = Carbon::today()->subDays(2)->toDateString();

        // 前日男會員、人數統計
        $two_days_ago_male = \App\Models\User::without(['user_meta', 'vip'])
            ->select('id')
            ->where('engroup', 1)
            ->whereBetween('created_at', [$date_2days_ago, $date_2days_ago . ' 23:59:59'])
            ->get();
        Log::Info(\App\Models\SimpleTables\banned_users::whereIn('member_id', $two_days_ago_male->pluck('id'))->get());
        $two_days_ago_male_count = LogSystemDayStatistic::where('date', $date_2days_ago)->first()->number_of_male_registrants ?? $two_days_ago_male->count();
        $two_days_ago_male_count_with_banned = \App\Models\SimpleTables\banned_users::whereIn('member_id', $two_days_ago_male->pluck('id'))->count();
        $two_days_ago_male_count_without_banned = $two_days_ago_male_count - $two_days_ago_male_count_with_banned;

        // 前日女會員、人數統計
        $two_days_ago_womale = \App\Models\User::without(['user_meta', 'vip'])
            ->select('id')
            ->where('engroup', 2)
            ->whereBetween('created_at', [$date_2days_ago, $date_2days_ago . ' 23:59:59'])
            ->get();
        $two_days_ago_womale_count = LogSystemDayStatistic::where('date', $date_2days_ago)->first()->number_of_female_registrants ?? $two_days_ago_womale->count();
        $two_days_ago_womale_count_with_banned = \App\Models\SimpleTables\banned_users::whereIn('member_id', $two_days_ago_womale->pluck('id'))->count();
        $two_days_ago_womale_count_without_banned = $two_days_ago_womale_count - $two_days_ago_womale_count_with_banned;
    


        $date_3days_ago = Carbon::today()->subDays(3)->toDateString();

        // 大前日男會員、人數統計
        $three_days_ago_male = \App\Models\User::without(['user_meta', 'vip'])
            ->select('id')
            ->where('engroup', 1)
            ->whereBetween('created_at', [$date_3days_ago, $date_3days_ago . ' 23:59:59'])
            ->get();
        $three_days_ago_male_count = LogSystemDayStatistic::where('date', $date_3days_ago)->first()->number_of_male_registrants ?? $three_days_ago_male->count();
        $three_days_ago_male_count_with_banned = \App\Models\SimpleTables\banned_users::whereIn('member_id', $three_days_ago_male->pluck('id'))->count();
        $three_days_ago_male_count_without_banned = $three_days_ago_male_count - $three_days_ago_male_count_with_banned;

        // 大前日女會員、人數統計
        $three_days_ago_womale = \App\Models\User::without(['user_meta', 'vip'])
            ->select('id')
            ->where('engroup', 2)
            ->whereBetween('created_at', [$date_3days_ago, $date_3days_ago . ' 23:59:59'])
            ->get();
        $three_days_ago_womale_count = LogSystemDayStatistic::where('date', $date_3days_ago)->first()->number_of_female_registrants ?? $three_days_ago_womale->count();
        $three_days_ago_womale_count_with_banned = \App\Models\SimpleTables\banned_users::whereIn('member_id', $three_days_ago_womale->pluck('id'))->count();
        $three_days_ago_womale_count_without_banned = $three_days_ago_womale_count - $three_days_ago_womale_count_with_banned;
    
        $message  = "\n昨日註冊男會員: $yesterdayMaleCount 人";
        $message .= "\n昨日註冊女會員: $yesterdayWomaleCount 人";
        $message .= "\n前日註冊男會員-被Ban男會員: $two_days_ago_male_count_without_banned 人 ( $two_days_ago_male_count - $two_days_ago_male_count_with_banned = $two_days_ago_male_count_without_banned )";
        $message .= "\n前日註冊女會員-被Ban的女會員: $two_days_ago_womale_count_without_banned 人 ( $two_days_ago_womale_count - $two_days_ago_womale_count_with_banned = $two_days_ago_womale_count_without_banned )";
        $message .= "\n大前日註冊男會員-被Ban男會員: $three_days_ago_male_count_without_banned 人 ( $three_days_ago_male_count - $three_days_ago_male_count_with_banned = $three_days_ago_male_count_without_banned )";
        $message .= "\n大前日註冊女會員-被Ban女會員: $three_days_ago_womale_count_without_banned 人 ( $three_days_ago_womale_count - $three_days_ago_womale_count_with_banned = $three_days_ago_womale_count_without_banned )";

        $lineNotify = new LineNotify;
        $lineNotify->sendLineNotifyMessage($message);
    }
}
