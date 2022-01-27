<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatUnsend implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message, $from_id, $to_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        //
        $this->message= $message;
        //$this->from_id = $from_id;
        //$this->to_id = $to_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        try{
            return [new PrivateChannel('ChatUnsend.'.$this->message->from_id.'.'.$this->message->to_id)];
        }
        catch (\Throwable $e){
            logger($e);
            return [];
        }
    }
}
