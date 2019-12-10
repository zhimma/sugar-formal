<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use Carbon\Carbon;

class TipEmail extends Notification
{

    public $from_id;           // 發車馬費(男)暱稱
    public $to_id;
    public $amount;
    public $business_id;       // 商家編號
    public $order_id;          // 訂單編號

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($from_id, $to_id, $amount, $business_id, $order_id)
    {
        $this->from_id = $from_id;
        $this->to_id = $to_id;
        $this->amount = $amount;
        $this->business_id = $business_id;
        $this->order_id = $order_id;
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
        $from = User::findById($this->from_id);
        $to = User::findById($this->to_id);

        return (new MailMessage)
            ->subject('車馬費邀請通知')
            ->line('商家編號：' . $this->business_id)
            ->line('訂單編號：' . $this->order_id)
            ->line('金額：' . $this->amount)
            ->line('男會員編號：' . $from->id)
            ->line('男會員暱稱：' . $from->name)
            ->line('女會員編號：' . $to->id)
            ->line('女會員暱稱：' . $to->name)
            ->line('時間：' . Carbon::now());
    }
}
