<?php

namespace App\Http\Controllers;

use Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\VipLogService;
use App\Models\Visited;
use App\Models\Board;
use App\Models\Message;
use App\Models\Reported;
use App\Models\ReportedAvatar;
use App\Models\ReportedPic;
use App\Models\User;
use App\Models\Vip;
use App\Models\Tip;
use App\Models\MemberFav;
use App\Models\Blocked;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\FormFilterRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Models\SimpleTables\banned_users;
use Session;

class PagesController extends Controller
{
    public function __construct(UserService $userService, VipLogService $logService)
    {
        $this->service = $userService;
        $this->logService = $logService;
    }

    public function error() {
        return view('errors.exception');
    }

    /**
     * View current user's settings
     *
     * @return \Illuminate\Http\Response
     */
    public function settings(Request $request)
    {
        $user = $request->user();

        if ($user) {
            return view('user.settings')
            ->with('user', $user);
        }

        return back()->withErrors(['找不到用戶']);
    }

    public function settingsUpdate(Request $request)
    {
        if ($this->service->update(auth()->id(), $request->all())) {
            return redirect('/dashboard')->with('message', '資料更新成功');
        }

        return redirect('/dashboard')->withErrors(['沒辦法更新']);
    }

    public function profileUpdate(Request $request, ProfileUpdateRequest $profileUpdateRequest)
    {
        //Custom validation.
        Validator::extend('not_contains', function($attribute, $value, $parameters)
        {
            $words = array('站長', '管理員');
            foreach ($words as $word)
            {
                if (stripos($value, $word) !== false) return false;
            }
            return true;
        });
        $rules = [
            'name'     => ['required', 'max:255', 'not_contains'],
        ];
        $messages = [
            'not_contains'  => '請勿使用包含「站長」或「管理員」的字眼做為暱稱！'
        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        
        if($validator->fails()){
            return redirect('/dashboard')->withErrors(['請勿使用包含「站長」或「管理員」的字眼做為暱稱！']);
        }
        else{
            if ($this->service->update(auth()->id(), $request->all())) {
                return redirect('/dashboard')->with('message', '資料更新成功');
            }
            return redirect('/dashboard')->withErrors(['沒辦法更新']);
        } 
        return redirect('/dashboard')->withErrors(['沒辦法更新']);
    }

    public function postBoard(Request $request)
    {
        Board::post(auth()->id(), $request->all()['msg']);
        return back()->with('message', '留言成功!');
    }

    public function postChatpay(Request $request)
    {
        $user = $request->user();
        $url = $request->fullUrl();
        //dd($url);

        if ($user == null)
        {
            $aid = auth()->id();
            $user = User::findById($aid);
        }
        $payload = $request->all();
        $pool = '';
        $count = 0;
        foreach ($payload as $key => $value){
            $pool .= 'Row '. $count . ' : ' . $key . ', Value : ' . $value . '
';//換行
            $count++;
        }
        $infos = new \App\Models\LogChatPayInfos();
        $infos->user_id = $user->id;
        $infos->content = $pool;
        $infos->save();
        if (isset($payload['final_result']))
        {
            Tip::upgrade($user->id, $payload['P_OrderNumber'], $payload['P_CheckSum']);
            //Tip::upgrade($user->id, $payload['to'], $payload['P_OrderNumber']);
        }

        Message::post($user->id, $payload['P_OrderNumber'], "系統通知: 車馬費邀請");

        return redirect('/dashboard/chat/' . $payload['P_OrderNumber'] . '?invite=success');
    }

    public function postChatpayLog(Request $request)
    {
        $user_id = $request->user_id;
        $to_id = $request->to_id;
        $log = new \App\Models\LogChatPay();
        $log->user_id = $user_id;
        $log->to_id = $to_id;
        if ($log->save()) {
            return response()->json(array(
                'status' => 1,
                'msg' => 'ok',
            ), 200);
        } else {
            return response()->json(array(
                'status' => 2,
                'msg' => 'fail',
            ), 500);
        }
    }

    public function postChatpayComment(Request $request)
    {
        $user = $request->user();
        $url = $request->fullUrl();

        $payload = $request->all();

        if(isset($payload['msg'])) {
            Tip::comment($payload['userId'], $payload['to'], $payload['msg']);
        }


        return redirect('/dashboard/chat/' . $payload['to'] . '?comment=success')->with('message', '評價成功');
    }

    /**
     * Homepage
     *
     * @return \Illuminate\Http\Response
     */
    public function home(Request $request)
    {
        $user = $request->user();
        return view('welcome')->with('user', $user);
    }

    public function privacy(Request $request)
    {
        $user = $request->user();
        return view('privacy')->with('user', $user);
    }

    public function terms(Request $request)
    {
        $user = $request->user();
        return view('terms')->with('user', $user);
    }

    public function message(Request $request)
    {
        $user = $request->user();
        return view('message')->with('user', $user);
    }

    public function contact(Request $request)
    {
        $user = $request->user();
        return view('contact')->with('user', $user);
    }

    public function about(Request $request)
    {
        $user = $request->user();
        return view('about')->with('user', $user);
    }

    /**
     * Dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $url = $request->fullUrl();
        //echo $url;

        if(str_contains($url, '?img')) {
            $tabName = 'm_user_profile_tab_4';
        }
        else {
            $tabName = 'm_user_profile_tab_1';
        }

        $birthday = date('Y-m-d', strtotime($user->meta_()->birthdate));
        $birthday = explode('-', $birthday);
        $year = $birthday[0];
        $month = $birthday[1];
        $day = $birthday[2];

        if ($user) {
            $cancel_notice = $request->session()->get('cancel_notice');
            $message = $request->session()->get('message');
            if(isset($cancel_notice)){
                return view('dashboard')
                    ->with('user', $user)
                    ->with('tabName', $tabName)
                    ->with('cur', $user)
                    ->with('year', $year)
                    ->with('month', $month)
                    ->with('day', $day)
                    ->with('message', $message)
                    ->with('cancel_notice', $cancel_notice);
            }
            return view('dashboard')
            ->with('user', $user)
            ->with('tabName', $tabName)
            ->with('cur', $user)
            ->with('year', $year)
            ->with('month', $month)
            ->with('day', $day);
        }
    }

    public function viewuser(Request $request, $uid = -1)
    {
        $user = $request->user();
        if (isset($user) && isset($uid)) {
            $tmp = User::where('id', $uid)->get()->first();
            if(!isset($tmp)){
                return view('errors.nodata');
            }
            if ($user->id != $uid) {
                Visited::visit($user->id, $uid);
            }
            return view('dashboard')
            ->with('user', $user)
            ->with('cur', $this->service->find($uid));
        }
    }

    public function report(Request $request)
    {
        $payload = $request->all();
        $uid = $payload['to'];
        $aid = auth()->id();
        if ( ! Reported::findMember( $aid , $uid ) )
        {
            if ($aid !== $uid)
            {
                $user = $request->user();
                return view('dashboard.reportUser', [ 'aid' => $aid, 'uid' => $uid, 'user' => $user ]);
            }
            else{
                return back()->withErrors(['錯誤，不能檢舉自己。']);
            }
        }
        else
        {
            return back()->withErrors(['檢舉失敗：您已經檢舉過這個人了']);
        }
    }

    public function reportNext(Request $request){
        Reported::report($request->aid, $request->uid, $request->content);
        return redirect('/user/view/'.$request->uid)->with('message', '檢舉成功');
    }

    public function reportPic($reporter_id, $pic_id, $uid = null)
    {
        $isAvatar = false;
        $user = Auth::user();
        if(str_contains($pic_id, 'uid')){
            $isAvatar = true;
            $pic_id = substr($pic_id, 3, strlen($pic_id));
        }
        if($isAvatar){
            if ( ! ReportedAvatar::findMember( $reporter_id , $pic_id ) )
            {
                if ($reporter_id !== $pic_id)
                {
                    return view('dashboard.reportAvatar', [
                        'reporter_id' => $reporter_id,
                        'reported_user_id' => $pic_id,
                        'user' => $user ]);
                }
                else{
                    return back()->withErrors(['錯誤，不能檢舉自己的大頭照。']);
                }
            }
            else
            {
                return back()->withErrors(['檢舉失敗：您已經檢舉過這張大頭照了']);
            }
        }
        else{
            if ( ! ReportedPic::findMember( $reporter_id , $pic_id ) )
            {
                if( $reporter_id !== $uid ){
                    return view('dashboard.reportPic', [
                        'reporter_id' => $reporter_id,
                        'reported_pic_id' => $pic_id,
                        'user' => $user,
                        'uid' => $uid]);
                }
                else{
                    return back()->withErrors(['錯誤，不能檢舉自己的照片。']);
                }
            }
            else
            {
                return back()->withErrors(['檢舉失敗：您已經檢舉過這張照片了']);
            }
        }
    }

    public function reportPicNext(Request $request){
        if($request->avatar){
            ReportedAvatar::report($request->reporter_id, $request->reported_user_id, $request->content);
        }
        if($request->pic){
            ReportedPic::report($request->reporter_id, $request->reported_pic_id, $request->content);
        }
        return redirect('/user/view/'.$request->reported_user_id)->with('message', '檢舉成功');
    }

    public function postBlock(Request $request)
    {
        $payload = $request->all();
        $bid = $payload['to'];
        $aid = auth()->id();
        if ($aid !== $bid)
        {
            Blocked::block($aid, $bid);
        }
        return back()->with('message', '封鎖成功');
    }

    public function unblock(Request $request)
    {
        $payload = $request->all();
        $bid = $payload['to'];
        $aid = auth()->id();

        if($aid !== $bid)
        {
            Blocked::unblock($aid, $bid);
        }

        return back()->with('message', '解除封鎖成功');
    }

    public function postfav(Request $request)
    {
        $payload = $request->all();
        $uid = $payload['to'];
        $aid = auth()->id();
        if ($aid !== $uid)
        {
            MemberFav::fav($aid, $uid);
        }
        return back()->with('message', '收藏成功');
    }

    public function removeFav(Request $request)
    {
        if ($request->userId !== $request->favUserId)
        {
            MemberFav::remove($request->userId, $request->favUserId);
        }
        return back()->with('message', '移除成功');
    }

    public function fav(Request $request)
    {
        $user = $request->user();
        if ($user) {
            return view('dashboard.fav')
            ->with('user', $user);
        }
    }

    public function chat(Request $request, $cid)
    {
        $user = $request->user();
        if ($user) {
            if (isset($cid)) {
                return view('dashboard.chat')
                ->with('user', $user)
                ->with('to', $this->service->find($cid));
            }
            else {
                return view('dashboard.chat')
                ->with('user', $user);
            }
        }
    }

    public function board(Request $request)
    {
        $user = $request->user();
        if ($user) {
            return view('dashboard.board')
            ->with('user', $user);
        }
    }

    public function history(Request $request)
    {
        $user = $request->user();
        if ($user) {
            return view('dashboard.history')
            ->with('user', $user);
        }
    }

    public function search(Request $request)
    {
        $user = $request->user();

        return view('dashboard.search')->with('user', $user);
    }

    public function upgrade(Request $request)
    {
        $user = $request->user();
        if ($user)
        {
            $log = new \App\Models\LogClickUpgrade();
            $log->user_id = $user->id;
            $log->save();
            return view('dashboard.upgrade')
            ->with('user', $user);
        }
    }

    public function block(Request $request)
    {
        $user = $request->user();
        if ($user)
        {
            return view('dashboard.block')
            ->with('user', $user);
        }
    }

    public function upgradesuccess(Request $request)
    {
        $user = $request->user();
        if ($user) {
            return view('dashboard.upgradesuccess')
            ->with('user', $user);
        }
    }

    public function upgradepay(Request $request)
    {
        $user = $request->user();
        if ($user == null)
        {
            $aid = auth()->id();
            $user = User::findById($aid);
        }
        $payload = $request->all();
        $pool = '';
        $count = 0;
        foreach ($payload as $key => $value){
            $pool .= 'Row '. $count . ' : ' . $key . ', Value : ' . $value . '
';//換行
            $count++;
        }
        $infos = new \App\Models\LogUpgradedInfos();
        $infos->user_id = $user->id;
        $infos->content = $pool;
        $infos->save();
        if (isset($payload['final_result']))
        {
            if(Vip::checkByUserAndTxnId($user->id, $payload['P_CheckSum'])){
                return view('dashboard.upgradesuccess')
                    ->with('user', $user)->withErrors(['升級成功後請勿在本頁面重新整理！']);
            }
            $pool = '';
            $count = 0;
            foreach ($payload as $key => $value){
                $pool .= 'Row '. $count . ' : ' . $key . ', Value : ' . $value . '
';//換行
                $count++;
            }
            $infos = new \App\Models\LogUpgradedInfosWhenGivingPermission();
            $infos->user_id = $user->id;
            $infos->content = $pool;
            $infos->save();
            $this->logService->upgradeLog($payload, $user->id);
            $this->logService->writeLogToDB();
            $this->logService->writeLogToFile();
            Vip::upgrade($user->id, $payload['P_MerchantNumber'], $payload['P_OrderNumber'], $payload['P_Amount'], $payload['P_CheckSum'], 1, 0);
        }

        if ($user) {
            return view('dashboard.upgradesuccess')
            ->with('user', $user)->with('message', 'VIP 升級成功！');
        }
        else redirect('dashboard.upgradesuccess')->with('message', 'VIP 升級成功！');
    }

    public function cancel(Request $request)
    {
        $user = $request->user();
        if ($user) {
            return view('dashboard.cancel')
            ->with('user', $user);
        }
    }

    public function cancelpay(Request $request)
    {
        $payload = $request->all();
        $user = $request->user();

        if ($user) {
            $log = new \App\Models\LogCancelVip();
            $log->user_id = $user->id;
            $log->save();
            if(User::isCorrectAccount($payload['email'], $payload['password'])) {
                $vip = Vip::findById($user->id);
                $this->logService->cancelLog($vip);
                $this->logService->writeLogToDB();
                $this->logService->writeLogToFile();
                Vip::cancel($user->id, 0);
                $data = Vip::where('member_id', $user->id)->where('expiry', '!=', '0000-00-00 00:00:00')->get()->first();
                $date = date('Y年m月d日', strtotime($data->expiry));
                $request->session()->flash('cancel_notice', '您已成功取消VIP付款，下個月起將不再繼續扣款，目前的VIP權限可以維持到'.$date);
                $request->session()->save();
                return redirect('/dashboard')->with('user', $user)->with('message', 'VIP 取消成功！')->with('cancel_notice', '您已成功取消VIP付款，下個月起將不再繼續扣款，目前的VIP權限可以維持到'.$date);
            }
            return back()->with('message', '帳號密碼輸入錯誤');
        }
    }

    public function showCheckAccount(Request $request) {
        $user = $request->user();

        if ($user) {
            return view('auth.checkAccount')->with('user', $user);
        }
    }

    

    /**
     * Check the user is banned or not then show notice page.
     */
    public function banned(Request $request)
    {
        if($user = Auth::user()){
            $banned_users = banned_users::select('*')->where('member_id', \Auth::user()->id)->count();
            if($banned_users > 0){    
                Auth::logout();
                $request->session()->flush();
                return view('errors.User-banned');
            }
            abort(404);
        }
        abort(404);
    }
}
