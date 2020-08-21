<?php

namespace App\Services;

use App\Models\Message;

class MessageService
{
	private $message;

    public function __construct(Message $message)
    {
    	$this->message = $message;
    }
}

?>