<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Services\LineNotifyService as LineNotify;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    
    public function handleCatchedException($e, $custom_err_str=null) 
    {
        $err_str = $custom_err_str;
        
        if(!$err_str) {
            $err_str = $e->getMessage().' in  '.$e->getFile().'  on line  '.$e->getLine();
        }
        else {
            $err_str.=',message:'.$e->getMessage().' in '.$e->getFile().' at line '.$e->getLine();
        }

        $lineNotify = new LineNotify;
        $lineNotify->sendLineNotifyMessage($err_str);       
    
        throw $e;
    }
    
}
