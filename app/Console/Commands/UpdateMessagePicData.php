<?php
namespace App\Console\Commands;

use App\Models\Posts;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateMessagePicData extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'UpdateMessagePicData';

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
            $getMsgList = DB::table('message')->whereNotNull('pic')->get();
            foreach ($getMsgList as $key => $value){
                $pics=json_decode($value->pic,true);
                $newPicData=[];
                if(count($pics)){
                    foreach ($pics as $picKey => $picValue){
                        if(!is_array($picValue)){
                            $newPicData[$picKey]['origin_name']=substr(strrchr($picValue, "/" ) ,1);
                            $newPicData[$picKey]['file_path']=$picValue;
                        }
                    }
                }
                if(count($newPicData)){
                    DB::table('message')->updateOrInsert(['id'=> $value->id], ['pic'=>json_encode($newPicData)]);
                }

            }
            DB::commit();
            dd('照片資料維護完成!');
            Log::info('message table date pic 照片資料維護完成');

        } catch (\Exception $e) {
            Log::info('message table  pic照片欄位資料維護失敗'.$e->getMessage() .' line: '.$e->getLine());
            DB::rollBack();
        }
	}
}
