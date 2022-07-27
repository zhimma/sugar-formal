<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\LogUserLogin;

class LogSuccessfulLoginListener
{
    /**
     * Create the event listener.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param
     */
    public function handle(Login $event)
    {
        Log::info('start_LogSuccessfulLoginListener');

        $cfp_hash = $this->request->cfp_hash;
        $user = $event->user;
        $debug = $this->request->debug;

        //新增登入紀錄
        LogUserLogin::recordLoginData($user, $cfp_hash);
    }
}