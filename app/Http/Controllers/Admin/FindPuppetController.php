<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use App\Models\LogUserLogin;
use App\Models\PuppetAnalysisCell;
use App\Models\PuppetAnalysisColumn;
use App\Models\PuppetAnalysisRow;

class FindPuppetController extends \App\Http\Controllers\Controller
{
    private $_columnIp = array();
    private $_rowUserId = array();
    private $_cellVal = array();
    private $_columnType = array();
    private $_groupIdx =0;
    
    public function __construct(LogUserLogin $logUserLogin,PuppetAnalysisColumn $column,PuppetAnalysisRow $row, PuppetAnalysisCell $cell)
    {
        ini_set("max_execution_time",'0');
        ini_set('memory_limit','-1');
        ini_set("request_terminate_timeout",'0');
        set_time_limit(0);
        error_reporting(0);

        $this->model = $logUserLogin;
        $this->model->setReadOnly();
        $this->column = $column;
        $this->row = $row;
        $this->cell = $cell;        
        
        $this->_columnIp = array();
        $this->_rowUserId = array();
        $this->_cellVal = array();
        $this->_columnType = array();
        $this->_groupIdx = 0; 
        $this->monarr = [];    
        $this->default_sdate = \Carbon\Carbon::now()->subDays(10)->format('Y/m/d');
    }    
    
    public function entrance(Request $request) {
        ini_set("max_execution_time",'0');
        ini_set('memory_limit','-1'); 
        set_time_limit(0);
        error_reporting(0);        

        $error_msg = '';
        $whereArr = [];
        $have_mon_limit = false;
        
        if(isset($this->monarr) && count($this->monarr)>0) {
            $monarr = $this->monarr;
            $have_mon_limit = true;
        }


        try {

            $sdate = $request->sdate?$request->sdate.'/01':$this->default_sdate;
            $edate = $request->edate?date('Y/m/d',strtotime($request->edate.'/01+1 month - 1 day')):date('Y/m/d');

            $sdate_arr = explode('/',$sdate);
            $edate_arr = explode('/',$edate);   
            if(!checkdate($sdate_arr[1],$sdate_arr[2],$sdate_arr[0]) || !checkdate($edate_arr[1],$edate_arr[2],$edate_arr[0])) {
                $error_msg = '日期格式錯誤或非正確日期';
            }
            else if($edate<$sdate) {
                $error_msg = '錯誤!結束日期小於開始日期';
            }

            echo $error_msg;

                if(!$error_msg) {
                    
                    $this->column->truncate();
                    $this->row->truncate();
                    $this->cell->truncate();                      
                    
                    if(isset($edate)) {
                        if($edate) $edate.=date(' H:i:s');
                        $whereArr[] = ['created_at','<',$edate];
                    }
                    
                    if(isset($sdate)) $whereArr[] = ['created_at','>=',$sdate];
                    
                    if($have_mon_limit) {
                        if(isset($mon) && $mon) {
                            $have_mon_limit = true;
                            $mon_date = '2021-'.$mon;
                            $whereArr[] = ['created_at','LIKE',$mon_date.'%'];
                        }
                        else $have_mon_limit = false;
                    
                    }                                    
                    
                    $excludeUserId = array_pluck(User::whereHas('roles', function($query){
                        $query->where('name', 'like', '%admin%');
                    })->Select('id')->orwhere('id',1049)
                    ->orWhere('email', 'LIKE', 'sandyh.dlc%@gmail.com')
                    ->orWhere('email', 'LIKE', 'TEST%@test.com')
                    ->orWhere('email', 'LIKE', 'lzong.tw%@gmail.com')
                    ->get()->toArray(), 'id');        
                    
                    $model = $this->model;
                    $loginDataEntrys = null;
                    $this->_columnIp = [];
                    $this->_rowUserId = [];
                    $this->_cellVal = [];
                    $this->_columnType = [];
                    $this->_groupIdx = 0;  

                    $loginDataQuery = $model->has('users')->groupBy('ip','user_id')
                            ->select('ip','user_id')->selectRaw('MAX(`created_at`) AS time,COUNT(*) AS num,MIN(`created_at`) AS stime')->whereNotNull('ip')->where('ip','<>','')
                            ->where($whereArr);

                    if($excludeUserId) $loginDataQuery=$loginDataQuery->whereNotIn('user_id',$excludeUserId);
                    
                    $loginDataEntrys = $loginDataQuery->get();
                    $this->loginDataByIp = [];
                    $this->loginDataByUserId = [];
                    
                    foreach($loginDataEntrys  as $loginDataEntry) {
                        $this->loginDataByIp[$loginDataEntry->ip][$loginDataEntry->user_id] = $loginDataEntry;
                        $this->loginDataByUserId[$loginDataEntry->user_id][$loginDataEntry->ip] = $loginDataEntry;
                    }  
                    
                    $loginDataEntrys = null;
                    
                    $loginDataCfpIdQuery = $model->has('users')->groupBy('cfp_id','user_id')
                            ->select('cfp_id','user_id')->selectRaw('MAX(`created_at`) AS time,COUNT(*) AS num,MIN(`created_at`) AS stime')->whereNotNull('cfp_id')->where('cfp_id','<>','')
                            ->where($whereArr);
                            
                    if($excludeUserId) $loginDataCfpIdQuery=$loginDataCfpIdQuery->whereNotIn('user_id',$excludeUserId);                            

                    $loginDataEntrys = $loginDataCfpIdQuery->get();
                    $this->loginDataByCfpId = [];
                    $this->loginDataByUserIdCfpId = [];

                    foreach($loginDataEntrys  as $loginDataCfpIdEntry) {
                        $this->loginDataByCfpId[$loginDataCfpIdEntry->cfp_id][$loginDataCfpIdEntry->user_id] = $loginDataCfpIdEntry;
                        $this->loginDataByUserIdCfpId[$loginDataCfpIdEntry->user_id][$loginDataCfpIdEntry->cfp_id] = $loginDataCfpIdEntry;
                    }    

                              
                    $puppetFromUsers = null;
                    $ipPuppetFromUserQuery = $model->has('users')->groupBy('ip')
                            ->select('ip')->selectRaw('COUNT(DISTINCT `user_id`) AS num')->whereNotNull('ip')->where('ip','<>','')
                            ->where($whereArr)->orderByDesc('num');
                            
                    if($excludeUserId) $ipPuppetFromUserQuery=$ipPuppetFromUserQuery->whereNotIn('user_id',$excludeUserId);                            

                    $puppetFromUsers = $ipPuppetFromUserQuery->get();

                    foreach($puppetFromUsers as $ipPuppet) {

                        if($ipPuppet->num<2 || $this->_isColumnChecked($ipPuppet->ip))
                            continue;
                        else {
                            if($this->_findMultiUserIdFromIp($ipPuppet->ip)===true) continue;

                            $this->_groupIdx++;
                        }
                    }
                    
                    $puppetFromUsers = null;
                  
                    $cfpidPuppetFromUserQuery = $model->has('users')->groupBy('cfp_id')
                            ->select('cfp_id')->selectRaw('COUNT(DISTINCT `user_id`) AS num')->whereNotNull('cfp_id')->where('cfp_id','<>','')
                            ->where($whereArr)->orderByDesc('num');
                            
                    if($excludeUserId) $cfpidPuppetFromUserQuery=$cfpidPuppetFromUserQuery->whereNotIn('user_id',$excludeUserId);                            

                    $puppetFromUsers = $cfpidPuppetFromUserQuery->get();

                    foreach($puppetFromUsers as $cfpidPuppet) {
                        
                        if($cfpidPuppet->num<2 || $this->_isColumnChecked($cfpidPuppet->cfp_id))
                            continue;
                        else {
                            if($this->_findMultiUserIdFromIp($cfpidPuppet->cfp_id,'cfp_id')===true) continue;

                            $this->_groupIdx++;
                            
                        }
                    } 
                    $add_num=0;
                    $creatingArr = null;
                    
                    foreach($this->_columnIp  as $groupIdx=>$colSet) {
                        foreach($colSet  as $nowColumnIdx=>$colElt) {
                            
                            $nowAddData = ['column_index'=>$nowColumnIdx,'name'=>$colElt
                                    ,'group_index'=>$groupIdx
                                    ,'type'=>$this->_columnType[$groupIdx][$nowColumnIdx]
                                    ,'created_at'=>$edate
                                    ,'updated_at'=>date('Y-m-d H:i:s')];
                                    
                            if($have_mon_limit) $nowAddData['mon']=$mon;
                            
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
                    
                    $creatingArr = null;
                    $add_num = 0;
                    
                    foreach($this->_rowUserId  as $groupIdx=>$rowSet) {
                        foreach($rowSet  as $nowRowIdx=>$rowElt) {
                            
                            $nowAddData = ['row_index'=>$nowRowIdx
                                ,'name'=>$rowElt,'group_index'=>$groupIdx
                                ,'created_at'=>$edate
                                ,'updated_at'=>date('Y-m-d H:i:s')];
                                    
                            if($have_mon_limit) $nowAddData['mon']=$mon;
                            
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
                    
                    $creatingArr = null;
                    
                    $add_num = 0;
                    
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
                
                }
            
            //}
        } catch (Exception $e) {
            echo $e->getMessage();exit;
        }
        
        echo '1';exit;

    }
    
    private function _findMultiUserIdFromIp($check_val,$type='ip') {
        $groupIdx = $this->_groupIdx;
        
        switch($type) {
            case 'ip':
            default:
                $loginData = $this->loginDataByIp;
            break;
            case 'cfp_id':
                $loginData = $this->loginDataByCfpId;
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

            $multiIps = $this->loginDataByUserId[$multiUserId->user_id];
            
            $new_check_column = null;

            if(isset($this->_columnIp[$groupIdx])) {
                $new_check_column = array_diff(array_keys($multiIps),$this->_columnIp[$groupIdx]);

            }
            else {$new_check_column = array_keys($multiIps);}
            
            foreach($new_check_column as $new_check_value) {
                    if($this->_havePuppetUserId($new_check_value,$this->loginDataByIp)) 
                        $this->_columnIp[$groupIdx][] = $new_check_value;
            }
            
             foreach($multiIps  as $multiIp) {
                 if($this->_findMultiUserIdFromIp($multiIp->ip)===true) continue;
             }
            
            $multiCfpIds = isset($this->loginDataByUserIdCfpId[$multiUserId->user_id])?$this->loginDataByUserIdCfpId[$multiUserId->user_id]:[];

             $new_check_column = null;
            if(isset($this->_columnIp[$groupIdx])) {
                $new_check_column = array_diff(array_keys($multiCfpIds),$this->_columnIp[$groupIdx]);

            }
            else {$new_check_column = array_keys($multiCfpIds);}

            foreach($new_check_column as $new_check_value) {
                    if($this->_havePuppetUserId($new_check_value,$this->loginDataByCfpId)) 
                        $this->_columnIp[$groupIdx][] = $new_check_value;
            }             
             
             foreach($multiCfpIds  as $multiCfpId) {
                 if($this->_findMultiUserIdFromIp($multiCfpId->cfp_id,'cfp_id')===true) continue;
             }             
             
        }   
    }
    
    public function display(Request $request) {
        ini_set("max_execution_time",'0');
        ini_set('memory_limit','-1');
        ini_set("request_terminate_timeout",'10000');
        ini_set("process_idle_timeout",'10000');
        ini_set("pm.request_terminate_timeout",'10000');
        ini_set("pm.process_idle_timeout",'10000');
        ini_set("max_children",'100');
        ini_set("pm.max_children",'100');
        set_time_limit(0);
        error_reporting(0);            
        
        $have_mon_limit = false;
        
        if(isset($this->monarr) && count($this->monarr)>0) {
            $monarr = $this->monarr;
            $have_mon_limit = true;
        }
        
        $whereArr = [];

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
        $groupOrderArr = [];
        
        if($have_mon_limit && (!isset($mon) || !$mon) && $show!='text') {

        }
        else {
            
            if($show!='text') {

                $groupInfo = [];
                
                
                if($have_mon_limit) $whereArr[] = ['mon',$mon];
                
                $edate_colentry = $this->column->select('created_at')->distinct()->first();
                $data['end_date'] = $edate_colentry->created_at;
                $sdate_from_model = $this->model->min('created_at');

                if($this->default_sdate>=$sdate_from_model) {
                    $data['start_date'] = $this->default_sdate;
                }
                else {
                    
                    $data['start_date'] = $sdate_from_model;
                }
                
                $groupChecks = $this->cell->where($whereArr)->groupBy('group_index')->select('group_index')
                    ->selectRaw('MAX(column_index) AS maxColIdx,MAX(row_index) AS maxRowIdx,MAX(time) AS last_time')
                    ->orderByDesc('last_time')
                    ->get();
                
                foreach($groupChecks as $idx=>$groupCheck) {
                    $groupOrderArr[] = $groupCheck->group_index;
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

            $colQuery = $this->column->where($whereArr);
            if($show=='text') {
                 if($g) $colQuery->where('group_index',$g);
            }
            $colEntrys = $colQuery->get();
            foreach($colEntrys as $colEntry) {
                if(isset($groupInfo[$colEntry->group_index]['cutData']) && $groupInfo[$colEntry->group_index]['cutData'] && $colEntry->column_index>$data['colLimit']) continue;
                
               $this->_columnIp[$colEntry->group_index][$colEntry->column_index] = $colEntry->name;
               $this->_columnType[$colEntry->group_index][$colEntry->column_index] = $colEntry->type;

            }
            
            $rowQuery = $this->row->where($whereArr);
            if($show=='text') {
                 if($g) $rowQuery->where('group_index',$g);
            }            
            $rowEntrys = $rowQuery->get();
            foreach($rowEntrys as $rowEntry) {
                if($show=='text') {
                    $this->_rowUserId[$rowEntry->group_index][$rowEntry->row_index]  = $rowEntry->name;
                }
                else {
                    if(isset($groupInfo[$colEntry->group_index]['cutData']) && $groupInfo[$rowEntry->group_index]['cutData'] && $rowEntry->row_index>$data['rowLimit']) continue;
                    $cur_user = User::with('vip','aw_relation', 'banned', 'implicitlyBanned')->find($rowEntry->name)??new User;
                    $cur_user->tag_class = '';
                    if($cur_user->id==null) $cur_user->id = $rowEntry->name;
                    if($cur_user->banned)  $cur_user->tag_class.= 'banned ';
                    if($cur_user->implicitlyBanned)  $cur_user->tag_class.= 'implicitlyBanned ';
                    if($cur_user->user_meta->isWarned || $cur_user->aw_relation)  $cur_user->tag_class.= 'isWarned ';
                    if($cur_user->accountStatus===0) $cur_user->tag_class.= 'isClosed ';
                    if($cur_user->account_status_admin===0) $cur_user->tag_class.= 'isClosedByAdmin ';
                    $this->_rowUserId[$rowEntry->group_index][$rowEntry->row_index] = $cur_user;
                    $cur_user = null;
                }
            }  
            
            $cellQuery = $this->cell->where($whereArr);
            if($show=='text') {
                 if($g) $cellQuery->where('group_index',$g);
            }                    
            $cellEntrys = $cellQuery->get();
            foreach($cellEntrys as $cellEntry) {
                if(isset($groupInfo[$colEntry->group_index]['cutData']) && $groupInfo[$cellEntry->group_index]['cutData'] && ($cellEntry->row_index>$data['rowLimit'] || $cellEntry->column_index>$data['colLimit'])) continue;
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
                for($i=0;$i<count($arr1[$g]);$i++) {
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
                
                $colnames = ['ip或cfp_id','user_id','last_login_time','login_count'];

                $text = '<table>'.$this->_getSimpleTableHead($colnames).$text.'</table>';
                $text = $this->_getSimpleStyle().$text;
                
                echo $text;exit;
            }
            
        }
        
    $data['groupOrderArr'] = $groupOrderArr;
        
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
        $time = $request->logtime;
        $query = $this->model;
        $html = null;
        $html = $this->_getSimpleStyle();
        $title='';
        $showLogQuery = [];
        
        $colnames = ['user_id','ip','cfp_id','created_at'];
        
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

            if($mon) {
                if($showLogQuery) $showLogQuery.='&';
                $query = $query->where('created_at','LIKE','%-'.$mon.'-%');
            }    
            $logEntrys = $query->orderByDesc('created_at')->get();
            
            foreach($logEntrys as $logEntry) {
                $html.='<tr>';
                foreach($colnames  as $colname) {
                    if($colname!='created_at') {
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
    
    private function _getSimpleTableHead($colnames) {
        
        $colshow = '<tr>';
        foreach($colnames  as $colname) {
            
            $colshow.='<th>'.$colname.'</th>';
            
        }
        $colshow.='</tr>';    

        return $colshow;        

    }
    
    private function _getSimpleStyle() {
        return ' <style>      
                    a, a:visited, a:hover, a:active {
                        text-decoration: underline;
                        color: inherit;
                    } 
                    table,tr,td,th {padding:5px;border-width:3px; border-style:solid;border-collapse: collapse;border-spacing:0;text-align: center;;vertical-align: middle;}</style>'
                    ."\n\r";
    }

    private function _isIpChecked($ip) {
        foreach($this->_columnIp  as $groupIpArr) {
            if(array_search($ip ,$groupIpArr)!==false) return true;
            else continue;
        }
    }
    
    private function _isColumnChecked($check_val) {
        if(array_search($check_val ,array_dot($this->_columnIp))!==false) return true;
    } 
    
    private function _havePuppetUserId($check_val,$login_data) {
        $multiUserIds = $login_data[$check_val];
        
        if(count($multiUserIds)<=1) {
            return false;
        }
        else return true;        
    }
      
}
