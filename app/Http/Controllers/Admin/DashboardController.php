<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Models\AdminMenuItems;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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
}
