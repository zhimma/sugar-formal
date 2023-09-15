<?php

namespace App\Services;

use App\Models\Message;
use Illuminate\Support\Facades\Http;

class LineNotifyService
{
	private $message;

    // public function __construct(Message $message)
    // {
    // 	$this->message = $message;
    // }

    public function sendLineNotifyMessage($message){

        $LineToken = 'fb4KiuX5WJE9Nodq8Xo5xALrNCQE7buHta0ukQ4lgv4';

        Http::withToken($LineToken)->asForm()->post('https://notify-api.line.me/api/notify', [
            'message' => $message
        ]);
    }

    public function sendLineNotifyNewRecommendList($message, $picurl){

        $LineToken = 'B22r27EgRuzvYHrX7pB48EVmUpQ9LTU7uiIDKKPFeMI';

        Http::withToken($LineToken)->asForm()->post('https://notify-api.line.me/api/notify', [
            'message' => $message,
            'imageThumbnail'=> $picurl,
            'imageFullsize'=> $picurl,
        ]);
    }

    public function sendLineNotifyPopularRecommendList($message, $picurl){

        $LineToken = 'wsVTu2tpgHJNmuWZ5GsZGtaP7hnTuUnfCFxl7xetXaI';

        Http::withToken($LineToken)->asForm()->post('https://notify-api.line.me/api/notify', [
            'message' => $message,
            'imageThumbnail'=> $picurl,
            'imageFullsize'=> $picurl,
        ]);
    }
}

?>