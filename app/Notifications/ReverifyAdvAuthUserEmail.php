<?php

namespace App\Notifications;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ReverifyAdvAuthUserEmail extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $checkCode;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($checkCode)
    {
        $this->checkCode = $checkCode;
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
            ->subject('用戶進階驗證')
            ->line('您收到此電子郵件是因為希望進行 edu.tw 網域的校內信箱進階驗證，驗證碼: ' . $this->checkCode);
    }
}