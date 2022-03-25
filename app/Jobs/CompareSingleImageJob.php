<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use App\Services\ImagesCompareService;

class CompareSingleImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $targetImg_path;
    protected $encode_by;
    protected $force_all;
    protected $force_compare;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($targetImg_path,$encode_by=null,$force_compare=false,$force_all=null)
    {
        $this->targetImg_path = $targetImg_path;
        $this->encode_by = $encode_by;
        $this->force_all = $force_all;
        $this->force_compare = $force_compare;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        $force_compare = $this->force_compare;
        $pic = $this->targetImg_path??'';
        if(!$pic) {
            \Sentry\captureMessage('CompareSingleImageJob 異常');     
            return;
        }
        $encode_by = $this->encode_by;
        $force_all = $this->force_all;        
        if($encode_by) {        
            Artisan::call("EncodeImagesSingle",['pic'=>$pic,'encode_by'=>$encode_by]); 
            if($force_all) {
               $call_argv = ['pic'=>$pic];
               if($force_compare) $call_argv['--force']=true;
               Artisan::call("CompareImagesSingle",$call_argv);             
            }
        }
        else {
           $call_argv = ['pic'=>$pic];
           if($force_compare) $call_argv['--force']=true;            
            Artisan::call("CompareImagesSingle",$call_argv);            
        }
    }
}
