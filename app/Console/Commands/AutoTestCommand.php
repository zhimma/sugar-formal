<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LineNotifyService as LineNotify;

class AutoTestCommand extends Command
{
    protected $signature = 'auto:test';

    protected $description = 'Command description';

    public function handle(): void
    {
        $root_path = base_path();
        $lineNotify = new LineNotify;
        $lineNotify->sendLineNotifyMessage("開始自動測試，環境：" . \App::environment());
        $result = shell_exec("cd " . $root_path . " && sudo -u www-data ./vendor/bin/pest 2>&1");
        $result = preg_replace('#\\x1b[[][^A-Za-z]*[A-Za-z]#', '', $result);
        logger($result);
        \Sentry\captureMessage("auto test result: " . $result);
        $lineNotify = new LineNotify;
        $lineNotify->sendLineNotifyMessage("自動測試結束");
    }
}
