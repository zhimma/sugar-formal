<?php

namespace App\Models;

use App\Models\User;
use App\Notifications\MessageEmail;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SearchIgnore extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'search_ignore';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'ignore_id',
        'created_at'
    ];

    public function user(){
        return $this->hasOne(User::class, 'id', 'member_id');
    }
    
    public function users(){
        return $this->hasOne(User::class, 'id', 'member_id');
    }    
    
    public function ignore_user(){
        return $this->hasOne(User::class, 'id', 'ignore_id');
    }    
}
