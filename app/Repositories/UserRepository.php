<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\SimpleTables\banned_users;

class UserRepository
{
    /**
     * @var model user
     * @var model banned_users
     */
    protected  $user;
    protected  $banned_users;

    /**
     * @param model User
     * @param model banned_users
     */
    public function __construct(User $user, banned_users $banned_users)
    {
        $this->user = $user;
        $this->banned_users = $banned_users;
    }

   	/** 
   	 * Get all user
   	 *
     * @return array users
     */
    public static function all()
    {
        return $this->user->get()->toArray();
    }

    /**
     * find user by id
     * 
     * @param array ids
     * @return array users
     */
    public static function findById($ids)
    {
        return $this->user->where('id', $isd)->first()->toArray();
    }
}
?>