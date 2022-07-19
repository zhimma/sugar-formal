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
}