<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\SetAutoBan;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Log;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class LoginAutoBan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $uid;

    public function __construct($uid)
    {
        Log::info('start_jobs_LoginAutoBan_construct');
        Log::info($uid);
        $this->uid = $uid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('start_jobs_LoginAutoBan, uid: '.$this->uid);
        if($this->uid != 0)
        {
            SetAutoBan::login_warned($this->uid);
        }
        Log::info('end_jobs_LoginAutoBan, uid: ' . $this->uid);
    }
}
