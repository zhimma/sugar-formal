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

    protected $address;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($address)
    {
        //
        $this->address = $address;
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
        \Mail::raw("123", function ($message) use ($address) {
            $message->from('admin@sugar-garden.org', 'Sugar-garden');
            $message->to($address);
            $message->subject('Fake Mail');
        });
    }
}
