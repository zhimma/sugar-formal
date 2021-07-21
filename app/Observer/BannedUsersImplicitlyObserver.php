<?php

namespace App\Observer;

use App\Models\BannedUsersImplicitly;
use App\Observer\Banned;

class BannedUsersImplicitlyObserver extends Banned
{
    public function retrieved(BannedUsersImplicitly $banned_user)
    {
    }
    
    public function created(BannedUsersImplicitly $banned_user)
    {
        $this->addRemindMsgFromBannedId($banned_user->target);
    }    

    /**
     * Handle the User "saved" event.
     *
     * @param  \App\Models\SimpleTables\banned_users  $user
     * @return void
     */
    public function saved(BannedUsersImplicitly $user)
    {
        
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
        //$user->connection = 'mysql_fp';
        //$user->delete();
    }

}