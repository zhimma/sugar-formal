<?
namespace App\Listeners;

use Illuminate\Mail\Events\MessageSending;

class LogEmail {
    public function handle(MessageSending $event) {
        $message = $event->message;
        // The Swift_Message has a __toString method so you should be able to log it.
        $arr = explode("#", $message, 2);
        $first = $arr[0];
        \Log::info(quoted_printable_decode($first));
    }
}
