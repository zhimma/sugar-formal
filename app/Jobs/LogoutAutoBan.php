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

class LogoutAutoBan implements ShouldQueue
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
        $this->uid = $uid;
    }

    public function middleware()
    {
        return [new WithoutOverlapping($this->uid)];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        SetAutoBan::logoutWarned($this->uid);
    }
}
