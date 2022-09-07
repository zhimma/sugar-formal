<?php

namespace App\Models;

use App\Models\User;
use App\Models\StayOnlineRecordPageName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StayOnlineRecord extends Model
{
    protected $table = 'stay_online_record';

    protected $guarded = ['id'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    } 

    public function page_name()
    {
        return $this->belongsTo(StayOnlineRecordPageName::class, 'url', 'url');
    }      
}
