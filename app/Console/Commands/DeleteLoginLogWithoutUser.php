<?php
namespace App\Console\Commands;

use App\Models\LogUserLogin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteLoginLogWithoutUser extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'DeleteLoginLogWithoutUser';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '維護舊資料＿已移除帳號的users log_user_login資料也要一併刪除';

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
        DB::beginTransaction();
        try {
            $getList = LogUserLogin::leftJoin('users','users.id','log_user_login.user_id')
                ->selectRaw('log_user_login.user_id')
                ->whereRaw('users.id is null')
                ->GroupBy('log_user_login.user_id')
                ->get();

            foreach ($getList as $key => $value){
                \App\Models\LogUserLogin::where('user_id',$value->user_id)->delete();
            }

            DB::commit();
            Log::info('資料維護完成');
            dd('資料維護完成!');

        } catch (\Exception $e) {
            Log::info('資料維護失敗'.$e->getMessage() .' line: '.$e->getLine());
            DB::rollBack();
        }
	}
}
