<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

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
        $schedule->call($this->callCheck())->daily();
        $schedule->call($this->callCheck())->dailyAt('4:00');
        $schedule->call($this->callCheck())->dailyAt('8:00');
        $schedule->call($this->callCheck())->dailyAt('12:00');
        $schedule->call($this->callCheck())->dailyAt('16:00');
        $schedule->call($this->callCheck())->dailyAt('20:00');
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

    protected function callCheck()
    {
        $this->VIPCheck(\Carbon\Carbon::now()->subDays(2)->toDateString());
        $this->VIPCheck(\Carbon\Carbon::now()->subDay()->toDateString());
        $this->VIPCheck();
    }

    protected function VIPCheck($date_set = null){
        if(isset($date_set)){
            $date = \Carbon\Carbon::createFromFormat("Y-m-d", $date_set)->toDateString();
        }
        else{
            $date = \Carbon\Carbon::now()->toDateString();
        }
        $datas = \DB::table('viplogs')->where('filename', 'LIKE', '%761404%')->where('created_at', 'LIKE', $date.'%')->get();
        $date = str_replace('-', '', $date);
        $string = '';
        if(!file_exists(storage_path('app/RP_761404_'.$date.'.dat'))){
            $file = '';
            $string = $string.'Today\'s file not found('.storage_path('app/RP_761404_'.$date.'.dat').").\n";
        }
        else{
            $file = File::get(storage_path('app/RP_761404_'.$date.'.dat'));
            //$file = explode(PHP_EOL, $file);
            $file = explode("\n", $file);
        }
        if($datas->count() == 0){
            $string = $string."There's no today's log.\n";
        }
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
                    //檢查是否已取消權限
                    if(isset($user) && $user->isVip()){
                        $vip = \App\Models\Vip::findById($user->id);
                        $this->logService->cancelLog($vip);
                        $this->logService->writeLogToFile();
                        $tmp = \App\Models\Vip::removeVIP($user->id, 0);
                        $string = $string.'Condition 1(Delete): ';
                        $log_str = $log_str.'Condition 1(Delete): ';
                        foreach ($line as $l){
                            $string = $string.$l.", ";
                            $log_str = $log_str.$l.", ";
                        }
                        $string = $string."\n";
                        $log_str = $string."\n";
                    }
                    else{
                        $string = $string.'Condition 1(Delete): The log over-recorded a member or encountered a NULL data, User: '.$line[1]."\n";
                        $log_str = $log_str.'Condition 1(Delete): The log over-recorded a member or encountered a NULL data, User: '.$line[1]."\n";
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
                        $string = $string.'Condition 2(New): ';
                        $log_str = $log_str.'Condition 2(New): ';
                        foreach ($line as $l){
                            $string = $string.$l.", ";
                            $log_str = $log_str.$l.", ";
                        }
                        $string = $string."\n";
                        $log_str = $log_str."\n";
                    } else {
                        $string = $string.'Condition 2(New): The log over-recorded a member or encountered a NULL data, User: '.$line[1]."\n";
                        $log_str = $log_str.'Condition 2(New): The log over-recorded a member or encountered a NULL data, User: '.$line[1]."\n";
                    }
                }
                \DB::table('log_vip_crontab')->insert(
                    ['user_id' => $line[1],
                        'content' => $log_str]
                );
            }
            return str_replace("\n", "<br>", $string);
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
                    //檢查是否已取消權限
                    if(isset($user) && $user->isVip()){
                        $tmp = \App\Models\Vip::where('member_id', $user->id)->get()->first()->removeVIP();
                        $string = $string.'Condition 3(Delete): ';
                        $log_str = $log_str.'Condition 3(Delete): ';
                        foreach ($line as $l){
                            $string = $string.$l.", ";
                            $log_str = $log_str.$l.", ";
                        }
                        $string = $string."\n";
                        $log_str = $log_str."\n";
                    }
                    else{
                        $string = $string.'Condition 3(Delete): The file over-recorded a member or encountered a NULL data, User: '.$line[1]."\n";
                        $log_str = $log_str.'Condition 3(Delete): The file over-recorded a member or encountered a NULL data, User: '.$line[1]."\n";
                    }
                }
                //若異動檔多的是New
                else {
                    $string = $string."Condition 4(file, New):\n";
                    //檢查是否已獲得權限
                    if (isset($user) && !$user->isVip()) {
                        //若沒獲得權限，補權限
                        $tmp = \App\Models\Vip::upgrade($user->id, $line[0], $line[2], $line[5], 'auto completion', 1, 0);
                        $string = $string.'Condition 4(New): ';
                        $log_str = $log_str.'Condition 4(New): ';
                        foreach ($line as $l){
                            $string = $string.$l.", ";
                            $log_str = $log_str.$l.", ";
                        }
                        $string = $string."\n";
                        $log_str = $log_str."\n";
                    } else {
                        $string = $string.'Condition 4(New): The file over-recorded a member or encountered a NULL data, User: '.$line[1]."\n";
                        $log_str = $log_str.'Condition 4(New): The file over-recorded a member or encountered a NULL data, User: '.$line[1]."\n";
                    }
                }
                \DB::table('log_vip_crontab')->insert(
                    ['user_id' => $line[1],
                        'content' => $log_str]
                );
            }
            return str_replace("\n", "<br>", $string);
        }
        \DB::table('log_vip_crontab')->insert(
            ['user_id' => '',
                'content' => $nothingStr."Nothing's done."]
        );
        return str_replace("\n", "<br>", $string."Nothing's done.");
    }
}
