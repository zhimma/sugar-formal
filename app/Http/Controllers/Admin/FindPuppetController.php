<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use App\Models\LogUserLogin;
use App\Models\PuppetAnalysisCell;
use App\Models\PuppetAnalysisColumn;
use App\Models\PuppetAnalysisRow;
use App\Models\PuppetAnalysisIgnore;
use App\Models\StayOnlineRecord;
use App\Models\Message;
use Illuminate\Support\Facades\Log;
use App\Services\UserService;
use App\Services\PuppetAnalysisAdminService;

class FindPuppetController extends \App\Http\Controllers\Controller
{
    private $_columnIp = array();
    private $_rowUserId = array();
    private $_cellVal = array();
    private $_columnType = array();
    private $_groupIdx =0;
    
    public function __construct(LogUserLogin $logUserLogin,PuppetAnalysisColumn $column,PuppetAnalysisRow $row, PuppetAnalysisCell $cell, PuppetAnalysisIgnore $ignore)
    {
        ini_set("max_execution_time",'0');
        ini_set('memory_limit','-1');
        ini_set("request_terminate_timeout",'0');
        ini_set('default_socket_timeout',-1);
        set_time_limit(0);
        error_reporting(0);

        $this->model = $logUserLogin;
        $this->model->setReadOnly();
        $this->column = $column;
        $this->row = $row;
        $this->cell = $cell;
        $this->ignore = $ignore;        
        
        $this->_columnIp = array();
        $this->_rowUserId = array();
        $this->_cellVal = array();
        $this->_columnType = array();
        $this->_cpfidOfOverLimitUserId = [];
        $this->_vidOfOverLimitUserId = [];
        $this->_groupIdx = 0; 
        $this->monarr = [];    
        $this->defaultSdateOfIp = \Carbon\Carbon::now()->subDays(10)->format('Y/m/d');
        $this->defaultSdateOfCfpId = null; 
        $this->defaultSdateOfVid = null;              
    }    
    
    public function entrance(Request $request) 
    {          
        ini_set("max_execution_time",'0');
        ini_set('memory_limit','-1'); 
        set_time_limit(0);
        error_reporting(0);        
        
        $error_msg = '';
        $whereArr = [];
        $whereArrOfIp = [];
        $whereArrOfCfpId = [];
        $whereArrOfVid = [];
        $have_mon_limit = false;
        $only= $request->only;
		$cat = $only?'only_'.$only:'';
        Log::info('findPuppet排程'.$cat.'：開始執行');

        if(isset($this->monarr) && count($this->monarr)>0) {
            $monarr = $this->monarr;
            $have_mon_limit = true;
        }
       
        $curdate = date('Y/m/d');
        $curtime = date(' H:i:s');
        
        $edate = $curdate.$curtime;

        try {
            $this->column->insert( ['column_index'=>-1
                ,'name'=>'開始執行'
                ,'group_index'=>-1
                ,'cat'=>$cat
                ,'type'=>''
                ,'created_at'=>$edate
                ,'updated_at'=>date('Y-m-d H:i:s')]);

            $sdateOfIp = $request->sdate?$request->sdate.'/01':$this->defaultSdateOfIp;
            $edateOfIp = $request->edate?date('Y/m/d',strtotime($request->edate.'/01+1 month - 1 day')):$curdate;

            $sdateOfCfpId = $request->sdate?$request->sdate.'/01':$this->defaultSdateOfCfpId;
            $edateOfCfpId = $request->edate?date('Y/m/d',strtotime($request->edate.'/01+1 month - 1 day')):$curdate;

            $sdateOfVid = $request->sdate?$request->sdate.'/01':$this->defaultSdateOfVid;
            $edateOfVid = $request->edate?date('Y/m/d',strtotime($request->edate.'/01+1 month - 1 day')):$curdate;

            if($sdateOfIp) $sdateOfIpArr = explode('/',$sdateOfIp);
            else $sdateOfIpArr  = null;
            if($edateOfIp) $edateOfIpArr = explode('/',$edateOfIp);   
            else $edateOfIpArr  = null;
            
            if(($sdateOfIpArr && !checkdate($sdateOfIpArr[1],$sdateOfIpArr[2],$sdateOfIpArr[0])) || ($edateOfIpArr && !checkdate($edateOfIpArr[1],$edateOfIpArr[2],$edateOfIpArr[0]))) {
                $error_msg = 'IP日期格式錯誤或非正確日期';
            }
            else if($edateOfIp<$sdateOfIp) {
                $error_msg = '錯誤!IP結束日期小於開始日期';
            }
            
            if($sdateOfCfpId) $sdateOfCfpIdArr = explode('/',$sdateOfCfpId);
            else $sdateOfCfpIdArr  = null;
            if($edateOfCfpId) $edateOfCfpIdArr = explode('/',$edateOfCfpId);   
            else $edateOfCfpIdArr  = null;
            
            if(($sdateOfCfpIdArr && !checkdate($sdateOfCfpIdArr[1],$sdateOfCfpIdArr[2],$sdateOfCfpIdArr[0])) || ($edateOfCfpIdArr && !checkdate($edateOfCfpIdArr[1],$edateOfCfpIdArr[2],$edateOfCfpIdArr[0]))) {
                $error_msg = 'CfpId日期格式錯誤或非正確日期';
            }
            else if($edateOfCfpId<$sdateOfCfpId) {
                $error_msg = '錯誤!CfpId結束日期小於開始日期';
            } 

            if($sdateOfVid) $sdateOfVidArr = explode('/',$sdateOfVid);
            else $sdateOfVidArr  = null;
            if($edateOfCfpId) $edateOfVidArr = explode('/',$edateOfVid);   
            else $edateOfVidArr  = null;
            
            if(($sdateOfVidArr && !checkdate($sdateOfVidArr[1],$sdateOfVidArr[2],$sdateOfVidArr[0])) || ($edateOfVidArr && !checkdate($edateOfVidArr[1],$edateOfVidArr[2],$edateOfVidArr[0]))) {
                $error_msg = 'CfpId日期格式錯誤或非正確日期';
            }
            else if($edateOfVid<$sdateOfVid) {
                $error_msg = '錯誤!CfpId結束日期小於開始日期';
            }               

            echo $error_msg;

                if(!$error_msg) {
                    if(isset($edateOfIp)) {
                        if($edateOfIp) $edateOfIp.=$curtime;
                        $whereArrOfIp[] = ['created_at','<',$edateOfIp];
                    }
                    
                    if(isset($sdateOfIp)) $whereArrOfIp[] = ['created_at','>=',$sdateOfIp];
                    
                    if(isset($edateOfCfpId)) {
                        if($edateOfCfpId) $edateOfCfpId.=$curtime;
                        $whereArrOfCfpId[] = ['created_at','<',$edateOfCfpId];
                    }
                    
                    if(isset($sdateOfCfpId)) $whereArrOfCfpId[] = ['created_at','>=',$sdateOfCfpId];                    


                    if(isset($edateOfVid)) {
                        if($edateOfVid) $edateOfVid.=$curtime;
                        $whereArrOfVid[] = ['created_at','<',$edateOfVid];
                    }
                    
                    if(isset($sdateOfVid)) $whereArrOfVid[] = ['created_at','>=',$sdateOfVid]; 

                    
                    if($have_mon_limit) {
                        if(isset($mon) && $mon) {
                            $have_mon_limit = true;
                            $mon_date = '2021-'.$mon;
                            $whereArr[] = ['created_at','LIKE',$mon_date.'%'];
                        }
                        else $have_mon_limit = false;
                    
                    }                                    
                    Log::info('findPuppet排程'.$cat.'：開始讀取不比對的user id $excludeUserId');
                    $this->column->insert( ['column_index'=>-1
                        ,'name'=>'開始讀取不比對的user id '
                        ,'group_index'=>-1
                        ,'cat'=>$cat
                        ,'type'=>''
                        ,'created_at'=>$edate
                        ,'updated_at'=>date('Y-m-d H:i:s')]);
                    
                    $exclude_email_arr = [
                        ' u10104025@go.utaipei.edu.tw'
                        ,'chadian0707@gmail.com'
                        ,'shueiyan@gmail.com'
                        ,'xwxwxwcc+2@gmail.com'
                        ,'judytasi0948@gmail.com'
                        ,'shueiyan+1@gmail.com'
                        ,'xwxwxwcc+3@gmail.com'
                        ,'xwxwxwcc+6@gmail.com'
                        ,'xwxwxwcc+4@gmail.com'
                        ,'xwxwxwcc+5@gmail.com'
                        ,'chadian0707+67@gmail.com'
                        ,'chadian0707+68@gmail.com'
                        ,'xwxwxwcc+31@gmail.com'
                        ,'shueiyan+99@gmail.com'
                        ,'xwxwxwcc+22@gmail.com'
                        ,'xwxwxwcc+21@gmail.com'
                        ,'xwxwxwcc+24@gmail.com'
                        ,'xwxwxwcc+25@gmail.com'
                        ,'xwxwxwcc+27@gmail.com'
                        ,'shueiyan+2001@gmail.com'
                        ,'eileen18192000@yahoo.com.tw'
                        ,'xwxwxwcc+29@gmail.com'
                        ,'linziandfatsi@gmail.com'
                        ,'linziandfatsi+3@gmail.com'
                        ,'linziandfatsi+5@gmail.com'
                        ,'linziandfatsi+7@gmail.com'
                        ,'linziandfatsi+9@gmail.com'
                        ,'linziandfatsi+10@gmail.com'
                        ,'linziandfatsi+2@gmail.com'
                        ,'linziandfatsi+4@gmail.com'
                        ,'linziandfatsi+6@gmail.com'
                        ,'linziandfatsi+15@gmail.com'
                        ,'linziandfatsi+16@gmail.com'
                        ,'linziandfatsi+17@gmail.com'
                        ,'linziandfatsi+18@gmail.com'
                        ,'linziandfatsi+19@gmail.com'
                        ,'linziandfatsi+20@gmail.com'
                        ,'linziandfatsi+23@gmail.com'
                        ,'linziandfatsi+21@gmail.com'
                        ,'linziandfatsi+31@gmail.com'
                        ,'linziandfatsi+32@gmail.com'
                        ,'linziandfatsi+34@gmail.com'
                        ,'linziandfatsi+35@gmail.com'
                        ,'linziandfatsi+38@gmail.com'
                        ,'linziandfatsi+40@gmail.com'
                        ,'linziandfatsi+41@gmail.com'
                        ,'linziandfatsi+47@gmail.com'
                        ,'linziandfatsi+48@gmail.com'
                        ,'linziandfatsi+50@gmail.com'
                        ,'linziandfatsi+51@gmail.com'
                        ,'linziandfatsi+53@gmail.com'
                        ,'linziandfatsi+54@gmail.com'
                        ,'linziandfatsi+55@gmail.com'
                        ,'linziandfatsi+56@gmail.com'
                        ,'linziandfatsi+57@gmail.com'
                        ,'linziandfatsi+58@gmail.com'
                        ,'linziandfatsi+59@gmail.com'
                        ,'linziandfatsi+60@gmail.com'
                        ,'linziandfatsi+61@gmail.com'
                        ,'linziandfatsi+62@gmail.com'
                        ,'linziandfatsi+63@gmail.com'
                        ,'linziandfatsi+64@gmail.com'
                        ,'linziandfatsi+65@gmail.com'
                        ,'linziandfatsi+66@gmail.com'
                        ,'linziandfatsi+67@gmail.com'
                        ,'linziandfatsi+68@gmail.com'
                        ,'nzskinny0204+1019+10@gmail.com'
                        ,'nzskinny0204+1019+20@gmail.com'
                        ,'nzskinny0204+1019+001@gmail.com'
                        ,'nzskinny0204+1019+002@gmail.com'
                        ,'nzskinny0204+1021+2@gmail.com'
                        ,'nzskinny0204+1026+2@gmail.com'
                        ,'nzskinny0204+1026+3@gmail.com'
                        ,'linziandfatsi+69@gmail.com'
                        ,'linziandfatsi+70@gmail.com'
                        ,'guessq@hotmail.com'
                        ,'linziandfatsi+71@gmail.com'
                        ,'guessq+1@hotmail.com'
                        ,'guessq+2@hotmail.com'
                        ,'guessq+4@hotmail.com'
                        ,'guessq+6@hotmail.com'
                        ,'guessq+7@hotmail.com'
                        ,'guessq+10@hotmail.com'
                        ,'guessq+11@hotmail.com'
                        ,'guessq+12114@hotmail.com'
                        ,'guessq+12115@hotmail.com'
                        ,'linziandfatsi+72@gmail.com'
                        ,'guessq+3@hotmail.com'
                        ,'guessq+80@hotmail.com'
                        ,'guessq+90@hotmail.com'
                        ,'guessq+91@hotmail.com'
                        ,'guessq+100@hotmail.com'
                        ,'guessq+110@hotmail.com'
                        ,'guessq+101@hotmail.com'
                        ,'guessq+102@hotmail.com'
                        ,'guessq+103@hotmail.com'
                        ,'guessq+13@hotmail.com'
                        ,'guessq+14@hotmail.com'
                        ,'liushuhao483+1@gmail.com'
                        ,'jeremylu708+3@gmail.com'
                        ,'liushuhao483+5@gmail.com'
                        ,'liushuhao483+6@gmail.com'
                        ,'cur.xiao+01@gmail.com'
                        ,'she0330e+4@gmail.com'
                        ,'she0330e+5@gmail.com'
                        ,'chloehsu0212+1@gmail.com'
                        ,'chloehsu0212@gmail.com'
                        ,'cordelltossiebut@gmail.com'
                        ,'yonyou58+1@gmail.com'
                        ,'yonyou58+2@gmail.com'
                        ,'RandyPuntervT@gmail.com'
                        ,'alinatb_g1@test.com'
                        ,'alinatb_g2@test.com'
                        ,'alinatb_m1@test.com'
                        ,'alinatb_m2@test.com'
                        ,'alinatb_Vg1@test.com'
                        ,'alinatb_Vg2@test.com'
                        ,'alinatb_Vm1@test.com'
                        ,'alinatb_Vm2@test.com'
                        ,'alinatb_Vg3@test.com'
                        ,'alinatb_Vg4@test.com'
                        ,'alinatb_Vg5@test.com'
                        ,'alinatb_Vg6@test.com'
                        ,'alinatb_Vg7@test.com'
                        ,'alinatb_Vm3@test.com'
                        ,'alinatb_Vm4@test.com'
                        ,'alinatb_Vm5@test.com'
                        ,'alinatb_Vm6@test.com'
                        ,'000001@gmail.com'
                        ,'000002@gmail.com'
                        ,'000003@gmail.com'
                        ,'000004@gmail.com'
                        ,'0000001@gmail.com'
                        ,'0000002@gmail.com'
                        ,'testG01@gmail.com'
                        ,'0000003@gmail.com'
                        ,'testG02@gmail.com'
                        ,'testG03@gmail.com'
                        ,'testG04@gmail.com'
                        ,'testG05@gmail.com'
                        ,'0000004@gmail.com'
                        ,'testG06@gmail.com'
                        ,'testB01@gmail.com'
                        ,'testB02@gmail.com'
                        ,'testB03@gmail.com'
                        ,'testB04@gmail.com'
                        ,'testB05@gmail.com'
                        ,'testB06@gmail.com'
                        ,'candice.kitagawa39@gmail.com'
                        ,'alinatb_Vm9@test.com'
                        ,'testW1@gmail.com'
                        ,'testW2@gmail.com'
                        ,'testW3@gmail.com'
                        ,'testW4@gmail.com'
                        ,'testM1@gmail.com'
                        ,'testM2@gmail.com'
                        ,'testM3@gmail.com'
                        ,'testM4@gmail.com'
                        ,'testW5@gmail.com'
                        ,'testW6@gmail.com'
                        ,'testW7@gmail.com'
                        ,'testW8@gmail.com'
                        ,'testM5@gmail.com'
                        ,'testM6@gmail.com'
                        ,'testM7@gmail.com'
                        ,'testM8@gmail.com'
                        ,'alinatb_Vg8@test.com'
                        ,'alinatb_Vm10@test.com'
                        ,'onetest11001@gmail.com'
                        ,'0726testsg@gmail.com'
                        ,'alinatb_g3@test.com'
                        ,'alinatb_g4@test.com'

                    ];
                    
                    
                    $excludeUserId = array_pluck(User::whereHas('roles', function($query){
                        $query->where('name', 'like', '%admin%');
                    })->Select('id')->orwhere('id',1049)->orwhere('id',1)->orwhere('id',2)
                    ->orWhere('email', 'LIKE', 'sandyh.dlc%@gmail.com')
                    ->orWhere('email', 'LIKE', 'TEST%@test.com')
                    ->orWhere('email', 'LIKE', 'lzong.tw%@gmail.com')
                    ->orWhere('email', 'LIKE', ' mmmaya111%@gmail.com')
                    ->orWhereIn('email', $exclude_email_arr)
                    ->orwhereHas('meta', function($query){
                        $query->where('is_active', '0');
                    })
                    ->get()->toArray(), 'id');
                    Log::info('findPuppet排程'.$cat.'：完成讀取不比對的user id $excludeUserId '.json_encode($excludeUserId??null));
                   $this->column->insert( ['column_index'=>-1
                        ,'name'=>'完成讀取不比對的user id 共 '.count($excludeUserId??[]).'個'
                        ,'group_index'=>-1
                        ,'cat'=>$cat
                        ,'type'=>''
                        ,'created_at'=>$edate
                        ,'updated_at'=>date('Y-m-d H:i:s')]);                     
                    //$ignoreUserId = array_pluck($this->ignore->whereNull('ip')->orwhere('ip','')->get()->toArray(),'item');                    
                    Log::info('findPuppet排程'.$cat.'：開始讀取略過名單 $ignoreUserIdArr');
                   $this->column->insert( ['column_index'=>-1
                                ,'name'=>'開始讀取略過名單'
                                ,'group_index'=>-1
                                ,'cat'=>$cat
                                ,'type'=>''
                                ,'created_at'=>$edate
                                ,'updated_at'=>date('Y-m-d H:i:s')]);                     
                    $ignoreUserIdEntrys = $this->ignore
                                            ->where(function($q){
                                                $q->whereNull('ip')->orwhere('ip','');
                                            })
                                            ->where(function($q){
                                                $q->whereNull('cfp_id')->orwhere('cfp_id','');
                                            }) 
                                            ->where(function($q){
                                                $q->whereNull('visitor_id')->orwhere('visitor_id','');
                                            })                                             
                                            ->get();                    
                    foreach($ignoreUserIdEntrys  as $ignoreUserIdEntry) {
                        $ignoreUserIdArr[$ignoreUserIdEntry->item] = $ignoreUserIdEntry;
                    }  
                    Log::info('findPuppet排程'.$cat.'：完成讀取略過名單 $ignoreUserIdArr '.json_encode($ignoreUserIdArr??null));
                    $this->column->insert( ['column_index'=>-1
                        ,'name'=>'完成讀取略過名單共 '.count($ignoreUserIdArr??[]).'個'
                        ,'group_index'=>-1
                        ,'cat'=>$cat
                        ,'type'=>''
                        ,'created_at'=>$edate
                        ,'updated_at'=>date('Y-m-d H:i:s')]);                    
                    $model = $this->model;
                    $loginDataEntrys = null;
                    $this->_columnIp = [];
                    $this->_rowUserId = [];
                    $this->_cellVal = [];
                    $this->_columnType = [];
                    $this->_groupIdx = 0; 
                    
                    if(!$only || $only=='ip') {
                        Log::info('findPuppet排程'.$cat.'：開始產生IP的Login資料');
                        $this->column->insert( ['column_index'=>-1
                            ,'name'=>'開始產生IP的Login資料'
                            ,'group_index'=>-1
                            ,'cat'=>$cat
                            ,'type'=>''
                            ,'created_at'=>$edate
                            ,'updated_at'=>date('Y-m-d H:i:s')]);                         
                        $ignoreUserIdIpCollect = $this->ignore->whereNotNull('ip')->where('ip','<>','')->get();         
                        $ignoreUserIdIpArr = [];
                        foreach($ignoreUserIdIpCollect  as $userIdIpEntry) {
                            $ignoreUserIdIpArr[$userIdIpEntry->item][$userIdIpEntry->ip] = $userIdIpEntry;
                        }                        

                        Log::info('findPuppet排程'.$cat.'：開始以IP讀取登入紀錄');
                        $this->column->insert( ['column_index'=>-1
                            ,'name'=>'開始以IP讀取登入紀錄'
                            ,'group_index'=>-1
                            ,'cat'=>$cat
                            ,'type'=>''
                            ,'created_at'=>$edate
                            ,'updated_at'=>date('Y-m-d H:i:s')]);
                        $loginDataQuery = $model->has('user')->groupBy('ip','user_id')
                                ->select('ip','user_id')
                                ->selectRaw('MAX(`created_at`) AS time,COUNT(*) AS num,MIN(`created_at`) AS stime')
                                ->whereNotNull('ip')->where('ip','<>','')
                                ->where('ip','NOT LIKE','162.158.%')
                                ->where('ip','NOT LIKE','172.70.%')
                                ->where('ip','NOT LIKE','172.69.%')
                                ->where('ip','NOT LIKE','172.68.%')
                                ->where('ip','NOT LIKE','108.162.%')
                                ->where('ip','NOT LIKE','103.22.201.%') 
                                ->where('ip','NOT LIKE','172.225.%')
                                ->where('ip','NOT LIKE','172.226.%')
                                ->where('cfp_id','!=','40788')
                                /*
                                ->where('ip','NOT LIKE','172.226.71.%')      
                                ->where('ip','NOT LIKE','172.226.72.%') 
                                ->where('ip','NOT LIKE','172.226.73.%')  
                                ->where('ip','NOT LIKE','172.226.74.31')
                                ->where('ip','NOT LIKE','172.226.74.30')
                                ->where('ip','NOT LIKE','172.226.74.%') 
                                */
                                ->where('ip','NOT LIKE','104.28.135.87') 
                                ->where('ip','NOT LIKE','104.28.135.%') 
                                ->where('ip','NOT LIKE','104.28.13%.%')
                                ->where('ip','NOT LIKE','104.28.12%.%')
                                ->where('ip','NOT LIKE','104.28.11%.%')
                                ->where('ip','NOT LIKE','104.28.10%.%')
                                ->where('ip','NOT LIKE','104.28.9%.%')
                                ->where('ip','NOT LIKE','104.28.8%.%')
                                ->where('ip','NOT LIKE','104.28.7%.%')
                                ->where('ip','NOT LIKE','104.28.6%.%')
                                ->where('ip','NOT LIKE','104.28.5%.%')
                                ->where('ip','NOT LIKE','104.28.49.%')
                                ->where('ip','NOT LIKE','104.28.48.%') 
                                ->where('ip','NOT LIKE','104.28.47.%') 
                                ->where('ip','NOT LIKE','104.28.46.%') 
                                ->where('ip','NOT LIKE','104.28.46.87')
                                ->where('ip','NOT LIKE','108.162.19%.%')
                                ->where('ip','NOT LIKE','108.162.2%.%')
                                ;
                    
                        if($whereArr) $loginDataQuery->where($whereArr);
                        if($whereArrOfIp) $loginDataQuery->where($whereArrOfIp);
                        if($excludeUserId) $loginDataQuery=$loginDataQuery->whereNotIn('user_id',$excludeUserId);
                        //if($ignoreUserId) $loginDataQuery=$loginDataQuery->whereNotIn('user_id',$ignoreUserId);
                        
                        $loginDataEntrys = $loginDataQuery->orderBy('time','desc')->get();
                        
                        Log::info('findPuppet排程'.$cat.'：完成以IP讀取登入紀錄共'.$loginDataEntrys->count().'筆資料');
                        $this->column->insert( ['column_index'=>-1
                            ,'name'=>'完成以IP讀取登入紀錄共'.$loginDataEntrys->count().'筆資料'
                            ,'group_index'=>-1
                            ,'cat'=>$cat
                            ,'type'=>''
                            ,'created_at'=>$edate
                            ,'updated_at'=>date('Y-m-d H:i:s')]);                        
                        
                        $this->loginDataByIp = [];
                        $this->loginDataByUserId = [];
                        
                        foreach($loginDataEntrys  as $loginDataEntry) {
                            
                            if(isset($ignoreUserIdArr[$loginDataEntry->user_id])) {
                                $nowIgnoreUserId = $ignoreUserIdArr[$loginDataEntry->user_id];
                                if($nowIgnoreUserId->created_at> $loginDataEntry->time)  
                                    continue;
                                else {
                                    $nowIgnoreUserId->delete();
                                    $ignoreUserIdArr[$loginDataEntry->user_id] = null;
                                    unset($ignoreUserIdArr[$loginDataEntry->user_id]);
                                }                                
                            }

                            if(isset($ignoreUserIdIpArr[$loginDataEntry->user_id][$loginDataEntry->ip])) {
                                $nowIgnoreUserIdIp = $ignoreUserIdIpArr[$loginDataEntry->user_id][$loginDataEntry->ip];
                                if($nowIgnoreUserIdIp->created_at> $loginDataEntry->time)  
                                    continue;
                                else {
                                    $nowIgnoreUserIdIp->delete();
                                }
                            }
                            $this->loginDataByIp[$loginDataEntry->ip][$loginDataEntry->user_id] = $loginDataEntry;
                            $this->loginDataByUserId[$loginDataEntry->user_id][$loginDataEntry->ip] = $loginDataEntry;
                        }  

                        $middleIpUserIdArr = $this->loginDataByIp;
                        
                        foreach($middleIpUserIdArr  as $middleIp=>$middleUserIds) {
                            $nowUserIdArr = $middleUserIds;
                            
                            foreach($middleUserIds as $middleUserId=>$entry) {
                                $check_rs = false;
                                
                                foreach($nowUserIdArr  as $checkUserId=>$compare_entry) {
                                    if($checkUserId==$middleUserId) continue;
                                    if($entry->stime>$compare_entry->time) {
                                        if(strtotime($entry->stime)-strtotime($compare_entry->time)<72*3600) {
                                            $check_rs = true;
                                            break;
                                        }
                                    } 
                                    else if($entry->time< $compare_entry->stime) {
                                        if(strtotime($compare_entry->stime)-strtotime($entry->time)<72*3600) {
                                            $check_rs = true;
                                            break;
                                        }                                   
                                    }
                                    else {$check_rs = true;break;}
                                }
                                
                                if(!$check_rs) {
                                    $this->loginDataByIp[$middleIp][$middleUserId] = null;
                                    unset($this->loginDataByIp[$middleIp][$middleUserId]);
                                }
                            }
                            $check_rs = null;
                            $nowUserIdArr = null;
                        }
                        Log::info('findPuppet排程'.$cat.'：完成產生IP的Login資料');
                        $this->column->insert( ['column_index'=>-1
                            ,'name'=>'完成產生IP的Login資料'
                            ,'group_index'=>-1
                            ,'cat'=>$cat
                            ,'type'=>''
                            ,'created_at'=>$edate
                            ,'updated_at'=>date('Y-m-d H:i:s')]);                          
                    }
                    $middleIpUserIdArr = null;
                    
                    $loginDataEntrys = null;
                    
                    if(!$only || $only=='cfpid') {            
                        Log::info('findPuppet排程'.$cat.'：開始產生CfpId的Login資料');
                        $this->column->insert( ['column_index'=>-1
                            ,'name'=>'開始產生CfpId的Login資料'
                            ,'group_index'=>-1
                            ,'cat'=>$cat
                            ,'type'=>''
                            ,'created_at'=>$edate
                            ,'updated_at'=>date('Y-m-d H:i:s')]); 

                        $ignoreUserIdCfpIdCollect = $this->ignore->whereNotNull('cfp_id')->where('cfp_id','<>','')->get();         
                        $ignoreUserIdCfpIdArr = [];
                        foreach($ignoreUserIdCfpIdCollect  as $userIdCfpIdEntry) {
                            $ignoreUserIdCfpIdArr[$userIdCfpIdEntry->item][$userIdCfpIdEntry->cfp_id] = $userIdCfpIdEntry;
                        }                              

                        Log::info('findPuppet排程'.$cat.'：開始以cfp_id讀取登入紀錄');
                        $this->column->insert( ['column_index'=>-1
                            ,'name'=>'開始以cfp_id讀取登入紀錄'
                            ,'group_index'=>-1
                            ,'cat'=>$cat
                            ,'type'=>''
                            ,'created_at'=>$edate
                            ,'updated_at'=>date('Y-m-d H:i:s')]);

                            
                        $loginDataCfpIdQuery = $model->has('user')->groupBy('cfp_id','user_id')
                                ->select('cfp_id','user_id')->selectRaw('MAX(`created_at`) AS time,COUNT(*) AS num,MIN(`created_at`) AS stime')->whereNotNull('cfp_id')->where('cfp_id','<>','');
                        
                        if($whereArr) $loginDataCfpIdQuery->where($whereArr);
                        if($whereArrOfCfpId) $loginDataCfpIdQuery->where($whereArrOfCfpId);                  
                        if($excludeUserId) $loginDataCfpIdQuery=$loginDataCfpIdQuery->whereNotIn('user_id',$excludeUserId);                            

                        $loginDataEntrys = $loginDataCfpIdQuery->orderBy('time','desc')->get();
                        
                        Log::info('findPuppet排程'.$cat.'：完成以cfp_id讀取登入紀錄共'.$loginDataEntrys->count().'筆資料');
                        $this->column->insert( ['column_index'=>-1
                            ,'name'=>'完成以cfp_id讀取登入紀錄共'.$loginDataEntrys->count().'筆資料'
                            ,'group_index'=>-1
                            ,'cat'=>$cat
                            ,'type'=>''
                            ,'created_at'=>$edate
                            ,'updated_at'=>date('Y-m-d H:i:s')]);                        
                        
                        $this->loginDataByCfpId = [];
                        $this->loginDataByUserIdCfpId = [];
                        $cpfidOfOverLimitUserId = [];

                        foreach($loginDataEntrys  as $loginDataCfpIdEntry) {
                            
                            if(isset($ignoreUserIdArr[$loginDataCfpIdEntry->user_id])) {
                                $nowIgnoreUserId = $ignoreUserIdArr[$loginDataCfpIdEntry->user_id];
                                if($nowIgnoreUserId->created_at> $loginDataCfpIdEntry->time)  
                                    continue;
                                else {
                                    $nowIgnoreUserId->delete();
                                    $ignoreUserIdArr[$loginDataCfpIdEntry->user_id] = null;
                                    unset($ignoreUserIdArr[$loginDataCfpIdEntry->user_id]);                                    
                                }                                
                            }                            

                            if(isset($ignoreUserIdCfpIdArr[$loginDataCfpIdEntry->user_id][$loginDataCfpIdEntry->cfp_id])) {
                                $nowIgnoreUserIdCfpId = $ignoreUserIdCfpIdArr[$loginDataCfpIdEntry->user_id][$loginDataCfpIdEntry->cfp_id];
                                if($nowIgnoreUserIdCfpId->created_at> $loginDataCfpIdEntry->time)  
                                    continue;
                                else {
                                    $nowIgnoreUserIdCfpId->delete();
                                }
                            }
                            
                            $this->loginDataByCfpId[$loginDataCfpIdEntry->cfp_id][$loginDataCfpIdEntry->user_id] = $loginDataCfpIdEntry;
                            //if(!in_array($loginDataCfpIdEntry->cfp_id,$this->_cpfidOfOverLimitUserId) && count($this->loginDataByCfpId[$loginDataCfpIdEntry->cfp_id]??[])>50) $this->_cpfidOfOverLimitUserId[]=$loginDataCfpIdEntry->cfp_id;
                            $this->loginDataByUserIdCfpId[$loginDataCfpIdEntry->user_id][$loginDataCfpIdEntry->cfp_id] = $loginDataCfpIdEntry;
                        }

                        foreach($this->_cpfidOfOverLimitUserId as $cv) {
                            $this->loginDataByCfpId[$cv] = null;
                            unset($this->loginDataByCfpId[$cv] );
                        }
                        
                        Log::info('findPuppet排程'.$cat.'：完成產生CfpId的Login資料');   
                        $this->column->insert( ['column_index'=>-1
                            ,'name'=>'完成產生CfpId的Login資料'
                            ,'group_index'=>-1
                            ,'cat'=>$cat
                            ,'type'=>''
                            ,'created_at'=>$edate
                            ,'updated_at'=>date('Y-m-d H:i:s')]);                         
                    }


                    if($only=='vid') {            
                        Log::info('findPuppet排程'.$cat.'：開始產生VisitorId的Login資料');
                        $this->column->insert( ['column_index'=>-1
                            ,'name'=>'開始產生VisitorId的Login資料'
                            ,'group_index'=>-1
                            ,'cat'=>$cat
                            ,'type'=>''
                            ,'created_at'=>$edate
                            ,'updated_at'=>date('Y-m-d H:i:s')]); 

                        $ignoreUserIdVidCollect = $this->ignore->whereNotNull('visitor_id')->where('visitor_id','<>','')->get();         
                        $ignoreUserIdVidArr = [];
                        foreach($ignoreUserIdVidCollect  as $userIdVidEntry) {
                            $ignoreUserIdVidArr[$userIdVidEntry->item][$userIdVidEntry->visitor_id] = $userIdVidEntry;
                        }                              

                        Log::info('findPuppet排程'.$cat.'：開始以visitor_id讀取登入紀錄');
                        $this->column->insert( ['column_index'=>-1
                            ,'name'=>'開始以visitor_id讀取登入紀錄'
                            ,'group_index'=>-1
                            ,'cat'=>$cat
                            ,'type'=>''
                            ,'created_at'=>$edate
                            ,'updated_at'=>date('Y-m-d H:i:s')]);

                            
                        $loginDataVidQuery = $model->has('user')->groupBy('visitor_id','user_id')
                                ->select('visitor_id','user_id')->selectRaw('MAX(`created_at`) AS time,COUNT(*) AS num,MIN(`created_at`) AS stime')->whereNotNull('visitor_id')->where('visitor_id','<>','');
                        
                        if($whereArr) $loginDataVidQuery->where($whereArr);
                        if($whereArrOfVid) $loginDataVidQuery->where($whereArrOfVid);                  
                        if($excludeUserId) $loginDataVidQuery=$loginDataVidQuery->whereNotIn('user_id',$excludeUserId);                            

                        $loginDataEntrys = $loginDataVidQuery->orderBy('time','desc')->get();
                        
                        Log::info('findPuppet排程'.$cat.'：完成以visitor_id讀取登入紀錄共'.$loginDataEntrys->count().'筆資料');
                        $this->column->insert( ['column_index'=>-1
                            ,'name'=>'完成以visitor_id讀取登入紀錄共'.$loginDataEntrys->count().'筆資料'
                            ,'group_index'=>-1
                            ,'cat'=>$cat
                            ,'type'=>''
                            ,'created_at'=>$edate
                            ,'updated_at'=>date('Y-m-d H:i:s')]);                        
                        
                        $this->loginDataByVid = [];
                        $this->loginDataByUserIdVid = [];
                        $vidOfOverLimitUserId = [];

                        foreach($loginDataEntrys  as $loginDataVidEntry) {
                            
                            if(isset($ignoreUserIdArr[$loginDataVidEntry->user_id])) {
                                $nowIgnoreUserId = $ignoreUserIdArr[$loginDataVidEntry->user_id];
                                if($nowIgnoreUserId->created_at> $loginDataVidEntry->time)  
                                    continue;
                                else {
                                    $nowIgnoreUserId->delete();
                                    $ignoreUserIdArr[$loginDataVidEntry->user_id] = null;
                                    unset($ignoreUserIdArr[$loginDataVidEntry->user_id]);                                    
                                }                                
                            }                            

                            if(isset($ignoreUserIdVidArr[$loginDataVidEntry->user_id][$loginDataVidEntry->visitor_id])) {
                                $nowIgnoreUserIdVid = $ignoreUserIdVidArr[$loginDataVidEntry->user_id][$loginDataVidEntry->visitor_id];
                                if($nowIgnoreUserIdVid->created_at> $loginDataVidEntry->time)  
                                    continue;
                                else {
                                    $nowIgnoreUserIdVid->delete();
                                }
                            }
                            
                            $this->loginDataByVid[$loginDataVidEntry->visitor_id][$loginDataVidEntry->user_id] = $loginDataVidEntry;
                            //if(!in_array($loginDataCfpIdEntry->cfp_id,$this->_cpfidOfOverLimitUserId) && count($this->loginDataByCfpId[$loginDataCfpIdEntry->cfp_id]??[])>50) $this->_cpfidOfOverLimitUserId[]=$loginDataCfpIdEntry->cfp_id;
                            $this->loginDataByUserIdVid[$loginDataVidEntry->user_id][$loginDataVidEntry->visitor_id] = $loginDataVidEntry;
                        }

                        foreach($this->_vidOfOverLimitUserId as $cv) {
                            $this->loginDataByVid[$cv] = null;
                            unset($this->loginDataByVid[$cv] );
                        }
                        
                        Log::info('findPuppet排程'.$cat.'：完成產生Vid的Login資料');   
                        $this->column->insert( ['column_index'=>-1
                            ,'name'=>'完成產生Vid的Login資料'
                            ,'group_index'=>-1
                            ,'cat'=>$cat
                            ,'type'=>''
                            ,'created_at'=>$edate
                            ,'updated_at'=>date('Y-m-d H:i:s')]);                         
                    }
                  

                                                           
                    $ignoreUserId = array_pluck($this->ignore
                                                ->where(function($q) {
                                                    $q->whereNull('ip')->orwhere('ip','');
                                                })
                                                ->where(function($q) {
                                                    $q->whereNull('cfp_id')->orwhere('cfp_id','');
                                                })                                                
                                                ->get()->toArray()
                                    ,'item');                                        


                    $puppetFromUsers = null;
                  
                    if($only=='vid') {
                        Log::info('findPuppet排程'.$cat.'：開始比對Vid');
                        $this->column->insert( ['column_index'=>-1
                            ,'name'=>'開始比對Vid'
                            ,'group_index'=>-1
                            ,'cat'=>$cat
                            ,'type'=>''
                            ,'created_at'=>$edate
                            ,'updated_at'=>date('Y-m-d H:i:s')]);                         
                        $vidPuppetFromUserQuery = $model->has('user')->groupBy('visitor_id')
                                ->select('visitor_id')->selectRaw('COUNT(DISTINCT `user_id`) AS num')
                                ->whereNotNull('visitor_id')->where('visitor_id','<>','')
                                ->orderByDesc('num');
                        
                        if($whereArr)  $vidPuppetFromUserQuery->where($whereArr);
                        if($whereArrOfVid) $vidPuppetFromUserQuery->where($whereArrOfVid);                        
                        if($excludeUserId) $vidPuppetFromUserQuery=$vidPuppetFromUserQuery->whereNotIn('user_id',$excludeUserId);                            
                        if($ignoreUserId) $vidPuppetFromUserQuery=$vidPuppetFromUserQuery->whereNotIn('user_id',$ignoreUserId);

                        $puppetFromUsers = $vidPuppetFromUserQuery->get();

                        foreach($puppetFromUsers as $vidPuppet) {
                            
                            if($vidPuppet->num<2 || $this->_isColumnChecked($vidPuppet->visitor_id))
                                continue;
                            else {
                                if($this->_findMultiUserIdFromIp($vidPuppet->visitor_id,'visitor_id')===true) continue;

                                $this->_groupIdx++;  
                            }
                        } 
                        Log::info('findPuppet排程'.$cat.'：完成比對Vid，組別達到'.$this->_groupIdx.'組');     
                        $this->column->insert( ['column_index'=>-1
                            ,'name'=>'完成比對CfpId，組別達到'.$this->_groupIdx.'組'
                            ,'group_index'=>-1
                            ,'cat'=>$cat
                            ,'type'=>''
                            ,'created_at'=>$edate
                            ,'updated_at'=>date('Y-m-d H:i:s')]);                          
                    }


                        

                    $puppetFromUsers = null;
                  
                    if(!$only || $only=='cfpid') {
                        Log::info('findPuppet排程'.$cat.'：開始比對CfpId');
                        $this->column->insert( ['column_index'=>-1
                            ,'name'=>'開始比對CfpId'
                            ,'group_index'=>-1
                            ,'cat'=>$cat
                            ,'type'=>''
                            ,'created_at'=>$edate
                            ,'updated_at'=>date('Y-m-d H:i:s')]);                         
                        $cfpidPuppetFromUserQuery = $model->has('user')->groupBy('cfp_id')
                                ->select('cfp_id')->selectRaw('COUNT(DISTINCT `user_id`) AS num')
                                ->whereNotNull('cfp_id')->where('cfp_id','<>','')
                                ->orderByDesc('num');
                        
                        if($whereArr)  $cfpidPuppetFromUserQuery->where($whereArr);
                        if($whereArrOfCfpId) $cfpidPuppetFromUserQuery->where($whereArrOfCfpId);                        
                        if($excludeUserId) $cfpidPuppetFromUserQuery=$cfpidPuppetFromUserQuery->whereNotIn('user_id',$excludeUserId);                            
                        if($ignoreUserId) $cfpidPuppetFromUserQuery=$cfpidPuppetFromUserQuery->whereNotIn('user_id',$ignoreUserId);

                        $puppetFromUsers = $cfpidPuppetFromUserQuery->get();

                        foreach($puppetFromUsers as $cfpidPuppet) {
                            
                            if($cfpidPuppet->num<2 || $this->_isColumnChecked($cfpidPuppet->cfp_id))
                                continue;
                            else {
                                if($this->_findMultiUserIdFromIp($cfpidPuppet->cfp_id,'cfp_id')===true) continue;

                                $this->_groupIdx++;  
                            }
                        } 
                        Log::info('findPuppet排程'.$cat.'：完成比對CfpId，組別達到'.$this->_groupIdx.'組');     
                        $this->column->insert( ['column_index'=>-1
                            ,'name'=>'完成比對CfpId，組別達到'.$this->_groupIdx.'組'
                            ,'group_index'=>-1
                            ,'cat'=>$cat
                            ,'type'=>''
                            ,'created_at'=>$edate
                            ,'updated_at'=>date('Y-m-d H:i:s')]);                          
                    }



                        
                    $puppetFromUsers = null;
                    
                    if(!$only || $only=='ip') {
                        Log::info('findPuppet排程'.$cat.'：開始比對IP'); 
                        $this->column->insert( ['column_index'=>-1
                            ,'name'=>'開始比對IP'
                            ,'group_index'=>-1
                            ,'cat'=>$cat
                            ,'type'=>''
                            ,'created_at'=>$edate
                            ,'updated_at'=>date('Y-m-d H:i:s')]);                         
                        $ipPuppetFromUserQuery = $model->has('user')->groupBy('ip')
                                ->select('ip')->selectRaw('COUNT(DISTINCT `user_id`) AS num')
                                ->whereNotNull('ip')->where('ip','<>','')
                                ->where('ip','NOT LIKE','162.158.%')
                                ->where('ip','NOT LIKE','172.70.%')
                                ->where('ip','NOT LIKE','172.69.%')
                                ->where('ip','NOT LIKE','172.68.%')
                                ->where('ip','NOT LIKE','108.162.%')
                                ->where('ip','NOT LIKE','103.22.201.%')                                
                                ->orderByDesc('num');
                                
                        if($whereArr)  $ipPuppetFromUserQuery->where($whereArr);
                        if($whereArrOfIp) $ipPuppetFromUserQuery->where($whereArrOfIp);        
                        if($excludeUserId) $ipPuppetFromUserQuery=$ipPuppetFromUserQuery->whereNotIn('user_id',$excludeUserId);                            
                        if($ignoreUserId) $ipPuppetFromUserQuery=$ipPuppetFromUserQuery->whereNotIn('user_id',$ignoreUserId);

                        $puppetFromUsers = $ipPuppetFromUserQuery->get();

                        foreach($puppetFromUsers as $ipPuppet) {

                            if($ipPuppet->num<2 || $this->_isColumnChecked($ipPuppet->ip))
                                continue;
                            else {
                                if($this->_findMultiUserIdFromIp($ipPuppet->ip)===true) continue;

                                $this->_groupIdx++;
                            }
                        }
                         Log::info('findPuppet排程'.$cat.'：完成比對IP，組別達到'.$this->_groupIdx.'組');   
                        $this->column->insert( ['column_index'=>-1
                            ,'name'=>'完成比對IP，組別達到'.$this->_groupIdx.'組'
                            ,'group_index'=>-1
                            ,'cat'=>$cat
                            ,'type'=>''
                            ,'created_at'=>$edate
                            ,'updated_at'=>date('Y-m-d H:i:s')]);                          
                    }
                    Log::info('findPuppet排程'.$cat.'：開始清空col、row、cell的舊資料');
                    $this->column->insert( ['column_index'=>-1
                        ,'name'=>'開始清空col、row、cell的舊資料'
                        ,'group_index'=>-1
                        ,'cat'=>$cat
                        ,'type'=>''
                        ,'created_at'=>$edate
                        ,'updated_at'=>date('Y-m-d H:i:s')]); 
                    $this->column->where('cat',$cat)->where('group_index','>',-1)->delete();
                    $this->row->where('cat',$cat)->where('group_index','>',-1)->delete();
                    $this->cell->where('cat',$cat)->where('group_index','>',-1)->delete(); 
                    Log::info('findPuppet排程'.$cat.'：完成清空col、row、cell的舊資料');
                    $this->column->insert( ['column_index'=>-1
                        ,'name'=>'完成清空col、row、cell的舊資料'
                        ,'group_index'=>-1
                        ,'cat'=>$cat
                        ,'type'=>''
                        ,'created_at'=>$edate
                        ,'updated_at'=>date('Y-m-d H:i:s')]);                       
                    $add_num=0;
                    $creatingArr = null;
                    Log::info('findPuppet排程'.$cat.'：開始寫入資料庫'); 
                    $this->column->insert( ['column_index'=>-1
                        ,'name'=>'開始寫入資料庫'
                        ,'group_index'=>-1
                        ,'cat'=>$cat
                        ,'type'=>''
                        ,'created_at'=>$edate
                        ,'updated_at'=>date('Y-m-d H:i:s')]);                     
                    Log::info('findPuppet排程'.$cat.'：開始寫入col資料');
                    $this->column->insert( ['column_index'=>-1
                        ,'name'=>'開始寫入col資料'
                        ,'group_index'=>-1
                        ,'cat'=>$cat
                        ,'type'=>''
                        ,'created_at'=>$edate
                        ,'updated_at'=>date('Y-m-d H:i:s')]);                      
                    foreach($this->_columnIp  as $groupIdx=>$colSet) {
                        foreach($colSet  as $nowColumnIdx=>$colElt) {
                            
                            $nowAddData = ['column_index'=>$nowColumnIdx,'name'=>$colElt
                                    ,'group_index'=>$groupIdx
                                    ,'type'=>$this->_columnType[$groupIdx][$nowColumnIdx]
                                    ,'created_at'=>$edate
                                    ,'updated_at'=>date('Y-m-d H:i:s')];
                                    
                            if($have_mon_limit) $nowAddData['mon']=$mon;
							if($cat) $nowAddData['cat'] = $cat;
                            
                            $creatingArr[] = $nowAddData;
                        
                            $nowAddData = null;
                                    
                            $add_num++;
                           if($add_num>999) {
               
                                $this->column->insert($creatingArr); 
                                $creatingArr = null;
                                $add_num = 0;
                            }                                     
                        }
                        
                    }
                     
                    if($creatingArr)
                    $this->column->insert($creatingArr);
                    Log::info('findPuppet排程'.$cat.'：完成寫入col資料');
                    $this->column->insert( ['column_index'=>-1
                        ,'name'=>'完成寫入col資料'
                        ,'group_index'=>-1
                        ,'cat'=>$cat
                        ,'type'=>''
                        ,'created_at'=>$edate
                        ,'updated_at'=>date('Y-m-d H:i:s')]);                     
                    $creatingArr = null;
                    $add_num = 0;
                    Log::info('findPuppet排程'.$cat.'：開始寫入row資料'); 
                    $this->column->insert( ['column_index'=>-1
                        ,'name'=>'開始寫入row資料'
                        ,'group_index'=>-1
                        ,'cat'=>$cat
                        ,'type'=>''
                        ,'created_at'=>$edate
                        ,'updated_at'=>date('Y-m-d H:i:s')]);                     
                    foreach($this->_rowUserId  as $groupIdx=>$rowSet) {
                        foreach($rowSet  as $nowRowIdx=>$rowElt) {
                            
                            $nowAddData = ['row_index'=>$nowRowIdx
                                ,'name'=>$rowElt,'group_index'=>$groupIdx
                                ,'created_at'=>$edate
                                ,'updated_at'=>date('Y-m-d H:i:s')];
                                    
                            if($have_mon_limit) $nowAddData['mon']=$mon;
                            if($cat) $nowAddData['cat'] = $cat;
                            $creatingArr[] = $nowAddData;
                            $nowAddData = null;
                            $add_num++;
                            if($add_num>999) {
                                $this->row->insert($creatingArr); 
                                $creatingArr = null;
                                $add_num = 0;
                            }                         
                        }
                        
                    }
                    if($creatingArr)
                    $this->row->insert($creatingArr);   
                    Log::info('findPuppet排程'.$cat.'：完成寫入row資料');
                    $this->column->insert( ['column_index'=>-1
                        ,'name'=>'完成寫入row資料'
                        ,'group_index'=>-1
                        ,'cat'=>$cat
                        ,'type'=>''
                        ,'created_at'=>$edate
                        ,'updated_at'=>date('Y-m-d H:i:s')]);                     
                    $creatingArr = null;
                    
                    $add_num = 0;
                    Log::info('findPuppet排程'.$cat.'：開始寫入cell資料'); 
                    $this->column->insert( ['column_index'=>-1
                        ,'name'=>'開始寫入cell資料'
                        ,'group_index'=>-1
                        ,'cat'=>$cat
                        ,'type'=>''
                        ,'created_at'=>$edate
                        ,'updated_at'=>date('Y-m-d H:i:s')]);                      
                    foreach($this->_cellVal  as $groupIdx=>$rowSet) {
                        foreach($rowSet  as $nowRowIdx=>$colSet) {
                            foreach($colSet  as $nowColumnIdx=>$cnt) {
                                
                                $nowAddData = ['column_index'=>$nowColumnIdx
                                    ,'row_index'=>$nowRowIdx
                                    ,'time'=>$cnt->time
                                    ,'num'=>$cnt->num
                                    ,'group_index'=>$groupIdx,'created_at'=>$edate
                                    ,'updated_at'=>date('Y-m-d H:i:s')];
                                        
                                if($have_mon_limit) $nowAddData['mon']=$mon;                                
                                if($cat) $nowAddData['cat'] = $cat;
                                $creatingArr[] = $nowAddData;
                                
                                $nowAddData = null;
                                
                                $add_num++;
                                
                               if($add_num>999) {
                   
                                    $this->cell->insert($creatingArr); 
                                    $creatingArr = [];
                                    $add_num = 0;
                                }                            
                            }
                        }
                        
                    }
                    if($creatingArr)
                        $this->cell->insert($creatingArr);  
                    Log::info('findPuppet排程'.$cat.'：完成寫入cell資料');
                    $this->column->insert( ['column_index'=>-1
                        ,'name'=>'完成寫入cell資料'
                        ,'group_index'=>-1
                        ,'cat'=>$cat
                        ,'type'=>''
                        ,'created_at'=>$edate
                        ,'updated_at'=>date('Y-m-d H:i:s')]);                     
                    Log::info('findPuppet排程'.$cat.'：完成寫入資料庫');
                    $this->column->insert( ['column_index'=>-1
                        ,'name'=>'完成寫入資料庫'
                        ,'group_index'=>-1
                        ,'cat'=>$cat
                        ,'type'=>''
                        ,'created_at'=>$edate
                        ,'updated_at'=>date('Y-m-d H:i:s')]);                     
                    Log::info('findPuppet排程'.$cat.'：執行完畢'); 
                    $this->column->insert( ['column_index'=>-1
                        ,'name'=>'執行完畢'
                        ,'group_index'=>-1
                        ,'cat'=>$cat
                        ,'type'=>''
                        ,'created_at'=>$edate
                        ,'updated_at'=>date('Y-m-d H:i:s')]); 
                    $this->column->where('cat',$cat)->where('group_index',-1)->delete();
                    $this->row->where('cat',$cat)->where('group_index',-1)->delete();
                    $this->cell->where('cat',$cat)->where('group_index',-1)->delete();                        
                }
                else {
                    Log::info('findPuppet排程'.$cat.'：由程式檢查出參數錯誤而中止執行(尚未開始讀寫資料庫)'.json_encode($error_msg??null));
                    $this->column->insert( ['column_index'=>-1
                        ,'name'=>'由程式檢查出參數錯誤而中止執行(尚未開始讀寫資料庫)'.json_encode($error_msg??null)
                        ,'group_index'=>-1
                        ,'cat'=>$cat
                        ,'type'=>'error'
                        ,'created_at'=>$edate
                        ,'updated_at'=>date('Y-m-d H:i:s')]);                     
                }

        } catch (Exception $e) {
            echo $e->getMessage();
            Log::info('findPuppet排程'.$cat.'：出現Exception錯誤而中止執行 '.json_encode($e??null));
                    $this->column->insert( ['column_index'=>-1
                        ,'name'=>'出現Exception錯誤而中止執行 '.json_encode($e??null)
                        ,'group_index'=>-1
                        ,'cat'=>$cat
                        ,'type'=>'error'
                        ,'created_at'=>$edate
                        ,'updated_at'=>date('Y-m-d H:i:s')]);            
            exit;
        }
        
        echo '1';exit;

    }
    
    private function _findMultiUserIdFromIp($check_val,$type='ip') 
    {
        $groupIdx = $this->_groupIdx;
        
        switch($type) {
            case 'ip':
            default:
                $loginData = $this->loginDataByIp;
            break;
            case 'cfp_id':
                $loginData = $this->loginDataByCfpId;
            break;
            case 'visitor_id':
                $loginData = $this->loginDataByVid;
            break;            
        }
        

        $model = $this->model;
        
        if(isset($loginData[$check_val]))
            $multiUserIds = $loginData[$check_val];
        else return true;

        if(isset($this->_rowUserId[$groupIdx]))
            $this->_rowUserId[$groupIdx] = array_merge_recursive($this->_rowUserId[$groupIdx],array_diff(array_keys($multiUserIds),$this->_rowUserId[$groupIdx]));
        else $this->_rowUserId[$groupIdx] =  array_keys($multiUserIds);

        if(isset($this->_columnIp[$groupIdx]) && array_search($check_val,$this->_columnIp[$groupIdx])!==false) {
            $nowColumnIdx = array_search($check_val,$this->_columnIp[$groupIdx]);
            $this->_columnType[$groupIdx][$nowColumnIdx] = $type;
        }    
        else {
            
            if(isset($this->_columnIp[$groupIdx])) return true;
            else {        
                $this->_columnIp[$groupIdx][] = $check_val;
                $this->_columnType[$groupIdx][] = $type;
                $nowColumnIdx = count($this->_columnIp[$groupIdx])-1;
            }            
        }        

        $creatingArr = null;    
        $findedIp = null;
                
        foreach($multiUserIds  as $multiUserId) {
             if(isset($this->_rowUserId[$groupIdx]) && array_search($multiUserId->user_id,$this->_rowUserId[$groupIdx])!==false) {
                 $nowRowIdx = array_search($multiUserId->user_id,$this->_rowUserId[$groupIdx]);
              }
              else {
                $this->_rowUserId[$groupIdx][] = $multiUserId->user_id;
                $nowRowIdx = count($this->_rowUserId[$groupIdx])-1; 

              }

            if(!isset($this->_cellVal[$groupIdx][$nowRowIdx][$nowColumnIdx])) {

                $cnt = $loginData[$check_val][$multiUserId->user_id];
                $this->_cellVal[$groupIdx][$nowRowIdx][$nowColumnIdx] = $cnt;

            }
            else continue;

            if(isset($this->loginDataByUserId[$multiUserId->user_id]))
                $multiIps = $this->loginDataByUserId[$multiUserId->user_id];
            else $multiIps = null;
            
            $new_check_column = null;

            if($multiIps) {
                if(isset($this->_columnIp[$groupIdx]) && $this->_columnIp[$groupIdx]) {
                    $new_check_column = array_diff(array_keys($multiIps),$this->_columnIp[$groupIdx]);

                }
                else {$new_check_column = array_keys($multiIps);}
            }
            
            if(isset($new_check_column) && $new_check_column)
                foreach($new_check_column as $new_check_value) {
                        if($this->_havePuppetUserId($new_check_value,$this->loginDataByIp)) 
                            $this->_columnIp[$groupIdx][] = $new_check_value;
                }
                
            if(isset($multiIps) && $multiIps)           
                 foreach($multiIps  as $multiIp) {
                     if(!$multiIp) continue;
                     if($this->_findMultiUserIdFromIp($multiIp->ip)===true) continue;
                 }
            
            $multiCfpIds = isset($this->loginDataByUserIdCfpId[$multiUserId->user_id])?$this->loginDataByUserIdCfpId[$multiUserId->user_id]:[];

            $new_check_column = null;
            $multiCfpIds_keys = array_keys($multiCfpIds);
            if($this->_cpfidOfOverLimitUserId) {
                $multiCfpIds_keys = array_diff($multiCfpIds_keys,$this->_cpfidOfOverLimitUserId);
            }  

            if(isset($this->_columnIp[$groupIdx])) {        
                $new_check_column = array_diff($multiCfpIds_keys,$this->_columnIp[$groupIdx]);
            }
            else {$new_check_column = $multiCfpIds_keys;}

            if(isset($new_check_column) && $new_check_column)
                foreach($new_check_column as $new_check_value) {
                        if($this->_havePuppetUserId($new_check_value,$this->loginDataByCfpId)) 
                            $this->_columnIp[$groupIdx][] = $new_check_value;
                }             
               
            if(isset($multiCfpIds_keys) && $multiCfpIds_keys)                 
                foreach($multiCfpIds_keys  as $multiCfpId) {
                     if(!$multiCfpId) continue;
                     if($this->_findMultiUserIdFromIp($multiCfpId,'cfp_id')===true) continue;
                } 

            //比對visitor id
            $multiVids = isset($this->loginDataByUserIdVid[$multiUserId->user_id])?$this->loginDataByUserIdVid[$multiUserId->user_id]:[];

            $new_check_column = null;
            $multiVids_keys = array_keys($multiVids);
            if($this->_vidOfOverLimitUserId) {
                $multiVids_keys = array_diff($multiVids_keys,$this->_vidOfOverLimitUserId);
            }  

            if(isset($this->_columnIp[$groupIdx])) {        
                $new_check_column = array_diff($multiVids_keys,$this->_columnIp[$groupIdx]);
            }
            else {$new_check_column = $multiVids_keys;}

            if(isset($new_check_column) && $new_check_column)
                foreach($new_check_column as $new_check_value) {
                        if($this->_havePuppetUserId($new_check_value,$this->loginDataByVid)) 
                            $this->_columnIp[$groupIdx][] = $new_check_value;
                }             
               
            if(isset($multiVids_keys) && $multiVids_keys)                 
                foreach($multiVids_keys  as $multiVid) {
                     if(!$multiVid) continue;
                     if($this->_findMultiUserIdFromIp($multiVid,'visitor_id')===true) continue;
                }                   
             
        }   
    }
    
    public function display(Request $request) 
    {
        if($request->ajax()) exit;

        ini_set("max_execution_time",'0');
        ini_set('memory_limit','-1');
        ini_set("request_terminate_timeout",'10000');
        ini_set("process_idle_timeout",'10000');
        ini_set("pm.request_terminate_timeout",'10000');
        ini_set("pm.process_idle_timeout",'10000');
        ini_set("max_children",'100');
        ini_set("pm.max_children",'100');
        ini_set('default_socket_timeout',-1);
        set_time_limit(0);
        error_reporting(0);            
        
        $have_mon_limit = false;
        
        if(isset($this->monarr) && count($this->monarr)>0) {
            $monarr = $this->monarr;
            $have_mon_limit = true;
        }
		
	
        
        $whereArr = [];
		$selectArr = [];

        if($request->clear) {
                $this->column->truncate();
                $this->row->truncate();
                $this->cell->truncate();    
                return redirect()->to($request->path());

        }
        
        $data = [];
        $data['urlPathInfo'] = $request->getPathInfo();
        $data['changeMonBaseQuery'] = http_build_query(request()->except(['mon']));
        $data['monarr'] = isset($this->monarr)?$this->monarr:[];
        $data['request'] = $request->all;
        $data['colOverload']= $data['rowOverload'] = 199;
        $data['colLimit']= $data['rowLimit'] = 99;
        
        if($have_mon_limit) {
            $mon = $request->mon;    
            if(!$mon) $have_mon_limit = false;
        }
        $g = $request->g;
        $show = $request->show;
        $start = $request->start;
        $only= $request->only;
		$cat = $only?'only_'.$only:'';			
        $groupOrderArr = [];
        $rowLastLoginArr = [];
        $rowLatestLastLoginArr = [];
        $group_segment = [];
        
        if($have_mon_limit && (!isset($mon) || !$mon) && $show!='text') {

        }
        else {
            $whereArr[] = ['cat',$cat];
            $whereArr[] = ['group_index','>',-1]; 
            if($have_mon_limit) $whereArr[] = ['mon',$mon];
                
            if($show!='text') {

                $groupInfo = [];

                $edate_colentry_of_ip = $this->column->selectRaw('max(created_at) as end_date')->selectRaw('max(updated_at) as end_cron_date')->where($whereArr)->first();
                $data['end_date'] = $edate_colentry_of_ip->end_date??null;
                $data['end_cron_date'] = $edate_colentry_of_ip->end_cron_date??null;
                $data['sdateOfIp'] = $data['sdateOfCfpId']  = null;
                if($this->defaultSdateOfIp)  {
                    $sdate_from_model = $this->model->whereNotNull('ip')->min('created_at');

                    if($this->defaultSdateOfIp>=$sdate_from_model) {
                        $data['sdateOfIp'] = $this->defaultSdateOfIp;
                    }
                    else {
                        
                        $data['sdateOfIp'] = $sdate_from_model;
                    }
                }
                
                if($this->defaultSdateOfCfpId)  {
                    $sdate_from_model = null;
                    $sdate_from_model = $this->model->whereNotNull('cfp_id')->min('created_at');

                    if($this->defaultSdateOfCfpId>=$sdate_from_model) {
                        $data['sdateOfCfpId'] = $this->defaultSdateOfCfpId;
                    }
                    else {
                        
                        $data['sdateOfCfpId'] = $sdate_from_model;
                    }
                }  

                if($this->defaultSdateOfVid)  {
                    $sdate_from_model = null;
                    $sdate_from_model = $this->model->whereNotNull('visitor_id')->min('created_at');

                    if($this->defaultSdateOfVid>=$sdate_from_model) {
                        $data['sdateOfVid'] = $this->defaultSdateOfVid;
                    }
                    else {
                        
                        $data['sdateOfVid'] = $sdate_from_model;
                    }
                }  


				$groupChecksQuery = $this->cell->select('group_index');
				
				$groupChecksQuery->where($whereArr);

                $groupChecksQuery = $groupChecksQuery->groupBy('group_index')
                    ->selectRaw('MAX(column_index) AS maxColIdx,MAX(row_index) AS maxRowIdx,MAX(time) AS last_time')
                    ->orderByDesc('last_time')
                    ;		

				
                $groupChecks = $groupChecksQuery->get();
                foreach($groupChecks as $idx=>$groupCheck) {
                    $groupInfo[$groupCheck->group_index] = $groupCheck->toArray();
                    $groupInfo[$groupCheck->group_index]['cutData'] = false;
                    if($groupCheck->maxColIdx>$data['colOverload'] && $groupCheck->maxRowIdx>$data['rowOverload']) {
                        $groupInfo[$groupCheck->group_index]['cutData'] = true;
                    }
                }
            }
            else {
                $text=null;
            }
            
            
            $rowQuery = $this->row->where($whereArr);
            if($show=='text') {
                 if($g) $rowQuery->where('group_index',$g);
            }  
            else {
                $rowQuery->orderBy('group_index');
            }
            $rowEntrys = $rowQuery->get();
            $max_email_len = 0;

            foreach($rowEntrys as $rowEntry) {
                if($show=='text') {
                    $this->_rowUserId[$rowEntry->group_index][$rowEntry->row_index]  = $rowEntry->name;
                }
                else {
                    $now_group = $rowEntry->group_index;
                    
                    if(isset($last_group)) {
                        if($now_group!=$last_group) {
                            $last_group_row_count = count($this->_rowUserId[$last_group]??[]);
                            $last_group_adminclosed_count = $now_group_adminclosed_count;
                            $last_group_banned_count = $now_group_banned_count;
                            $last_group_implicitlyBanned_count = $now_group_implicitlyBanned_count;
                            if(($last_group_row_count-$last_group_banned_count-$last_group_implicitlyBanned_count)<=0
                                || ($last_group_row_count-$last_group_adminclosed_count)<=1
                            ) 
                            {
                                $gp_seg_idx =1;
                                
                            }
                            else $gp_seg_idx =0;

                            $groupLastLoginValus = array_values($rowLastLoginArr[$last_group]);
                            $rowLatestLastLoginArr[$gp_seg_idx][$last_group] = $groupLastLoginValus[0];                         
                            
                            $now_group_adminclosed_count 
                            = $now_group_banned_count 
                            = $now_group_implicitlyBanned_count     
                            = $last_group_adminclosed_count 
                            = $last_group_banned_count 
                            = $last_group_implicitlyBanned_count 
							= $gp_seg_idx
                            = 0;
                        } else $gp_seg_idx =0;
                    }
                    else {
                        $now_group_adminclosed_count 
                        = $now_group_banned_count 
                        = $now_group_implicitlyBanned_count 
                        = $last_group_row_count 
                        = $last_group_adminclosed_count 
                        = $last_group_banned_count 
                        = $last_group_implicitlyBanned_count  
						= $gp_seg_idx						
                        = 0;
                    }

                    if(isset($groupInfo[$rowEntry->group_index]['cutData']) && $groupInfo[$rowEntry->group_index]['cutData'] && $rowEntry->row_index>$data['rowLimit']) continue;
                    $cur_user = User::with('vip','aw_relation', 'banned', 'implicitlyBanned')->find($rowEntry->name)??new User;
                    $cur_user->tag_class = '';
                    if($cur_user->id==null) $cur_user->id = $rowEntry->name;

                    $cur_user->ignoreEntry = $this->ignore->where('item',(string)$cur_user->id)
                        ->where(function($q){
                            $q->whereNull('ip')->orwhere('ip','');
                        })
                        ->where(function($q){
                            $q->whereNull('cfp_id')->orwhere('cfp_id','');
                        }) 
                        ->where(function($q){
                            $q->whereNull('visitor_id')->orwhere('visitor_id','');
                        })                          
                        ->first();

                    if($cur_user->banned)  {
                        $cur_user->tag_class.= 'banned ';
                        $now_group_banned_count++;
                    }
                    if($cur_user->implicitlyBanned) {
                        $cur_user->tag_class.= 'implicitlyBanned ';
                        $now_group_implicitlyBanned_count++;
                    } 
                    if((isset($cur_user->user_meta->isWarned) && $cur_user->user_meta->isWarned) || $cur_user->aw_relation)  $cur_user->tag_class.= 'isWarned ';
                    if($cur_user->accountStatus===0) $cur_user->tag_class.= 'isClosed ';
                    if($cur_user->account_status_admin===0) {
                        $cur_user->tag_class.= 'isClosedByAdmin ';
                        $now_group_adminclosed_count++;
                    }

                    $cur_user->register_days = UserService::getRegisterDaysByUser($cur_user);
                    if($cur_user->register_days>99) $cur_user->register_days=99;

                    if(isset($cur_user->email) && strlen($cur_user->email)>$max_email_len) $max_email_len = strlen($cur_user->email);
                    $this->_rowUserId[$rowEntry->group_index][$rowEntry->row_index] = $cur_user;
                    $rowLastLoginArr[$rowEntry->group_index][$rowEntry->row_index] = $cur_user->last_login;
                    arsort($rowLastLoginArr[$rowEntry->group_index]);
                    $cur_user = null;
                    $last_group = $now_group;
                }
            }  
            
            if(isset($last_group)) {
				$last_group_row_count = count($this->_rowUserId[$last_group]??[]);
				$last_group_adminclosed_count = $now_group_adminclosed_count;
				$last_group_banned_count = $now_group_banned_count;
				$last_group_implicitlyBanned_count = $now_group_implicitlyBanned_count;
				if(($last_group_row_count-$last_group_banned_count-$last_group_implicitlyBanned_count)<=0
					|| ($last_group_row_count-$last_group_adminclosed_count)<=1
				) 
				{
					$gp_seg_idx =1;
					
				}
				else $gp_seg_idx =0;

				
				$group_segment[$gp_seg_idx][] = $last_group;
				$groupLastLoginValus = array_values($rowLastLoginArr[$last_group]);
				$rowLatestLastLoginArr[$gp_seg_idx][$last_group] = $groupLastLoginValus[0];             
            }
            
            
            if($max_email_len<30) $max_email_len=30;
            $data['max_email_len'] = $max_email_len;            
            
            

            $colQuery = $this->column->where($whereArr);
            if($show=='text') {
                 if($g) $colQuery->where('group_index',$g);
            }
            
            $colEntrys = $colQuery->get();
            $colIdxOfIp = [];
            $colIdxOfCfpId = [];
            $colIdxOfVid = [];
            foreach($colEntrys as $colEntry) {
                if(isset($groupInfo[$colEntry->group_index]['cutData']) && $groupInfo[$colEntry->group_index]['cutData'] && $colEntry->column_index>$data['colLimit']) continue;
                
               $this->_columnIp[$colEntry->group_index][$colEntry->column_index] = $colEntry->name;
               $this->_columnType[$colEntry->group_index][$colEntry->column_index] = $colEntry->type;
                
                if($colEntry->type=='ip') {
                    $colIdxOfIp[$colEntry->group_index][] = $colEntry->column_index;
                }
                else if($colEntry->type=='cfp_id'){
                    $colIdxOfCfpId[$colEntry->group_index][] = $colEntry->column_index;
                }
                else if($colEntry->type=='visitor_id'){
                    $colIdxOfVid[$colEntry->group_index][] = $colEntry->column_index;
                }                
            }
            

            
            $cellQuery = $this->cell->where($whereArr);
            if($show=='text') {
                 if($g) $cellQuery->where('group_index',$g);
            }  

            $cellEntrys = $cellQuery->get();
            foreach($cellEntrys as $cellEntry) {
                if(isset($groupInfo[$cellEntry->group_index]['cutData']) && $groupInfo[$cellEntry->group_index]['cutData'] && ($cellEntry->row_index>$data['rowLimit'] || $cellEntry->column_index>$data['colLimit'])) continue;
                $cellEntry->ignoreEntry = $this->ignore->where('item',$this->_rowUserId[$cellEntry->group_index][$cellEntry->row_index]->id)
                        ->where($this->_columnType[$cellEntry->group_index][$cellEntry->column_index],(string)$this->_columnIp[$cellEntry->group_index][$cellEntry->column_index])->first();               
               $this->_cellVal[$cellEntry->group_index][$cellEntry->row_index][$cellEntry->column_index] = $cellEntry;
            }  
            
            if($show!='text') {
                $data['groupInfo'] = $groupInfo;
            }
            else {
                
                switch($start) {
                    case 'ipcfpid':
                    default:
                        $arr1 = $this->_columnIp;
                        $arr2 = $this->_rowUserId;
                        $colIdx = 'i';
                        $rowIdx = 'j';
                        $len1 = 15;
                        $len2 = 7;
                        $queryName2 = 'user_id';
                    break;
                    case 'userid':
                        $arr2 = $this->_columnIp;
                        $arr1 = $this->_rowUserId;    
                        $colIdx = 'j';
                        $rowIdx = 'i';
                        $len2 = 15;
                        $len1 = 7;    
                        $queryName1 = 'user_id';                        
                    break;
                }
                $last_i = -1;
                for($i=0;$i<count($arr1[$g]??[]);$i++) {
                    for($j=0;$j<count($arr2[$g]);$j++) {
                        if(!isset($this->_cellVal[$g][$$rowIdx][$$colIdx])) continue;
                        if(!isset($queryName1)) $queryName1 = $this->_columnType[$g][$$colIdx];
                        if(!isset($queryName2)) $queryName2 = $this->_columnType[$g][$$colIdx];
                        if($i && $i!=$last_i) $text.='<tr><td colspan="4">&nbsp;</td></tr>'."\n\r";
                    
                        $text.="<tr><td><a target=\"_blank\" href=\"showLog?".$queryName1.'='.$arr1[$g][$i].(isset($mon)?'&mon='.$mon:'')."\">".$arr1[$g][$i]."</a></td>";

                        $text.="<td><a target=\"_blank\" href=\"showLog?".$queryName2.'='.$arr2[$g][$j].(isset($mon)?'&mon='.$mon:'')."\">".$arr2[$g][$j]."</a></td>\n\r";
                        
                        $text.='<td>'.$this->_cellVal[$g][$$rowIdx][$$colIdx]->time
                        .'</td><td><a target="_blank" href="showLog?'.$queryName1.'='.$arr1[$g][$i].'&'.$queryName2.'='.$arr2[$g][$j].(isset($mon)?'&mon='.$mon:'').'">'.$this->_cellVal[$g][$$rowIdx][$$colIdx]->num."</a></td></tr>\n\r";

                        $last_i = $i;
                    }                        
                }
                
                $colnames = ['ip或cfp_id或....','user_id','last_login_time','login_count'];

                $text = '<table>'.$this->_getSimpleTableHead($colnames).$text.'</table>';
                $text = $this->_getSimpleStyle().$text;
                
                echo $text;exit;
            }
            
        }
    foreach($rowLatestLastLoginArr  as $rlll_idx=>$rlll_arr) {
        arsort($rowLatestLastLoginArr[$rlll_idx]); 
        $groupOrderArr[$rlll_idx] = array_keys($rowLatestLastLoginArr[$rlll_idx]);
    } 
    
    $new_exec_log = $this->column->where('cat',$cat)->where('group_index',-1)->orderBy('updated_at','DESC')->orderBy('id','DESC')->get();
    //arsort($rowLatestLastLoginArr);  
    //$groupOrderArr = array_keys($rowLatestLastLoginArr);
	ksort($groupOrderArr);
    $data['groupOrderArr'] = $groupOrderArr;
    $data['rowLastLoginArr'] = $rowLastLoginArr;
    $data['colIdxOfCfpId'] = $colIdxOfCfpId;
    $data['colIdxOfIp'] = $colIdxOfIp;  
    $data['colIdxOfVid'] = $colIdxOfVid;  
    $data['new_exec_log'] = $new_exec_log;  
    //$data['group_segment'] = $group_segment;

    return view('findpuppet',$data)
            ->with('columnSet', $this->_columnIp)
            ->with('columnTypeSet',$this->_columnType)
            ->with('rowSet', $this->_rowUserId)
            ->with('cellValue', $this->_cellVal)
            ->with('error_msg',[])->with('data',$data);        
        
    }  
    
    public function displayDetail(Request $request) {
        $mon = $request->mon;
        $user_id = $request->user_id;
        $ip = $request->ip;
        $cfp_id = $request->cfp_id;
        $visitor_id = $request->visitor_id;
        $time = $request->logtime;
        $query = $this->model;
        $html = null;
        $html = $this->_getSimpleStyle();
        $title='';
        $showLogQuery = [];
        
        $colnames = ['user_id','ip','cfp_id','visitor_id','created_at','userAgent'];
        
        if($time) {
            $usuery = $this->model->where('created_at','<',$time)->take(10)->orderByDesc('created_at');
            $duery = $this->model->where('created_at','>',$time)->take(10)->orderBy('created_at');
            
            
        }    
        else {
            if($user_id) {
                $title.='UserId ：<a target="_blank" href="showLog?user_id='.$user_id.($mon?'&mon='.$mon:'').'">'.$user_id.'</a>';
                $query = $query->where('user_id',$user_id);
                $killColNameIdx =  array_search('user_id',$colnames);
                $colnames[$killColNameIdx] = null;
                unset($colnames[$killColNameIdx]);
            }
            
            if($ip) {
                if($title) $title.='、';
                $title.='IP ：<a target="_blank" href="showLog?ip='.$ip.($mon?'&mon='.$mon:'').'">'.$ip.'</a>';
                $query = $query->where('ip',$ip);
                $killColNameIdx =  array_search('ip',$colnames);
                $colnames[$killColNameIdx] = null;
                unset($colnames[$killColNameIdx]);                
            }
            
            if($cfp_id) {
                if($title) $title.='、';
                $title.='cfp_id ：<a target="_blank" href="showLog?cfp_id='.$cfp_id.($mon?'&mon='.$mon:'').'">'.$cfp_id.'</a>';    
                $query = $query->where('cfp_id',$cfp_id);
                $killColNameIdx =  array_search('cfp_id',$colnames);
                $colnames[$killColNameIdx] = null;
                unset($colnames[$killColNameIdx]);                
            } 

            if($visitor_id) {
                if($title) $title.='、';
                $title.='visitor_id ：<a target="_blank" href="showLog?visitor_id='.$visitor_id.($mon?'&mon='.$mon:'').'">'.$visitor_id.'</a>';    
                $query = $query->where('visitor_id',$visitor_id);
                $killColNameIdx =  array_search('visitor_id',$colnames);
                $colnames[$killColNameIdx] = null;
                unset($colnames[$killColNameIdx]);                
            } 
            

            if($mon) {
                if($showLogQuery) $showLogQuery.='&';
                $query = $query->where('created_at','LIKE','%-'.$mon.'-%');
            }    
            $logEntrys = $query->orderByDesc('created_at')->get();
            
            foreach($logEntrys as $logEntry) {
                $html.='<tr>';
                foreach($colnames  as $colname) {
                    if($colname!='created_at' && $colname!='userAgent') {
                        $link_start = '<a target="_blank" href="'.$request->url().'?'.$colname.'='.$logEntry->$colname.(isset($mon)?'&mon='.$mon:'').'">';
                        $link_end = '</a>';
                    }
                    else $link_start = $link_end = null;
                    $html.='<td>'.$link_start.$logEntry->$colname.$link_end.'</td>';
                }
                $html.='</tr>';
            
            }
            if($title) $title.='的';
            if($mon) $title.='<a target="_blank" href="showLog?mon='.$mon.'">'.$mon.'月</a>';
            $title.='Log紀錄';
            $html = ($title?'<h1>'.$title.'</h1>':'').'<table>'.$this->_getSimpleTableHead($colnames).$html.'</table>';
            
            echo $html;
            exit;
            
        }

        $logEntrys = $query->get();
    
    }
    
    public function switchIgnore(Request $request) 
    {
        $value = $request->value;
        $cat_type = $request->cat_type;
        if(!$value) return;
        $cat = $request->cat ?? '';
        if(!$cat_type) {
            if(strrpos($cat,'.')>0) $cat_type='ip';
            else $cat_type='cfp_id';
        }
        
        $op = $request->op;
        $ignore = $this->ignore;
        
        switch($op) {
            case '1':
                $ignore_entry = $ignore->firstOrNew(['item'=>$value,$cat_type=>$ip]);
                if($cat_type=='ip')
                    $ignore_entry->ip = $cat;
                else if($cat_type=='cfp_id') $ignore_entry->cfp_id = $cat;
                else if($cat_type=='visitor_id') $ignore_entry->visitor_id = $cat;
                $ignore_entry->item = $value;
                $ignore_entry->save() ;
            break;
            case '0':
                $ignore->where('item',$value)->where($cat_type,$cat)->delete();
            break;
            default:
                $ignore_entry = $ignore->firstOrNew(['item'=>$value,$cat_type=>$cat]);

                if($ignore_entry->id) $ignore_entry->delete();
                else {
                    $ignore_entry->item = $value;
                    if($cat_type=='ip')
                        $ignore_entry->ip = $cat;
                    else if($cat_type=='cfp_id') $ignore_entry->cfp_id = $cat;
                    else if($cat_type=='visitor_id') $ignore_entry->visitor_id = $cat;
                    $ignore_entry->save();                  
                }
            break;
        }
    }
    
    private function _getSimpleTableHead($colnames) 
    {
        
        $colshow = '<tr>';
        foreach($colnames  as $colname) {
            
            $colshow.='<th>'.$colname.'</th>';
            
        }
        $colshow.='</tr>';    

        return $colshow;        

    }
    
    private function _getSimpleStyle() 
    {
        return ' <style>      
                    a, a:visited, a:hover, a:active {
                        text-decoration: underline;
                        color: inherit;
                    } 
                    table,tr,td,th {padding:5px;border-width:3px; border-style:solid;border-collapse: collapse;border-spacing:0;text-align: center;;vertical-align: middle;}</style>'
                    ."\n\r";
    }

    private function _isIpChecked($ip) 
    {
        foreach($this->_columnIp  as $groupIpArr) {
            if(array_search($ip ,$groupIpArr)!==false) return true;
            else continue;
        }
    }
    
    private function _isColumnChecked($check_val) 
    {
        if(array_search($check_val ,array_dot($this->_columnIp))!==false) return true;
    } 
    
    private function _havePuppetUserId($check_val=null,$login_data=[]) 
    {
        $multiUserIds = $login_data[$check_val]??[];
        
        if(!$multiUserIds || count($multiUserIds)<=1) {
            return false;
        }
        else return true;        
    }
    
    public function compare_login_time_show(Request $request,PuppetAnalysisAdminService $service) 
    {
        $data['service'] = $service;
        $group_index = $request->group_index;
        $only = $request->only;
       
        
        switch($only) {
            case 'cfpid':
                $row_list = $service->getRowListOnlyCfpIdFromGroupIndex($group_index);
            break;
            default:
                $row_list = $service->getRowListStandardFromGroupIndex($group_index);
            break;
        }
        
        
        $data['row_list'] = $row_list;
        return view('admin.users.findpuppet_compare_login_time_show',$data)
                ->with('data',$data); 
    }
    
    public function compare_login_time(Request $request,PuppetAnalysisAdminService $service) 
    {
        $data['service'] = $service;
        
        if($request->compare_interval??null) {
            $service->compare_interval = $request->compare_interval;
        }          
        
        $data['target_service']= unserialize(serialize($service));
        $group_index = $request->group_index;
        $only = $request->only;
        $master = $request->master;
        $target = $request->target;
        $before_period = $request->before_period;
        $compare_interval = $request->compare_interval;
        
        if(!$master || !$target)  return back();
        $date_filter_arr['before_period'] = $before_period;
        $date_list = $service->riseByUserId($master)->getUserLoginDateList($date_filter_arr);
        $data['date_list'] = $date_list;
        $data['target'] = $target;
 
        return view('admin.users.findpuppet_compare_login_time',$data)
                ->with('data',$data); 
    }  

    public function get_multi_account_mail_num_list(Request $request)
    {
        $only = $request->only;
        $user_list_query = PuppetAnalysisRow::select('name')->distinct();
        if($only) {
            $user_list_query->where('cat','only_'.$only);
        }
        $user_list = $user_list_query->pluck('name');
        
        $keyword_arr =['目前使用多個帳號','請選擇一個主帳號','個人資料','帳號設定','自行關閉','查到會封鎖帳號','到時候就救不回來'];
        
        $msg_query = Message::where('from_id',1049)->whereIn('to_id',$user_list)->selectRaw('to_id,count(*) as num')->groupBy('to_id');
        
        foreach($keyword_arr as $k=>$v) {
             $msg_query->where('content','like','%'.$v.'%');
        }
        
        $msg_num_list = $msg_query->get();
        
        return response()->json($msg_num_list);
        
    }
    
    public function get_newer_manual_stay_online_time_list(Request $request)
    {
        $only = $request->only;
        $user_list_query = PuppetAnalysisRow::select('name')->distinct();
        if($only) {
            $user_list_query->where('cat','only_'.$only);
        }
        $user_list = $user_list_query->pluck('name');
        $female_list = User::where('engroup',2)->whereIn('id',$user_list)->pluck('id');
        $male_list = User::where('engroup',1)->whereIn('id',$user_list)->pluck('id');
        $female_time_query = StayOnlineRecord::where('url','like','%#nr_fnm%')->whereIn('user_id',$female_list)->groupBy('user_id')->selectRaw('user_id,sum(stay_online_time) as time');
        $male_time_query = StayOnlineRecord::whereIn('user_id',$male_list)->groupBy('user_id')->selectRaw('user_id,SUM(newer_manual) as time');

        $newer_time_list = $male_time_query->union($female_time_query)->get();
        
        $zero_time_list = $newer_time_list->where('time',0);
        
        return response()->json(array_diff($newer_time_list->all(),$zero_time_list->all()));
        
    }    
    
      
}
