<?php

namespace App\Jobs;

use App\Services\LineNotifyService as LineNotify;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeployJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        $lineNotify = new LineNotify;
        try {
            $lineNotify->sendLineNotifyMessage("正式站部署中");
        }
        catch (\Exception $e) {
            \Sentry\captureException($e);
        }
        \Sentry::captureMessage('正式站部署中');
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', -1);
        $root_path = base_path();
        $result = shell_exec('cd ' . $root_path . '; sudo sh ./deploy.sh 2>&1');
        $commit = shell_exec('cd ' . $root_path . '; git rev-parse HEAD 2>&1');
        try {
            $lineNotify->sendLineNotifyMessage('正式站部署完成，目前 commit: ' . $commit);
        }
        catch (\Exception $e) {
            \Sentry\captureException($e);
        }
        \Sentry\captureMessage("production manually deployed\n commit: " . $commit . "\n, result: " . $result);
        logger('production manually deployed' . $result);
    }
}
