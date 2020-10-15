<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use Carbon\Carbon;

class BannedUserImplicitly extends Notification {

    public $userName;           // 帳號名稱
    public $userBannedDay;      // 帳號檢舉時間
    public $bannedName;         // 被檢舉帳號名稱
    public $adminBannedDay;     // admin封鎖時間

    public function __construct($userName, $userBannedDay, $bannedName, $adminBannedDay) {
        $this->userName = $userName;
        $this->userBannedDay = $userBannedDay;
        $this->bannedName = $bannedName;
        $this->adminBannedDay = $adminBannedDay;
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
            ->subject('檢舉通知')
            ->line($this->userName .' 您好，您在'. $this->userBannedDay.'檢舉 '.$this->bannedName.'，經站長檢視後，已於'. $this->adminBannedDay.'將其封鎖。')
            ->line('您可到瀏覽資料/懲處名單中瀏覽。');
    }
}
