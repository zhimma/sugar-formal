<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Reported;
use App\Models\LogUserLogin;
use App\Models\DataForFilterByInfo;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class FillDataForFilterByInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'FillDataForFilterByInfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill Data For Filter By User AdvInfo';

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
     * @return mixed
     */
    public function handle()
    {
        //
        try {
            $date_start = \Carbon\Carbon::now()->subDays(1)->format('Y-m-d H:i:s');
            $date_end = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            $wantIndexArr = array('message_count_7'
				,'visit_other_count_7'
				,'message_count'
				,'visit_other_count'
				,'be_blocked_other_count'
				,'blocked_other_count'
			);
			$query = LogUserLogin::where('created_at', '>=', $date_start)->where('created_at', '<=', $date_end)->select('user_id')->groupBy('user_id')->with('user');
			echo $query->toSql()."\n\r";
			echo '$date_start='.$date_start."\n\r";
			echo '$date_end='.$date_end."\n\r";
			$gUser_set =$query->get();

			$data = [];
			DataForFilterByInfo::truncate();
			foreach($gUser_set  as $k => $row) {
				$gUser = $row->user;
				if(!isset($gUser) || !$gUser) $gUser=new User;
				if(!isset($gUser->id)) $gUser->id = $row->user_id;
				$cur_advInfo = $gUser->getAdvInfo($wantIndexArr);
				$cur_advInfo['be_reported_other_count'] = Reported::cntr($gUser->id);
				$cur_advInfo['created_at'] = $date_end;
				$cur_advInfo['updated_at'] = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
				$cur_advInfo['user_id'] = $gUser->id;
				DataForFilterByInfo::create($cur_advInfo);
				$cur_advInfo = null;
				$gUser = null;
			}
            
        } catch (\Exception $e) {
            //Log::info('PR新增失敗'.$e->getMessage() .' LINE:'.$e->getLine());
			print('PiR新增失敗'.$e->getMessage() .' LINE:'.$e->getLine());
        }
    }
}
