<?php

namespace App\Models\SimpleTables;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class short_message extends Model
{
    use SoftDeletes;
    //
    protected $table = 'short_message';
    
    public $timestamps = false;
    
    protected $guarded = ['id']; 

    public function user()
    {
        return $this->belongsTo(User::class, 'member_id', 'id');
    }     
}
