<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UsersToBakArea extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'UsersToBakArea';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '';

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
            $getUserList = DB::table('users')->where('last_login','<=', date("Y-m-d H:i:s", strtotime("-1 year")));
            $userIDAry = array();
            foreach ($getUserList->get() as $key => $val){
                $data = (array)$val;
                DB::table('users_bak')->updateOrInsert(['id'=> array_get($data,'id')], $data);
                $userIDAry[] = $val->id;
            }


            $getUserMeta = DB::table('user_meta')->whereIn('user_id', $userIDAry);
            foreach ($getUserMeta->get() as $key => $val){
                $data = (array)$val;
                DB::table('user_meta_bak')->updateOrInsert(['user_id'=> array_get($data,'user_id')], $data);
            }

            $getUserList->delete();
            $getUserMeta->delete();

            DB::commit();
        } catch (\Exception $e) {
            Log::info('帳號移入備份table失敗'.$e->getMessage() .'line:'.$e->getLine());
            DB::rollBack();
        }
	}
}
