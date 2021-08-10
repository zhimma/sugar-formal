<?php
namespace App\Console\Commands;

use App\Models\Posts;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateUserAccountStatusUpdateTime extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'UpdateUserAccountStatusUpdateTime';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '維護舊資料＿寫入帳號狀態異動時的更新時間';

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
            $getList = DB::table('account_status_log')->GroupBy('user_id')->get();
            foreach ($getList as $key => $value){
                $user=User::findById($value->user_id);
                if($user){
                    $last_record=DB::table('account_status_log')->where('user_id',$value->user_id)->orderBy('created_at','desc')->first();
                    $user->accountStatus_updateTime= $last_record ? $last_record->created_at : null;
                    $user->save();
                }
            }
            DB::commit();
            //dd('accountStatus_updateTime資料維護完成!');
            Log::info('accountStatus_updateTime資料維護完成');

        } catch (\Exception $e) {
            Log::info('accountStatus_updateTime資料維護失敗'.$e->getMessage() .' line: '.$e->getLine());
            DB::rollBack();
        }
	}
}
