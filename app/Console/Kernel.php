<?php

namespace App\Console;

use App\Models\AnonymousChat;
use App\Models\MemberPic;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Services\EnvironmentService;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\UsersToBakArea::class,
        \App\Console\Commands\SendSMS::class,
        \App\Console\Commands\BlockAreaUpdate::class,
        \App\Console\Commands\InsertPR::class,
        \App\Console\Commands\checkwarned::class,
	    \App\Console\Commands\ComputeStatisticInformation::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if(app()->environment('MISC')) {
            //$schedule->command('UserLogin')->timezone('Asia/Taipei')->everyMinute();
            $schedule->command('LocalMachine_AutoBanAndWarn')->timezone('Asia/Taipei')->everyMinute();
        }
        if(app()->environment('MISC_SECOND')) {
            $schedule->command('LocalMachine_AutoBanAndWarn_Second')->timezone('Asia/Taipei')->everyMinute();
        }
        if(app()->environment('CFP')){
            // $schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance')->timezone('Asia/Taipei')->dailyAt('05:00');
            // $schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance')->timezone('Asia/Taipei')->dailyAt('11:00');
            // $schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance')->timezone('Asia/Taipei')->dailyAt('17:00');
            // $schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance')->timezone('Asia/Taipei')->dailyAt('23:00');
            //$schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance')->timezone('Asia/Taipei')->dailyAt('09:00');
            $schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance')->timezone('Asia/Taipei')->dailyAt('21:00');            
            $puppetReq = new Request();
            $puppetReq->only = 'cfpid';
            $schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance',['request'=>$puppetReq])->timezone('Asia/Taipei')->dailyAt('03:00')->monitorName('Fingerprint 比對 - 僅 cfpid');
            //$schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance',['request'=>$puppetReq])->timezone('Asia/Taipei')->dailyAt('09:00');
            //$schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance',['request'=>$puppetReq])->timezone('Asia/Taipei')->dailyAt('15:00');
            //$schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance',['request'=>$puppetReq])->timezone('Asia/Taipei')->dailyAt('21:00');
        }
        if(app()->isProduction() || EnvironmentService::isLocalOrTestMachine()){
            $schedule->command('FillDataForFilterByInfo')->timezone('Asia/Taipei')->dailyAt('01:00')->monitorName('檢查發信、檢舉、封鎖的異常資料 - 01:00');
            $schedule->command('MessageSchedule')->timezone('Asia/Taipei')->dailyAt('01:30')->monitorName('調整會員訊息的處理狀態');
            $schedule->call(function (){
                $this->checkECPayVip();
                $this->checkECPayForValueAddedService();
                $this->checkEmailVailUser();
            })->timezone('Asia/Taipei')->dailyAt('3:00')->monitorName('檢查 VIP 訂單 + 檢查付費隱藏及 VVIP 訂單 + 刪除48小時未驗證會員');
            $schedule->call(function (){
                $this->checkVipExpired();
            })->timezone('Asia/Taipei')->dailyAt('3:10')->monitorName('VIP 會員過期檢查');
            $schedule->call(function (){
                // $this->VIPCheck();
                $this->checkEmailVailUser();
            })->timezone('Asia/Taipei')->dailyAt('4:00')->monitorName('刪除48小時未驗證會員 - 4:00');
            $schedule->command('InsertPR')->timezone('Asia/Taipei')->dailyAt('04:00')->monitorName('會員 PR 值計算');
            $schedule->call(function (){
                $this->checkEmailVailUser();
            })->timezone('Asia/Taipei')->dailyAt('5:00')->monitorName('刪除48小時未驗證會員 - 5:00');
            $schedule->call(function (){
                $this->deleteAnonymousChat();
            })->timezone('Asia/Taipei')->weeklyOn(0, '23:59')->monitorName('刪除匿名聊天室訊息');

            //新增檢查等待更多資料名單
            $schedule->command('Step2CheckExtendRecheck')->timezone('Asia/Taipei')->dailyAt('03:00')->monitorName('檢查等待更多資料名單 - 3:00');
            $schedule->command('Step2CheckExtendRecheck')->timezone('Asia/Taipei')->dailyAt('09:00')->monitorName('檢查等待更多資料名單 - 9:00');
            $schedule->command('Step2CheckExtendRecheck')->timezone('Asia/Taipei')->dailyAt('13:00')->monitorName('檢查等待更多資料名單 - 13:00');
            $schedule->command('Step2CheckExtendRecheck')->timezone('Asia/Taipei')->dailyAt('18:30')->monitorName('檢查等待更多資料名單 - 18:30');
            //新增檢查等待更多資料名單
        }
        if(app()->environment('CFP')){
            //$schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance')->timezone('Asia/Taipei')->dailyAt('05:00');
            //$schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance')->timezone('Asia/Taipei')->dailyAt('11:00');
            //$schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance')->timezone('Asia/Taipei')->dailyAt('17:00');
            //$schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance')->timezone('Asia/Taipei')->dailyAt('23:00');
            $schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance')->timezone('Asia/Taipei')->hourly();
            
            $puppetReq = new Request();
            $puppetReq->only = 'cfpid';
            //$schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance',['request'=>$puppetReq])->timezone('Asia/Taipei')->dailyAt('03:00');
            //$schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance',['request'=>$puppetReq])->timezone('Asia/Taipei')->dailyAt('09:00');
            //$schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance',['request'=>$puppetReq])->timezone('Asia/Taipei')->dailyAt('15:00');
            //$schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance',['request'=>$puppetReq])->timezone('Asia/Taipei')->dailyAt('21:00');
        
            //$schedule->command('EncodeImagesForCompare')->timezone('Asia/Taipei')->dailyAt('02:01');
            //$schedule->command('queue:work --queue=compare_images --daemon --sleep=3 --tries=3 --delay=3  --timeout=0')->timezone('Asia/Taipei')->everyFiveMinutes()->between('02:00', '12:00');
            //$schedule->command('CompareImages')->timezone('Asia/Taipei')->dailyAt('08:00');       
            //$schedule->command('CompareImages  --dsort')->timezone('Asia/Taipei')->everyTenMinutes();//->between('02:00', '12:00');     
        }
        if(app()->isProduction() || app()->isLocal()){
            
            $puppetReq = new Request();
            $puppetReq->only = 'vid';
            $schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance',['request'=>$puppetReq])->timezone('Asia/Taipei')->dailyAt('01:00')->monitorName('Fingerprint 比對 - 全部 - 01:00');
            $schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance',['request'=>$puppetReq])->timezone('Asia/Taipei')->dailyAt('13:00')->monitorName('Fingerprint 比對 - 全部 - 13:00');            
            
            $schedule->call(function (){
                $this->checkEmailVailUser();
            })->timezone('Asia/Taipei')->dailyAt('8:00')->monitorName('刪除48小時未驗證會員 - 8:00');
            $schedule->call(function (){
                // $this->VIPCheck();
                $this->checkEmailVailUser();
            })->timezone('Asia/Taipei')->dailyAt('12:00')->monitorName('刪除48小時未驗證會員 - 12:00');
            $schedule->command('FillDataForFilterByInfo')->timezone('Asia/Taipei')->dailyAt('13:00')->monitorName('檢查發信、檢舉、封鎖的異常資料 - 13:00');
            $schedule->call(function (){
                // $this->VIPCheck();
                $this->checkEmailVailUser();
            })->timezone('Asia/Taipei')->dailyAt('16:00')->monitorName('刪除48小時未驗證會員 - 16:00');
            $schedule->call(function (){
                // $this->VIPCheck();
                $this->checkEmailVailUser();
            })->timezone('Asia/Taipei')->dailyAt('20:00')->monitorName('刪除48小時未驗證會員 - 20:00');
            $schedule->call(function (){
                // $this->VIPCheck();
                $this->checkEmailVailUser();
            })->timezone('Asia/Taipei')->dailyAt('23:59')->monitorName('刪除48小時未驗證會員 - 23:59');
            $schedule->call(function (){
                $this->checkUserPics();
            })->everyFiveMinutes()->timezone('Asia/Taipei')->monitorName('檢查今日上傳的照片數量');
            $schedule->call(function (){
                $this->resetUserPicsSwitches();
            })->timezone('Asia/Taipei')->dailyAt('6:30')->monitorName('重設今日上傳的照片數量');

            $schedule->command('command:checkwarned')->timezone('Asia/Taipei')->dailyAt('07:00')->monitorName('會員警示、封鎖狀態調整及去重複資料');           

            //每週檢查討論區
            $schedule->command('ForumCheck')->timezone('Asia/Taipei')->weeklyOn(1, '2:15')->monitorName('檢查討論區');
            $schedule->command('CompareImages')->timezone('Asia/Taipei')->dailyAt('08:00')->monitorName('照片比對');       
            $schedule->command('CompareImages  --dsort')->timezone('Asia/Taipei')->everyTenMinutes()->monitorName('由最新記錄開始做照片比對');//->between('02:00', '12:00');   
            //$schedule->command('EncodeImagesForCompare')->timezone('Asia/Taipei')->dailyAt('02:01');
            //$schedule->command('queue:work --queue=compare_images --daemon --sleep=3 --tries=3 --delay=3  --timeout=0')->timezone('Asia/Taipei')->everyFiveMinutes()->between('02:00', '12:00');

            //每天計算統計資料
            $schedule->command('ComputeStatisticInformation')->timezone('Asia/Taipei')->dailyAt('00:01')->monitorName('計算統計資料'); 

            //每天檢查疑似八大名單
            $schedule->command('CheckSuspiciousUserList')->timezone('Asia/Taipei')->dailyAt('00:30')->monitorName('檢查疑似八大名單'); 
            $schedule->command("auto:test")->timezone('Asia/Taipei')->everyFifteenMinutes()->monitorName('自動測試');
        }
        if(app()->isProduction()) {
            $schedule->command('send_registed_users_statistics_by_LineNotify')->timezone('Asia/Taipei')->dailyAt('2:00')->monitorName('每日註冊會員統計'); 
            /*
            $schedule->call(function (){
                $this->send_registed_users_statistics_by_LineNotify();
            })->timezone('Asia/Taipei')->dailyAt('2:00');
            */
        }

        //計算options_xref的統計數量
        $schedule->command('ComputeOptionXrefCount')->timezone('Asia/Taipei')->dailyAt('23:59');
        $schedule->command('ComputeOptionXrefCount')->timezone('Asia/Taipei')->dailyAt('11:59');

        $schedule->command('ComputeDailyRecommendSweetheart')->timezone('Asia/Taipei')->dailyAt('00:00');
        if(app()->isProduction()) {
            //發送推薦名單line通知方便後台人員檢查
            $schedule->command('LineNotifyRecommendList')->timezone('Asia/Taipei')->dailyAt('00:01');
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected function checkEmailVailUser(){
        $constraint = function ($query){
            return $query->where('is_active', 0);
        };
        $users = \App\Models\User::with(['user_meta'=>$constraint])
            ->whereHas('user_meta', $constraint)
            ->where('created_at', '<',Carbon::now()->subHours(48))->get();
        foreach ($users as $user){
            \App\Models\LogUserLogin::where('user_id',$user->id)->delete();
            $user->delete();
        }
    }

    protected function checkECPayVip(){
        $vipDatas = \App\Models\Vip::where(['free' => 0, 'active' => 1])->get();
        // $vipDatas = \App\Models\Vip::where('business_id','3137610')->where('order_id','like','SG%')->get();
        foreach ($vipDatas as $vipData){
            \App\Jobs\CheckECpay::dispatch($vipData);
        }
    }

    protected function checkECPayForValueAddedService(){
        $valueAddedServiceData = \App\Models\ValueAddedService::where(['active' => 1])
            ->whereIn('service_name', ['hideOnline', 'VVIP'])
            ->get();
        foreach ($valueAddedServiceData as $Data){
            \App\Jobs\CheckECpayForValueAddedService::dispatch($Data);
        }
    }

    protected function checkVipExpired(){
        $vipDatas = \App\Models\Vip::where(['active' => 1])->get();
        foreach ($vipDatas as $vipData){
            if($vipData->expiry != "0000-00-00 00:00:00"){
                $date = \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $vipData->expiry);
                $now = \Carbon\Carbon::now();
                if($now > $date){
                    $vipData->addToLog(0, 'Background auto cancellation, with expiry: ' . $vipData->expiry);
                    $vipData->removeVIP();
                }
            }
        }
    }

    protected function VIPCheck($date_set = null){
        if(isset($date_set)){
            $date = \Carbon\Carbon::createFromFormat("Y-m-d", $date_set)->toDateString();
        }
        else{
            //異動檔在29號之後一律存至28號，所以要檢查28號的檔案
            if(Carbon::now()->format('d') <= 28){
                $date = \Carbon\Carbon::now()->toDateString();
            }
            else{
                $date = \Carbon\Carbon::now()->format('Ym').'28';
            }
        }
        $datas = \DB::table('viplogs')->where('filename', 'LIKE', '%761404%')->where('created_at', 'LIKE', $date.'%')->get();
        $dateStr = $date;
        $date = str_replace('-', '', $date);
        $string = '';
        if(!file_exists(storage_path('app/RP_761404_'.$date.'.dat'))){
            $file = '';
            $string = $string.'Today\'s file was not found('.storage_path('app/RP_761404_'.$date.'.dat').").\n";
        }
        else{
            $file = File::get(storage_path('app/RP_761404_'.$date.'.dat'));
            //$file = explode(PHP_EOL, $file);
            $file = explode("\n", $file);
        }
        if($datas->count() == 0){
            $string = $string."There's no today's log.\n";
        }
        $nothingStr = '';
        if($string == ''){
            foreach ($file as $key => &$line){
                foreach ($datas as $key2 => &$data){
                    if($line === $data->content){
                        unset($file[$key]);
                        unset($datas[$key2]);
                    }
                }
            }
        }
        else{
            $nothingStr = $string;
        }
        //如果資料庫多，補異動檔，補權限
        if(empty($file) && $datas->count() > 0){
            foreach ($datas as &$line){
                $log_str = '';
                $line = explode(',', $line->content);
                $user = \App\Models\User::where('id', $line[1])->get()->first();
                //若資料庫多的是Cancel
                if($line[7] == 'Delete'){
                    $string = $string."Condition 1(log, Delete):\n";
                    $log_str = $log_str."Condition 1(log, Delete):\n";
                    $vip = \App\Models\Vip::where('member_id', $line[1])->get()->first();
                    //如果該會員有設定到期日，則不做任何動作
                    if(isset($user) && $vip->expiry != '0000-00-00 00:00:00'){
                        $string = $string."The VIP of this user is still valid. (It hasn't expired yet, nothing will be changed.)\n";
                        $log_str = $log_str."The VIP of this user is still valid. (It hasn't expired yet, nothing will be changed.)\n";
                    }
                    //若無，檢查是否已取消權限，並補上異動檔、取消VIP
                    else if(isset($user) && $user->isVip()){
                        $vip = \App\Models\Vip::findById($user->id);
                        $this->logService->cancelLog($vip);
                        $this->logService->writeLogToFile();
                        $tmp = \App\Models\Vip::where('member_id', $user->id)->orderBy('created_at', 'desc')->first()->compactCancel();
                        //$string = $string.'Condition 1(Delete): ';
                        //$log_str = $log_str.'Condition 1(Delete): ';
                        foreach ($line as $l){
                            $string = $string.$l.", ";
                            $log_str = $log_str.$l.", ";
                        }
                        $string = $string."\n";
                        $log_str = $string."\n";
                    }
                    else{
                        $string = $string.'The log over-recorded a member or encountered a NULL data, User: '.$line[1]."\n";
                        $log_str = $log_str.'The log over-recorded a member or encountered a NULL data, User: '.$line[1]."\n";
                    }
                }
                //若資料庫多的是New
                else {
                    $string = $string."Condition 2(log, New):\n";
                    $log_str = $log_str."Condition 2(log, New):\n";
                    //檢查是否已獲得權限
                    if (isset($user) && !$user->isVip() && !$user->isVVIP()) {
                        //若沒獲得權限，補權限
                        $tmp = \App\Models\Vip::upgrade($user->id, $line[0], $line[2], $line[5], 'auto completion', 1, 0);
                        //$string = $string.'Condition 2(New): ';
                        //$log_str = $log_str.'Condition 2(New): ';
                        foreach ($line as $l){
                            $string = $string.$l.", ";
                            $log_str = $log_str.$l.", ";
                        }
                        $string = $string."\n";
                        $log_str = $log_str."\n";
                    } else {
                        $string = $string.'The log over-recorded a member or encountered a NULL data, User: '.$line[1]."\n";
                        $log_str = $log_str.'The log over-recorded a member or encountered a NULL data, User: '.$line[1]."\n";
                    }
                }
                \DB::table('log_vip_crontab')->insert(
                    ['date'    => $dateStr,
                     'user_id' => $line[1],
                     'content' => $log_str]
                );
            }
            //return str_replace("\n", "<br>", $string);
            return $string;
        }
        //如果異動檔多，補權限
        if($datas->count() == 0 && !empty($file)){
            foreach ($file as &$line){
                $log_str = '';
                $line = explode(',', $line);
                if(!isset($line[1])){
                    continue;
                }
                $user = \App\Models\User::where('id', $line[1])->get()->first();
                //若異動檔多的是Delete
                if($line[7] == 'Delete'){
                    $string = $string."Condition 3(file, Delete):\n";
                    $log_str = $log_str."Condition 3(file, Delete):\n";
                    $vip = \App\Models\Vip::where('member_id', $line[1])->get()->first();
                    //如果該會員有設定到期日，則不做任何動作
                    if(isset($user) && $vip->expiry != '0000-00-00 00:00:00'){
                        $string = $string."The VIP of this user is still valid. (It hasn't expired yet, nothing will be changed.)\n";
                        $log_str = $log_str."The VIP of this user is still valid. (It hasn't expired yet, nothing will be changed.)\n";
                    }
                    //若無，則檢查是否已取消權限
                    else if(isset($user) && $user->isVip()){
                        $tmp = \App\Models\Vip::where('member_id', $user->id)->orderBy('created_at', 'desc')->first()->compactCancel();
                        //$string = $string.'Condition 3(Delete): ';
                        //$log_str = $log_str.'Condition 3(Delete): ';
                        foreach ($line as $l){
                            $string = $string.$l.", ";
                            $log_str = $log_str.$l.", ";
                        }
                        $string = $string."\n";
                        $log_str = $log_str."\n";
                    }
                    else{
                        $string = $string.'The file over-recorded a member or encountered a NULL data, User: '.$line[1]."\n";
                        $log_str = $log_str.'The file over-recorded a member or encountered a NULL data, User: '.$line[1]."\n";
                    }
                }
                //若異動檔多的是New
                else {
                    $string = $string."Condition 4(file, New):\n";
                    //檢查是否已獲得權限
                    if (isset($user) && !$user->isVip() && !$user->isVVIP()) {
                        //若沒獲得權限，補權限
                        $tmp = \App\Models\Vip::upgrade($user->id, $line[0], $line[2], $line[5], 'auto completion', 1, 0);
                        //$string = $string.'Condition 4(New): ';
                        //$log_str = $log_str.'Condition 4(New): ';
                        foreach ($line as $l){
                            $string = $string.$l.", ";
                            $log_str = $log_str.$l.", ";
                        }
                        $string = $string."\n";
                        $log_str = $log_str."\n";
                    } else {
                        $string = $string.'The file over-recorded a member or encountered a NULL data, User: '.$line[1]."\n";
                        $log_str = $log_str.'The file over-recorded a member or encountered a NULL data, User: '.$line[1]."\n";
                    }
                }
                \DB::table('log_vip_crontab')->insert(
                    ['date'    => $dateStr,
                     'user_id' => $line[1],
                     'content' => $log_str]
                );
            }
            //return str_replace("\n", "<br>", $string);
            return $string;
        }
        \DB::table('log_vip_crontab')->insert(
            ['date'    => $dateStr,
             'user_id' => '',
             'content' => $nothingStr."Nothing's done."]
        );
        //return str_replace("\n", "<br>", $string."Nothing's done.");
        return $string."Nothing's done.";
    }

    protected function uploadDatFile(){
        //由於異動檔在29號開始一律存至本月28號，所以30號開始一律都不上傳檔案，待下個月再重新開始。
        if(Carbon::now()->format('d') <= 29 && Carbon::now()->format('d') > 1){
            $date = \Carbon\Carbon::now()->subDay()->toDateString();
        }
        elseif(Carbon::now()->format('d') == 1){
            $date = \Carbon\Carbon::now()->subMonth()->format('Ym').'28';
        }
        else{
            \DB::table('log_dat_file')->insert(
                [   'upload_check' => 1,
                    'local_file'   => 'None.',
                    'remote_file'  => 'None.',
                    'content' => "本次程序啟動日為本月29、30、31號，故上傳程序不會啟動。"]
            );
            return "No file, end of the month or the first day of the month.";
        }
        $date = str_replace('-', '', $date);
        if(file_exists(storage_path('app/RP_761404_'.$date.'.dat'))){
            $fileContent = file_get_contents(storage_path('app/RP_761404_'.$date.'.dat'));
            $destinDate = \Carbon\Carbon::now()->toDateString();
            $destinDate = str_replace('-', '', $destinDate);
            $file = 'RP_761404_'.$destinDate.'.dat';
            \GrahamCampbell\Flysystem\Facades\Flysystem::connection('sftp')->put($file, $fileContent);

            \DB::table('log_dat_file')->insert(
                ['upload_check' => 0,
                 'local_file'    => 'RP_761404_'.$date.'.dat',
                 'remote_file' => $file,
                 'content' => "RP_761404_".$date.".dat: 上傳成功。"]
            );
            return "RP_761404_".$date."dat: Upload completed.";
        }
        else{
            \DB::table('log_dat_file')->insert(
                ['upload_check' => 0,
                 'local_file'    => 'RP_761404_'.$date.'.dat',
                 'remote_file' => '',
                 'content' => "沒有找到本地檔案：RP_761404_".$date.".dat，上傳程序沒有執行。"]
            );
            return "File not found, upload process didn't initiate.";
        }
    }

    protected function checkDatFile(){
        //由於異動檔在29號開始一律存至下個月1號，所以29號開始一律都不上傳檔案，待下個月再重新開始。
        //此外，由於原先上傳方式為隔日才上傳前一日的異動檔，故每1號也要跳過上傳。
        //因上述理由，故前一月30日起至下個月1號，藍新端都不會有異動檔，所以檢查要略過。(3/1例外)
        if(Carbon::now()->format('m-d') != "03-01" && Carbon::now()->format('d') > 29 && Carbon::now()->format('d') == 1){
            \DB::table('log_dat_file')->insert(
                [   'upload_check' => 1,
                    'local_file'   => 'None.',
                    'remote_file'  => 'None.',
                    'content' => "本次程序啟動日為本月29、30、31、1號，故檢查程序不會啟動。"]
            );
            return "No file, end of the month or the first day of the month.";
        }
        $localDate = \Carbon\Carbon::now()->subDay()->toDateString();
        $localDate = str_replace('-', '', $localDate);
        if(file_exists(storage_path('app/RP_761404_'.$localDate.'.dat'))){
            $localFileContent = file_get_contents(storage_path('app/RP_761404_'.$localDate.'.dat'));
            $remoteDate = \Carbon\Carbon::now()->toDateString();
            $remoteDate = str_replace('-', '', $remoteDate);
            $remoteFile = 'RP_761404_'.$remoteDate.'.dat';
            $remoteFileContent = \GrahamCampbell\Flysystem\Facades\Flysystem::connection('sftp')->read($remoteFile);
            if($localFileContent == $remoteFileContent){
                \DB::table('log_dat_file')->insert(
                    ['upload_check' => 1,
                     'local_file'    => 'RP_761404_'.$localDate.'.dat',
                     'remote_file' => $remoteFile,
                     'content' => "檔案比較完成：本地與遠端的檔案內容一致。"]
                );
                return "File comparison success.";
            }
            else{
                \DB::table('log_dat_file')->insert(
                    ['upload_check' => 1,
                     'local_file'    => 'RP_761404_'.$localDate.'.dat',
                     'remote_file' => $remoteFile,
                     'content' => "檔案比較失敗：本地與遠端的檔案內容不一致。"]
                );
                $admin = \App\Models\User::findByEmail(config('social.admin.notice-email'));
                $admin->notify(new \App\Notifications\AutoComparisonFailedEmail(\Carbon\Carbon::now()->toDateTimeString(), 'RP_761404_'.$localDate.'.dat', '本地與遠端的檔案內容不一致'));
                return "File comparison failed, the contents of local and remote files are not the same.";
            }
        }
        else{
            \DB::table('log_dat_file')->insert(
                ['upload_check' => 1,
                 'local_file'    => 'RP_761404_'.$localDate.'.dat',
                 'remote_file' => '',
                 'content' => "本地端沒有檔案，檢查程序沒有執行。"]
            );
            $admin = \App\Models\User::findByEmail(config('social.admin.notice-email'));
            $admin->notify(new \App\Notifications\AutoComparisonFailedEmail(\Carbon\Carbon::now()->toDateTimeString(), 'RP_761404_'.$localDate.'.dat', '本地端沒有檔案'));
            return "Local file not found, check process didn't initiate.";
        }
    }

    public function resetUserPicsSwitches(){
        DB::table("queue_global_variables")
            ->where("name", "sent_today_200")->update(["value" => 0, "updated_at" => Carbon::now()]);
        DB::table("queue_global_variables")
            ->where("name", "sent_today_600")->update(["value" => 0, "updated_at" => Carbon::now()]);
        DB::table("queue_global_variables")
            ->where("name", "sent_today_4500")->update(["value" => 0, "updated_at" => Carbon::now()]);
    }

    public function checkUserPics() {
        // 每天超過 250 張發警告信
        // 每天超過 600 發警告信並停止
        // 每個月超過 4500 張停止
        $picCount = MemberPic::withTrashed()->where('created_at', '>', Carbon::today()->format('Y-m-d'))->count();
        $picCountMonth = MemberPic::withTrashed()->whereBetween('created_at', [Carbon::today()->subMonth()->format('Y-m-d'), Carbon::today()->format('Y-m-d')])->count();
        $str = null;
        $isOn = DB::table("queue_global_variables")
                    ->where("name", "similar_images_search")->first()->value;
        $todayHasSent200 = DB::table("queue_global_variables")
            ->where("name", "sent_today_200")->first();
        $todayHasSent600 = DB::table("queue_global_variables")
            ->where("name", "sent_today_600")->first();
        $todayHasSent4500 = DB::table("queue_global_variables")
            ->where("name", "sent_today_4500")->first();
        if ($picCount > 600) {
            if($isOn && $todayHasSent600->value == 0) {
                DB::table("queue_global_variables")
                    ->where("name", "similar_images_search")
                    ->update([
                        "value" => 0,
                        'updated_at' => Carbon::now(),
                    ]);
                $str = "本日會員照片數已超過 600 張，比對程序已暫停。";
                DB::table("queue_global_variables")
                    ->where("name", "sent_today_600")
                    ->update(["value" => 1, "updated_at" => Carbon::now()]);
            }
        }
        elseif($picCount > 200 && $todayHasSent200->value == 0) {
            $str = "本日會員照片數已超過 200 張。";
            DB::table("queue_global_variables")
                    ->where("name", "sent_today_200")
                    ->update(["value" => 1, "updated_at" => Carbon::now()]);
        }
        // elseif ($picCountMonth > 4500 && $todayHasSent4500->value == 0) {
        //     if($isOn) {
        //         DB::table("queue_global_variables")
        //             ->where("name", "similar_images_search")
        //             ->update([
        //                 "value" => 0,
        //                 'updated_at' => \Carbon\Carbon::now(),
        //             ]);
        //         $str = "一個月內會員照片數已超過 4500 張，比對程序已暫停。";
        //         DB::table("queue_global_variables")
        //             ->where("name", "sent_today_4500")
        //             ->update(["value" => 1, "updated_at" => Carbon::now()]);
        //     }
        // }
        if($str) {
            $to = ["admin@sugar-garden.org", "sandyh.dlc@gmail.com", "lzong.tw@gmail.com"];
            foreach ($to as $t) {
                \Mail::raw($str, function ($message) use ($t) {
                    $message->from('admin@sugar-garden.org', 'Sugar-garden');
                    $message->to($t);
                    $message->subject('會員照片數量通知');
                });
            }
        }
    }

    protected function deleteAnonymousChat(){
        AnonymousChat::select('*')->delete();
    }
}
