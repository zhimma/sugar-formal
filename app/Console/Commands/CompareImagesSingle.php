<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserMeta;
use App\Models\ImagesCompareStatus;
use App\Models\ImagesCompareEncode;
use App\Models\ImagesCompare;
use App\Models\MemberPic;
use App\Models\AvatarDeleted;
use App\Services\ImagesCompareService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CompareImagesSingle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CompareImagesSingle {pic}  {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compare Images Single';
    
    protected $now_entry = null;


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
        ini_set("max_execution_time",'0');
        ini_set('memory_limit','-1');
        ini_set("request_terminate_timeout",'0');
        set_time_limit(0);
        error_reporting(0);        
        
        try {
            $stime = time();
            $this->now_entry =  null;
            $specific_pic  = $this->argument('pic');
            $force = $this->option('force');

            Log::info('CompareImagesSingle:開始比對圖片 $specific_pic='.$specific_pic);    
            $imgEncodeEntry = collect([]); 
            $lastEncodeEntry = null;
            $imgEncodeQuery = ImagesCompareEncode::where('encode','<>','[]')->where('pic','<>','/img/illegal.jpg');          
            if($specific_pic) {
                $encodeEntry = ImagesCompareEncode::where('pic',$specific_pic)->get();    
                $this->now_entry = $encodeEntry->first();
            }
            else {
                echo '未指定照片路徑，無法比對，中斷執行';
                Log::info('CompareImagesSingle:未指定照片路徑，無法比對，中斷執行 $specific_pic='.$specific_pic);    
            }

            $is_not_compare = false;
            $imgEncode = $this->now_entry;
            $nowPicEntry = ImagesCompareService::getEntryByPic($imgEncode->pic);

            if($nowPicEntry && !ImagesCompareService::isNeedCompareByEntry($nowPicEntry,$force)) {
                $is_not_compare=true;
                echo '不比對 from isNeedCompareByEntry specific_pic='.$specific_pic;
                Log::info('isNeedCompareByEntry return false 不比對  specific_pic='.$specific_pic);                 
            }
            else if(!$nowPicEntry) {
                $is_not_compare=true; 
                return;
            }
                
            $this->now_entry = $imgEncode;
            $now_pic = $imgEncode->pic;
            $statusEntrys = ImagesCompareStatus::where('pic',$now_pic)->get(); 
            $statusNum = count($statusEntrys);
                
            if(!$statusNum) {            
                $statusEntrys = ImagesCompareStatus::where('pic',$now_pic)->get();
                $statusNum = count($statusEntrys);
            }
            if($statusNum>1) {
                echo '請注意 '.$imgEncode->pic.'有重複'.$statusNum.'筆的status'; 
                Log::info('請注意 '.$imgEncode->pic.'有重複'.$statusNum.'筆的status');                    
            }

            $statusEntry = $statusEntrys->sortBy('id')->first();
            
            if($statusEntry) {
                if(!$statusEntry->is_error) {
                    
                    if($statusEntry->status==1  ) {
                        if($statusEntry->start_time && Carbon::now()->diffInMinutes(Carbon::parse($statusEntry->start_time))<ImagesCompareStatus::$hold_too_long_time) {
                            if($specific_pic) {
                                echo '未超過'.ImagesCompareStatus::$hold_too_long_time.'分鐘的status=1不比對'; 
                                Log::info('未超過'.ImagesCompareStatus::$hold_too_long_time.'分鐘的status=1不比對 specific_pic='.$specific_pic); 
                            }
                            $is_not_compare=true;
                            return;
                        }
                    }
                }
            }
            else {
                if($is_not_compare) return;
                $statusEntry = new ImagesCompareStatus();                    
                $statusEntry->pic = $imgEncode->pic;
            }
            $statusEntry->encode_id = $imgEncode->id;
            $statusEntry->status=1;
            $statusEntry->queue=0;
            $statusEntry->is_error=0;
            $statusEntry->is_specific = $specific_pic?1:0;
            $statusEntry->start_time=Carbon::now();
            $statusEntry->save();

            $target = null;
            if(!$is_not_compare) {
                $imgEncodeEntry = $imgEncodeQuery->orderBy('id')->get();
                $lastEncodeEntry = $imgEncodeEntry->last();
                
                if(($lastEncodeEntry??false) && $statusEntry->status==0 && $statusEntry->encode_break_id==$lastEncodeEntry->id) {
                    echo '中斷點等於encode最後一筆id 不比對';
                    Log::info('中斷點等於encode最後一筆id 不比對  specific_pic='.$specific_pic); 
                    $is_not_compare=true;
                }                        
                    
                if(!$is_not_compare)
                {
                    $nowUserId = $nowPicEntry->user_id??$nowPicEntry->member_id;
                    $notCheckPicArr = [];
                    if($nowUserId) {
                        $notCheckPicArr = MemberPic::withTrashed()->select('pic')->where('member_id',$nowUserId)->whereNotNull('pic')->where('pic','<>','')->pluck('pic')->all();                        
                        $nowUserAvatar = UserMeta::select('pic')->where('user_id',$nowUserId)->whereNotNull('pic')->where('pic','<>','')->pluck('pic')->all(); 
                        $delAvatarArr = AvatarDeleted::select('pic')->where('user_id',$nowUserId)->where('pic','<>','')->whereNotNull('pic')->pluck('pic')->all(); 
                        if($nowUserAvatar) $notCheckPicArr[]=$nowUserAvatar->pic;
                        if($delAvatarArr) $notCheckPicArr = array_merge($notCheckPicArr,$delAvatarArr);
                    }                    
                    
                    $targetArr = $imgEncodeEntry->whereNotIn('pic',$notCheckPicArr)->where('id','>',$statusEntry->encode_break_id??0);
                    if(ImagesCompareService::$sys_pic_arr) {
                        $targetArr = $targetArr->whereNotIn('pic',ImagesCompareService::$sys_pic_arr);
                    }
                    $targetArr = $targetArr->all();
                    
                    $srcEncode =  json_decode($imgEncode->encode,true);                    
                    
                    $compareAllEntry = ImagesCompare::get();
                    $compareAllPicArr = $compareAllEntry->groupBy('encode_id')->all();
                    $compareAllEntry = null;
                    $nowCompareFoundArr = collect($compareAllPicArr[$imgEncode->id])->groupBy('found_encode_id')->all();
                    
                    foreach($targetArr as $k=>$target) {
                        $interval = 5000;
                        if($k%$interval==0 && intval($k/$interval)>0) {
                            $statusEntry->encode_break_id = $last_target->id;
                            $statusEntry->save();
                        }
                        $last_target = null;
                        
                        $compareEntry = $nowCompareFoundArr[$target->id]??[];

                        if(!is_countable($compareEntry)) {
                            \Sentry\captureMessage('照片比對程序異常');                            
                            Log::info('CompareImages:照片比對程序異常，強制結束比對圖片 pic='.$statusEntry->pic);            
                            $is_not_compare = true;
                            break;
                        }
                        elseif(count($compareEntry)) {
                            continue;                            
                        }


                        $targetEncode =  json_decode($target->encode,true);
                        if(!$targetEncode || count($targetEncode)==0) continue;
                        
                        $srcDiff = array_diff_key($srcEncode,$targetEncode);
                        $targetDiff = array_diff_key($targetEncode,$srcEncode);
                        
                        $srcDiffSum = array_sum($srcDiff);
                        $targetDiffSum = array_sum($targetDiff); 
                        
                        $srcInterset = array_intersect_key($srcEncode,$targetEncode);
                        $targetInterset = array_intersect_key($targetEncode,$srcEncode);                        

                        $srcPartInterDiffSum = 0;
                        $i = 0;
                        $srcIntersetSum = 0;
                        foreach($srcInterset  as $ki => $vi) {  
                            if($i>=10) break;
                            $srcPartInterDiffSum+= ($vi-$targetEncode[$ki]>0)?($vi-$targetEncode[$ki]):0;
                            $srcIntersetSum+=$vi;
                            $i++;
                        }

                        if($srcIntersetSum == 0) {
                            // 避免 ÷ 0 的問題發生
                            continue;
                        }
                        $srcInterPercent = 100 - ( 100 * $srcPartInterDiffSum / $srcIntersetSum);

                        $targetPartInterDiffSum = 0;
                        $i=0;
                        $targetIntersetSum=0;
                        foreach($targetInterset  as $ki=>$vi) {  
                            if($i>=10) break;
                            $targetPartInterDiffSum+= ($vi-$srcEncode[$ki]>0)?($vi-$srcEncode[$ki]):0;
                            $targetIntersetSum+=$vi;
                            $i++;
                        }
                        $targetInterPercent =100-( 100*$targetPartInterDiffSum/ $targetIntersetSum);

                        $srcPercent = 100- (($srcDiffSum/$imgEncode->total_spot)*100);
                        $targetPercent = 100- (($targetDiffSum/$target->total_spot)*100);                    

                        if((($srcPercent>=90 && $targetPercent>=80) || ($targetPercent>=90 && $srcPercent>=80))
                                && $srcInterPercent>=50 && $targetInterPercent>=50
                        ) {

                            $compare = ImagesCompare::where('encode_id',$imgEncode->id)->where('found_encode_id',$target->id)->firstOrNew();

                            if($compare->id??null) continue;
                            $compare->encode_id = $imgEncode->id;
                            $compare->pic = $imgEncode->pic;
                            $compare->found_encode_id = $target->id;
                            $compare->found_pic = $target->pic;
                            $compare->asc_diff_count = count($srcDiff);
                            $compare->desc_diff_count = count($targetDiff);
                            $compare->asc_diff_sum = $srcDiffSum;
                            $compare->desc_diff_sum = $targetDiffSum;
                            $compare->asc_percent = $srcPercent;
                            $compare->desc_percent = $targetPercent;
                            
                            $compare->asc_inter_part_percent = $srcInterPercent;
                            $compare->desc_inter_part_percent = $targetInterPercent;                             
                            $compare->save();                        
                        }
                        $last_target = $target;
                        $compare=$targetPercent=$srcPercent=$targetDiffSum=$srcDiffSum=$srcDiff=$targetDiff=null;
                        unset($compare,$targetPercent,$srcPercent,$targetDiffSum,$srcDiffSum,$srcDiff,$targetDiff);
                    }
                    
                    $nowCompareFoundArr = $compareAllPicArr[$imgEncode->id] = $targetArr = null;
                }
            }

            if($is_not_compare) {
                Log::info('不比對  specific_pic='.$specific_pic); 
            }
            $statusEntry->status=0;        
            if($target??null)
                $statusEntry->encode_break_id = $target->id??null;
            $statusEntry->save();
            $last_target = null;
                
            Log::info('CompareImages:結束比對圖片 $specific_pic='.$specific_pic);
        } catch (\Exception $e) {
            $now_pic = $this->now_entry->pic;
            if($now_pic ) {
                $now_status = ImagesCompareStatus::where('pic',$now_pic)->first(); 
                if($now_status)
                    $now_status->update(['status'=>0,'is_error'=>1]);
                else {
                    $now_status = new ImagesCompareStatus();
                    $now_status->pic = $now_pic;
                    $now_status->status=0;
                    $now_status->queue=0;
                    $now_status->is_error=1;
                    $now_status->is_specific=1;
                    $now_status->save();
                }
            }
            
            Log::info('CompareImages:比對圖片失敗'.$e->getMessage() .' LINE:'.$e->getLine().'  now_pic ='.$this->now_entry->pic );
        }

    }
}
