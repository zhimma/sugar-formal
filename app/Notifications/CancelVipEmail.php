<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use Carbon\Carbon;

class CancelVipEmail extends Notification {

    public $member_id;           // 會員編號
    //public $name;              // 會員暱稱
    public $business_id;       // 商家編號
    public $order_id;          // 訂單編號
    //public $msg;               // 信件內容
    public $time;              // 時間

    public function __construct($member_id, $business_id, $order_id) {
        $this->member_id = $member_id;
        $this->business_id = $business_id;
        $this->order_id = $order_id;
        $this->time = Carbon::now();
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
        $member = User::findById($this->member_id);

        return (new MailMessage)
            ->subject('取消VIP通知')
            ->line('商家編號：' . $this->business_id)
            ->line('訂單編號：' . $this->order_id)
            ->line('會員編號：' . $member->id)
            ->line('會員暱稱：' . $member->name)
            ->line('時間：' . Carbon::now());
            //->action('回復', url('dashboard/chat/'.$fromName));
    }
}
