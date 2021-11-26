<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class CompareImagesCaller implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $targetImg_path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($targetImg_path)
    {
        $this->targetImg_path = $targetImg_path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pic = $this->targetImg_path;
        if(!$pic) return;
        Artisan::call("EncodeImagesForCompare",['pic'=>$pic]); 
        Artisan::call("CompareImages",['pic'=>$pic]);  
    }
}
