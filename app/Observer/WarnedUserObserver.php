<?php

namespace App\Observer;

use App\Models\SimpleTables\warned_users;
use App\Observer\BadUserCommon;

class WarnedUserObserver
{
	public function __construct(BadUserCommon $badUserCommn) {
		$this->comm = $badUserCommn;
	} 
	
    public function retrieved(warned_users $warned_user)
    {
    }
    
    public function created(warned_users $warned_user)
    {
        $this->comm->addRemindMsgFromBadId($warned_user->member_id);
    }    

    /**
     * Handle the User "saved" event.
     *
     * @param  \App\Models\SimpleTables\banned_users  $user
     * @return void
     */
    public function saved(warned_users $warned_user)
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