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

class LogoutAutoBan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $uid;
    
    public function __construct($uid)
    {
        Log::info('start_jobs_LogoutAutoBan_construct');
        Log::info($uid);
        $this->uid = $uid;
    }

    public function middleware()
    {
        return [(new WithoutOverlapping($this->uid))];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $timeStart = microtime(true);
        Log::info('start_jobs_LogoutAutoBan, uid: '.$this->uid);
        if($this->uid != 0)
        {
            SetAutoBan::logoutWarned($this->uid);
        }
        $diff = microtime(true) - $timeStart;
        Log::info('end_jobs_LogoutAutoBan, uid: ' . $this->uid . ', time elapsed: ' . $diff);
        return 0;
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addMinutes(120);
    }

    /**
    * The job failed to process.
    *
    * @param  Exception  $exception
    * @return void
    */
    public function failed(\Exception $exception)
    {
        logger($exception);
    }
}
