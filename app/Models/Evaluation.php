<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluation extends Model
{
    use SoftDeletes;
    protected $table = 'evaluation';
    protected $fillable = [
        'from_id',
        'to_id',
        'content',
        'rating',
        'read',
        'admin_comment',
        'deleted_at'
    ];
    public function user(){
        return $this->hasOne(User::class, 'id', 'from_id');
    }
}
