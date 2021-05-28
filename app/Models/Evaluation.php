<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    //
    protected $table = 'evaluation';
    protected $fillable = [
        'from_id',
        'to_id',
        'content',
        'rating',
        'read',
    ];
    public function user(){
        return $this->hasOne(User::class, 'id', 'from_id');
    }
}
