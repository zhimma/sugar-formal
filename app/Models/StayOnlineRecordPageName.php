<?php

namespace App\Models;

use App\Models\StayOnlineRecord;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StayOnlineRecordPageName extends Model
{
    protected $table = 'stay_online_record_page_name';

    protected $guarded = ['id'];
    
    public static $is_partial_enable_list = null;
    
    public function stay_online_record()
    {
        return $this->hasMany(StayOnlineRecord::class, 'url', 'url');
    }  

    public static function getIsPartialEnableList()
    {
        if(StayOnlineRecordPageName::$is_partial_enable_list) return StayOnlineRecordPageName::$is_partial_enable_list;
        return StayOnlineRecordPageName::where('is_partial',1)->orderByRaw('LENGTH(url) DESC')->get();
    }
}
