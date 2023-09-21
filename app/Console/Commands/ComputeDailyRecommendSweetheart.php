<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SearchService;
use App\Models\DailyRecommendSweetheart;
use Illuminate\Support\Facades\Log;

class ComputeDailyRecommendSweetheart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ComputeDailyRecommendSweetheart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '計算每日推薦甜心名單';

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
        $insert_data = [];

        //人氣甜心
        $recommend_popular_sweetheart = SearchService::personal_page_recommend_popular_sweetheart_all_list_query()->get();
        foreach($recommend_popular_sweetheart as $sweetheart)
        {
            $insert_data[] = [
                'user_id' => $sweetheart->id,
                'sweetheart_type' => 'popular',
                'truth_message_count' => $sweetheart->received_messages_count
            ];
        }

        //新進甜心
        $recommend_new_sweetheart = SearchService::personal_page_recommend_new_sweetheart_all_list_query()->get();
        foreach($recommend_new_sweetheart as $sweetheart)
        {
            $insert_data[] = [
                'user_id' => $sweetheart->id,
                'sweetheart_type' => 'new',
                'truth_message_count' => null
            ];
        }

        DailyRecommendSweetheart::truncate();
        DailyRecommendSweetheart::insert($insert_data);

    }
}
