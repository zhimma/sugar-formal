<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class AutoComparisonFailedEmail extends Notification
{
    /**
     * The password
     *
     * @var string
     */
    public $dateTime;
    public $fileName;
    public $content;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($dateTime, $fileName, $content)
    {
        $this->dateTime = $dateTime;
        $this->fileName = $fileName;
        $this->content  = $content;
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
            ->subject('每日異動檔自動比對發生錯誤 - '.$this->dateTime)
            ->line('每日異動檔自動比對發生錯誤，本地檔案：'.$this->fileName)
            ->line('比對結果：'.$this->content);
    }
}
