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
        $new_sweetheart_list = SearchService::personal_page_recommend_new_sweetheart_all_list_query()->get();
        $popular_sweetheart_list = SearchService::personal_page_recommend_popular_sweetheart_all_list_query()->get();

        
        $message = "\n";
        $message .= "推薦新進甜心名單:\n";
        foreach($new_sweetheart_list as $sweetheart)
        {
            $message .= $sweetheart->name . "\n";
            $message .= route("users/advInfo", ['uid' => $sweetheart->id]) . "\n";
        }

        $message  .= "\n";
        $message .= "推薦人氣甜心名單:\n";
        foreach($popular_sweetheart_list as $sweetheart)
        {
            $message .= $sweetheart->name . "\n";
            $message .= route("users/advInfo", ['uid' => $sweetheart->id]) . "\n";
        }

        $lineNotify = new LineNotify;
        $lineNotify->sendLineNotifyRecommendList($message);
        return 0;
    }
}
