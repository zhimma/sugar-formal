<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:sms {mobile} {email}';

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
        //
        $checkCode = str_pad(rand(0, pow(10, 5) - 1), 5, '0', STR_PAD_LEFT);
        $username = '54666024';
        $password = 'zxcvbnm';
        $smbody = '使用者註冊失敗，Email: ' . $this->argument('email');
        $smbody = mb_convert_encoding($smbody, "BIG5", "UTF-8");
        $Data = array(
            "username" => $username, //三竹帳號
            "password" => $password, //三竹密碼
            "dstaddr" => $this->argument('mobile'), //客戶手機
            "DestName" => '系統回報', //對客戶的稱謂 於三竹後台看的時候用的
            "smbody" => $smbody, //簡訊內容
            // "response" =>$ReturnResultUrl, //回傳網址
            // "ClientID" => $ClientID //使用者代號
        );
        $dataString = http_build_query($Data);
        $url = "http://smexpress.mitake.com.tw:9600/SmSendGet.asp?$dataString";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $this->info('發送完成。');
    }
}
