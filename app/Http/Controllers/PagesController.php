<?php

namespace App\Http\Controllers;

use Auth;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\VipLogService;
use App\Models\Fingerprint;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

    //新版編輯會員資料
    public function profileUpdate_ajax(Request $request, ProfileUpdateRequest $profileUpdateRequest)
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
        $status_data=[];
        if($validator->fails()){
            $status_data =[
                'status' => false,
                'msg' => '請勿使用包含「站長」或「管理員」的字眼做為暱稱！',
            ];
        }else{
            if ($this->service->update(auth()->id(), $request->all())) {
                $status_data =[
                    'status' => true,
                    'msg' => '資料更新成功',
                    'redirect'=>'/dashboard',
                ];
            }else{
                $status_data =[
                    'status' => true,
                    'msg' => '無法更新',
                ];
            }
        }
        if(empty($status_data))
            $status_data=[
                    'status' => true,
                    'msg' => '無法更新',
            ];
        return response()->json($status_data, 200)
                ->header("Cache-Control", "no-cache, no-store, must-revalidate")
                ->header("Pragma", "no-cache")
                ->header("Last-Modified", gmdate("D, d M Y H:i:s")." GMT")
                ->header("Cache-Control", "post-check=0, pre-check=0", false)
                ->header("Expires", "Fri, 01 Jan 1990 00:00:00 GMT");
    }

    public function postBoard(Request $request)
    {
        Board::post(auth()->id(), $request->all()['msg']);
        return back()->with('message', '留言成功!');
    }

    public  function postChatpayEC(Request $request){
        return '1|OK';
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
        if (isset($payload['final_result'])) {
            $targetUserID = substr($payload['P_OrderNumber'], 0, -10);
            if($payload['final_result'] == 1){
                Tip::upgrade($user->id, $targetUserID, $payload['P_CheckSum']);
                Message::post($user->id, $targetUserID, "系統通知: 車馬費邀請");
                if($user->engroup == 1) {
                    // 給男會員訊息
                    Message::post($user->id, $targetUserID, "系統通知: 車馬費邀請\n您已經向 ". User::findById($targetUserID)->name ." 發動車馬費邀請。\n流程如下\n1:網站上進行車馬費邀請\n2:網站上訊息約見(重要，站方判斷約見時間地點，以網站留存訊息為準)\n3:雙方見面\n\n如果雙方在第二步就約見失敗。\n將扣除手續費 288 元後，1500匯入您指定的帳戶。也可以用現金袋或者西聯匯款方式進行。\n(聯繫我們有站方聯絡方式)\n\n若雙方有見面意願，被女方放鴿子。\n站方會參照女方提出的證據，判斷是否將尾款交付女方。", false);
                    Message::post($targetUserID, $user->id, "系統通知: 車馬費邀請\n". $user->name . " 已經向 您 發動車馬費邀請。\n流程如下\n1:網站上進行車馬費邀請\n2:網站上訊息約見(重要，站方判斷約見時間地點，以網站留存訊息為準)\n3:雙方見面(建議約在知名連鎖店丹堤星巴克或者麥當勞之類)\n\n若成功見面男方沒有提出異議，那站方會在發動後 7~14 個工作天\n將 1500 匯入您指定的帳戶。若您不想提供銀行帳戶。\n也可以用現金袋或者西聯匯款方式進行。\n(聯繫我們有站方聯絡方式)\n\n若男方提出當天女方未到場的爭議。請您提出當天消費的發票證明之。\n所以請約在知名連鎖店以利站方驗證。\n", false);
                }
                else if($user->engroup == 2) {
                    // 給女會員訊息
                    // Message::post($user->id, $payload['P_OrderNumber'], "系統通知: 車馬費邀請\n". $user->name . " 已經向 您 發動車馬費邀請。\n流程如下\n1:網站上進行車馬費邀請\n2:網站上訊息約見(重要，站方判斷約見時間地點，以網站留存訊息為準)\n3:雙方見面(建議約在知名連鎖店丹堤星巴克或者麥當勞之類)\n\n若成功見面男方沒有提出異議，那站方會在發動後 7~14 個工作天\n將 1500 匯入您指定的帳戶。若您不想提供銀行帳戶。\n也可以用現金袋或者西聯匯款方式進行。\n(聯繫我們有站方聯絡方式)\n\n若男方提出當天女方未到場的爭議。請您提出當天消費的發票證明之。\n所以請約在知名連鎖店以利站方驗證。\n");
                }
                //return redirect('/dashboard/chat/' . $payload['P_OrderNumber'] . '?invite=success');
                return redirect()->route('chatWithUser', [ 'id' => $targetUserID ])->with('message', '車馬費已成功發送！');
            }
            else{
                return redirect()->route('chatWithUser', [ 'id' => $targetUserID ])->withErrors(['交易系統回傳結果顯示交易未成功，車馬費無法發送！請檢查信用卡資訊。']);
            }
        }
        else{
            return redirect()->route('chatView')->withErrors(['交易系統沒有回傳資料，車馬費無法發送！請檢查網路是否順暢。']);
        }
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

    public function upgradepayLog(Request $request) {
        $filename = 'api_datalogger_' . Carbon::now()->format('Y-m-d') . '.log';
        $dataToLog  = 'Time: '   . Carbon::now()->toDateTimeString() . "\n";
        $dataToLog .= 'IP Address: ' . $request->ip() . "\n";
        $dataToLog .= 'Content: '  . $request->getContent() . "\n";
        if (\File::append( storage_path('logs' . DIRECTORY_SEPARATOR . $filename), $dataToLog . "\n" . str_repeat("=", 20) . "\n\n")) {
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
     * cd1 cd2 ts1 ts2
     */
    public function cd_1() {
        //$user = $request->user();
        $imgUserM = User::select('users.name', 'users.title', 'user_meta.pic')
            ->join('user_meta', 'users.id', '=', 'user_meta.user_id')
            ->whereNotNull('user_meta.pic')
            ->where('engroup', 1)->inRandomorder()->take(3)->get();
        $imgUserF = User::select('users.name', 'users.title', 'user_meta.pic')
            ->join('user_meta', 'users.id', '=', 'user_meta.user_id')
            ->whereNotNull('user_meta.pic')
            ->where('engroup', 2)->inRandomorder()->take(3)->get();
        return view('cd1.welcome')
            ->with('imgUserM', $imgUserM)
            ->with('imgUserF', $imgUserF);

    }

    public function cd_2() {
        $imgUserM = User::select('users.name', 'users.title', 'user_meta.pic')
            ->join('user_meta', 'users.id', '=', 'user_meta.user_id')
            ->whereNotNull('user_meta.pic')
            ->where('engroup', 1)->inRandomorder()->take(3)->get();
        $imgUserF = User::select('users.name', 'users.title', 'user_meta.pic')
            ->join('user_meta', 'users.id', '=', 'user_meta.user_id')
            ->whereNotNull('user_meta.pic')
            ->where('engroup', 2)->inRandomorder()->take(3)->get();
        return view('cd2.welcome')
            ->with('imgUserM', $imgUserM)
            ->with('imgUserF', $imgUserF);
    }

    public function ts_1() {
        $imgUserM = User::select('users.name', 'users.title', 'user_meta.pic')
            ->join('user_meta', 'users.id', '=', 'user_meta.user_id')
            ->whereNotNull('user_meta.pic')
            ->where('engroup', 1)->inRandomorder()->take(3)->get();

        $imgUserF = User::select('users.name', 'users.title', 'user_meta.pic')
            ->join('user_meta', 'users.id', '=', 'user_meta.user_id')
            ->whereNotNull('user_meta.pic')
            ->where('engroup', 2)->inRandomorder()->take(3)->get();

        $infoM = User::select('users.id')
            ->join('user_meta', 'users.id', '=', 'user_meta.user_id')
            ->whereNotNull('user_meta.pic')
            ->whereNotNull('user_meta.about')
            ->whereNotNull('user_meta.birthdate')
            ->whereNotNull('user_meta.city')
            ->whereNotNull('user_meta.occupation')
            ->where('engroup', 1)->inRandomorder()->take(1)->first();

        $infoF = User::select('users.id')
            ->join('user_meta', 'users.id', '=', 'user_meta.user_id')
            ->whereNotNull('user_meta.pic')
            ->whereNotNull('user_meta.about')
            ->whereNotNull('user_meta.birthdate')
            ->whereNotNull('user_meta.city')
            ->whereNotNull('user_meta.occupation')
            ->where('engroup', 2)->inRandomorder()->take(1)->first();

        $infoF_d = User::where('id',$infoF->id)->first();
        $infoM_d = User::where('id',$infoM->id)->first();

        return view('ts1.welcome')
            ->with('imgUserM', $imgUserM)
            ->with('imgUserF', $imgUserF)
            ->with('infoM_d', $infoM_d)
            ->with('infoF_d', $infoF_d);
    }

    public function ts_2() {
        $imgUserM = User::select('users.name', 'users.title', 'user_meta.pic')
            ->join('user_meta', 'users.id', '=', 'user_meta.user_id')
            ->whereNotNull('user_meta.pic')
            ->where('engroup', 1)->inRandomorder()->take(3)->get();
        $imgUserF = User::select('users.name', 'users.title', 'user_meta.pic')
            ->join('user_meta', 'users.id', '=', 'user_meta.user_id')
            ->whereNotNull('user_meta.pic')
            ->where('engroup', 2)->inRandomorder()->take(3)->get();
        return view('ts2.welcome')
            ->with('imgUserM', $imgUserM)
            ->with('imgUserF', $imgUserF);
    }

    /**
     * Homepage
     *
     * @return \Illuminate\Http\Response
     */
    public function home(Request $request)
    {
        $user = $request->user();
        $imgUserM = User::select('users.name', 'users.title', 'user_meta.pic')
            ->join('user_meta', 'users.id', '=', 'user_meta.user_id')
            ->whereNotNull('user_meta.pic')
            ->where('engroup', 1)->inRandomorder()->take(3)->get();
        $imgUserF = User::select('users.name', 'users.title', 'user_meta.pic')
            ->join('user_meta', 'users.id', '=', 'user_meta.user_id')
            ->whereNotNull('user_meta.pic')
            ->where('engroup', 2)->inRandomorder()->take(3)->get();
        return view('new.welcome')
            ->with('user', $user)
            ->with('imgUserM', $imgUserM)
            ->with('imgUserF', $imgUserF);
    }

    public function privacy(Request $request)
    {
        $user = $request->user();
        return view('privacy')->with('user', $user);
    }

    //站長開講
    public function notification(Request $request)
    {
        $user = $request->user();
        return view('new/notification')->with('user', $user);
    }

    //網站使用
    public function feature(Request $request)
    {
        $user = $request->user();
        return view('new/feature')->with('user', $user);
    }

    //使用條款
    public function terms(Request $request)
    {
        $user = $request->user();
        return view('new/terms')->with('user', $user);
    }

    public function message(Request $request)
    {
        $user = $request->user();
        return view('message')->with('user', $user);
    }

    public function contact(Request $request)
    {
        $user = $request->user();
        return view('new/contact')->with('user', $user);
    }

    public function about(Request $request)
    {
        $user = $request->user();
        return view('about')->with('user', $user);
    }

    public function browse(Request $request)
    {
        $user = $request->user();
        return view('new.browse')->with('user', $user);
    }

    /**
     * Dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard2(Request $request)
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
        if($year=='1970'){
            $year=$month=$day='';
        }
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
            return view('new.dashboard')
                ->with('user', $user)
                ->with('tabName', $tabName)
                ->with('cur', $user)
                ->with('year', $year)
                ->with('month', $month)
                ->with('day', $day);
        }
    }

    public function dashboard_img(Request $request)
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
        if($year=='1970'){
            $year=$month=$day='';
        }
        if ($user) {
            $cancel_notice = $request->session()->get('cancel_notice');
            $message = $request->session()->get('message');
            if(isset($cancel_notice)){
                return view('dashboar_img')
                    ->with('user', $user)
                    ->with('tabName', $tabName)
                    ->with('cur', $user)
                    ->with('year', $year)
                    ->with('month', $month)
                    ->with('day', $day)
                    ->with('message', $message)
                    ->with('cancel_notice', $cancel_notice);
            }
            if($user->engroup==1){
                return view('new.dashboard_img')
                ->with('user', $user)
                ->with('tabName', $tabName)
                ->with('cur', $user)
                ->with('year', $year)
                ->with('month', $month)
                ->with('day', $day);
            }else{
                return view('new.dashboard_img')
                ->with('user', $user)
                ->with('tabName', $tabName)
                ->with('cur', $user)
                ->with('year', $year)
                ->with('month', $month)
                ->with('day', $day);
            }
        }
    }

    public function viewuser(Request $request, $uid = -1)
    {
        $user = $request->user();

        if (isset($user) && isset($uid)) {
            $targetUser = User::where('id', $uid)->get()->first();
            if(!isset($targetUser)){
                return view('errors.nodata');
            }
            if ($user->id != $uid) {
                Visited::visit($user->id, $uid);
            }

            $checkRecommendedUser['description'] = null;
            $checkRecommendedUser['stars'] = null;
            $checkRecommendedUser['background'] = null;
            $checkRecommendedUser['title'] = null;
            $checkRecommendedUser['button'] = null;
            $checkRecommendedUser['height'] = null;
            try{
                $checkRecommendedUser = $this->service->checkRecommendedUser($targetUser);
                $tracker = $checkRecommendedUser['description'];
            }
            catch (\Exception $e){
                Log::info('Current URL: ' . url()->current());
                Log::debug('checkRecommendedUser() failed, $targetUser: '. $targetUser);
            }
            finally{
                return view('dashboard')
                    ->with('user', $user)
                    ->with('cur', $this->service->find($uid))
                    ->with('description', $checkRecommendedUser['description'])
                    ->with('stars', $checkRecommendedUser['stars'])
                    ->with('background', $checkRecommendedUser['background'])
                    ->with('title', $checkRecommendedUser['title'])
                    ->with('button', $checkRecommendedUser['button'])
                    ->with('height', $checkRecommendedUser['height']);
            }
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
        if(empty($this->customTrim($request->content))){
            $user = $request->user();
            return view('dashboard.reportUser', [ 'aid' => $request->aid, 'uid' => $request->uid, 'user' =>  $user])->withErrors(['檢舉失敗，請填寫理由。']);
        }
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
            if(empty($this->customTrim($request->content))){
                return back()->withErrors(['檢舉失敗，請填寫理由。']);
            }
            ReportedAvatar::report($request->reporter_id, $request->reported_user_id, $request->content);
        }
        if($request->pic){
            if(empty($this->customTrim($request->content))){
                return back()->withErrors(['檢舉失敗，請填寫理由。']);
            }
            ReportedPic::report($request->reporter_id, $request->reported_pic_id, $request->content);
        }
        return redirect('/user/view/'.$request->reported_user_id)->with('message', '檢舉成功');
    }

    private function customTrim($str)
    {
        $search = array(" ","　","\n","\r","\t");
        $replace = array("","","","","");
        return str_replace($search, $replace, $str);
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

    public function removeFav_ajax(Request $request)
    {
        if ($request->userId !== $request->favUserId)
        {
            MemberFav::remove($request->userId, $request->favUserId);
            return response()->json(array(
                'status' => true,
                'msg' => '移除成功',
            ), 200)
                ->header("Cache-Control", "no-cache, no-store, must-revalidate")
                ->header("Pragma", "no-cache")
                ->header("Last-Modified", gmdate("D, d M Y H:i:s")." GMT")
                ->header("Cache-Control", "post-check=0, pre-check=0", false)
                ->header("Expires", "Fri, 01 Jan 1990 00:00:00 GMT");
        }
        return back()->with('message', '移除成功');
    }

    public function fav(Request $request)
    {
        $user = $request->user();
        //$visitors = \App\Models\MemberFav::findBySelf($user->id);
        //dd($visitors);
        //$favUser = \App\Models\User::findById($visitor->member_fav_id);
        if ($user) {
            return view('new.dashboard.fav')
            ->with('user', $user);
        }
    }

    public function fav_ajax(Request $request)
    {
        $user_id = $request->uid;
        $data = \App\Models\MemberFav::showFav($user_id);
        if (isset($data)) {
            return response()->json(array(
                'status' => 1,
                'msg' => $data,
            ), 200)
                ->header("Cache-Control", "no-cache, no-store, must-revalidate")
                ->header("Pragma", "no-cache")
                ->header("Last-Modified", gmdate("D, d M Y H:i:s")." GMT")
                ->header("Cache-Control", "post-check=0, pre-check=0", false)
                ->header("Expires", "Fri, 01 Jan 1990 00:00:00 GMT");
        } else {
            return response()->json(array(
                'status' => 2,
                'msg' => 'fail',
            ), 500);
        }
    }

    public function fav2(Request $request)
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
        $m_time = '';
        if (isset($user)) {
            $isVip = $user->isVip();
            if (isset($cid)) {
                if(!$user->isVip() && $user->engroup == 1){
                    $m_time = Message::select('created_at')->
                    where('from_id', $user->id)->
                    orderBy('created_at', 'desc')->first();
                    if(isset($m_time)){
                        $m_time = $m_time->created_at;
                    }
                }
                return view('dashboard.chat')
                    ->with('user', $user)
                    ->with('to', $this->service->find($cid))
                    ->with('m_time', $m_time)
                    ->with('isVip', $isVip);
            }
            else {
                return view('dashboard.chat')
                    ->with('user', $user)
                    ->with('m_time', $m_time)
                    ->with('isVip', $isVip);
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

    public function upgrade_ec(Request $request)
    {
        $user = $request->user();
        if ($user)
        {
            $log = new \App\Models\LogClickUpgrade();
            $log->user_id = $user->id;
            $log->save();
            return view('dashboard.upgrade_EC')
                ->with('user', $user);
        }
    }

    public function upgrade_esafe(Request $request)
    {
        $user = $request->user();
        if ($user)
        {
            $log = new \App\Models\LogClickUpgrade();
            $log->user_id = $user->id;
            $log->save();
            return view('dashboard.upgrade_Esafe')
                ->with('user', $user);
        }
    }

    public function block(Request $request)
    {
        $user = $request->user();
        if ($user)
        {
            // blocked by user->id
            $blocks = \App\Models\Blocked::where('member_id', $user->id)->paginate(15);

            $usersInfo = array();
            foreach($blocks as $blockUser){
                $id = $blockUser->blocked_id;
                $usersInfo[$id] = User::findById($id);
            }
            return view('new.dashboard.block')
            ->with('blocks', $blocks)
            ->with('users', $usersInfo)
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
            if($payload['final_result'] == 1){
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
                return view('dashboard.upgradesuccess')
                    ->with('user', $user)->with('message', 'VIP 升級成功！');
            }
            else{
                return view('dashboard.upgradefailed')
                    ->with('user', $user)->withErrors(['交易系統回傳結果顯示交易未成功，VIP 升級失敗！請檢查信用卡資訊。']);
            }
        }
        else{
            return view('dashboard.upgradefailed')
                ->with('user', $user)->withErrors(['交易系統沒有回傳資料，VIP 升級失敗！請檢查網路是否順暢。']);
        }
    }

    public function upgradepayEC(Request $request) {
        return ['1', 'OK'];
    }

    public function receive_esafe(Request $request)
    {
        $user = $request->user();
        if ($user == null)
        {
            $aid = auth()->id();
            if(is_null($aid)){
                $aid = $request['UserNo'];
            }
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
        if (isset($payload['errcode']))
        {
            if(Vip::checkByUserAndTxnId($user->id, $payload['ChkValue'])){
                return view('dashboard.upgradesuccess')
                    ->with('user', $user)->withErrors(['升級成功後請勿在本頁面重新整理！']);
            }
            if($payload['errcode'] == '00'){
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
                $this->logService->upgradeLog_esafe($payload, $user->id);
                $this->logService->writeLogToDB();
                $this->logService->writeLogToFile();
                $transactionType = '';

                if(isset($payload['Card_Type']) && !is_null($payload['Card_Type'])){
                    $transactionType = 'CreditCard';
                }elseif(isset($payload['BarcodeA']) && !is_null($payload['BarcodeA'])){
                    $transactionType = 'Barcode';
                }elseif(isset($payload['BarcodeA']) && !is_null($payload['BarcodeA'])){
                    $transactionType = 'Barcode';
                }else{
                    $transactionType = 'WebATM';
                }                                

                Vip::upgrade($user->id, $payload['web'], $payload['buysafeno'], $payload['MN'], $payload['ChkValue'], 1, 0,$transactionType);

                return view('dashboard.upgradesuccess')
                    ->with('user', $user)->with('message', 'VIP 升級成功！');
            }
            else{
                return view('dashboard.upgradefailed')
                    ->with('user', $user)->withErrors(['交易系統回傳結果顯示交易未成功，VIP 升級失敗！請檢查信用卡資訊。']);
            }
        }
        else{
            return view('dashboard.upgradefailed')
                ->with('user', $user)->withErrors(['交易系統沒有回傳資料，VIP 升級失敗！請檢查網路是否順暢。']);
        }
    }

    public function repaid_esafe(Request $request)
    {
        $infos = new \App\Models\VipStore();
        $infos->user_id = $request->UserNo;
        $infos->buysafeno = $request->buysafeno;
        $infos->store_code = $request->Td;
        $infos->ChkValue = $request->ChkValue;
        if(isset($request->BarcodeA)){
            if(!is_null($request->BarcodeA)){
                $infos->type = 'Barcode';
            }
        }
        if(isset($request->paycode)){
            if(!is_null($request->paycode)){
                $infos->type = 'paycode';
            }
        }

        
        if(!$infos->checkByUser($request->UserNo,$request->ChkValue)){
            $infos->save();
        }

        $user = User::findById($infos->user_id);
        
        
        return view('dashboard.esafepaystroe')
                    ->with('user', $user)->with('message', '請儘速至超商繳款');
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
            if(Auth::attempt(array('email' => $payload['email'], 'password' => $payload['password']))){
                $vip = Vip::findById($user->id);
                $this->logService->cancelLog($vip);
                $this->logService->writeLogToDB();
                $file = $this->logService->writeLogToFile();
                if( strpos(\Storage::disk('local')->get($file[0]), $file[1]) !== false) {
                    Vip::cancel($user->id, 0);
                    $data = Vip::where('member_id', $user->id)->where('expiry', '!=', '0000-00-00 00:00:00')->get()->first();
                    $date = date('Y年m月d日', strtotime($data->expiry));
                    $request->session()->flash('cancel_notice', '您已成功取消VIP付款，下個月起將不再繼續扣款，目前的VIP權限可以維持到'.$date);
                    $request->session()->save();
                    return redirect('/dashboard')->with('user', $user)->with('message', 'VIP 取消成功！')->with('cancel_notice', '您已成功取消VIP付款，下個月起將不再繼續扣款，目前的VIP權限可以維持到'.$date);
                }
                else{
                    $log = new \App\Models\LogCancelVipFailed();
                    $log->user_id = $user->id;
                    $log->reason = 'File saving failed.';
                    $log->save();
                    return redirect('/dashboard')->with('user', $user)->withErrors(['VIP 取消失敗！'])->with('cancel_notice', '本次VIP取消資訊沒有成功寫入，請再試一次。');
                }
            }
            else{
                $log = new \App\Models\LogCancelVipFailed();
                $log->user_id = $user->id;
                $log->save();
                return back()->with('message', '帳號密碼輸入錯誤');
            }
        }
        else{
            Log::error('User not found while canceling VIP.');
        }
    }

    public function showCheckAccount(Request $request) {
        $user = $request->user();
        if(!$user->isVip()){
            return back()->withErrors(['很抱歉，您目前還不是本站VIP，因此無法執行這個步驟。']);
        }
        else if($user->isFreeVip()){
            return back()->withErrors(['很抱歉，由於您是免費VIP，因此無法執行這個步驟。']);
        }
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

    // 公告封鎖名單
    public function showWebAnnouncement(Request $request) {
        $user = $request->user();
        $start = \Carbon\Carbon::now()->subDays(30)->toDateTimeString();
        $end = \Carbon\Carbon::now()->toDateTimeString();
        $userBanned = banned_users::select('users.name','banned_users.*')
                    ->whereBetween('banned_users.created_at',[($start),($end)])
                    ->join('users','banned_users.member_id','=','users.id')
                    ->orderBy('banned_users.created_at','asc')->get();
        foreach($userBanned as $userData){
            if(mb_strlen(trim($userData['name']),"utf-8") <= 3){
                $userData['name'] = (mb_substr($userData['name'],0 ,1,"utf-8").'***');
            }else{
                $userData['name'] = (mb_substr($userData['name'],0 ,3,"utf-8").'***');
            }
        }
        
        return view('dashboard.adminannouncement_web')
                ->with('user',$user)
                ->with('users', $userBanned);
    }
	
    public function showAnnouncement(Request $request){
        return view('new.dashboard.announcement')
                ->with('user', $request->user());
    }
    
	public function mem_member()
    {
        $uri = $request->segments();
        $user_id = $uri[2];
        $mid = $request->user()->id ?? 689;

        // $mid = isset($_SESSION['user_id'])??689;
        $user_id = $user_id;
        $user = User::selectraw('*')->join('user_meta', 'user_meta.user_id','=','users.id')->where('users.id', $user_id)->first();

        /*七天前*/
        $date = date('Y-m-d H:m:s', strtotime('-7 days'));

        /*車馬費邀請次數*/
        $tip_count = Tip::where('to_id', $user_id)->get()->count();

        /*收藏會員次數*/
        $fav_count = MemberFav::where('member_id', $user_id)->get()->count();
        /*被收藏次數*/
        $be_fav_count = MemberFav::where('member_fav_id', $user_id)->get()->count();

        /*是否封鎖我*/
        $is_block_mid = Blocked::where('blocked_id', $mid)->where('member_id', $user_id)->count()==1?'是':'否';
        /*是否看過我*/
        $is_visit_mid = Visited::where('visited_id', $mid)->where('member_id', $user_id)->count()==1?'是':'否';

        /*瀏覽其他會員次數*/
        $visit_other_count  = Visited::where('member_id', $user_id)->count();
        /*被瀏覽次數*/
        $be_visit_other_count  = Visited::where('visited_id', $user_id)->count();
        /*過去7天被瀏覽次數*/
        $be_visit_other_count_7  = Visited::where('visited_id', $user_id)->where('created_at', '>=', $date)->count();

        /*發信次數*/
        $message_count = Message::where('from_id', $user_id)->count();

        $message_count_7 = Message::where('from_id', $user_id)->where('created_at', '>=', $date)->count();

        $data = array(
            'tip_count'=> $tip_count,
            'fav_count'=> $fav_count,
            'be_fav_count'=> $be_fav_count,
            'is_vip' => 0,
            'is_block_mid' => $is_block_mid,
            'is_visit_mid' => $is_visit_mid,
            'visit_other_count' => $visit_other_count,
            'be_visit_other_count'=>$be_visit_other_count,
            'be_visit_other_count_7'=>$be_visit_other_count_7,
            'message_count' => $message_count,
            'message_count_7' => $message_count_7,
        );
        return view('/new/mem_member', $data)
                ->with('user', $user);
    }
    public function mem_search()
    {
        $users = User::selectraw("*")->join('user_meta', 'user_meta.user_id','=','users.id')->get();

        // $City = "select * from City where State=0";
        // $City_rs = mysql_query($City);
        $data['city'] = DB::table('city')->get()->toArray();

        return view('new.mem_search', $data);
    }
    public function town_ajax(Request $request)
    {
        $r = $request->post();

        // $Town = "select * from Town where CNo='" . $_POST["CNo"] . "'";
        $Town_rs = DB::table('town')->where("CNo", $r['CNo'])->get()->toArray();

        $Town_num = count($Town_rs);
        if ($Town_num > 0) {//縣市編號帶入後如果有資料存在顯示底下區域內容回傳
            echo "<option value=''>選擇鄉鎮</option>";
                foreach($Town_rs as $Town_rows){
                    echo "<option value='" . $Town_rows->AutoNo . "'>" . $Town_rows->Name . "</option>";
                }

        } else {//縣市編號帶入後如果有資料存在顯示底下內容回傳
            echo "<option value=''>選擇鄉鎮</option>";
        }
    }

    public function mem_updatevip()
    {
        return view('new.mem_updatevip');
    }
    public function women_updatevip()
    {
        return view('new.women_updatevip');
    }
    public function women_search()
    {
        return view('new.women_search');
    }

    public function searchData(Request $request)
    {
        $r = (object)$request->post();

        $page = $request->post('page')??1;
        $perPage = 8;
        $skip = $page*$perPage;
        // dd($page, $skip);
        $user = User::selectraw('*')->join('user_meta', 'user_meta.user_id','=','users.id')->join('member_pic', 'member_pic.member_id', '=', 'user_meta.user_id')->groupBy('users.id')->skip($skip)->take($perPage);
        if($r->city!='-1'){
            $user->orWhere('city', $r->city);
        }
        if($r->area!='-1'){
            $user->orWhere('area', $r->area);
        }
        if($r->age_pre!='-1'){
            $user->where('last_login','>', $r->age_pre);
        }
        if($r->age_next!='-1'){
            $user->where('last_login','<', $r->age_next);
        }
        if($r->budget!='-1'){
            $user->orWhere('budget', $r->budget);
        }
        if($r->smoking!='-1'){
            $user->orWhere('smoking', $r->smoking);
        }
        if($r->body!='-1'){
            $user->orWhere('body', $r->body);
        }
        if($r->cup!='-1'){
            $user->orWhere('cup', $r->cup);
        }
        if($r->marriage!='-1'){
            $user->orWhere('marriage', $r->marriage);
        }
        if($r->body!='-1'){
            $user->orWhere('body', $r->body);
        }
        if($r->drinking!='-1'){
            $user->orWhere('drinking',$r->drinking);
        }
        if($r->search_sort!='-1'){
            $user->orWhere('search_sort', $r->search_sort);
        }

        $user = $user->get();
        $data = array(
            'page'=>$page,
            'user'=>$user,
        );
        echo json_encode($data);
    }

    public function updateMemberData(Request $request){

        $r = (array)$request->post('data');
        foreach($r as $r){
            $data[$r['name']] = $r['value'];
        }

        $users = User::selectraw("*")->join('user_meta', 'user_meta.user_id','=','users.id')->where('user_meta.user_id', $data['id']);
        $users->timestamps = false;
        $users->update($data);

       
    }
}
