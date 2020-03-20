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
    public function all()
    {
        return $this->user->get();
    }

    /**
     * find user by id
     * 
     * @param array ids
     * @return array users
     */
    public function findById($ids)
    {
        return $this->user->where('id', $isd)->first();
    }

    /**
    * find all girls
    *
    * @return array users
    **/
    public function findAllGirls()
    {
        return $this->user->where('engroup', '2');
    }

    /**
    * find by city
    *
    * @param string city 
    */
    public function findByCity($city)
    {
        $city = str_replace('台', "臺", $city);
        return $this->user->where('city', $city);
    }
}
?>