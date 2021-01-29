<?php
namespace App\Console\Commands;

use App\Models\UserMeta;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BlockAreaUpdate extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'BlockAreaUpdate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '針對舊資料,更新blockarea欄位 (ex: 臺北市中正區, 臺北市全區)';

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
            $getDataList = UserMeta::whereNotNull('blockcity')->get();//->where('user_id',689)->get();
            foreach ($getDataList as $data)
            {
                //移除空白blockcity
                $blockcityCheck = explode(',',$data->blockcity);
                $blockareaCheck = explode(',',$data->blockarea);
                foreach ($blockcityCheck as $key2 => $value2){
                    if(empty($value2)){
                        unset($blockcityCheck[$key2]);
                        unset($blockareaCheck[$key2]);
                    }
                }

                //整理blockarea欄位,寫入格式為city+area (ex: 臺北市中正區, 臺北市全區)
                foreach ($blockcityCheck as $citykey => $cityval){
                    $area_str = str_replace($cityval,'',$blockareaCheck[$citykey]);
                    //$area_str = empty($blockareaCheck[$citykey]) ? '全區' : $blockareaCheck[$citykey];
                    $area_str = empty($area_str) ? '全區' : $area_str;
                    $blockareaCheck[$citykey] = $cityval . $area_str;
                }
                $newBlockArea = implode(",", $blockareaCheck);

                $data->blockarea = $newBlockArea;
                $data->save();
            }
            $this->info('blockarea 欄位更新完成。');

            DB::commit();
        } catch (\Exception $e) {
            Log::info('user_meta blockarea欄位更新失敗'.$e->getMessage() .' LINE:'.$e->getLine());
            DB::rollBack();
        }
	}
}
