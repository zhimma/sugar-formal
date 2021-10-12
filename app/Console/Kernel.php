<?php

namespace App\Console;

use App\Models\MemberPic;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

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
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('FillDataForFilterByInfo')->timezone('Asia/Taipei')->dailyAt('01:00');
        $schedule->call(function (){
            $this->checkECPayVip();
            $this->checkEmailVailUser();
        })->timezone('Asia/Taipei')->dailyAt('3:00');
        $schedule->call(function (){
            $this->checkVipExpired();
        })->timezone('Asia/Taipei')->dailyAt('3:10');
        $schedule->call(function (){
            $this->VIPCheck();
            $this->checkEmailVailUser();
        })->timezone('Asia/Taipei')->dailyAt('4:00');
        $schedule->call(function (){
            $this->checkEmailVailUser();
        })->timezone('Asia/Taipei')->dailyAt('5:00');
		$puppetReq = new Request();
		$puppetReq->only = 'cfpid';
        $schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance')->timezone('Asia/Taipei')->everySixHours();
		$schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance',['request'=>$puppetReq])->timezone('Asia/Taipei')->dailyAt('6:30');
		$schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance',['request'=>$puppetReq])->timezone('Asia/Taipei')->dailyAt('12:30');
		$schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance',['request'=>$puppetReq])->timezone('Asia/Taipei')->dailyAt('18:30');
		$schedule->call('\App\Http\Controllers\Admin\FindPuppetController@entrance',['request'=>$puppetReq])->timezone('Asia/Taipei')->dailyAt('0:30');
        $schedule->call(function (){
            $this->checkEmailVailUser();
        })->timezone('Asia/Taipei')->dailyAt('8:00');
        $schedule->call(function (){
            $this->VIPCheck();
            $this->checkEmailVailUser();
        })->timezone('Asia/Taipei')->dailyAt('12:00');
        $schedule->command('FillDataForFilterByInfo')->timezone('Asia/Taipei')->dailyAt('13:00');
        $schedule->call(function (){
            $this->VIPCheck();
            $this->checkEmailVailUser();
        })->timezone('Asia/Taipei')->dailyAt('16:00');
        $schedule->call(function (){
            $this->VIPCheck();
            $this->checkEmailVailUser();
        })->timezone('Asia/Taipei')->dailyAt('20:00');
        $schedule->call(function (){
            $this->VIPCheck();
            $this->checkEmailVailUser();
        })->timezone('Asia/Taipei')->dailyAt('23:59');
        $schedule->call(function (){
            $this->checkUserPics();
        })->everyFiveMinutes();
        $schedule->call(function (){
            $this->send_registed_users_statistics_by_LineNotify();
        })->timezone('Asia/Taipei')->dailyAt('1:00');
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
        $vipDatas = \App\Models\Vip::where(['business_id' => '3137610', 'active' => 1])->get();
        foreach ($vipDatas as $vipData){
            \App\Jobs\CheckECpay::dispatch($vipData);
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
                    if (isset($user) && !$user->isVip()) {
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
                    if (isset($user) && !$user->isVip()) {
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
                $admin = \App\Models\User::findByEmail(config('social.admin.email'));
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
            $admin = \App\Models\User::findByEmail(config('social.admin.email'));
            $admin->notify(new \App\Notifications\AutoComparisonFailedEmail(\Carbon\Carbon::now()->toDateTimeString(), 'RP_761404_'.$localDate.'.dat', '本地端沒有檔案'));
            return "Local file not found, check process didn't initiate.";
        }
    }

    public function checkUserPics() {
        // 每天超過 250 張發警告信
        // 每天超過 500 發警告信並停止
        // 每個月超過 6000 張停止
        $picCount = MemberPic::withTrashed()->where('created_at', '>', Carbon::today()->format('Y-m-d'))->count();
        $picCountMonth = MemberPic::withTrashed()->whereBetween('created_at', [Carbon::today()->subMonth()->format('Y-m-d'), Carbon::today()->format('Y-m-d')])->count();
        $str = null;
        if ($picCount > 400) {
            DB::table("queue_global_variables")
                ->where("similar_images_search")
                ->update([
                    "value" => 0,
                    'updated_at' => Carbon::now(),
                ]);
            $str = "本日會員照片數已超過 400 張，比對程序已暫停。";
        }
        elseif($picCount > 200) {
            $str = "本日會員照片數已超過 200 張。";
        }
        elseif ($picCountMonth > 4500) {
            DB::table("queue_global_variables")
                ->where("similar_images_search")
                ->update([
                    "value" => 0,
                    'updated_at' => \Carbon\Carbon::now(),
                ]);
            $str = "一個月內會員照片數已超過 4500 張，比對程序已暫停。";
        }
        $to = ["admin@sugar-garden.org", "sandyh.dlc@gmail.com", "lzong.tw@gmail.com"];
        foreach ($to as $t) {
            \Mail::raw($str, function ($message) use ($t) {
                $message->from('admin@sugar-garden.org', 'Sugar-garden');
                $message->to($t);
                $message->subject('會員照片數量通知');
            });
        }
    }

    public function send_registed_users_statistics_by_LineNotify(){

        $LineToken = '7OphzAHOKgDyrDupP5BjGfol9QJKtSNj08NQNxx3H76';

        // 昨日男會員數
        $date_yesterday = Carbon::yesterday()->toDateString();
        $yesterdayMaleCount = \App\Models\User::without(['user_meta', 'vip'])
            ->select('id')
            ->where('engroup', 1)
            ->whereBetween('created_at', [$date_yesterday, $date_yesterday . ' 23:59:59'])
            ->count();

        // 昨日女會員數
        $yesterdayWomaleCount = \App\Models\User::without(['user_meta', 'vip'])
            ->select('id')
            ->where('engroup', 2)
            ->whereBetween('created_at', [$date_yesterday, $date_yesterday . ' 23:59:59'])
            ->count();

        // 前日男會員、人數統計
        $date_2days_ago = Carbon::today()->subDays(2)->toDateString();
        $two_days_ago_male = \App\Models\User::without(['user_meta', 'vip'])
            ->select('id')
            ->where('engroup', 1)
            ->whereBetween('created_at', [$date_2days_ago, $date_2days_ago . ' 23:59:59'])
            ->get();
        $two_days_ago_male_count = $two_days_ago_male->count();
        $two_days_ago_male_count_with_banned = \App\Models\SimpleTables\banned_users::whereIn('member_id', $two_days_ago_male)->count();
        $two_days_ago_male_count_without_banned = $two_days_ago_male_count - $two_days_ago_male_count_with_banned;

        // 前日女會員、人數統計
        $two_days_ago_womale = \App\Models\User::without(['user_meta', 'vip'])
            ->select('id')
            ->where('engroup', 2)
            ->whereBetween('created_at', [$date_2days_ago, $date_2days_ago . ' 23:59:59'])
            ->get();
        $two_days_ago_womale_count = $two_days_ago_womale->count();
        $two_days_ago_womale_count_with_banned = \App\Models\SimpleTables\banned_users::whereIn('member_id', $two_days_ago_womale)->count();
        $two_days_ago_womale_count_without_banned = $two_days_ago_womale_count - $two_days_ago_womale_count_with_banned;
    
        $date_3days_ago = Carbon::today()->subDays(3)->toDateString();
        // 大前日男會員、人數統計
        $three_days_ago_male = \App\Models\User::without(['user_meta', 'vip'])
            ->select('id')
            ->where('engroup', 1)
            ->whereBetween('created_at', [$date_3days_ago, $date_3days_ago . ' 23:59:59'])
            ->get();
        $three_days_ago_male_count = $three_days_ago_male->count();
        $three_days_ago_male_count_with_banned = \App\Models\SimpleTables\banned_users::whereIn('member_id', $three_days_ago_male)->count();
        $three_days_ago_male_count_without_banned = $three_days_ago_male_count - $three_days_ago_male_count_with_banned;

        // 大前日女會員、人數統計
        $three_days_ago_womale = \App\Models\User::without(['user_meta', 'vip'])
            ->select('id')
            ->where('engroup', 2)
            ->whereBetween('created_at', [$date_3days_ago, $date_3days_ago . ' 23:59:59'])
            ->get();
        $three_days_ago_womale_count = $three_days_ago_womale->count();
        $three_days_ago_womale_count_with_banned = \App\Models\SimpleTables\banned_users::whereIn('member_id', $three_days_ago_womale)->count();
        $three_days_ago_womale_count_without_banned = $three_days_ago_womale_count - $three_days_ago_womale_count_with_banned;
    
        $message  = "\n昨日註冊男會員: $yesterdayMaleCount 人";
        $message .= "\n昨日註冊女會員: $yesterdayWomaleCount 人";
        $message .= "\n前日註冊男會員-被Ban男會員: $two_days_ago_male_count_without_banned 人 ( $two_days_ago_male_count - $two_days_ago_male_count_with_banned = $two_days_ago_male_count_without_banned )";
        $message .= "\n前日註冊女會員-被Ban的女會員: $two_days_ago_womale_count_without_banned 人 ( $two_days_ago_womale_count - $two_days_ago_womale_count_with_banned = $two_days_ago_womale_count_without_banned )";
        $message .= "\n大前日註冊男會員-被Ban男會員: $three_days_ago_male_count_without_banned 人 ( $three_days_ago_male_count - $three_days_ago_male_count_with_banned = $three_days_ago_male_count_without_banned )";
        $message .= "\n大前日註冊女會員-被Ban女會員: $three_days_ago_womale_count_without_banned 人 ( $three_days_ago_womale_count - $three_days_ago_womale_count_with_banned = $three_days_ago_womale_count_without_banned )";
    
        Http::withToken($LineToken)->asForm()->post('https://notify-api.line.me/api/notify', [
            'message' => $message
        ]);
    }
}
