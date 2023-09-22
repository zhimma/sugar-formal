<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LineNotifyService as LineNotify;
use App\Services\SearchService;
use App\Models\DailyRecommendSweetheart;

class LineNotifyRecommendList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'LineNotifyRecommendList';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '推送Line通知-personal page推薦名單';

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
        $lineNotify = new LineNotify;

        //新進甜心
        $meta_constraint = SearchService::get_user_search_area_constraint(['高雄市', '臺南市', '臺中市', '桃園市', '新北市', '臺北市'], [null, null, null, null, null, null]);
        $new_sweetheart_list = DailyRecommendSweetheart::get_daily_recommend_new_sweetheart_query()
                                                        ->whereHas('user', function($query) use($meta_constraint) {
                                                            $query->whereHas('user_meta', $meta_constraint);
                                                        })
                                                        ->limit(20)
                                                        ->get();
        
        foreach($new_sweetheart_list as $sweetheart)
        {
            $message = "\n";
            $message .= "推薦新進甜心: ";
            $message .= $sweetheart->user->name . "\n";
            $message .= route("viewuser", ['uid' => $sweetheart->user->id]) . "\n";
            $picurl = url('/') . ($sweetheart->user->meta->pic ? (file_exists(public_path() . $sweetheart->user->meta->pic) ? $sweetheart->user->meta->pic : '/new/images/female.png') : '/new/images/female.png');
            $lineNotify->sendLineNotifyNewRecommendList($message, $picurl);
        }

        //人氣甜心
        $meta_constraint = SearchService::get_user_search_area_constraint(['高雄市', '臺南市', '臺中市', '桃園市', '新北市', '臺北市'], [null, null, null, null, null, null]);
        $popular_sweetheart_list = DailyRecommendSweetheart::get_daily_recommend_popular_sweetheart_query()
                                                            ->whereHas('user', function($query) use($meta_constraint) {
                                                                $query->whereHas('user_meta', $meta_constraint);
                                                            })
                                                            ->limit(20)
                                                            ->get();
                                                
        
        foreach($popular_sweetheart_list as $sweetheart)
        {
            $message = "\n";
            $message .= "推薦人氣甜心: ";
            $message .= $sweetheart->user->name . "(真心話數:" . $sweetheart->user->received_messages_count . ")" . "\n";
            $message .= route("viewuser", ['uid' => $sweetheart->user->id]) . "\n";
            $picurl = url('/') . ($sweetheart->user->meta->pic ? (file_exists(public_path() . $sweetheart->user->meta->pic) ? $sweetheart->user->meta->pic : '/new/images/female.png') : '/new/images/female.png');
            
            $lineNotify->sendLineNotifyPopularRecommendList($message, $picurl);
        }
        
    }
}
