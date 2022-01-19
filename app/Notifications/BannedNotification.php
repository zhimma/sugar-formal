<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BannedNotification extends Notification
{
    /**
     * General notification info
     *
     * @var string
     */
    public $info;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($content)
    {
        $this->content = $content;
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
        return (new MailMessage)
            ->subject('您已遭到封鎖')
            ->line($this->content['hello'])
            ->line($this->content['notice1'])
            ->line($this->content['notice2'])
            ->line($this->content['notice3'])
            ->action('聯絡站長', url('/contact'));
    }
}