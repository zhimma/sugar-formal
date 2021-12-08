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

class CompareImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CompareImages {pic?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compare Images';
    
    protected $now_pic = null;


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
            $this->now_pic =  null;
            $specific_pic  = $this->argument('pic');
            if(!$specific_pic) {
                if(!(\App::environment('CFP') || \App::environment('local'))) {
                    echo '本命令只能在特定主機或測試環境下執行，已中止';
                    Log::info('CompareImages:本命令只能在特定主機或測試環境下執行，已中止');
                    return;
                }
                
                Log::info('CompareImages:開始比對圖片');
            }
            else {
                 Log::info('CompareImages:開始比對圖片 $specific_pic='.$specific_pic);
                
            }
            $imgEncodeEntry = ImagesCompareEncode::where('encode','<>','[]')->where('pic','<>','/img/illegal.jpg')->orderBy('id')->get();
            $lastEncodeEntry = $imgEncodeEntry->last();            

            if($specific_pic) {
                $this->now_pic = $specific_pic;
                $encodeEntry = $imgEncodeEntry->where('pic',$specific_pic);
                $statusAllEntry = ImagesCompareStatus::where('pic',$specific_pic)->get();                  
            }
            else {
                $check_break_id = ImagesCompareStatus::where('status',0)->where('is_specific',0)->where('is_error',0)->min('encode_break_id');                
                
                if(($lastEncodeEntry??false) && ($check_break_id??false) && $check_break_id<$lastEncodeEntry->id) {
                    $encodeEntry1 = $imgEncodeEntry->where('id','>',$check_break_id);
                    $encodeEntry2 = $imgEncodeEntry->where('id','<=',$check_break_id);
                    $encodeEntry = $encodeEntry1->merge($encodeEntry2);
                }
                else {
                    $encodeEntry = $imgEncodeEntry;
                }
                
                $statusAllEntry = ImagesCompareStatus::get();                
            }
            
            $memPicAllEntry = MemberPic::withTrashed()->select('member_id','pic','created_at','updated_at')->where('pic','<>','')->whereNotNull('pic')->orderByDesc('id')->get();
            $avatarAllEntry = UserMeta::select('user_id','pic','created_at','updated_at')->where('pic','<>','')->whereNotNull('pic')->orderByDesc('id')->get();
            $delAvatarAllEntry = AvatarDeleted::select('user_id','pic','created_at','updated_at')->where('pic','<>','')->whereNotNull('pic')->orderByDesc('id')->get();
            
            foreach($encodeEntry as $imgEncode) {
                $is_not_compare = false;
                $nowPicEntry = ImagesCompareService::getEntryByPic($imgEncode->pic);

                if($nowPicEntry && !ImagesCompareService::isNeedCompareByEntry($nowPicEntry)) {
                    $is_not_compare=true;
                }

                $this->now_pic = $imgEncode->pic;
                $statusEntry = $statusAllEntry->where('pic',$imgEncode->pic)->first();

                if($statusEntry) {
                    if(!$statusEntry->is_error) {
                        if($statusEntry->status==1  ) {
                            if($statusEntry->start_time && Carbon::now()->diffInMinutes(Carbon::parse($statusEntry->start_time))<ImagesCompareStatus::$hold_too_long_time) {
                                if($specific_pic) {
                                    echo '未超過'.ImagesCompareStatus::$hold_too_long_time.'分鐘的status=1不比對'; 
                                    Log::info('未超過'.ImagesCompareStatus::$hold_too_long_time.'分鐘的status=1不比對 specific_pic='.$specific_pic); 
                                }
                                $is_not_compare=true;
                            }
                        }
                      
                        if(($lastEncodeEntry??false) && $statusEntry->status==0 && $statusEntry->encode_break_id==$lastEncodeEntry->id) {
                            if($specific_pic) {
                                echo '中斷點等於encode最後一筆id 不比對';
                                Log::info('中斷點等於encode最後一筆id 不比對  specific_pic='.$specific_pic); 
                            }
                            $is_not_compare=true;
                        }
                    }
                }
                else {
                    $statusEntry = new ImagesCompareStatus();
                    $statusEntry->pic = $imgEncode->pic;
                }
                
                $statusEntry->status=1;
                $statusEntry->queue=0;
                $statusEntry->is_error=0;
                $statusEntry->is_specific = $specific_pic?1:0;
                $statusEntry->start_time=Carbon::now();
                $statusEntry->save();

                $nowUserId = $nowPicEntry->user_id??$nowPicEntry->member_id;
                $notCheckPicArr = [];
                if($nowUserId) {
                    $notCheckPicArr = $memPicAllEntry->where('member_id',$nowUserId)->pluck('pic')->all();
                    $nowUserAvatar = $avatarAllEntry->where('user_id',$nowUserId)->first();                

                    if($nowUserAvatar) $notCheckPicArr[]=$nowUserAvatar->pic;
                }

                $targetArr = $imgEncodeEntry->whereNotIn('pic',$notCheckPicArr)->where('id','>',$statusEntry->encode_break_id??0);
                if(ImagesCompareService::$sys_pic_arr) {
                    $targetArr = $targetArr->whereNotIn('pic',ImagesCompareService::$sys_pic_arr);
                }
                $targetArr = $targetArr->all();
                $srcEncode =  json_decode($imgEncode->encode,true);
                if(!$is_not_compare) {
                    foreach($targetArr as $k=>$target) {
                        $interval = 5000;
                        if($k%$interval==0 && intval($k/$interval)>0) {
                            $statusEntry->encode_break_id = $last_target->id;
                            $statusEntry->save();
                        }
                        $last_target = null;
                        $compare = ImagesCompare::where('pic',$imgEncode->pic)->where('finded_pic',$target->pic)->firstOrNew();
                        
                        if($compare->id) continue;

                        $targetEncode =  json_decode($target->encode,true);
                        if(!$targetEncode || count($targetEncode)==0) continue;
                        
                        $srcDiff = array_diff_key($srcEncode,$targetEncode);
                        $targetDiff = array_diff_key($targetEncode,$srcEncode);
                        
                        $srcDiffSum = array_sum($srcDiff);
                        $targetDiffSum = array_sum($targetDiff); 
                        
                        $srcInterset = array_intersect_key($srcEncode,$targetEncode);
                        $targetInterset = array_intersect_key($targetEncode,$srcEncode);                        

                        $srcPartInterDiffSum = 0;
                        $i=0;
                        $srcIntersetSum=0;
                        foreach($srcInterset  as $ki=>$vi) {  
                            if($i>=10) break;
                            $srcPartInterDiffSum+= ($vi-$targetEncode[$ki]>0)?($vi-$targetEncode[$ki]):0;
                            $srcIntersetSum+=$vi;
                            $i++;
                        }
                        $srcInterPercent =100-( 100*$srcPartInterDiffSum/ $srcIntersetSum);

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
                            $compare->pic = $imgEncode->pic;
                            $compare->finded_pic = $target->pic;
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
                }
                elseif($specific_pic) {
                    Log::info('不比對  specific_pic='.$specific_pic); 
                }
                $statusEntry->status=0;                  
                $statusEntry->encode_break_id = $target->id;
                $statusEntry->save();
                $last_target = null;
                
                if(time()-$stime>86400) {
                    Log::info('CompareImages:超過限制時間仍未完成，強制結束比對圖片 pic='.$statusEntry->pic);            
                    exit;
                }
            }  

            if(!$specific_pic) Log::info('CompareImages:結束比對圖片');            
            else                  Log::info('CompareImages:結束比對圖片 $specific_pic='.$specific_pic);
        } catch (\Exception $e) {
            $now_pic = $this->now_pic;
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
            
            Log::info('CompareImages:比對圖片失敗'.$e->getMessage() .' LINE:'.$e->getLine().' $this->now_pic ='.$this->now_pic );
        }

    }
}
