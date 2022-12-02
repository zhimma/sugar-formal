<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Models\AdminMenuItems;
use App\Models\PaymentFlowChoose;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdminActionLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\RoleUser;
use Carbon\Carbon;

class DashboardController extends \App\Http\Controllers\BaseController
{
    /**
     * Dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    public function accessPermission(Request $request)
    {
        $adminList = DB::table('role_user')->where('role_id',3)->groupBy('user_id')->get();
        return view('admin.adminAccessPermission',compact('adminList'));
    }

    public function showJuniorAdmin(Request $request)
    {
        $permissionItems = AdminMenuItems::where('status',1)->orderBy('sort')->get();
        return view('admin.showJuniorAdmin',compact('permissionItems'));
    }

    public function juniorAdminCreate(Request $request)
    {
        $adminUser=User::findByEmail($request->get('account'));
        if(is_null($adminUser)){
            return back()->withErrors('初階帳號建立失敗,資料庫找不到該帳號');
        }

        $data=[
            'item_permission'=>is_null($request->get('items')) ? '' : implode(',',$request->get('items')),
            'created_at' => now()
        ];
        DB::table('role_user')->updateOrInsert([
            'user_id'=>$adminUser->id,
            'role_id' =>3], $data);
        return redirect('admin/dashboard/accessPermission')->with('message','新增成功');
    }

    public function juniorAdminEdit(Request $request)
    {
        $userid=$request->userid;
        DB::table('role_user')->where('user_id',$userid)->where('role_id',3)->update([
            'item_permission' =>is_null($request->get('items')) ? '' : implode(',',$request->get('items')),
            'updated_at' => now()
        ]);

        return redirect('admin/dashboard/accessPermission')->with('message','修改成功');
    }

    public function juniorAdminDelete($userid)
    {
        DB::table('role_user')->where('user_id',$userid)->where('role_id',3)->delete();
        return back()->withErrors('該初階帳號,刪除成功');
    }

    public function showGlobalVariables(){
        return view('admin.dashboard.globalVariables');
    }

    public function paymentFlowChoose(Request $request)
    {
        $paymentList = PaymentFlowChoose::where('status', 1)->get();
        return view('admin.payment_flow_choose',compact('paymentList'));
    }

    public function showPaymentFlowChoose(Request $request)
    {
        $paymentInfo = PaymentFlowChoose::where('id', $request->id)->first();
        return view('admin.showPaymentFlowChoose',compact('paymentInfo'));
    }

    public function paymentFlowChooseEdit(Request $request)
    {
        $paymentInfo = PaymentFlowChoose::where('id', $request->id)->first();
        if($paymentInfo){
            $paymentInfo->payment=$request->payment;
            $paymentInfo->save();
        }

        return redirect('admin/dashboard/paymentFlowChoose')->with('message','修改成功');
    }

    public function juniorAdminCheckRecord(Request $request)
    {
        $operator_list = RoleUser::leftJoin('users', 'users.id', '=', 'role_user.user_id')->where('role_id', 3)->get();
        return view('admin.juniorAdminCheckRecord')
                ->with('operator_list', $operator_list);
    }

    public function juniorAdminCheckRecordShow(Request $request)
    {
        $perator = $request->operator_list;
        $junior_admin_log_list = [];

        $admin_user = User::whereIn('id', $perator)->get();
        foreach($admin_user as $admin)
        {
            $junior_admin_log_list[$admin->id]['operator_data'] = $admin;
            $junior_admin_log_list[$admin->id]['action_log'] = [];
        }

        $admin_action_log = AdminActionLog::with('user')
                                            ->with('user_meta')
                                            ->whereIn('operator', $perator)
                                            ->where(function($query) {
                                                $query->where('action_id', 28);
                                                $query->orWhere('act','封鎖會員');
                                                $query->orWhere('act','隱性封鎖');
                                                $query->orWhere('act','站方警示');
                                                $query->orWhere('act','警示用戶');
                                            });
        if($request->start_time ?? false)
        {
            $admin_action_log = $admin_action_log->where('created_at', '>', Carbon::parse($request->start_time));
        }

        if($request->end_time ?? false)
        {
            $admin_action_log = $admin_action_log->where('created_at', '<', Carbon::parse($request->end_time)->addDay());
        }

        $admin_action_log = $admin_action_log->orderByDesc('id')
                                            ->get();
        foreach($admin_action_log as $log)
        {
            $junior_admin_log_list[$log->operator]['action_log'][] = $log;
        }

        return view('admin.juniorAdminCheckRecordShow')
            ->with('junior_admin_log_list', $junior_admin_log_list);
    }
}