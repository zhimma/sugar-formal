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
    
    public static $partial_url_page_name = null;
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    } 

    public function page_name()
    {
        return $this->belongsTo(StayOnlineRecordPageName::class, 'url', 'url');
    } 

    public function getUserDescPageStayOnlineRecordsPaginate()
    {
        if($this->user) {
            $this->paginate = $this->user->stay_online_record_only_page()->orderByDesc('id')->paginate(20,['*'], 'pageU'.$this->user->id, request()->input('pageU'.$this->user->id));//->setPageName('pageU'.$this->user->id);
        } else {
            $this->paginate = $this->addOnlyPageClauseToQuery($this->where('user_id',$this->user_id))->orderByDesc('id')->paginate(20,['*'], 'pageU'.$this->user_id, request()->input('pageU'.$this->user_id));
        }
        
        return $this->paginate;
    }
    
    public static function addOnlyPageClauseToQuery($query) 
    {
        return $query->whereNotNull('stay_online_time')->whereNotNull('url');
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function getPartialUrlPageName()
    {
        if($this->partial_url_page_name) return $this->partial_url_page_name;
        
        $partial_page_name_list = StayOnlineRecordPageName::getIsPartialEnableList();;
    
        foreach($partial_page_name_list as $name_entry) {
            if(strpos($this->url,$name_entry->url)!==false) return $name_entry->name;
        }
    }
}
