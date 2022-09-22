<?php

namespace App\Services;

use App\Models\Message;
use App\Models\MessageRoom;
use App\Models\MessageRoomUserXref;

class MessageService
{
	private $message;

    public function __construct(Message $message)
    {
    	$this->message = $message;
    }

    public function setMessageHandling($messageId,$handleStatus){
        return $this->message->where('id',$messageId)->update(['handle'=>$handleStatus]);
    }

    public function setMessageHandlingBySenderId($userid){
        return $this->message->where('from_id',$userid)->update(['handle'=>0]);
    }

    public function getMessageById($id){
        return $this->message->where([
            'id'     => $id,
        ])->first();
    }
}