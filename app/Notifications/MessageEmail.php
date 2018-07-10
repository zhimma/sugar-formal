<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class MessageEmail extends Notification
{
    /**
     * The password
     *
     * @var string
     */
    public $from_id;
    public $to_id;
    public $msg;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($from_id, $to_id, $msg)
    {
        $this->from_id = $from_id;
        $this->to_id = $to_id;
        $this->msg = $msg;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return [
            'mail'
        ];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $fromName = User::findById($this->from_id)->name;
        $toName = User::findById($this->to_id)->name;
        return (new MailMessage)
            ->subject('新訊息 - '.$fromName)
            ->line($toName.' 你好， ')
            ->line($fromName.' 說: '.$this->msg)
            ->action('回復', url('dashboard/chat/'.$fromName));
    }
}
