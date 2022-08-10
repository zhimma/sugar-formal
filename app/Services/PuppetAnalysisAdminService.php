<?php
namespace App\Services;

use App\Services\UserService;
use App\Repositories\PuppetAnalysisRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;


class PuppetAnalysisAdminService 
{
    public function __construct(
        UserService $userService,
        PuppetAnalysisRepository $repo
    ) {
        
        $this->user_service = $userService;
        $this->repo = $repo; 
        //$this->riseByUserEntry($this->user());
        $this->init();
    } 
    
    public function init() 
    {
        $this->repo->init();
        $this->error_msg('');
        return $this;
    }
    
    public function riseByUserService(UserService $userService) 
    {
        return $this->riseByUserEntry($userService->model);
    }
    
    public function riseByUserId($user_id) 
    {
        $user_entry = $this->getUserById($user_id);
        return $this->riseByUserEntry($user_entry);
    }
    
    public function riseByUserEntry($user_entry) 
    {
        $this->repo()->riseByUserEntry($user_entry);
        
        if($this->user_service->model->id!=$user_entry->id) {
            $this->user_service->riseByUserEntry($user_entry);
        }
        
        return $this;    
    }    

  

    public function repo() 
    {
        return $this->repo;
    } 

    public function user($value_or_reset=false)
    {
        return $this->repo()->user($value_or_reset);
    }     

    public function cell_entry($value_or_reset=false)
    {
        return $this->repo()->cell_entry($value_or_reset);
    }  

    public function row_entry($value_or_reset=false)
    {
        return $this->repo()->row_entry($value_or_reset);
    }   

    public function col_entry($value_or_reset=false)
    {
        return $this->repo()->col_entry($value_or_reset);
    }
    
    public function user_service($value_or_reset) 
    {        
        return $this->user_service;
    }      

    public function row_list($value_or_reset)
    {
        return $this->repo()->row_list($value_or_reset);
    } 

    public function error_msg($msg=null) 
    {
        if($msg!==null) $this->_error_msg = $msg;
        return $this->_error_msg;        
    }    
    /*
    public function initByUserService($userService) 
    {
        
        $this->user_service = $userService;
        $this->repo->riseByUserEntry($this->user_service->model??null);        
        $this->init();
        return $this;
    }
    
    public function initByUserEntry($userEntry) 
    {
        $this->user_service->riseByUserEntry($userEntry);
        $this->repo->riseByUserEntry($userEntry);        
        $this->init();
        return $this;
    }    
    
    public function riseByUserService(UserService $userService) 
    {
        $this->user_service = $userService;
        $this->repo->riseByUserEntry($this->user_service->model??null);
        return $this;
    }
    
    public function riseByUserEntry($user_entry) 
    {
        $this->repo()->riseByUserEntry($this->user_service->riseByUserEntry($user_entry)->model);
        return $this;    
    }
    
    public function riseByUserId($user_id) 
    {
        $this->repo()->riseByUserId($user_id);
        $this->user_service->riseByUserEntry($this->rau_repo()->user());
        return $this;    
    }    
    
    public function user() 
    {
        return $this->user_service->model??null;

    }

    public function user_service() 
    {
        return $this->user_service;
    }
    */
    
    public function getRowListFromCatAndGroupIndex($cat,$group_index=null)
    {
        $query = $this->row_entry()->with('user')->where('cat',$cat);
        
        if($group_index!==null) $query->where('group_index',$group_index);
        
        $row_list = $query->with('user')->get()->sortByDesc('user.last_login');

        return $this->row_list($row_list);

    }
    
    public function getRowListStandardFromGroupIndex($group_index=null)
    {
        return $this->getRowListFromCatAndGroupIndex('',$group_index);
    }

    public function getRowListOnlyCfpIdFromGroupIndex($group_index=null)
    {
        return $this->getRowListFromCatAndGroupIndex('only_cfpid',$group_index);
    }
    
    public function getUserById($user_id)
    {
        return $this->user($this->user_service->find($user_id));
    }
    
    public function getCityLayoutByUserEntry($user)
    {
        $city_layout = '';
        if(!$user->meta->city) return;
        $city_list = explode(",",$user->meta->city);
        $area_list = explode(",",$user->meta->area);
    
        foreach($city_list as $k=>$city) {
            if($k) $city_layout.=',';
            $city_layout.= $city.'/'.$area_list[$k];
        }
        
        return $city_layout;
    }
 
    public function getUserLoginDateList($filter_arr=[])
    {
        //$user = $this->getUserById($user_id);
        $user = $this->user();
        $query = $user->log_user_login();
        //if($filter_arr['before_period']??null) {
            //$this->before_period = $filter_arr['before_period']??1;
            $query->where('created_date','>=',\DB::raw('DATE_SUB(DATE(NOW()), INTERVAL '.intval($filter_arr['before_period']??1).'  MONTH)'));
        //}
        
        
        return $query->distinct('created_date')->orderBy('created_date')->pluck('created_date');
    }
    
    public function getUserLoginCreatedAtList($filter_arr=[])
    {
        $user = $this->user();
        $query = $user->log_user_login();
        
        if($filter_arr['date']??null) {
            $query->where('created_date',$filter_arr['date']);
        }
        
        //return $query->distinct('created_at')->pluck('created_at');
        return $query->distinct('created_at')->get();
    } 

    
    
    public function getCompareLoginTimeResultLayoutByDateAndCreatedAtList($date,$created_at_list)
    {
        $layout = '';
        
       $target = $this->user();

        $time_list = $this->getUserLoginCreatedAtList(['date'=>$date]);

        foreach($time_list->pluck('created_at') as $time) {
            
            $start_time = Carbon::parse($time)->subMinutes($this->compare_interval??30);
            $end_time = Carbon::parse($time)->addMinutes($this->compare_interval??30);
            $is_time_match = 0;
            $is_time_match = $created_at_list->filter(function($item) use ($start_time,$end_time) {
                                return $item->created_at<=$end_time && $item->created_at>=$start_time;
                            })->count();
            
            $matched_class = '';
            
            if($is_time_match) {
                $matched_class = 'time_matched';
            }
            
            $layout.= '<div class="'.$matched_class.'">'.substr($time,11,5).'</div>';
        }
        
        if($layout=='')  $layout='無';
        
        return $layout;
    }
    
    public function getGenderClass()
    {
        if($this->user()->engroup==1) return 'male';
        else  return 'female';
    }
    
    public function getWarnedBannedClass()
    {
        $tag_class = '';
        $cur_user =  $this->user();
        
        if($cur_user->banned)  {
            $tag_class.= 'banned ';
        }
        
        if($cur_user->implicitlyBanned) {
            $tag_class.= 'implicitlyBanned ';
        } 

        if((isset($cur_user->user_meta->isWarned) && $cur_user->user_meta->isWarned) || $cur_user->aw_relation)  $tag_class.= 'isWarned ';
        if($cur_user->accountStatus===0) $tag_class.= 'isClosed ';
        if($cur_user->account_status_admin===0) {
            $tag_class.= 'isClosedByAdmin ';
        } 

        return $tag_class;
    }
    
    public function getColIndexClassByIndex($index)
    {
        $class = '';
        
        switch($index) {
            case 1:
                $class ='col-1st';
            break;
            case 2:
                $class ='col-2nd';
            break;
            case 3:
                $class='col-3rd';
            break;
            default:
                $class='col-most';
            break;
        }
        
        return $class;
    }
 
}
