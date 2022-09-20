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
    
    public $paginate = null;
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    } 

    public function page_name()
    {
        return $this->belongsTo(StayOnlineRecordPageName::class, 'url', 'url');
    } 

    public function getUserDescRecordsPaginate()
    {
        $this->paginate = $this->user->stay_online_record_only_page()->orderByDesc('id')->paginate(20,['*'], 'pageU'.$this->user->id, request()->input('pageU'.$this->user->id));//->setPageName('pageU'.$this->user->id);
        return $this->paginate;
    }
}
