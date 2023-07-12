<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Services\LineNotifyService as LineNotify;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    
    public function handleCatchedException($e, $custom_err_str=null) 
    {
        $err_str = $custom_err_str . PHP_EOL . "ENV:" . \App::environment() . PHP_EOL;
        $err_str.='message:'.$e->getMessage().' in '.$e->getFile().' at line '.$e->getLine();

        if (!app()->isProduction()) {
            echo $err_str . PHP_EOL;
            echo "開發環境下，請確認測試所使用的帳號，是否都已完成所有基本流程（驗證、VIP...等），可以正常使用網站。待確認完成後再進行測試，否則會遇到假錯誤。";
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($err_str);
        }
        else {
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($err_str);
        }
    
        throw $e;
    }
    
}
