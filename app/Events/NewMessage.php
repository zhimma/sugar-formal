<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message_id, $content, $from_id, $to_id,$pic;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message_id, $content, $from_id, $to_id,$pic)
    {
        //
        $this->message_id = $message_id;
        $this->content = $content;
        $this->from_id = $from_id;
        $this->to_id = $to_id;
        $this->pic = $pic;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        try {
            return [new PrivateChannel('NewMessage.' . $this->to_id)];
        }
        catch (\Throwable $e){
            logger($e);
            return [];
        }
    }
}
