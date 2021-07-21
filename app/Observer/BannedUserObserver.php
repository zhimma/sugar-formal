<?php

namespace App\Observer;

use App\Models\SimpleTables\banned_users;
use App\Observer\Banned;

class BannedUserObserver extends Banned
{
    public function retrieved(banned_users $banned_user)
    {
    }
    
    public function created(banned_users $banned_user)
    {
        $this->addRemindMsgFromBannedId($banned_user->member_id);
    }    

    /**
     * Handle the User "saved" event.
     *
     * @param  \App\Models\SimpleTables\banned_users  $user
     * @return void
     */
    public function saved(banned_users $user)
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