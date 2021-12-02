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
    protected $encode_by;
    protected $force_all;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($targetImg_path,$encode_by=null,$force_all=null)
    {
        $this->targetImg_path = $targetImg_path;
        $this->encode_by = $encode_by;
        $this->force_all = $force_all;
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
        $encode_by = $this->encode_by;
        $force_all = $this->force_all;
        if($encode_by) {
            Artisan::call("EncodeImagesForCompare",['pic'=>$pic,'encode_by'=>$encode_by]); 
            if($force_all) Artisan::call("CompareImages",['pic'=>$pic]);            
        }
        else {
            Artisan::call("CompareImages",['pic'=>$pic]);            
        }
    }
}
