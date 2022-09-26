<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Message;
use App\Models\UserMeta;

class ComputeStatisticInformation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ComputeStatisticInformation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '計算統計資訊';

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
        Log::info('start_ComputeStatisticInformation');

        $date_start = date("Y-m-d",strtotime("-15 days", strtotime(date('Y-m-d'))));
        $date_end = date('Y-m-d',strtotime("-1 days", strtotime(date('Y-m-d'))));
        
        $recipients_count_of_vip_male = Message::select('users.id', DB::raw('count(DISTINCT message.to_id) as recipients_count'))
            ->join('users', 'message.from_id', '=', 'users.id')
            ->join('member_vip', 'message.from_id', '=', 'member_vip.member_id')
            ->where('users.id', "<>", 1049)
            ->where('users.engroup', 1)
            ->where('member_vip.active', 1)
            ->where(function ($q) {
                $q->where('member_vip.expiry', 'like', '%0000-00-00 00:00:00%')->orWhere('member_vip.expiry', '>', now());
            })
            ->whereBetween('message.created_at', array($date_start . ' 00:00', $date_end . ' 23:59'))
            ->groupby('message.from_id')
            ->get();

        $avg = $recipients_count_of_vip_male->avg('recipients_count');
        $median = $recipients_count_of_vip_male->median('recipients_count');

        DB::table('log_system_day_statistic')->insert(
            [   'date' => now(),
                'average_recipients_count_of_vip_male_senders'   => $avg,
                'median_recipients_count_of_vip_male_senders'  => $median,
                'created_at' => now(),
                'updated_at' => now()
        ]);

        $recipients_count_of_male = Message::select('users.id', DB::raw('count(DISTINCT message.to_id) as recipients_count'))
            ->join('users', 'message.from_id', '=', 'users.id')
            ->where('users.id', "<>", 1049)
            ->where('users.engroup', 1)
            ->whereBetween('message.created_at', array($date_start . ' 00:00', $date_end . ' 23:59'))
            ->groupby('message.from_id')
            ->get();

        foreach($recipients_count_of_male as $male)
        {
            UserMeta::where('user_id', $male->id)->update(['recipients_count' => $male->recipients_count]);
        }
    }
}
