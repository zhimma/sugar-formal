<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class CustomFingerPrint implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;

    protected $uid, $payload;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uid, $payload)
    {
        //
        $this->uid = $uid;
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fingerprintService = new FingerprintService;
        $fingerprintService->judgeUserFingerprintAll($this->uid, $this->payload);
        $fingerprintService->judgeUserFingerprintCanvasOnly($this->uid, $this->payload);
    }
}
