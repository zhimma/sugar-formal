<?php

namespace App\Services;

use DB;
use Auth;
use Config;
use Exception;
use App\Services\UserService;
use App\Models\SearchIgnore;
use Illuminate\Support\Facades\Schema;

class SearchIgnoreService
{
    public function __construct(
        SearchIgnore $model
    ) {
        $this->model = $model;
        $this->member_id = $model->member_id??null;
        if(!$this->member_id) {
            $this->member_id = Auth::user()->id;
        }
        $this->member_query = $model->where('member_id',$this->member_id);
        $this->entrys = null;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }
    
    public function getMemberQueryByIgnoreId($ignore_id)
    {
        return $this->member_query->where('ignore_id', $ignore_id);
    }
    
    public function create($input)
    {
        try {
            if(!($input['member_id']??null)) {
                 $input['member_id'] = $this->member_id;
            }
            
            return $this->model->firstOrCreate($input);
        } catch (Exception $e) {
            throw new Exception("Failed to create role", 1);
        }
    }

    public function delByIgnoreId($ignore_id)
    {
        if(!$ignore_id??null) return false;
        try {
            $result = false;
            $result = DB::transaction(function () use ($ignore_id) {
                $ignoreEntry = $this->getMemberQueryByIgnoreId($ignore_id);
                if($ignoreEntry->count()) return $ignoreEntry->delete();
                else return true;

            });
            return $result;
        } catch (Exception $e) {
            throw new Exception("We were unable to delete this search ignore".$e, 1);
        }

        return $result;
    }
    
    public function delMemberAll() {
        if(!($this->member_id??null)) return false;
        return $this->member_query->delete();
    }
    
    public function setMemberId($member_id) {
        $this->member_id = $member_id??null;
        $this->member_query = $this->model->where('member_id',$this->member_id);        
        return $this;
    }
    
    public function appendFilterListQuery() {
        $member_id = $this->member_id;
        $this->member_query->whereHas('ignore_user',function($query) use ($member_id){
            $query->where('accountStatus',1)->where('account_status_admin',1);
            $query->doesntHave('banned');
            $query->doesntHave('implicitlyBanned');
            $query->whereDoesntHave('blockedInBlocked',function($bquery) use ($member_id){
                $bquery->where('member_id',$member_id);
            });
        });
        
        return $this;
    }
    
    public function fillPagingEntrys() {
        $this->entrys = $this->appendFilterListQuery()->member_query->orderByDesc('id')->paginate(15);
        return $this;
    } 

    public function getCityShowByUser($userEntry) {
        $umeta = $userEntry->meta;
        $show_str = '';
        if(isset($umeta->city)){
            $umeta->city = explode(",",$umeta->city);
            $umeta->area = explode(",",$umeta->area);
        }    
       
        if(!empty($umeta->city)) {
            foreach($umeta->city as $key => $cityval) {
                if (!$key)
                    $show_str.=$umeta->city[$key].' '.(($umeta->isHideArea == 0)?$umeta->area[$key]:'');
                else
                    $show_str.='<span>'.$umeta->city[$key].(($umeta->isHideArea == 0)?$umeta->area[$key]:'').'</span>';
            }
        }   
        
        return $show_str;
    }
    
    public function isBlurAvatarByUser($userEntry) {
        return UserService::isBlurAvatar($userEntry, auth()->user());
    }
    
    public function getShowPicByUser($userEntry) {
        $pic_show = '';
        if($userEntry->user_meta->isAvatarHidden == 1) {
            $pic_show = 'makesomeerror';
        }
        else {
            $pic_show = $userEntry->user_meta->pic;  
        } 

        return $pic_show;
    }
    
    public function getPicOnErrorByEngroup($engroup) {
        $pic_for_error = '';
        if(!$engroup) return;
        if ($engroup == 1) 
            $pic_for_error=asset("/new/images/male.png");
        else $pic_for_error=asset("/new/images/female.png");
      
        return $pic_for_error;
    }

}
