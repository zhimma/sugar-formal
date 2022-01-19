<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\FemaleVipActive;

class FemaleVipActiveListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param
     */
    public function handle(FemaleVipActive $event)
    {
        // $user = $event->request->user();
        // if($user->engroup == 1 || ($user->engroup == 2 && !Vip::status($user->id)) || $user->existHeaderImage()) continue;
        //
        // if($user->last_login->diffInDays(Carbon::now()) >= 1 && !$user->isVip()) {
        //     $record = Carbon::now();
        //     Vip::upgrade($user->id, 'Test Active', 'Test Active', 1, 1);
        // }
        // else if($user->isVip() && $record->diffInDays(Carbon::now()) <= Config::get('social.vip.free-days')) {
        //     continue;
        // }
        // else {
        //     Vip::cancel($user->id, 1);
        // }

        //event($event->femaleActive());
    }
}
