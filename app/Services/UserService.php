<?php

namespace App\Services;

use DB;
use Auth;
use Mail;
use Config;
use Session;
use Exception;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Role;
use App\Events\UserRegisteredEmail;
use App\Notifications\ActivateUserEmail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * User model
     * @var User
     */
    public $model;

    /**
     * User Meta model
     * @var UserMeta
     */
    protected $userMeta;

    /**
     * Role Service
     * @var RoleService
     */
    protected $role;

    public function __construct(
        User $model,
        UserMeta $userMeta,
        Role $role
    ) {
        $this->model = $model;
        $this->userMeta = $userMeta;
        $this->role = $role;
    }

    /**
     * Get all users
     *
     * @return array
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Find a user
     * @param  integer $id
     * @return User
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Search the users
     *
     * @param  string $input
     * @return mixed
     */
    public function search($input)
    {
        $query = $this->model->orderBy('created_at', 'desc');

        $columns = Schema::getColumnListing('users');

        foreach ($columns as $attribute) {
            $query->orWhere($attribute, 'LIKE', '%'.$input.'%');
        };

        return $query->paginate(env('PAGINATE', 25));
    }

    /**
     * Find a user by email
     *
     * @param  string $email
     * @return User
     */
    public function findByEmail($email)
    {
        return $this->model->findByEmail($email);
    }

    /**
     * Find by Role ID
     * @param  integer $id
     * @return Collection
     */
    public function findByRoleID($id)
    {
        $usersWithRepo = [];
        $users = $this->model->all();

        foreach ($users as $user) {
            if ($user->roles->first()->id == $id) {
                $usersWithRepo[] = $user;
            }
        }

        return $usersWithRepo;
    }

    /**
     * Find by the user meta activation token
     *
     * @param  string $token
     * @return boolean
     */
    public function findByActivationToken($token)
    {
        $userMeta = UserMeta::where('activation_token', $token)->first();

        if ($userMeta) {
            return $userMeta->user();
        }

        return false;
    }

    /**
     * Create a user's profile
     *
     * @param  User $user User
     * @param  string $password the user password
     * @param  string $role the role of this user
     * @param  boolean $sendEmail Whether to send the email or not
     * @return User
     */
    public function create($user, $password, $sendEmail = true)
    {
        try {
            DB::transaction(function () use ($user, $password, $sendEmail) {
                $this->userMeta->firstOrCreate([
                    'user_id' => $user->id
                ]);

                //$this->assignRole($role, $user->id);

                if ($sendEmail) {
                    event(new UserRegisteredEmail($user, $password));
                }

            });

            $this->setAndSendUserActivationToken($user);

            return $user;
        } catch (Exception $e) {
            throw new Exception("We were unable to generate your profile, please try again later. " . $e, 1);
        }
    }

    /**
     * Update a user's profile
     *
     * @param  int $userId User Id
     * @param  array $inputs UserMeta info
     * @return User
     */
    public function update($userId, $payload)
    {
        if (isset($payload['meta']) && ! isset($payload['meta']['terms_and_cond'])) {
            throw new Exception("You must agree to the terms and conditions.", 1);
        }

        try {
            return DB::transaction(function () use ($userId, $payload) {
                $user = $this->model->find($userId);

                if (isset($payload['meta']['terms_and_cond']) && ($payload['meta']['terms_and_cond'] == 1 || $payload['meta']['terms_and_cond'] == 'on')) {
                    $payload['meta']['terms_and_cond'] = 1;
                } else {
                    $payload['meta']['terms_and_cond'] = 0;
                }

                unset($payload['meta']['marketing']);
                if (isset($payload['city']))
                {
                  $payload['meta']['city'] = $payload['city'];
                  unset($payload['city']);
                }
                  if (isset($payload['isHideCup']))
                  {
                  $payload['meta']['isHideCup'] = $payload['isHideCup'];
                  unset($payload['isHideCup']);
                  }
                  if (isset($payload['isHideArea']))
                  {
                  $payload['meta']['isHideArea'] = $payload['isHideArea'];
                  unset($payload['isHideArea']);
                  }
                  if (isset($payload['isHideWeight']))
                  {
                  $payload['meta']['isHideWeight'] = $payload['isHideWeight'];
                  unset($payload['isHideWeight']);
                  }
                  if (isset($payload['isHideOccupation']))
                  {
                  $payload['meta']['isHideOccupation'] = $payload['isHideOccupation'];
                  unset($payload['isHideOccupation']);
                  }
                  if (isset($payload['income']))
                  {
                  $payload['meta']['income'] = $payload['income'];
                  unset($payload['income']);
                  }
                  if (isset($payload['assets']))
                  {
                  $payload['meta']['assets'] = $payload['assets'];
                  unset($payload['assets']);
                  }
                  if (isset($payload['area']))
                  {
                  $payload['meta']['area'] = $payload['area'];
                  unset($payload['area']);
                  }
                  if (isset($payload['budget']))
                  {
                  $payload['meta']['budget'] = $payload['budget'];
                  unset($payload['budget']);
                  }
                  if (isset($payload['birthdate']))
                  {
                  $payload['meta']['birthdate'] = $payload['birthdate'];
                  unset($payload['birthdate']);
                  }
                  if (isset($payload['height']))
                  {
                  $payload['meta']['height'] = $payload['height'];
                  unset($payload['height']);
                  }
                  if (isset($payload['weight']))
                  {
                  $payload['meta']['weight'] = $payload['weight'];
                  unset($payload['weight']);
                  }
                  if (isset($payload['cup']))
                  {
                  $payload['meta']['cup'] = $payload['cup'];
                  unset($payload['cup']);
                  }
                  if (isset($payload['job']))
                  {
                  $payload['meta']['job'] = $payload['job'];
                  unset($payload['job']);
                  }
                  if (isset($payload['domain']))
                  {
                  $payload['meta']['domain'] = $payload['domain'];
                  unset($payload['domain']);
                  }
                  if (isset($payload['domainType']))
                  {
                  $payload['meta']['domainType'] = $payload['domainType'];
                  unset($payload['domainType']);
                  }
                   if (isset($payload['blockdomain']))
                  {
                  $payload['meta']['blockdomain'] = $payload['blockdomain'];
                  unset($payload['blockdomain']);
                  }
                  if (isset($payload['domainType']))
                  {
                  $payload['meta']['domainType'] = $payload['domainType'];
                  unset($payload['domainType']);
                  }
                  if (isset($payload['blockdomainType']))
                  {
                  $payload['meta']['blockdomainType'] = $payload['blockdomainType'];
                  unset($payload['blockdomainType']);
                  }
                  if (isset($payload['blockcity']))
                  {
                  $payload['meta']['blockcity'] = $payload['blockcity'];
                  unset($payload['blockcity']);
                  }
                  if (isset($payload['blockarea']))
                  {
                  $payload['meta']['blockarea'] = $payload['blockarea'];
                  unset($payload['blockarea']);
                  }
                  if (isset($payload['body']))
                  {
                  $payload['meta']['body'] = $payload['body'];
                  unset($payload['body']);
                  }
                  if (isset($payload['about']))
                  {
                  $payload['meta']['about'] = $payload['about'];
                  unset($payload['about']);
                  }
                  if (isset($payload['style']))
                  {
                  $payload['meta']['style'] = $payload['style'];
                  unset($payload['style']);
                  }
                  if (isset($payload['situation']))
                  {
                  $payload['meta']['situation'] = $payload['situation'];
                  unset($payload['situation']);
                  }
                  if (isset($payload['education']))
                  {
                  $payload['meta']['education'] = $payload['education'];
                  unset($payload['education']);
                  }
                  if (isset($payload['marriage']))
                  {
                  $payload['meta']['marriage'] = $payload['marriage'];
                  unset($payload['marriage']);
                  }
                  if (isset($payload['drinking']))
                  {
                  $payload['meta']['drinking'] = $payload['drinking'];
                  unset($payload['drinking']);
                  }
                  if (isset($payload['smoking']))
                  {
                  $payload['meta']['smoking'] = $payload['smoking'];
                  unset($payload['smoking']);
                  }
                  if (isset($payload['occupation']))
                  {
                $payload['meta']['occupation'] = $payload['occupation'];
                  unset($payload['occupation']);
                  }
                  if (isset($payload['notifmessage']))
                  {
                $payload['meta']['notifmessage'] = $payload['notifmessage'];
                  unset($payload['notifmessage']);
                  }
                   if (isset($payload['notifhistory']))
                  {
                                  $payload['meta']['notifhistory'] = $payload['notifhistory'];
                  unset($payload['notifhistory']);
                  }
                $meta = $user->meta_();
                if (isset($payload['meta']))
                {
                    $meta->exists = true;
                    $meta->update($payload['meta']);
                    $userMetaResult = true;
                }
                else $userMetaResult = false;
                $user->update($payload);

                if (isset($payload['roles'])) {
                    $this->unassignAllRoles($userId);
                    $this->assignRole($payload['roles'], $userId);
                }

                return $user;
            });
        } catch (Exception $e) {
            throw new Exception("We were unable to update your profile " . $e, 1);
        }
    }

    /**
     * Invite a new member
     * @param  array $info
     * @return void
     */
    public function invite($info)
    {
        $password = substr(md5(rand(1111, 9999)), 0, 10);

        return DB::transaction(function () use ($password, $info) {
            $user = $this->model->create([
                'email' => $info['email'],
                'name' => $info['name'],
                'password' => bcrypt($password)
            ]);

            return $this->create($user, $password, $info['roles'], true);
        });
    }

    /**
     * Destroy the profile
     *
     * @param  int $id
     * @return bool
     */
    public function destroy($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $this->unassignAllRoles($id);
                $this->leaveAllTeams($id);

                $userMetaResult = $this->userMeta->where('user_id', $id)->delete();
                $userResult = $this->model->find($id)->delete();

                return ($userMetaResult && $userResult);
            });
        } catch (Exception $e) {
            throw new Exception("We were unable to delete this profile", 1);
        }
    }

    /**
     * Switch user login
     *
     * @param  integer $id
     * @return boolean
     */
    public function switchToUser($id)
    {
        try {
            $user = $this->model->find($id);
            Session::put('original_user', Auth::id());
            Auth::login($user);
            return true;
        } catch (Exception $e) {
            throw new Exception("Error logging in as user", 1);
        }
    }

    /**
     * Switch back
     *
     * @param  integer $id
     * @return boolean
     */
    public function switchUserBack()
    {
        try {
            $original = Session::pull('original_user');
            $user = $this->model->find($original);
            Auth::login($user);
            return true;
        } catch (Exception $e) {
            throw new Exception("Error returning to your user", 1);
        }
    }

    /**
     * Set and send the user activation token via email
     *
     * @param void
     */
    public function setAndSendUserActivationToken($user)
    {
        $token = md5(str_random(40));

        $user->meta_()->update([
            'activation_token' => $token
        ]);

        $user->notify(new ActivateUserEmail($token));
    }

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */

    /**
     * Assign a role to the user
     *
     * @param  string $roleName
     * @param  integer $userId
     * @return void
     */
    public function assignRole($roleName, $userId)
    {
        $role = $this->role->findByName($roleName);
        $user = $this->model->find($userId);

        $user->roles()->attach($role);
    }

    /**
     * Unassign a role from the user
     *
     * @param  string $roleName
     * @param  integer $userId
     * @return void
     */
    public function unassignRole($roleName, $userId)
    {
        $role = $this->role->findByName($roleName);
        $user = $this->model->find($userId);

        $user->roles()->detach($role);
    }

    /**
     * Unassign all roles from the user
     *
     * @param  string $roleName
     * @param  integer $userId
     * @return void
     */
    public function unassignAllRoles($userId)
    {
        $user = $this->model->find($userId);
        $user->roles()->detach();
    }
}
