<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\SimilarImages;

class SimilarImagesSearcher implements ShouldQueue
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
    public function handle(SimilarImages $SimilarImages)
    {
        $SimilarImages->update_or_create($this->targetImg_path);
    }
}
