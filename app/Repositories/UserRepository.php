<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\SimpleTables\banned_users;

class UserRepository
{
    /**
     * @var model
     */
    protected static $user;
    protected static $banned_users;

    /**
     * @param models User
     * @param models banned_users
     */
    public function __construct(User $user, banned_users $banned_users)
    {
        self::$user = $user;
        self::$banned_users = $banned_users;
    }

   	/** 
   	 * Get all user
   	 *
     * @return array users
     */
    public static function all()
    {
        return self::$user->get()->toArray();
    }

    /**
     * find user by id
     * 
     * @param array ids
     * @return array users
     */
    public static function findById($ids)
    {
        return self::$user->where('id', $isd)->first()->toArray();
    }
}
?>