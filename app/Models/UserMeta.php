<?php

namespace App\Models;

use \Datetime;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class UserMeta extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_meta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'terms_and_cond',
        'is_active',
        'activation_token',
        'title',
        'city',
        'blockcity',
        'area',
        'blockarea',
        'isHideArea',
        'budget',
        'birthdate',
        'height',
        'weight',
        'isHideWeight',
        'cup',
        'isHideCup',
        'body',
        'about',
        'style',
        'situation',
        'occupation',
        'education',
        'marriage',
        'drinking',
        'smoking',
        'isHideOccupation',
        'country',
        'memo',
        'pic',
        'domainType',
        'blockdomainType',
        'domain',
        'blockdomain',
        'job',
        'realName',
        'assets',
        'income',
        'notifmessage',
        'notifhistory'
    ];

    public function age() {
        if (isset($this->birthdate) && $this->birthdate !== null && $this->birthdate != 'NULL')
        {
            $userDob = $this->birthdate;
            $dob = new DateTime($userDob);

            $now = new DateTime();

            $difference = $now->diff($dob);

            $age = $difference->y;
            return $age;
        }
        return 0;
    }

    public function isAllSet()
    {
        return isset($this->smoking) && isset($this->drinking) && isset($this->marriage) && isset($this->education) && isset($this->about) && isset($this->style) && isset($this->birthdate) && isset($this->budget) && $this->height > 0 && isset($this->area) && isset($this->city);
    }

    // public static function uploadUserHeader($uid, $fieldContent) {
    //     return DB::table('user_meta')->where('user_id', $uid)->update(['pic' => $fieldContent]);
    // }

    /**
     * User
     *
     * @return Relationship
     */
     // public function user() {
     //     return $this->belongsTo(User::class);
     // }

    public function user()
    {
        return User::where('id', $this->user_id)->first();
    }

    public static function search($city, $area, $cup, $marriage, $budget, $income, $smoking, $drinking, $photo, $agefrom, $ageto, $engroup, $blockcity, $blockarea, $blockdomain, $blockdomainType)
    {
        if ($engroup == 1)
        {
            $engroup = 2;

        }
        else if ($engroup == 2) { $engroup = 1; }

        $query = UserMeta::where('users.engroup', $engroup)->join('users', 'user_id', '=', 'users.id');

        if (isset($city) && strlen($city) != 0) $query = $query->where('city', $city);
        if (isset($area) && strlen($area) != 0) $query = $query->where('area', $area);
        if ($engroup == 1)
        {
           if (isset($blockarea) && strlen($blockarea) != 0) $query->where('blockarea', '<>', $blockarea);
            if (isset($blockcity) && strlen($blockcity) != 0) $query->where('blockcity', '<>', $blockcity);
            if (isset($blockdomain) && strlen($blockdomain) != 0) $query->where('blockdomain', '<>', $blockdomain);
            if (isset($blockdomainType) && strlen($blockdomainType) != 0) $query->where('blockdomainType', '<>', $blockdomainType);
        }
        if (isset($cup) && strlen($cup) != 0) $query = $query->where('cup', $cup);
        if (isset($marriage) && strlen($marriage) != 0) $query = $query->where('marriage', $marriage);
        if (isset($budget) && strlen($budget) != 0) $query = $query->where('budget', $budget);
        if (isset($income) && strlen($income) != 0) $query = $query->where('income', $income);
        if (isset($smoking) && strlen($smoking) != 0) $query = $query->where('smoking', $smoking);
        if (isset($drinking) && strlen($drinking) != 0) $query = $query->where('drinking', $drinking);
        if (isset($photo) && strlen($photo) != 0) $query = $query->whereNotNull('photo')->where('photo', '<>', 'NULL');
        if (isset($agefrom) && isset($ageto) && strlen($agefrom) != 0 && strlen($ageto) != 0) $query = $query->whereBetween('birthdate', [Carbon::now()->subYears($ageto), Carbon::now()->subYears($agefrom)]);
        return $query->paginate(12);
    }
}
