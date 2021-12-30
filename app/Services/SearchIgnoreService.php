<?php

namespace App\Services;

use DB;
use Auth;
use Config;
use Exception;
use App\Models\SearchIgnore;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use App\Services\UserService;

class SearchIgnoreService
{
    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
        $this->user = $userService->model??null;

        if(!$this->user->id??null) {
            $this->user = Auth::user();
        }

        $this->member_query = $this->user->search_ignore();
        $this->entrys = null;
    }
    
    public function model() {
        if(!$this->model??null) {
            if($this->user->search_ignore->count()>0)
                $this->model = $this->user->search_ignore->first();
            else $this->model = new SearchIgnore;
        }
        return $this->model;
    }
    
    public function member_query() {
        if(!$this->member_query??null) {
            $this->user->search_ignore();
        }
        return $this->member_query;
    }
    
    public function userService() {
        if(!$this->userService??null) 
            $this->userService = new UserService($this->user,$this->user->meta);
        else if($this->userService->model->id!=$this->user->id) {
            $this->userService = new UserService($this->user,$this->user->meta);
        }
        
        return $this->userService;
        
    }
    
    

    public function find($id)
    {
        return $this->model()->find($id);
    }
    
    public function getMemberQueryByIgnoreId($ignore_id)
    {
        return $this->member_query()->where('ignore_id', $ignore_id);
    }
    
    public function create($input)
    {
        try {
            $memUser = $this->user;
            $ignreUser = $this->userService()->find($input['ignore_id']);
            if($memUser->engroup == $ignreUser->engroup) return false;
            return $this->member_query()->firstOrCreate($input);
        } catch (Exception $e) {
            throw new Exception("Failed to create search ignore", 1);
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
        if(!($this->member_query()??null)) return false;
        return $this->member_query()->delete();
    }
    
    public function appendFilterListQuery() {
        $member_id = $this->user->id;;
        $this->member_query()->whereHas('ignore_user',function($query) use ($member_id){
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
        $this->entrys = $this->appendFilterListQuery()->member_query()->orderByDesc('id')->paginate(15);
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
        return $this->userService()->isBlurAvatar($userEntry, auth()->user());
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
