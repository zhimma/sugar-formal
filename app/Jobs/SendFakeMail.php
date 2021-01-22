<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendFakeMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $address, $content;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($address, $content)
    {
        //
        $this->address = $address;
        $this->content = $content;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $address = $this->address;
        $content = $this->content;
        \Mail::raw($content, function ($message) use ($address) {
            $message->from('admin@sugar-garden.org', 'Sugar-garden');
            $message->to($address);
            $message->subject('Fake Mail');
        });
    }
}
