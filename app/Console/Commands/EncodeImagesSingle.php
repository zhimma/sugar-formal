<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserMeta;
use App\Models\AvatarDeleted;
use App\Models\ImagesCompareEncode;
use App\Models\MemberPic;
use App\Services\ImagesCompareService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class EncodeImagesSingle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'EncodeImagesSingle {pic}  {encode_by?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encode Images For Compare Single';

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
            $specific_pic  = $this->argument('pic');
            if($specific_pic) {
                if(ImagesCompareEncode::select('pic')->where('pic',$specific_pic)->first()) 
                {
                    return;
                }    
            }
            else {
                echo '未指定照片路徑，無法編碼，中斷執行';
                Log::info('EncodeImagesSingle:未指定照片路徑，無法編碼，中斷執行 $specific_pic='.$specific_pic); 
                return;
            }
            $encode_by  = $this->argument('encode_by')??'cron';
            $encodedPicArr = ImagesCompareEncode::select('pic')->orderBy('id')->where('pic',$specific_pic)->pluck('pic')->all();
            $lastMemPic = null;
            $breakMemPic = null;

            $memPicQuery = MemberPic::withTrashed()->orderByDesc('id');   
            
            $memPicQuery->where('pic',$specific_pic);

            $memPicEntry = $memPicQuery->get();
            
            $metaPicQuery = UserMeta::where(function($q) {
                $q->where('pic','LIKE','/img/Member/%/%/%/%');
                $q->orWhere('pic','LIKE','/Member_pics/%');
            })->orderByDesc('id');

            $metaPicQuery->where('pic',$specific_pic);
            $metaPicEntry = $metaPicQuery->get();
            
            $delAvatarQuery = AvatarDeleted::where(function($q) {
                $q->where('pic','LIKE','/img/Member/%/%/%/%');
                $q->orWhere('pic','LIKE','/Member_pics/%');
            })->orderByDesc('id');

            $delAvatarQuery->where('pic',$specific_pic);
            $delAvatarEntry = $delAvatarQuery->get();

            foreach($memPicEntry as $memPic) {
                if(in_array($memPic->pic,$encodedPicArr)) continue;
                if(ImagesCompareService::addEncodeByEntry($memPic,$encode_by)) {
                    $encodedPicArr[] = $memPic->pic;
                }
                else continue;
                
                if(time()-$stime>7200) {
                     Log::info('EncodeImagesForCompare：超過限制時間仍未完成，強制結束圖片編碼 memPic='.$memPic->pic);
                     exit;
                }                
            }

            foreach($metaPicEntry as $metaPic) {
                if(in_array($metaPic->pic,$encodedPicArr)) continue;
                if(ImagesCompareService::addEncodeByEntry($metaPic,$encode_by)) {
                    $encodedPicArr[] = $metaPic->pic;
                }
                else continue;
                
                if(time()-$stime>7200) {
                     Log::info('EncodeImagesForCompare：超過限制時間仍未完成，強制結束圖片編碼 metaPic='.$metaPic->pic);
                    exit;
                }                 
            } 

            foreach($delAvatarEntry as $delAvatar) {
                if(in_array($delAvatar->pic,$encodedPicArr)) continue;

                if(ImagesCompareService::addEncodeByEntry($delAvatar,$encode_by)) {
                    $encodedPicArr[] = $delAvatar->pic;
                }
                else continue;
  
                if(time()-$stime>7200) {
                     Log::info('EncodeImagesForCompare：超過限制時間仍未完成，強制結束圖片編碼 delAvatar='.$delAvatar->pic);
                    exit;
                }    
            }               
            
        } catch (\Exception $e) {
            
            Log::info('Encode失敗'.$e->getMessage() .' LINE:'.$e->getLine());
        }

    }
}
