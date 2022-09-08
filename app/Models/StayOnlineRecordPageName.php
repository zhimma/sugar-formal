<?php

namespace App\Models;

use App\Models\StayOnlineRecord;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StayOnlineRecordPageName extends Model
{
    protected $table = 'stay_online_record_page_name';

    protected $guarded = ['id'];
    
    public function stay_online_record()
    {
        return $this->hasMany(StayOnlineRecord::class, 'url', 'url');
    }     
}
