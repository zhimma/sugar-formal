<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Models\MemberPic;
use App\Models\Message;
use App\Models\Reported;
use App\Models\ReportedAvatar;
use App\Models\ReportedPic;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\AdminService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserInviteRequest;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\AdminAnnounce;
use App\Models\VipLog;
use App\Models\Vip;
use App\Models\SimpleTables\member_vip;
use App\Models\SimpleTables\banned_users;
use App\Notifications\BannedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatController extends Controller
{
    public function __construct(UserService $userService, AdminService $adminService)
    {
        $this->service = $userService;
        $this->admin = $adminService;
    }

    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function vip()
    {
        //SELECT * FROM `member_vip_log` WHERE `member_id` NOT IN
        //(SELECT `member_id` FROM `member_vip_log` WHERE `action` = 0) ORDER BY `created_at` DESC
        $results = VipLog::whereNotIn('member_id', function($query){
            $query->select('member_id')
                ->from(with(new VipLog)->getTable())
                ->where('action', 0);
        })->orderBy('created_at', 'ASC')->get();
        $end = date_create();
        foreach ($results as $key => $result){
            $start  = date_create($result->created_at);
            $user = User::select('name','engroup')->where('id', $result->member_id)->get()->first();
            if($user == null){
                $results[$key]['name'] = '無資料';
            }
            else{
                $results[$key]['name'] = $user->name;
            }
            if($user->engroup == 1){
                $results[$key]['engroup'] = '男';
            }
            else{
                $results[$key]['engroup'] = '女';
            }
            $results[$key]['times'] = date_diff( $start, $end );
        }
        return view('admin.stats.vip', ['results' => $results]);
    }
    public function vipLog($id)
    {
        $results = VipLog::where('member_id', $id)->get();
        $name = User::where('id', $id)->get()->first()->name;
        $expiry = Vip::where('member_id', $id)->orderBy('created_at', 'asc')->get()->first()->expiry;
        return view('admin.stats.vipLog', [
            'results' => $results,
            'name' => $name,
            'expiry' => substr($expiry, 0, 10)]);
    }
    public function cronLog(){
        $data = \DB::table('log_vip_crontab')->orderBy('id', 'desc')->paginate(20);
        foreach ($data as &$d){
            if($d->user_id == 0){
                $d->user_id = '無';
            }
            $d->date = substr($d->date, 0, 10);
            $d->content = str_replace("\n", "<br>", $d->content);
            $d->created_at = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at)->addHours(14);
        }
        return view('admin.stats.cronLog')->with('data', $data);
    }
    public function datFileLog(){
        $data = \DB::table('log_dat_file')->orderBy('id', 'desc')->paginate(21);
        foreach ($data as &$d){
            if($d->upload_check == 0){
                $d->upload_check = '上傳';
            }
            else{
                $d->upload_check = '檢查';
            }
            $d->created_at = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at)->addHours(14);
        }
        return view('admin.stats.datFileLog')->with('data', $data);
    }

    public function set_autoBan(){
        $data = DB::table('set_auto_ban')->get();
        return view('admin.stats.set_autoBan')->with('data', $data);
    }

    public function set_autoBan_add(Request $request){
        if(isset($request->content)){
            DB::table('set_auto_ban')->insert(['type' => $request->type, 'content' => $request->content, 'set_ban' => $request->set_ban]);
        }
        $data = DB::table('set_auto_ban')->get();
        return view('admin.stats.set_autoBan')->with('data', $data);
    }

    public function set_autoBan_del(Request $request){
        DB::table('set_auto_ban')->where('id', '=', $request->id)->delete();
        $data = DB::table('set_auto_ban')->get();
        return view('admin.stats.set_autoBan')->with('data', $data);
    }
}
