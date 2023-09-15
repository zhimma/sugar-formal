<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LineNotifyService as LineNotify;
use App\Services\SearchService;

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
        $new_sweetheart_list = SearchService::personal_page_recommend_new_sweetheart_all_list_query()
                                            ->get();
        
        foreach($new_sweetheart_list as $sweetheart)
        {
            $message = "\n";
            $message .= "推薦新進甜心: ";
            $message .= $sweetheart->name . "\n";
            $message .= route("viewuser", ['uid' => $sweetheart->id]) . "\n";
            $picurl = url('/') . ($sweetheart->meta->pic ? (file_exists(public_path() . $sweetheart->meta->pic) ? $sweetheart->meta->pic : '/new/images/female.png') : '/new/images/female.png');
            $lineNotify->sendLineNotifyNewRecommendList($message, $picurl);
        }

        //人氣甜心
        $popular_sweetheart_list = SearchService::personal_page_recommend_popular_sweetheart_all_list_query()
                                                ->get();
        
        foreach($popular_sweetheart_list as $sweetheart)
        {
            $message = "\n";
            $message .= "推薦人氣甜心: ";
            $message .= $sweetheart->name . "(真心話數:" . $sweetheart->received_messages_count . ")" . "\n";
            $message .= route("viewuser", ['uid' => $sweetheart->id]) . "\n";
            $picurl = url('/') . ($sweetheart->meta->pic ? (file_exists(public_path() . $sweetheart->meta->pic) ? $sweetheart->meta->pic : '/new/images/female.png') : '/new/images/female.png');
            
            $lineNotify->sendLineNotifyPopularRecommendList($message, $picurl);
        }
        
    }
}
