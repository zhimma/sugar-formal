<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use App\Models\SetAutoBan;
use Illuminate\Support\Facades\Log;

class AutoBanCaller implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $uid;
    protected $toid;
    protected $msg;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uid,$toid=null,$msg=null)
    {
        $this->uid = $uid;
        $this->toid = $toid;
        $this->msg = $msg;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $uid = $this->uid;
        $toid = $this->toid;
        $msg = $this->msg;
        
        if($uid && $toid && $msg) {
            SetAutoBan::autoBanMsg($uid,$toid,$msg);
        }
        else if($uid && !$toid && !$msg){
            SetAutoBan::autoBan($uid);
        }
        else {
            Log::info('AutoBanCaller無法處理的參數個數 $uid='.$uid.'  $toid='.$toid.'  $msg='.$msg);                
            if(app()->bound('sentry')) {
                \Sentry\captureMessage('AutoBanCaller無法處理的參數個數 $uid='.$uid.'  $toid='.$toid.'  $msg='.$msg);
            }     
        }
        
        return;
    }
}
