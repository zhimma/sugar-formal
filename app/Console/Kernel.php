<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function (){
            $this->VIPCheck(\Carbon\Carbon::now()->subDays(2)->toDateString());
            $this->VIPCheck(\Carbon\Carbon::now()->subDay()->toDateString());
            $this->VIPCheck();
        })->timezone('Asia/Taipei')->daily();
        $schedule->call(function (){
            $this->uploadDatFile();
        })->timezone('Asia/Taipei')->dailyAt('1:00');
        $schedule->call(function (){
            $this->checkDatFile();
        })->timezone('Asia/Taipei')->dailyAt('3:00');
        $schedule->call(function (){
            $this->VIPCheck(\Carbon\Carbon::now()->subDays(2)->toDateString());
            $this->VIPCheck(\Carbon\Carbon::now()->subDay()->toDateString());
            $this->VIPCheck();
        })->timezone('Asia/Taipei')->dailyAt('4:00');
        $schedule->call(function (){
            $this->checkDatFile();
        })->timezone('Asia/Taipei')->dailyAt('5:00');
        $schedule->call(function (){
            $this->VIPCheck(\Carbon\Carbon::now()->subDays(2)->toDateString());
            $this->VIPCheck(\Carbon\Carbon::now()->subDay()->toDateString());
            $this->VIPCheck();
        })->timezone('Asia/Taipei')->dailyAt('8:00');
        $schedule->call(function (){
            $this->VIPCheck(\Carbon\Carbon::now()->subDays(2)->toDateString());
            $this->VIPCheck(\Carbon\Carbon::now()->subDay()->toDateString());
            $this->VIPCheck();
        })->timezone('Asia/Taipei')->dailyAt('12:00');
        $schedule->call(function (){
            $this->VIPCheck(\Carbon\Carbon::now()->subDays(2)->toDateString());
            $this->VIPCheck(\Carbon\Carbon::now()->subDay()->toDateString());
            $this->VIPCheck();
        })->timezone('Asia/Taipei')->dailyAt('16:00');
        $schedule->call(function (){
            $this->VIPCheck(\Carbon\Carbon::now()->subDays(2)->toDateString());
            $this->VIPCheck(\Carbon\Carbon::now()->subDay()->toDateString());
            $this->VIPCheck();
        })->timezone('Asia/Taipei')->dailyAt('20:00');
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

    protected function VIPCheck($date_set = null){
        if(isset($date_set)){
            $date = \Carbon\Carbon::createFromFormat("Y-m-d", $date_set)->toDateString();
        }
        else{
            $date = \Carbon\Carbon::now()->toDateString();
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
        $date = \Carbon\Carbon::now()->subDay()->toDateString();
        $date = str_replace('-', '', $date);
        if(file_exists(storage_path('app/RP_761404_'.$date.'.dat'))){
            $fileContent = file_get_contents(storage_path('app/RP_761404_'.$date.'.dat'));
            $destinDate = \Carbon\Carbon::now()->toDateString();
            $destinDate = str_replace('-', '', $destinDate);
            $file = 'RP_761404_'.$destinDate.'.dat';
            GrahamCampbell\Flysystem\Facades\Flysystem::connection('sftp')->put($file, $fileContent);

            \DB::table('log_dat_file')->insert(
                ['upload_check' => 0,
                 'local_file'    => 'RP_761404_'.$date.'.dat',
                 'remote_file' => $file,
                 'content' => "RP_761404_".$date.".dat: Upload completed."]
            );
            return "RP_761404_".$date."dat: Upload completed.";
        }
        else{
            \DB::table('log_dat_file')->insert(
                ['upload_check' => 0,
                 'local_file'    => 'RP_761404_'.$date.'.dat',
                 'remote_file' => '',
                 'content' => "File RP_761404_".$date.".dat not found, upload process didn't initiate."]
            );
            return "File not found, upload process didn't initiate.";
        }
    }

    protected function checkDatFile(){
        $localDate = \Carbon\Carbon::now()->subDay()->toDateString();
        $localDate = str_replace('-', '', $localDate);
        if(file_exists(storage_path('app/RP_761404_'.$localDate.'.dat'))){
            $localFileContent = file_get_contents(storage_path('app/RP_761404_'.$localDate.'.dat'));
            $remoteDate = \Carbon\Carbon::now()->toDateString();
            $remoteDate = str_replace('-', '', $remoteDate);
            $remoteFile = 'RP_761404_'.$remoteDate.'.dat';
            $remoteFileContent = GrahamCampbell\Flysystem\Facades\Flysystem::connection('sftp')->read($remoteFile);
            if($localFileContent == $remoteFileContent){
                \DB::table('log_dat_file')->insert(
                    ['upload_check' => 1,
                     'local_file'    => 'RP_761404_'.$localDate.'.dat',
                     'remote_file' => $remoteFile,
                     'content' => "File comparison success."]
                );
                return "File comparison success.";
            }
            else{
                \DB::table('log_dat_file')->insert(
                    ['upload_check' => 1,
                     'local_file'    => 'RP_761404_'.$localDate.'.dat',
                     'remote_file' => $remoteFile,
                     'content' => "File comparison failed."]
                );
                $admin = \App\Models\User::findByEmail(Config::get('social.admin.email'));
                $admin->notify(new AutoComparisonFailedEmail(\Carbon\Carbon::now()->toDateTimeString(), 'RP_761404_'.$localDate.'.dat', '本地與遠端的檔案內容不一致'));
                return "File comparison failed, the contents of local and remote files are not the same.";
            }
        }
        else{
            \DB::table('log_dat_file')->insert(
                ['upload_check' => 1,
                 'local_file'    => 'RP_761404_'.$localDate.'.dat',
                 'remote_file' => '',
                 'content' => "Local file not found, check process didn't initiate."]
            );
            $admin = \App\Models\User::findByEmail(Config::get('social.admin.email'));
            $admin->notify(new AutoComparisonFailedEmail(\Carbon\Carbon::now()->toDateTimeString(), 'RP_761404_'.$localDate.'.dat', '本地端沒有檔案'));
            return "Local file not found, check process didn't initiate.";
        }
    }
}
