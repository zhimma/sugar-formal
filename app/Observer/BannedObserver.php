<?php

namespace App\Observers;

use App\Models\SimpleTables\banned_users;

class BannedObserver
{

    /**
     * Handle the User "saved" event.
     *
     * @param  \App\Models\SimpleTables\banned_users  $user
     * @return void
     */
    public function saved(banned_users $user)
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\SimpleTables\banned_users  $user
     * @return void
     */
    public function deleted(banned_users $user)
    {
        //
    }

}