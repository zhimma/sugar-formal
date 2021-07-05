<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\LogUserLogin;
use App\Models\PuppetAnalysisCell;
use App\Models\PuppetAnalysisColumn;
use App\Models\PuppetAnalysisRow;

class FindPuppetController extends Controller
{
    private $_columnIp = array();
    private $_rowUserId = array();
    private $_cellVal = array();
    private $_columnType = array();
    private $_groupIdx =0;

    public function __construct(LogUserLogin $logUserLogin,PuppetAnalysisColumn $column,PuppetAnalysisRow $row, PuppetAnalysisCell $cell)
    {
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
    }

    public function entrance(Request $request) {
        ini_set("max_execution_time",'0');
        ini_set('memory_limit','-1');

        $error_msg = '';

        try {
            $this->column->truncate();
            $this->row->truncate();
            $this->cell->truncate();

            $sdate = $request->sdate?:'2000/01/01';
            $edate = $request->edate?:date('Y/m/d');
            $sdate_arr = explode('/',$sdate);
            $edate_arr = explode('/',$edate);
            if(!checkdate($sdate_arr[1],$sdate_arr[2],$sdate_arr[0]) || !checkdate($edate_arr[1],$edate_arr[2],$edate_arr[0])) {
                $error_msg = '日期格式錯誤或非正確日期';
            }
            else if($edate<$sdate) {
                $error_msg = '錯誤!結束日期小於開始日期';
            }


            if(!$error_msg) {
                $edate.=' 23:59:59';
                $model = $this->model;

                $loginDataQuery = $model->groupBy('ip','user_id')
                    ->select('ip','user_id')->selectRaw('MAX(`created_at`) AS time,COUNT(*) AS num')->whereNotNull('ip')->where('ip','<>','')
                    ->where('created_at','>=',$sdate)
                    ->where('created_at','<=',$edate);

                $loginDataEntrys = $loginDataQuery->get();
                $this->loginDataByIp = [];
                $this->loginDataByUserId = [];

                foreach($loginDataEntrys  as $loginDataEntry) {
                    $this->loginDataByIp[$loginDataEntry->ip][$loginDataEntry->user_id] = $loginDataEntry;
                    $this->loginDataByUserId[$loginDataEntry->user_id][$loginDataEntry->ip] = $loginDataEntry;
                }

                $loginDataCfpIdQuery = $model->groupBy('cfp_id','user_id')
                    ->select('cfp_id','user_id')->selectRaw('MAX(`created_at`) AS time,COUNT(*) AS num')->whereNotNull('cfp_id')->where('cfp_id','<>','')
                    ->where('created_at','>=',$sdate)
                    ->where('created_at','<=',$edate);

                $loginDataCfpIdEntrys = $loginDataCfpIdQuery->get();
                $this->loginDataByCfpId = [];
                $this->loginDataByUserIdCfpId = [];

                foreach($loginDataCfpIdEntrys  as $loginDataCfpIdEntry) {
                    $this->loginDataByCfpId[$loginDataCfpIdEntry->cfp_id][$loginDataCfpIdEntry->user_id] = $loginDataCfpIdEntry;
                    $this->loginDataByUserIdCfpId[$loginDataCfpIdEntry->user_id][$loginDataCfpIdEntry->cfp_id] = $loginDataCfpIdEntry;
                }

                $ipPuppetFromUserQuery = $model->groupBy('ip')
                    ->select('ip')->selectRaw('COUNT(DISTINCT `user_id`) AS num')->whereNotNull('ip')->where('ip','<>','')
                    ->where('created_at','>=',$sdate)
                    ->where('created_at','<=',$edate);

                $ipPuppetFromUsers = $ipPuppetFromUserQuery->get();

                foreach($ipPuppetFromUsers as $ipPuppet) {

                    if($ipPuppet->num<2 || $this->_isIpChecked($ipPuppet->ip))
                        continue;
                    else {
                        if($this->_findMultiUserIdFromIp($ipPuppet->ip,$sdate,$edate)===true) continue;

                        $this->_groupIdx++;

                    }
                }

                $cfpidPuppetFromUserQuery = $model->groupBy('cfp_id')
                    ->select('cfp_id')->selectRaw('COUNT(DISTINCT `user_id`) AS num')->whereNotNull('cfp_id')->where('cfp_id','<>','')
                    ->where('created_at','>=',$sdate)
                    ->where('created_at','<=',$edate);

                $cfpidPuppetFromUsers = $cfpidPuppetFromUserQuery->get();

                foreach($cfpidPuppetFromUsers as $cfpidPuppet) {

                    if($cfpidPuppet->num<2 || $this->_isColumnChecked($cfpidPuppet->cfp_id))
                        continue;
                    else {
                        if($this->_findMultiUserIdFromIp($cfpidPuppet->cfp_id,$sdate,$edate,'cfp_id')===true) continue;

                        $this->_groupIdx++;

                    }
                }

                $creatingArr = [];

                foreach($this->_columnIp  as $groupIdx=>$colSet) {
                    foreach($colSet  as $nowColumnIdx=>$colElt) {
                        $creatingArr[] = ['column_index'=>$nowColumnIdx,'name'=>$colElt
                            ,'group_index'=>$groupIdx
                            ,'type'=>$this->_columnType[$groupIdx][$nowColumnIdx],'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')];
                    }

                }

                $this->column->insert($creatingArr);

                $creatingArr = [];

                foreach($this->_rowUserId  as $groupIdx=>$rowSet) {
                    foreach($rowSet  as $nowRowIdx=>$rowElt) {
                        $creatingArr[] = ['row_index'=>$nowRowIdx,'name'=>$rowElt,'group_index'=>$groupIdx,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')];
                    }

                }

                $this->row->insert($creatingArr);

                $creatingArr = [];

                $add_num = 0;

                foreach($this->_cellVal  as $groupIdx=>$rowSet) {
                    foreach($rowSet  as $nowRowIdx=>$colSet) {
                        foreach($colSet  as $nowColumnIdx=>$cnt) {
                            $creatingArr[] = ['column_index'=>$nowColumnIdx
                                ,'row_index'=>$nowRowIdx
                                ,'time'=>$cnt->time
                                ,'num'=>$cnt->num
                                ,'group_index'=>$groupIdx,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')];
                            $add_num++;

                            if($add_num>999) {

                                $this->cell->insert($creatingArr);
                                $creatingArr = [];
                                $add_num = 0;
                            }
                        }
                    }

                }

                $this->cell->insert($creatingArr);
            }
        } catch (Exception $e) {
            echo $e->getMessage();exit;
        }

        echo '1';exit;

    }

    private function _findMultiUserIdFromIp($check_val,$sdate,$edate,$type='ip') {
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

        if(isset($this->_columnIp[$groupIdx]) && array_search($check_val,$this->_columnIp[$groupIdx])!==false) return true;
        $model = $this->model;

        $multiUserIds = $loginData[$check_val];

        if(count($multiUserIds)<=1) return true;

        $this->_columnIp[$groupIdx][] = $check_val;
        $this->_columnType[$groupIdx][] = $type;


        $nowColumnIdx = count($this->_columnIp[$groupIdx])-1;

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

            foreach($multiIps  as $multiIp) {
                if($this->_findMultiUserIdFromIp($multiIp->ip,$sdate,$edate)===true) continue;
            }

            $multiCfpIds = isset($this->loginDataByUserIdCfpId[$multiUserId->user_id])?$this->loginDataByUserIdCfpId[$multiUserId->user_id]:[];

            foreach($multiCfpIds  as $multiCfpId) {
                if($this->_findMultiUserIdFromIp($multiCfpId->cfp_id,$sdate,$edate,'cfp_id')===true) continue;
            }

        }
    }

    public function display(Request $request) {
        if($request->clear) {
            $this->column->truncate();
            $this->row->truncate();
            $this->cell->truncate();
            return redirect()->to($request->path());

        }

        $colEntrys = $this->column->all();
        foreach($colEntrys as $colEntry) {
            $this->_columnIp[$colEntry->group_index][$colEntry->column_index] = $colEntry->name;
            $this->_columnType[$colEntry->group_index][$colEntry->column_index] = $colEntry->type;
        }

        $rowEntrys = $this->row->all();
        foreach($rowEntrys as $rowEntry) {
            $this->_rowUserId[$rowEntry->group_index][$rowEntry->row_index] = $rowEntry->name;
        }

        $cellEntrys = $this->cell->all();
        foreach($cellEntrys as $cellEntry) {
            $this->_cellVal[$cellEntry->group_index][$cellEntry->row_index][$cellEntry->column_index] = $cellEntry;
        }

        return view('findpuppet')
            ->with('columnSet', $this->_columnIp)
            ->with('columnTypeSet',$this->_columnType)
            ->with('rowSet', $this->_rowUserId)
            ->with('cellValue', $this->_cellVal)
            ->with('error_msg',[]);

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

}