<?php

namespace App\Http\Controllers;

use App\Jobs\CheckECpay;
use App\Jobs\CheckECpayForValueAddedService;
use App\Models\AccountStatusLog;
use App\Models\AdminAnnounce;
use App\Models\AdminCommonText;
use App\Models\AnnouncementRead;
use App\Models\BannedUsersImplicitly;
use App\Models\CustomFingerPrint;
use App\Models\Evaluation;
use App\Models\EvaluationPic;
use App\Models\ForumManage;
use App\Models\ForumPosts;
use App\Models\hideOnlineData;
use App\Models\LogUserLogin;
use App\Models\Message_new;
use App\Models\MessageBoard;
use App\Models\MessageBoardPic;
use App\Models\Forum;
use App\Models\ReportedMessageBoard;
use App\Models\SimpleTables\warned_users;
use App\Notifications\BannedUserImplicitly;
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
use App\Models\BasicSetting;
use App\Models\Posts;
use App\Models\UserMeta;
use App\Models\MemberPic;
use App\Models\SetAutoBan;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\FormFilterRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\SimpleTables\banned_users;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
use Session;
use App\Notifications\AccountConsign;
use App\Models\ValueAddedService;
use App\Repositories\SuspiciousRepository;
use App\Services\AdminService;
use App\Models\LogFreeVipPicAct;
use App\Models\UserTinySetting;
use App\Http\Controllers\Admin\UserController;
use App\Models\SimpleTables\short_message;
use App\Models\LogAdvAuthApi;
use Illuminate\Support\Facades\Http;
use App\Services\SearchIgnoreService;

class PagesController extends BaseController
{
    protected $suspiciousRepo = null;
    public function __construct(UserService $userService, VipLogService $logService, SuspiciousRepository $suspiciousRepo)
    {
        parent::__construct();
        $this->service = $userService;
        $this->logService = $logService;
        $this->suspiciousRepo = $suspiciousRepo;
        $this->middleware('throttle:100,1');
        $this->middleware('pseudoThrottle:80,1');
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
            'tattoo_part'=> ['required_with:tattoo_range'],
            'tattoo_range'=> ['required_with:tattoo_part']        
        ];
        $messages = [
            'not_contains'  => '請勿使用包含「站長」或「管理員」的字眼做為暱稱！',
            'tattoo_part' => '請選擇刺青位置',
            'tattoo_range' => '請選擇刺青面積'            
        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        if($validator->fails()){
            return redirect('/dashboard')->withErrors(['請勿使用包含「站長」或「管理員」的字眼做為暱稱！']);
        }
        else{
            if ($this->service->update(auth()->id(), $request->all())) {

                //更新完後判斷是否需備自動封鎖
                SetAutoBan::auto_ban(auth()->id());
                
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
            'tattoo_part'=> ['required_with:tattoo_range'],
            'tattoo_range'=> ['required_with:tattoo_part']
        ];
        $messages = [
            'not_contains'  => '請勿使用包含「站長」或「管理員」的字眼做為暱稱！',
            'tattoo_part' => '請選擇刺青位置',
            'tattoo_range' => '請選擇刺青面積'
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

                //更新完後判斷是否需備自動封鎖
                SetAutoBan::auto_ban(auth()->id());

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

    public function postValueAddedService(Request $request) : string{
        return '1|OK';
    }

    public  function postMobileVerifyPayEC(Request $request){
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
                // Message::post($user->id, $targetUserID, "系統通知: 車馬費邀請");
                if($user->engroup == 1) {
                    //取資料庫並替換名字
                    $tip_msg1 = AdminCommonText::getCommonText(1);//id2給男會員訊息                    
                    $tip_msg1 = str_replace('NAME', User::findById($targetUserID)->name, $tip_msg1);
                    $tip_msg1 = str_replace('|$report|', User::findById($targetUserID)->name, $tip_msg1);
                    $tip_msg1 = str_replace('LINE_ICON', AdminService::$line_icon_html, $tip_msg1); 
                    $tip_msg1 = str_replace('|$lineIcon|', AdminService::$line_icon_html, $tip_msg1);
                    $tip_msg1 = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $tip_msg1);
                    $tip_msg1 = str_replace('|$reportTime|', date("Y-m-d H:i:s"), $tip_msg1);
                    $tip_msg1 = str_replace('NOW_TIME', date("Y-m-d H:i:s"), $tip_msg1); 
                    $tip_msg2 = AdminCommonText::getCommonText(2);//id3給女會員訊息                    
                    $tip_msg2 = str_replace('NAME', $user->name, $tip_msg2);
                    $tip_msg2 = str_replace('|$report|', $user->name, $tip_msg2);
                    $tip_msg2 = str_replace('LINE_ICON', AdminService::$line_icon_html, $tip_msg2); 
                    $tip_msg2 = str_replace('|$lineIcon|', AdminService::$line_icon_html, $tip_msg2);
                    $tip_msg2 = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $tip_msg2);
                    $tip_msg2 = str_replace('|$reportTime|', date("Y-m-d H:i:s"), $tip_msg2);
                    $tip_msg2 = str_replace('NOW_TIME', date("Y-m-d H:i:s"), $tip_msg2);  
                    // 給男會員訊息（需在發送方的訊息框看到，所以是由男會員發送）
                    Message::post($user->id, $targetUserID, $tip_msg1, false, 1);
                    // 給女會員訊息（需在接收方的訊息框看到，所以是由女會員發送）
                    Message::post($targetUserID, $user->id, $tip_msg2, false, 1);
                    // 給男會員訊息
                    // Message::post($user->id, $targetUserID, "系統通知: 車馬費邀請\n您已經向 ". User::findById($targetUserID)->name ." 發動車馬費邀請。\n流程如下\n1:網站上進行車馬費邀請\n2:網站上訊息約見(重要，站方判斷約見時間地點，以網站留存訊息為準)\n3:雙方見面\n\n如果雙方在第二步就約見失敗。\n將扣除手續費 288 元後，1500匯入您指定的帳戶。也可以用現金袋或者西聯匯款方式進行。\n(聯繫我們有站方聯絡方式)\n\n若雙方有見面意願，被女方放鴿子。\n站方會參照女方提出的證據，判斷是否將尾款交付女方。", false);
                    // Message::post($targetUserID, $user->id, "系統通知: 車馬費邀請\n". $user->name . " 已經向 您 發動車馬費邀請。\n流程如下\n1:網站上進行車馬費邀請\n2:網站上訊息約見(重要，站方判斷約見時間地點，以網站留存訊息為準)\n3:雙方見面(建議約在知名連鎖店丹堤星巴克或者麥當勞之類)\n\n若成功見面男方沒有提出異議，那站方會在發動後 7~14 個工作天\n將 1500 匯入您指定的帳戶。若您不想提供銀行帳戶。\n也可以用現金袋或者西聯匯款方式進行。\n(聯繫我們有站方聯絡方式)\n\n若男方提出當天女方未到場的爭議。請您提出當天消費的發票證明之。\n所以請約在知名連鎖店以利站方驗證。\n", false);
                }
                else if($user->engroup == 2) {
                    // 給女會員訊息
                    // Message::post($user->id, $payload['P_OrderNumber'], "系統通知: 車馬費邀請\n". $user->name . " 已經向 您 發動車馬費邀請。\n流程如下\n1:網站上進行車馬費邀請\n2:網站上訊息約見(重要，站方判斷約見時間地點，以網站留存訊息為準)\n3:雙方見面(建議約在知名連鎖店丹堤星巴克或者麥當勞之類)\n\n若成功見面男方沒有提出異議，那站方會在發動後 7~14 個工作天\n將 1500 匯入您指定的帳戶。若您不想提供銀行帳戶。\n也可以用現金袋或者西聯匯款方式進行。\n(聯繫我們有站方聯絡方式)\n\n若男方提出當天女方未到場的爭議。請您提出當天消費的發票證明之。\n所以請約在知名連鎖店以利站方驗證。\n");
                }
                //return redirect('/dashboard/chat/' . $payload['P_OrderNumber'] . '?invite=success');
                return redirect()->route('chat2WithUser', [ 'id' => $targetUserID ])->with('message', '車馬費已成功發送！');
            }
            else{
                return redirect()->route('chat2WithUser', [ 'id' => $targetUserID ])->withErrors(['交易系統回傳結果顯示交易未成功，車馬費無法發送！請檢查信用卡資訊。']);
            }
        }
        else{
            return redirect()->route('chat2View')->withErrors(['交易系統沒有回傳資料，車馬費無法發送！請檢查網路是否順暢。']);
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
    public function fingerprint(){
        return view('fingerprint');
    }

    public function saveFingerprint(Request $request){
        $fingerprintValue = $request->fingerprintValue;
        $user = User::findByEmail($request->email);
        if(Fingerprint::isExist(['fingerprintValue'=>$fingerprintValue])){
            Log::info('User id: ' . isset($user) ? $user->id : null . ', fingerprint value: ' . $fingerprintValue);
            return '找到相符合資料';
        }
        else{
            $fingerprintValue = Hash::make($fingerprintValue . $request->ip());
            $data = [
                'user_id' => isset($user) ? $user->id : null,
                'ip' => request()->ip(),
                'fingerprintValue' => $fingerprintValue,
                'browser_name' => $request->browser_name,
                'browser_version' => $request->browser_version,
                'os_name' => $request->os_name,
                'os_version' => $request->os_version,
                'timezone' => $request->timezone,
                'plugins' => $request->plugins,
                'language' => $request->language
            ];

            Fingerprint::insert($data);
            return '已新增至資料庫';
        }
    }

    public function saveFingerprintPOST($payload){
        $fingerprintValue = $payload['fingerprintValue'];
        $user = User::findByEmail($payload['email']);
        if(Fingerprint::isExist(['fingerprintValue'=>$fingerprintValue])){
            Log::info('User id: ' . isset($user) ? $user->id : null . ', fingerprint value: ' . $fingerprintValue);
            return '找到相符合資料';
        }
        else{
            $fingerprintValue = Hash::make($fingerprintValue . $payload['ip']);
            $data = [
                'user_id' => isset($user) ? $user->id : null,
                'ip' => $payload['ip'],
                'fingerprintValue' => $fingerprintValue,
                'browser_name' => $payload['browser_name'],
                'browser_version' => $payload['browser_version'],
                'os_name' => $payload['os_name'],
                'os_version' => $payload['os_version'],
                'timezone' => $payload['timezone'],
                'plugins' => $payload['plugins'],
                'language' => $payload['language']
            ];

            Fingerprint::insert($data);
            return '已新增至資料庫';
        }
    }

    /**
     * Homepage
     *
     * @return \Illuminate\Http\Response
     */
    public function home(Request $request)
    {
        \Session::forget('is_remind_puppet');
        \Session::forget('filled_data');        
        // (SELECT CEIL(RAND() * (SELECT MAX(id) FROM random)) AS id) as u2
        $imgUserM = User::select('users.name', 'users.title', 'user_meta.pic')
            ->join(\DB::raw("(SELECT CEIL(RAND() * (SELECT MAX(id) FROM users)) AS id) as u2"), function($join){
                $join->on('users.id', '>', 'u2.id');
            })
            ->join('user_meta', 'users.id', '=', 'user_meta.user_id')
            ->leftJoin('banned_users as b1', 'b1.member_id', '=', 'users.id')
            ->leftJoin('banned_users as b2', 'b2.member_id', '=', 'users.id')
            ->leftJoin('banned_users_implicitly as b3', 'b3.target', '=', 'users.id')
            ->leftJoin('banned_users_implicitly as b4', 'b4.target', '=', 'users.id')
            ->withOut(['vip', 'user_meta'])
            ->whereNull('b1.member_id')
            ->whereNull('b2.member_id')
            ->whereNull('b3.target')
            ->whereNull('b4.target')
            ->whereNotNull('user_meta.pic')
            ->where('engroup', 1)->take(3)->get();
        $imgUserF = User::select('users.name', 'users.title', 'user_meta.pic')
            ->join(\DB::raw("(SELECT CEIL(RAND() * (SELECT MAX(id) FROM users)) AS id) as u2"), function($join){
                $join->on('users.id', '>', 'u2.id');
            })
            ->join('user_meta', 'users.id', '=', 'user_meta.user_id')
            ->leftJoin('banned_users as b1', 'b1.member_id', '=', 'users.id')
            ->leftJoin('banned_users as b2', 'b2.member_id', '=', 'users.id')
            ->leftJoin('banned_users_implicitly as b3', 'b3.target', '=', 'users.id')
            ->leftJoin('banned_users_implicitly as b4', 'b4.target', '=', 'users.id')
            ->withOut(['vip', 'user_meta'])
            ->whereNull('b1.member_id')
            ->whereNull('b2.member_id')
            ->whereNull('b3.target')
            ->whereNull('b4.target')
            ->whereNotNull('user_meta.pic')
            ->where('engroup', 2)->take(3)->get();
        return view('new.welcome')
            ->with('cur', view()->shared('user'))
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
        return view('new.browse')->with('user', $user)->with('cur', $user);
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
        $no_avatar = AdminCommonText::where('alias','no_avatar')->get()->first();
        
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
                    ->with('cancel_notice', $cancel_notice)
                    ->with('no_avatar', isset($no_avatar)?$no_avatar->content:'');
            }
            return view('dashboard')
            ->with('user', $user)
            ->with('tabName', $tabName)
            ->with('cur', $user)
            ->with('year', $year)
            ->with('month', $month)
            ->with('day', $day)
            ->with('no_avatar', isset($no_avatar)?$no_avatar->content:'');
        }
    }

    public function dashboard(Request $request)
    {
        // todo: 驗證 VIP 是否成功付款
        //      1. 綠界：連 API 檢查，使用 Laravel Queue 執行檢查
        //      2. 藍新：後台手動
        
        $user = $this->user;
        $url = $request->fullUrl();

        $this->service->dispatchCheckECPay($this->userIsVip, $this->userIsFreeVip, $this->userVipData);
        //valueAddedService
        if($this->valueAddedServices['hideOnline'] == 1){
            //如未來service有多個以上則此段需設計並再改寫成ALL in one的方式
            $service_name = 'hideOnline';
            $valueAddedServiceData = \App\Models\ValueAddedService::getData($user->id,'hideOnline');
            if(is_object($valueAddedServiceData)){
                $this->dispatch(new CheckECpayForValueAddedService($valueAddedServiceData));
            }
            else{
                Log::info('ValueAddedService '.$service_name.' data null, user id: ' . $user->id);
            }

        }


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

        /*編輯文案-add avatar-START*/
        $add_avatar = AdminCommonText::getCommonText(41);//id 41
        /*編輯文案-add avatar-END*/

//        $isWarnedReason = AdminCommonText::getCommonText(56);//id 56 警示用戶原因

        $isAdminWarnedRead = warned_users::select('isAdminWarnedRead')->where('member_id',$user->id)->first();

        $no_avatar = AdminCommonText::where('alias','no_avatar')->get()->first();
        if($year=='1970'){
            $year=$month=$day='';
        }
        if ($user) {

            $pr = DB::table('pr_log')->where('user_id',$user->id)->where('active',1)->first();
            if(isset($pr)){
                $pr = $pr->pr;
            }else{
                $pr = '無';
            }
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
                    ->with('cancel_notice', $cancel_notice)
                    ->with('add_avatar', $add_avatar)
                    ->with('no_avatar', isset($no_avatar)?$no_avatar->content:'');
            }
            return view('new.dashboard')
                ->with('user', $user)
                ->with('tabName', $tabName)
                ->with('cur', $user)
                ->with('year', $year)
                ->with('month', $month)
                ->with('day', $day)
                ->with('cancel_notice', $cancel_notice)
                ->with('add_avatar', $add_avatar)
                ->with('isAdminWarnedRead',$isAdminWarnedRead)
                ->with('no_avatar', isset($no_avatar)?$no_avatar->content:'')
                ->with('pr', $pr);
//                ->with('isWarnedReason',$isWarnedReason)
        }
    }

    function base64_image_content($base64_image_content,$path){
        //匹配出圖片的格式
        if (preg_match('/^(data:\s*image\/(\w );base64,)/', $base64_image_content, $result)){
            $type = $result[2];
            $new_file = $path."/".date('Ymd',time())."/";
            if(!file_exists($new_file)){
                //檢查是否有該資料夾，如果沒有就建立，並給予最高許可權
                mkdir($new_file, 0700);
            }
            $new_file = $new_file.time().".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
                return '/'.$new_file;
            }else{
                return false;
            }
        }else{
            return false;
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

        $member_pics = MemberPic::where('member_id',$user->id)->whereRaw('pic  NOT LIKE "%IDPhoto%"')->orderByDesc('created_at')->get()->take(6);
        /*$member_pics = MemberPic::withTrashed()->where('member_id', $user->id)->where('self_deleted', 0)->whereRaw('pic  NOT LIKE "%IDPhoto%"')->orderByDesc('created_at')->get();
        $member_pics = $member_pics->filter(function ($member_pic) {
            $admin_deleted_check = \App\Models\AdminPicturesSimilarActionLog::where('pic', $member_pic->pic)->first();
            return !$member_pic->deleted_at || ($member_pic->deleted_at && $admin_deleted_check);
        })->values()->take(6);*/
        $avatar = UserMeta::where('user_id', $user->id)->get()->first();
        $userMeta = UserMeta::where('user_id', $user->id)->first();
        $blurryAvatar = $userMeta->blurryAvatar;
        $blurryLifePhoto = $userMeta->blurryLifePhoto;

        $birthday = date('Y-m-d', strtotime($user->meta_()->birthdate));
        $birthday = explode('-', $birthday);
        $year = $birthday[0];
        $month = $birthday[1];
        $day = $birthday[2];

        $girl_to_vip = AdminCommonText::where('alias', 'girl_to_vip')->get()->first();
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
                    ->with('cancel_notice', $cancel_notice)
                    ->with('girl_to_vip', $girl_to_vip->content);
            }
            if($user->engroup==1){
                return view('new.dashboard_img')
                    ->with('user', $user)
                    ->with('tabName', $tabName)
                    ->with('cur', $user)
                    ->with('year', $year)
                    ->with('month', $month)
                    ->with('day', $day)
                    ->with('member_pics', $member_pics)
                    ->with('girl_to_vip', $girl_to_vip->content)
                    ->with('avatar', $avatar);
            }else{
                return view('new.dashboard_img')
                    ->with('user', $user)
                    ->with('tabName', $tabName)
                    ->with('cur', $user)
                    ->with('year', $year)
                    ->with('month', $month)
                    ->with('day', $day)
                    ->with('member_pics', $member_pics)
                    ->with('girl_to_vip', $girl_to_vip->content)
                    ->with('avatar', $avatar)
                    ->with('blurry_avatar', $blurryAvatar)
                    ->with('blurry_life_photo', $blurryLifePhoto);
            }
        }
    }

    public function delPic(Request $request){
        $user=$request->user();
        $user_id = $user->id;

        $pic_id = $request->pic_id;

        $pic = MemberPic::where('member_id', $user_id)->where('id', $pic_id)->first();
        //delete file
        try{
            \File::delete(public_path($pic->pic));
            //delete data
            MemberPic::where('member_id', $user_id)->where('id', $pic_id)->delete();
        }
        catch(\Exception $e){
            Log::info("delPic failed, pic_id = $pic_id.");
        }

        /*設第一張照片為大頭貼*/
        $avatar = MemberPic::where('member_id', $user->id)->orderBy('id', 'asc')->first();
        if(!is_null($avatar)){
            UserMeta::uploadUserHeader($user->id,$avatar->pic);
        }else{
            //刪除大頭照
            UserMeta::uploadUserHeader($user->id,null);
        }
        $data = array(
            'code' => '200'
        );
        $pic_count = MemberPic::where('member_id', $user_id)->count();
        // dd($pic_count);
        if($pic_count==3){
            $is_delete = Vip::where('member_id', $user_id)->delete();
            if($is_delete){
                $data = array(
                    'code' => '400'
                );
            }
        }
        
        return json_encode($data);
    }

    public function save_img(Request $request)
    {
        $user=$request->user();
        $user_id = $user->id;
        $data = json_decode($request->data);
        // dd($data);
        $member_pics = $data->name;
        $pic_infos = $data->reader;
        // dd($member_pics);

        //VER.1
        // $this->base64_image_content($pic_infos[0], '/public/new/img/Member');
        // define('UPLOAD_PATH', '/new/img/Member/');
        // $img = str_replace('data:image/png;base64,', '', $pic_infos[0]);
        // $img = str_replace(' ', '+', $img);
        // $data = base64_decode($img);
        // $file = UPLOAD_PATH;
        // // dump($file);
        // dd($data, $file);
        // $success = file_put_contents('icon_010.txt', '12345');
        // $output = ($success) ? '<img src="'. $file .'" alt="Canvas Image" />' : '<p>Unable to save the file.</p>';
        // dd($output);


        //VER.2

        // $file = base64_decode($pic_infos[0]);
        // // dd($file);
        //             $folderName = '/public/new/img/Member/';
        //             $safeName = str_random(10).'.'.'png';
        //             $destinationPath = $folderName;
        //             // dd($safeName);
        //             file_put_contents($safeName, $file);

        //save new file path into db
        // $userObj->profile_pic = $safeName;


        if(count($member_pics)==0){
            $data = array(
                'code'=>'600'
            );
            // dd('123');
        }
        else{
            // dd('456');
            //VER.3
            $pic_count = MemberPic::where('member_id', $user->id)->count();
            for($i=0;$i<count($member_pics);$i++){
                if($pic_count>=6){
                    $data = array(
                        'code'=>'400',
                    );
                    break;
                }
                $now = date("Ymdhis", strtotime(now()));
                if(isset($pic_infos[$i])){
                    $image = $pic_infos[$i];  // your base64 encoded
                    // $image = str_replace('data:image/png;base64,', '', $image);
                    // $image = str_replace(' ', '+', $image);
                    // $imageName = str_random(10).'.'.'png';
                    list($type, $image) = explode(';', $image);
                    list(, $image)      = explode(',', $image);
                    $image = base64_decode($image);
                    \File::put(public_path(). '/Member_pics' .'/'. $user->id.'_'.$now.$member_pics[$i], $image);
                    MemberPic::insert(
                        array('member_id' => $user->id, 'pic' => '/Member_pics'.'/'.$user->id.'_'.$now.$member_pics[$i], 'isHidden' => 0, 'created_at'=>now(), 'updated_at'=>now())
                    );
                }
                else{
                    Log::info('save_img() failed, user id: ' . $user->id);
                    return false;
                }
            }

            $data = array(
                'code'=>'200',
            );
            /* 此段沒有必要，middleware 中的 FemaleVipActive 會處理這個判斷
            $is_vip = $user->isVip();
            if(($pic_count+1)>=4 && $is_vip==0 &&$user->engroup==2){
                $isVipCount = DB::table('member_vip')->where('member_id',$user->id)->count();
                if($isVipCount==0){
                    DB::table('member_vip')->insert(array('member_id'=>$user->id,'active'=>1, 'free'=>1));
                }else{
                    DB::table('member_vip')->where('member_id',$user->id)->update(['active'=>1, 'free'=>1]);
                }
                $data = array(
                    'code'=>'800'
                );
            }*/
            $isVip = Vip::where('member_id', $user->id)->count();
            $pic_count_final = MemberPic::where('member_id', $user->id)->count();
            if(($pic_count_final)>=4 && $user->engroup==2 && $isVip<=0){
                $data = array(
                    'code'=>'800'
                );
            }
            /*設第一張照片為大頭貼*/
            $avatar = MemberPic::where('member_id', $user->id)->orderBy('id', 'asc')->first();
            if(!is_null($avatar)){
                UserMeta::uploadUserHeader($user->id,$avatar->pic);
            }
        }


        // dd($data);
        // foreach($member_pics as $key=>$member_pic){

        //     DB::table('member_pic')->insert(
        //         array('member_id' => $user->id, 'pic' => date("Ymdhis", strtotime(now())).$member_pic, 'isHidden' => 0, 'created_at'=>now(), 'updated_at'=>now())
        //     );
        // }
        echo json_encode($data);
    }

    public function dashboard_img_new(Request $request)
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

    public function view_changepassword(Request $request)
    {
        $user = $request->user();
        return view('new.dashboard.password')->with('user', $user)->with('cur', $user);
    }

    public function viewSuspicious(Request $request) 
    {
        $user = $request->user();
        $suspicious = [];

        if($request->has('q') && !empty($request->input('q'))) {
            $suspicious = $this->suspiciousRepo->wherePaginate($request->input('q'));
        } else {
            $suspicious = $this->suspiciousRepo->paginate();
        }
        // dd($suspicious);
        return view('new.dashboard.suspicious')->with('user', $user)->with('suspicious', $suspicious)->with('query', $request->input('q'));
    }

    public function changePassword(Request $request){
        $user = $request->user();
        if($request->input('password') != $request->input('password_confirmation')){
            return back()->with('message', '確認新密碼不符合，請重新操作');
        }

       // dd(Hash::make($request->input('old_password')));
        if( Hash::check($request->input('old_password'),$user->password) ) {
            $password = $request->input('password') == null ? '123456' : $request->input('password');
            $user->password = bcrypt($password);
            $user->save();
            return back()->with('message', '更新成功');
        }else{
            return back()->with('message', '原密碼有誤，請重新操作');
        }
    }

    public function view_openCloseAccount(Request $request)
    {
        $user = $request->user();
        return view('new.dashboard.openCloseAccount')->with('user', $user)
            ->with('reasonType', $request->get('reasonType',1));
    }

    public function view_closeAccountReason(Request $request)
    {
        $user = $request->user();
        $input = $request->input();

        if($user->email == $input['email']){
            if(Auth::attempt(array('email' => $input['email'], 'password' => $input['password'])) ){
                //驗證成功
                $reasonType = $request->get('reasonType');
                if($reasonType == '3'){
                    $this->updateAccountStatus($request);
                    //關閉帳號後需登出
                    session()->put('needLogOut','Y');
                    return redirect('/dashboard/openCloseAccount')->with('message', '非常感謝您選擇甜心花園來為您提供服務，也恭喜您找到適合的他/她，您的帳號目前為關閉狀態，系統將於30秒後自動登出。');
                }
                else
                    return view('new.dashboard.closeAccountReason', compact('user','reasonType'));
            }else{
                //驗證失敗
                return back()->with('message', '帳號驗證失敗');
            }
        }else{
            //驗證失敗
            return back()->with('message', '帳號驗證失敗');
        }
    }

    public function updateAccountStatus(Request $request){
        $user = $request->user();
        $input = $request->input();
        $status = $request->get('status');

        if($status == 'close'){
            if($request->get('reasonType') ==1){

                $images = $request->file('image');
                if(!is_null($images))
                {
                    $destinationPath = [];
                    foreach ($images as $image){
                        $now = Carbon::now()->format('Ymd');
                        $input['imagename'] = $now . rand(100000000,999999999) . '.' . $image->getClientOriginalExtension();

                        $rootPath = public_path('/img/Member');
                        $tempPath = $rootPath . '/' . substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/';

                        if(!is_dir($tempPath)) {
                            File::makeDirectory($tempPath, 0777, true);
                        }
                        $destinationPath[] = '/img/Member/'. substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/' . $input['imagename'];

                        $img = Image::make($image->getRealPath());
                        $img->resize(400, 600, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($tempPath . $input['imagename']);
                    }

                    //整理images
                    if(count($destinationPath) > 0){
                        $destinationPath = json_encode($destinationPath);
                    }
                }
            }

            AccountStatusLog::insert([
                'user_id' => $user->id,
                'reasonType' => $request->get('reasonType'),
                'reported_id' => is_array($request->get('reportedId')) ? implode(',', $request->get('reportedId'))  : $request->get('reportedId'),
                'content' => is_array($request->get('content')) ? json_encode($request->get('content')) : $request->get('content'),
                'remark1' => $request->get('remark1'),
                'remark2' => $request->get('remark2'),
                'image' => isset($destinationPath) ? $destinationPath : null,
                'created_at' => Carbon::now()
            ]);
            $user->accountStatus = 0;
            $user->accountStatus_updateTime = Carbon::now();
            $user->save();


            $closeMsg = '';
            switch ($input['reasonType']){
                case 1 :
                    $closeMsg = '非常感謝您撥空填寫，我們會盡速處理，若此帳號確實有違規行為，會對其進行懲處，並於email另行聯絡您。您的帳號目前為關閉狀態，系統將於30秒後自動登出。';
                    break;
                case 2 :
                case 4 :
                    $closeMsg = '非常感謝您的回饋，我們會盡速優化與改善此問題，您的帳號目前為關閉狀態，系統將於30秒後自動登出。';
                    break;
                case 3 :
                    $closeMsg = '非常感謝您選擇甜心花園來為您提供服務，也恭喜您找到適合的他/她，您的帳號目前為關閉狀態，系統將於30秒後自動登出。';
                    break;
            }

            //關閉帳號後需登出
            session()->put('needLogOut','Y');
            return redirect('/dashboard/openCloseAccount')->with('message', $closeMsg);
        }
        else if ($status == 'open')
        {
            if($user->account_status_admin==0){
                return back()->with('message', '此帳號已被站方關閉，若有疑問請點選右下方，加站長line@');
            }
            $dbCloseDay = \App\Models\AccountStatusLog::where('user_id',$user->id)->orderBy('created_at', 'desc')->first();
            $waitDay = 30;
            if(!is_null($dbCloseDay)){
                $baseDay = date("Y-m-d",strtotime("+30 days",substr(strtotime($dbCloseDay->created_at), 0 ,10)));
                $nowDay = date("Y-m-d");
                $waitDay = round((strtotime($baseDay)-strtotime($nowDay))/3600/24);
            }

            if(auth()->user()->isVip() || $waitDay <=0){
                if($user->email == $input['email']){
                    if(Auth::attempt(array('email' => $input['email'], 'password' => $input['password'])) ){
                        //驗證成功
                        $user->accountStatus = 1;
                        $user->accountStatus_updateTime = Carbon::now();
                        $user->save();
                        return redirect('/dashboard')->with('message', '帳號已成功開啟');
                    }else{
                        //驗證失敗
                        return back()->with('message', '帳號驗證失敗');
                    }
                }else{
                    //驗證失敗
                    return back()->with('message', '帳號驗證失敗');
                }
            }else{
                return redirect('/dashboard/openCloseAccount')->with('message', '帳號開啟失敗');
            }
        }
        return view('new.dashboard.openCloseAccount')->with('user', $user);
    }

    public function view_account_manage(Request $request)
    {
        $chinese_num_arr = ['一','二','三','四','五','六','七','八','九'];
        $user = $request->user();
        $user_pause_during = config('memadvauth.user.pause_during');
        $api_pause_during = config('memadvauth.api.pause_during');        
        $userPauseMsg = '驗證失敗需'.(($user_pause_during%1440 || $user_pause_during/1440>=10)?$user_pause_during.'分鐘':$chinese_num_arr[$user_pause_during/1440-1].'天').'後才能重新申請。';
        $apiPauseMsg = '本日進階驗證功能維修，請'.(intval($api_pause_during/60)?intval($api_pause_during/60).'hr':'').(($api_pause_during%60)?($api_pause_during%60).'分鐘':'').'後再試。';

        return view('new.dashboard.account_manage')->with('user', $user)->with('cur', $user)
                ->with('is_pause_api',LogAdvAuthApi::isPauseApi())
                ->with('isAdvAuthUsable',$user->isAdvanceAuth()?$user->isAdvanceAuth():UserService::isAdvAuthUsableByUser($user))
                ->with('userPauseMsg',$userPauseMsg??null)
                ->with('apiPauseMsg',$apiPauseMsg??null);
    }

    public function view_name_modify(Request $request)
    {
        $user = $request->user();
        return view('new.dashboard.account_name_modify')->with('user', $user)->with('cur', $user);
    }

    public function changeName(Request $request){
        $user = $request->user();
        if( Hash::check($request->input('password'),$user->password) ) {
            $name = $request->input('name');
            $reason = $request->input('reason');
            if (!isset($name)) {
                return back()->with('message', '沒有填寫新暱稱！');
            }
            //檢查是否申請過
            $check_user = DB::table('account_name_change')->where('user_id', $user->id)->first();
            if (isset($check_user->user_id)) {
                return back()->with('message', '您已申請過，無法再修改喔！');
            } else {
                //送出申請
                DB::table('account_name_change')->insert(
                    ['user_id' => $user->id, 'change_name' => $name, 'before_change_name' => $user->name, 'reason' => $reason, 'status' => 0, 'created_at' => Carbon::now()]
                );
                return back()->with('message', '已送出申請，等待24hr站長審核');
            }
        }else{
            return back()->with('message', '密碼有誤，請重新操作');
        }
    }

    public function view_gender_change(Request $request)
    {
        $user = $request->user();
        return view('new.dashboard.account_gender_change')->with('user', $user)->with('cur', $user);
    }

    public function changeGender(Request $request){
        $user = $request->user();

        if( Hash::check($request->input('password'),$user->password) ) {
            //檢查是否申請過
            $check_user = DB::table('account_gender_change')->where('user_id', $user->id)->first();
            if (isset($check_user->user_id)) {
                return back()->with('message', '您已申請過，無法再修改喔！');
            } else {
                //送出申請
                DB::table('account_gender_change')->insert(
                    ['user_id' => $user->id, 'change_gender' => $request->input('gender'), 'before_change_gender' => $user->engroup, 'reason' => $request->input('reason'), 'status' => 0, 'created_at' => Carbon::now()]
                );
                return back()->with('message', '已送出申請，等待24hr站長審核');
            }
        }else{
            return back()->with('message', '密碼有誤，請重新操作');
        }
    }

    public function view_consign_add(Request $request)
    {
        $user = $request->user();
        return view('new.dashboard.account_consign_add')->with('user', $user)->with('cur', $user);
    }

    public function consignAdd(Request $request){
        $user = $request->user();

        //檢查是否有申請交付
        $check_user = DB::table('account_consign')->where('a_user_id',$user->id)->orWhere('b_user_id',$user->id)->where('cancel_id',null)->first();
        if(isset($check_user->id)){
            return back()->with('message', '您的帳號尚在交付中');
        }else if( Hash::check($request->input('password'),$user->password) ) {
            //取得對方帳號ID
            $consign_user = User::where('email',$request->input('account'))->first();
            if(!isset($consign_user->id)){
                return back()->with('message', '對方帳號有誤，請重新操作');
            }

            $check_user_a = DB::table('account_consign')->where('a_to_id',$user->id)->where('cancel_id',null)->first();
            if(isset($check_user_a->id)){
                //已配對A 更新交付資料表
                DB::table('account_consign')->where('id',$check_user_a->id)
                    ->update(['b_user_id' => $user->id, 'b_to_id' => $consign_user->id, 'b_created_at' => Carbon::now()]);
                //雙方已申請 更新交付狀態
                UserMeta::where('user_id',$user->id)->update(['isConsign'=>1]);
                UserMeta::where('user_id',$consign_user->id)->update(['isConsign'=>1]);

                //notify
                $current_data = DB::table('account_consign')->where('id',$check_user_a->id)->first();
                $user_a = User::findById($current_data->a_user_id);
                $user_b = User::findById($current_data->b_user_id);
                $content_a = $user_a->name.' 您好：<br>您在 '.$current_data->a_created_at.' 交付帳號，經站長審視已通過您的申請。';
                $content_b = $user_b->name.' 您好：<br>您在 '.$current_data->b_created_at.' 交付帳號，經站長審視已通過您的申請。';
//                $user_a->notify(new AccountConsign('交付帳號關閉通知',$user_a->name, $content_a));
//                $user_b->notify(new AccountConsign('交付帳號關閉通知',$user_b->name, $content_b));

                //站長系統訊息
                Message::post(1049, $current_data->a_user_id, $content_a, true, 1);
                Message::post(1049, $current_data->b_user_id, $content_b, true, 1);

                return back()->with('message', '帳號關閉成功');
            }else{
                //存入交付資料表
                DB::table('account_consign')->insert(
                    ['a_user_id' => $user->id, 'a_to_id' => $consign_user->id, 'a_created_at' => Carbon::now()]
                );

                //notify

                return back()->with('message', '交付申請已送出，等待對方提出申請');
            }

        }else{
            return back()->with('message', '密碼有誤，請重新操作');
        }
    }

    public function view_consign_cancel(Request $request)
    {
        $user = $request->user();
        return view('new.dashboard.account_consign_cancel')->with('user', $user)->with('cur', $user);
    }

    public function consignCancel(Request $request){
        $user = $request->user();

        if( Hash::check($request->input('password'),$user->password) ) {
            //取得對方帳號ID
            $consign_user = User::where('email',$request->input('account'))->first();
            if(!isset($consign_user->id)){
                return back()->with('message', '對方帳號有誤，請重新操作');
            }
            //檢查是否有申請交付
            $check_user = DB::table('account_consign')->where('a_user_id',$user->id)->orWhere('b_user_id',$user->id)->where('cancel_id',null)->first();

            if(isset($check_user->id) && ($check_user->a_to_id==$consign_user->id || $check_user->b_to_id==$consign_user->id)){

                if($check_user->a_user_id==null){
                    //交付中的取消流程
                    //更新cancel資料
                    DB::table('account_consign')->where('id',$check_user->id)
                        ->update(['cancel_id' => $user->id, 'canceled_at' => Carbon::now()]);
                    return back()->with('message', '已取消交付帳號申請');
                }else {
                    //尚未交付的取消流程
                    //更新cancel資料
                    DB::table('account_consign')->where('id',$check_user->id)
                        ->update(['cancel_id' => $user->id, 'canceled_at' => Carbon::now()]);
                    UserMeta::where('user_id',$user->id)->update(['isConsign' => 0, 'consign_expiry_date' => Carbon::now()->addHours(24)]);
                    UserMeta::where('user_id',$consign_user->id)->update(['isConsign' => 0, 'consign_expiry_date' => Carbon::now()->addHours(24)]);

                    //notify
                    $current_data = DB::table('account_consign')->where('id',$check_user->id)->first();
                    $user_a = User::findById($current_data->a_user_id);
                    $user_b = User::findById($current_data->b_user_id);
                    $content_a = $user_a->name.' 您好：<br>您申請的交付帳號已結束，系統將於 '.$user_a->meta_()->consign_expiry_date.' 後開啟您的帳號';
                    $content_b = $user_b->name.' 您好：<br>您申請的交付帳號已結束，系統將於 '.$user_b->meta_()->consign_expiry_date.' 後開啟您的帳號';
//                    $user_a->notify(new AccountConsign('交付帳號開啟通知',$user_a->name, $content_a));
//                    $user_b->notify(new AccountConsign('交付帳號開啟通知',$user_b->name, $content_b));

                    //站長系統訊息
                    Message::post(1049, $current_data->a_user_id, $content_a, true, 1);
                    Message::post(1049, $current_data->b_user_id, $content_b, true, 1);

                    return back()->with('message', '帳號開啟成功，將於24小時候啟用');
                }

            }else{
                return back()->with('message', '對方帳號不在交付申請中');
            }

        }else{
            return back()->with('message', '密碼有誤，請重新操作');
        }

    }

    public function view_exchange_period(Request $request)
    {
        $user = $request->user();
        return view('new.dashboard.account_exchange_period')->with('user', $user)->with('cur', $user);
    }

    public function exchangePeriodModify(Request $request){
        $user = $request->user();

        if( Hash::check($request->input('password'),$user->password) ) {
            //檢查是否申請過
            $check_user = DB::table('account_exchange_period')->where('user_id', $user->id)->first();
            $period = $request->input('exchange_period');
            $reason = $request->input('reason');
            $exchange_period_read = DB::table('exchange_period_temp')->where('user_id', $user->id)->count();
            if (isset($check_user->user_id)) {
                return back()->with('message', '您已申請過，無法再修改喔！');
            } elseif ($exchange_period_read == 1) {
                //未動過者首次直接通過
                User::where('id', $user->id)->update(['exchange_period' => $period]);
                DB::table('exchange_period_temp')->insert(['user_id' => $user->id, 'created_at' => \Carbon\Carbon::now()]);
                return back()->with('message', '已完成首次設定，無需審核');
            } elseif ($period == $user->exchange_period) {
                //與原本設定的一樣則不做動作
                return back()->with('message', '您當前所選項目無需變更');
            } else {
                //送出申請
                DB::table('account_exchange_period')->insert(
                    ['user_id' => $user->id, 'exchange_period' => $period, 'before_exchange_period' => $user->exchange_period, 'reason' => $reason, 'status' => 0, 'created_at' => Carbon::now()]
                );
                return back()->with('message', '已送出申請，等待48hr站長審核');
            }
        }else{
            return back()->with('message', '密碼有誤，請重新操作');
        }

    }

    public function view_account_hide_online(Request $request)
    {
        $user = $request->user();
        return view('new.dashboard.account_hide_online')->with('user', $user)->with('cur', $user);
    }

    public function viewVipForNewebPay(Request $request)
    {

        $cancel_vip = AdminCommonText::where('alias','cancel_vip')->get()->first();


        /*編輯文案-檢舉會員訊息-START*/
        $vip_text = AdminCommonText::where('alias','vip_text')->get()->first();
        /*編輯文案-檢舉會員訊息-END*/

        /*編輯文案-檢舉會員訊息-START*/
        $upgrade_vip = AdminCommonText::where('alias','upgrade_vip')->get()->first();
        /*編輯文案-檢舉會員訊息-END*/
        $user = $request->user();
        //VIP到期日
        $expiry_time = Vip::select('expiry')
            ->where('member_id', $user->id)
            ->where('business_id','761404')
            ->where('active',0)
            ->orderBy('created_at', 'desc')
            ->first();
        $days=0;
        if(isset($expiry_time)) {
            $expiry_time = $expiry_time->expiry;
            $expiry = Carbon::parse($expiry_time);
            $days = $expiry->diffInDays(Carbon::now());
        }

        return view('new.dashboard.vipForNewebPay')
            ->with('user', $user)->with('cur', $user)
            ->with('vip_text', $vip_text->content)
            ->with('upgrade_vip', $upgrade_vip->content)
            ->with('cancel_vip', $cancel_vip->content)
            ->with('expiry_time', $expiry_time)
            ->with('days',$days);
    }

    public function view_vip(Request $request)
    {

        $cancel_vip = AdminCommonText::where('alias','cancel_vip')->get()->first();


        /*編輯文案-檢舉會員訊息-START*/
        $vip_text = AdminCommonText::where('alias','vip_text')->get()->first();
        /*編輯文案-檢舉會員訊息-END*/

        /*編輯文案-檢舉會員訊息-START*/
        $upgrade_vip = AdminCommonText::where('alias','upgrade_vip')->get()->first();
        /*編輯文案-檢舉會員訊息-END*/
        $user = $request->user();
        //VIP到期日
        $expiry_time = Vip::select('expiry')->where('member_id', $user->id)->where('expiry', '!=', '0000-00-00 00:00:00')->orderBy('created_at', 'desc')->first();
        $days=0;
        if(isset($expiry_time)) {
            $expiry_time = $expiry_time->expiry;
            $expiry = Carbon::parse($expiry_time);
            $days = $expiry->diffInDays(Carbon::now());
        }

        return view('new.dashboard.vip')
            ->with('user', $user)->with('cur', $user)
            ->with('vip_text', $vip_text->content)
            ->with('upgrade_vip', $upgrade_vip->content)
            ->with('cancel_vip', $cancel_vip->content)
            ->with('expiry_time', $expiry_time)
            ->with('days',$days);
    }

    public function view_new_vip(Request $request)
    {

        $cc_monthly_payment = AdminCommonText::where('category_alias','vip_text')->where('alias','cc_monthly_payment')->get()->first();
        $cc_quarterly_payment = AdminCommonText::where('category_alias','vip_text')->where('alias','cc_quarterly_payment')->get()->first();
        $one_month_payment = AdminCommonText::where('category_alias','vip_text')->where('alias','one_month_payment')->get()->first();
        $one_quarter_payment = AdminCommonText::where('category_alias','vip_text')->where('alias','one_quarter_payment')->get()->first();
        $atm_cvs_notice = AdminCommonText::where('category_alias','vip_text')->where('alias','atm_cvs_notice')->get()->first();

        $cc_monthly_payment_red = AdminCommonText::where('category_alias','vip_text_red')->where('alias','cc_monthly_payment')->get()->first();
        $cc_quarterly_payment_red = AdminCommonText::where('category_alias','vip_text_red')->where('alias','cc_quarterly_payment')->get()->first();
        $one_month_payment_red= AdminCommonText::where('category_alias','vip_text_red')->where('alias','one_month_payment')->get()->first();
        $one_quarter_payment_red = AdminCommonText::where('category_alias','vip_text_red')->where('alias','one_quarter_payment')->get()->first();
        $atm_cvs_notice_red = AdminCommonText::where('category_alias','vip_text_red')->where('alias','atm_cvs_notice')->get()->first();

        $cancel_vip = AdminCommonText::where('alias','cancel_vip')->get()->first();


        /*編輯文案-檢舉會員訊息-START*/
        $vip_text = AdminCommonText::where('alias','vip_text')->get()->first();
        /*編輯文案-檢舉會員訊息-END*/

        /*編輯文案-檢舉會員訊息-START*/
        $upgrade_vip = AdminCommonText::where('alias','upgrade_vip')->get()->first();
        /*編輯文案-檢舉會員訊息-END*/
        $user = $request->user();
        //VIP到期日
        $expiry_time = Vip::select('expiry')->where('member_id', $user->id)->where('expiry', '!=', '0000-00-00 00:00:00')->orderBy('created_at', 'desc')->first();
        $days=0;
        if(isset($expiry_time)) {
            $expiry_time = $expiry_time->expiry;
            $expiry = Carbon::parse($expiry_time);
            $days = $expiry->diffInDays(Carbon::now());
        }

        return view('new.dashboard.new_vip')
            ->with('user', $user)->with('cur', $user)
            ->with('vip_text', $vip_text->content)
            ->with('upgrade_vip', $upgrade_vip->content)
            ->with('cancel_vip', $cancel_vip->content)
            ->with('cc_monthly_payment',$cc_monthly_payment->content)
            ->with('cc_quarterly_payment',$cc_quarterly_payment->content)
            ->with('one_month_payment',$one_month_payment->content)
            ->with('one_quarter_payment',$one_quarter_payment->content)
            ->with('cc_monthly_payment_red',$cc_monthly_payment_red->content)
            ->with('cc_quarterly_payment_red',$cc_quarterly_payment_red->content)
            ->with('one_month_payment_red',$one_month_payment_red->content)
            ->with('one_quarter_payment_red',$one_quarter_payment_red->content)
            ->with('atm_cvs_notice',$atm_cvs_notice->content)
            ->with('atm_cvs_notice_red',$atm_cvs_notice_red->content)
            ->with('expiry_time', $expiry_time)
            ->with('days',$days);
    }

    public function view_valueAddedHideOnline(Request $request)
    {
        $user = $request->user();
        $isPaidOnePayment = \App\Models\ValueAddedService::isPaidOnePayment($user->id,'hideOnline');
        $isPaidCancelNotOnePayment = \App\Models\ValueAddedService::isPaidCancelNotOnePayment($user->id,'hideOnline');
        $expiry_time = ValueAddedService::where('member_id', $user->id)->where('service_name', 'hideOnline')->where('expiry','!=','0000-00-00 00:00:00')->first();
        $days=0;
        if(isset($expiry_time)) {
            $expiry_time = $expiry_time->expiry;
            $expiry = Carbon::parse($expiry_time);
            $days = $expiry->diffInDays(Carbon::now());
        }


        $cc_monthly_payment = AdminCommonText::where('category_alias','hideOnline_text')->where('alias','cc_monthly_payment')->get()->first();
        $cc_quarterly_payment = AdminCommonText::where('category_alias','hideOnline_text')->where('alias','cc_quarterly_payment')->get()->first();
        $one_month_payment = AdminCommonText::where('category_alias','hideOnline_text')->where('alias','one_month_payment')->get()->first();
        $one_quarter_payment = AdminCommonText::where('category_alias','hideOnline_text')->where('alias','one_quarter_payment')->get()->first();
        $atm_cvs_notice = AdminCommonText::where('category_alias','hideOnline_text')->where('alias','atm_cvs_notice')->get()->first();

        $cc_monthly_payment_red = AdminCommonText::where('category_alias','hideOnline_text_red')->where('alias','cc_monthly_payment')->get()->first();
        $cc_quarterly_payment_red = AdminCommonText::where('category_alias','hideOnline_text_red')->where('alias','cc_quarterly_payment')->get()->first();
        $one_month_payment_red = AdminCommonText::where('category_alias','hideOnline_text_red')->where('alias','one_month_payment')->get()->first();
        $one_quarter_payment_red = AdminCommonText::where('category_alias','hideOnline_text_red')->where('alias','one_quarter_payment')->get()->first();
        $atm_cvs_notice_red = AdminCommonText::where('category_alias','hideOnline_text_red')->where('alias','atm_cvs_notice')->get()->first();

        return view('new.dashboard.valueAddedHideOnline')
            ->with('user', $user)
            ->with('cur', $user)
            ->with('isPaidOnePayment',$isPaidOnePayment)
            ->with('isPaidCancelNotOnePayment',$isPaidCancelNotOnePayment)
            ->with('cc_monthly_payment',$cc_monthly_payment->content)
            ->with('cc_quarterly_payment',$cc_quarterly_payment->content)
            ->with('one_month_payment',$one_month_payment->content)
            ->with('one_quarter_payment',$one_quarter_payment->content)
            ->with('cc_monthly_payment_red',$cc_monthly_payment_red->content)
            ->with('cc_quarterly_payment_red',$cc_quarterly_payment_red->content)
            ->with('one_month_payment_red',$one_month_payment_red->content)
            ->with('one_quarter_payment_red',$one_quarter_payment_red->content)
            ->with('atm_cvs_notice',$atm_cvs_notice->content)
            ->with('atm_cvs_notice_red',$atm_cvs_notice_red->content)
            ->with('expiry_time',$expiry_time)
            ->with('days',$days);
    }

    public function hideOnlineSwitch(Request $request)
    {
        $user_id = $request->input('userId');
        $isHideOnline = $request->input('isHideOnline');
        $insertData = false;
        $status_msg = 'error';

        if($isHideOnline == 0){

            User::where('id', $user_id)->update(['is_hide_online' => 0]);
            $status_msg = '搜索排序設定已開啟。';

        }else if($isHideOnline == 1){
            //check current is_hide_online
            $checkHideOnlineData = hideOnlineData::where('user_id',$user_id)->where('deleted_at', null)->get()->first();
            $user = User::where('id', $user_id)->get()->first();
            $insertData = true;

            if($user->is_hide_online==2 && isset($checkHideOnlineData)){
                $insertData = false;
            }

            User::where('id', $user_id)->update(['is_hide_online' => 1, 'hide_online_time' => $checkHideOnlineData->login_time]);

            $status_msg = '搜索排序設定已關閉。';

        }else if($isHideOnline == 2){
            //check current is_hide_online
            $checkHideOnlineData = hideOnlineData::where('user_id',$user_id)->where('deleted_at', null)->get()->first();
            $user = User::where('id', $user_id)->get()->first();
            $insertData = true;

            if($user->is_hide_online==1 && isset($checkHideOnlineData)){
                $insertData = false;
            }

            User::where('id', $user_id)->update(['is_hide_online' => 2, 'hide_online_time' => $checkHideOnlineData->login_time, 'hide_online_hide_time' => Carbon::now()]);

            $status_msg = '搜索排序設定已隱藏。';
        }

//        if($insertData == true) {
//            //如當前使用者為隱藏is_hide_online=2 則跳離快照
//            $register_time = $user->created_at;
//            $login_time = Carbon::now();
//            /*每周平均上線次數*/
//            $datetime1 = new \DateTime(now());
//            $datetime2 = new \DateTime($user->created_at);
//            $diffDays = $datetime1->diff($datetime2)->days;
//            $week = ceil($diffDays / 7);
//            if ($week == 0) {
//                $login_times_per_week = 0;
//            } else {
//                $login_times_per_week = round(($user->login_times / $week), 0);
//            }
//            $be_fav_count = MemberFav::where('member_fav_id', $user_id)->get()->count();
//            $fav_count = MemberFav::where('member_id', $user_id)->get()->count();
//            $tip_count = Tip::where('to_id', $user_id)->get()->count();
//            /*七天前*/
//            $date = date('Y-m-d H:m:s', strtotime('-7 days'));
//            /*發信＆回信次數統計*/
//            $messages_all = Message::select('id', 'to_id', 'from_id', 'created_at')->where('to_id', $user_id)->orwhere('from_id', $user_id)->orderBy('id')->get();
//            $countInfo['message_count'] = 0;
//            $countInfo['message_reply_count'] = 0;
//            $countInfo['message_reply_count_7'] = 0;
//            $send = [];
//            $receive = [];
//            foreach ($messages_all as $message) {
//                //user_id主動第一次發信
//                if ($message->from_id == $user_id && array_get($send, $message->to_id) < $message->id) {
//                    $send[$message->to_id][] = $message->id;
//                }
//                //紀錄每個帳號第一次發信給uid
//                if ($message->to_id == $user_id && array_get($receive, $message->from_id) < $message->id) {
//                    $receive[$message->from_id][] = $message->id;
//                }
//                if (!is_null(array_get($receive, $message->to_id))) {
//                    $countInfo['message_reply_count'] += 1;
//                    if ($message->created_at >= $date) {
//                        //計算七天內回信次數
//                        $countInfo['message_reply_count_7'] += 1;
//                    }
//                }
//            }
//            $countInfo['message_count'] = count($send);
//
//            $messages_7days = Message::select('id', 'to_id', 'from_id', 'created_at')->whereRaw('(to_id =' . $user_id . ' OR from_id=' . $user_id . ')')->where('created_at', '>=', $date)->orderBy('id')->get();
//            $countInfo['message_count_7'] = 0;
//            $send = [];
//            foreach ($messages_7days as $message) {
//                //七天內uid主動第一次發信
//                if ($message->from_id == $user_id && array_get($send, $message->to_id) < $message->id) {
//                    $send[$message->to_id][] = $message->id;
//                }
//            }
//            $countInfo['message_count_7'] = count($send);
//
//            /*發信次數*/
//            $message_count = $countInfo['message_count'];
//            /*過去7天發信次數*/
//            $message_count_7 = $countInfo['message_count_7'];
//            /*回信次數*/
//            $message_reply_count = $countInfo['message_reply_count'];
//            /*過去7天回信次數*/
//            $message_reply_count_7 = $countInfo['message_reply_count_7'];
//            /*過去7天罐頭訊息比例*/
//            $date_start = date("Y-m-d", strtotime("-6 days", strtotime(date('Y-m-d'))));
//            $date_end = date('Y-m-d');
//
//            /**
//             * 效能調整：使用左結合以大幅降低處理時間
//             *
//             * @author LZong <lzong.tw@gmail.com>
//             */
//            $query = Message::select('users.email', 'users.name', 'users.title', 'users.engroup', 'users.created_at', 'users.last_login', 'message.id', 'message.from_id', 'message.content', 'user_meta.about')
//                ->join('users', 'message.from_id', '=', 'users.id')
//                ->join('user_meta', 'message.from_id', '=', 'user_meta.user_id')
//                ->leftJoin('banned_users as b1', 'b1.member_id', '=', 'message.from_id')
//                ->leftJoin('banned_users_implicitly as b3', 'b3.target', '=', 'message.from_id')
//                ->leftJoin('warned_users as wu', function ($join) {
//                    $join->on('wu.member_id', '=', 'message.from_id')
//                        ->where('wu.expire_date', '>=', Carbon::now())
//                        ->orWhere('wu.expire_date', null);
//                })
//                ->whereNull('b1.member_id')
//                ->whereNull('b3.target')
//                ->whereNull('wu.member_id')
//                ->where(function ($query) use ($date_start, $date_end) {
//                    $query->where('message.from_id', '<>', 1049)
//                        ->where('message.sys_notice', 0)
//                        ->whereBetween('message.created_at', array($date_start . ' 00:00', $date_end . ' 23:59'));
//                });
//            $query->where('users.email', $user->email);
//            $results_a = $query->distinct('message.from_id')->get();
//
//            if ($results_a != null) {
//                $msg = array();
//                $from_content = array();
//                $user_similar_msg = array();
//
//                $messages = Message::select('id', 'content', 'created_at')
//                    ->where('from_id', $user->id)
//                    ->where('sys_notice', 0)
//                    ->whereBetween('created_at', array($date_start . ' 00:00', $date_end . ' 23:59'))
//                    ->orderBy('created_at', 'desc')
//                    ->take(100)
//                    ->get();
//
//                foreach ($messages as $row) {
//                    array_push($msg, array('id' => $row->id, 'content' => $row->content, 'created_at' => $row->created_at));
//                }
//
//                array_push($from_content, array('msg' => $msg));
//
//                $unique_id = array(); //過濾重複ID用
//                //比對訊息
//                foreach ($from_content as $data) {
//                    foreach ($data['msg'] as $word1) {
//                        foreach ($data['msg'] as $word2) {
//                            if ($word1['created_at'] != $word2['created_at']) {
//                                similar_text($word1['content'], $word2['content'], $percent);
//                                if ($percent >= 70) {
//                                    if (!in_array($word1['id'], $unique_id)) {
//                                        array_push($unique_id, $word1['id']);
//                                        array_push($user_similar_msg, array($word1['id'], $word1['content'], $word1['created_at'], $percent));
//                                    }
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//            $message_percent_7 = count($user_similar_msg) > 0 ? round((count($user_similar_msg) / count($messages)) * 100) . '%' : '0%';
//            /*瀏覽其他會員次數*/
//            $visit_other_count = Visited::where('member_id', $user_id)->count();
//            /*被瀏覽次數*/
//            $be_visit_other_count = Visited::where('visited_id', $user_id)->count();
//            /*過去7天瀏覽其他會員次數*/
//            $visit_other_count_7 = Visited::where('member_id', $user_id)->where('created_at', '>=', $date)->count();
//            /*過去7天被瀏覽次數*/
//            $be_visit_other_count_7 = Visited::where('visited_id', $user_id)->where('created_at', '>=', $date)->count();
//            /*此會員封鎖多少其他會員*/
//            $blocked_other_count = Blocked::where('member_id', $user_id)->count();
//            /*此會員被多少會員封鎖*/
//            $be_blocked_other_count = Blocked::where('blocked_id', $user_id)->count();
//            //寫入hide_online_data
//
//            //先刪後增 softDelete
//            hideOnlineData::where('user_id', $user_id)->delete();
//            hideOnlineData::insert([
//                'user_id' => $user_id,
//                'created_at' => Carbon::now(),
//                'register_time' => $register_time,
//                'login_time' => $login_time,
//                'login_times_per_week' => $login_times_per_week,
//                'be_fav_count' => $be_fav_count,
//                'fav_count' => $fav_count,
//                'tip_count' => $tip_count,
//                'message_count' => $message_count,
//                'message_count_7' => $message_count_7,
//                'message_reply_count' => $message_reply_count,
//                'message_reply_count_7' => $message_reply_count_7,
//                'message_percent_7' => $message_percent_7,
//                'visit_other_count' => $visit_other_count,
//                'visit_other_count_7' => $visit_other_count_7,
//                'be_visit_other_count' => $be_visit_other_count,
//                'be_visit_other_count_7' => $be_visit_other_count_7,
//                'blocked_other_count' => $blocked_other_count,
//                'be_blocked_other_count' => $be_blocked_other_count
//            ]);
//        }

        return back()->with('message', $status_msg);
    }

    public function cancelValueAddedService(Request $request)
    {
        $payload = $request->all();
        $user = $request->user();

        if ($user) {
            $log = new \App\Models\LogCancelValueAddedService();
            $log->user_id = $user->id;
            $log->service_name = $payload['service_name'];
            $log->created_at = \Carbon\Carbon::now();
            $log->save();
            if(Auth::attempt(array('email' => $payload['email'], 'password' => $payload['password']))){
                $valueAddedServiceData = ValueAddedService::findByIdAndServiceNameWithDateDesc($user->id, $payload['service_name']);
                $this->logService->cancelLog($valueAddedServiceData);
                $this->logService->writeLogToDB();
                $file = $this->logService->writeLogToFile();
                logger('$before_cancelValueAddedService:'.$valueAddedServiceData->updated_at);
                if( strpos(\Storage::disk('local')->get($file[0]), $file[1]) !== false) {
                    $array = ValueAddedService::cancel($user->id, $payload['service_name']);
                    if(isset($array["str"])){
                        $offVIP = $array["str"];
                    }
                    else{
                        $data = ValueAddedService::where('member_id', $user->id)->where('service_name', $payload['service_name'])->where('expiry', '!=', '0000-00-00 00:00:00')->get()->first();
                        $date = date('Y年m月d日', strtotime($data->expiry));
                        if($payload['service_name'] == 'hideOnline') {
                            $offVIP = '您已成功取消付費隱藏功能，下個月起將不再繼續扣款，目前的付費功能權限可以維持到 ' . $date;
                        }
                        logger('$expiry: ' . $data->expiry);
                        logger('base day: ' . $date);
                        logger('payment: ' . $data->payment);
                    }
                    logger('User ' . $user->id . ' ValueAddedService cancellation finished.');
                    $request->session()->flash('cancel_notice', $offVIP);
                    $request->session()->save();
                    if($payload['service_name']=='hideOnline') {
                        return redirect('/dashboard/valueAddedHideOnline#valueAddedServiceCanceled')->with('user', $user)->with('message', $offVIP);
                    }
                }
                else{
                    return redirect('/dashboard/valueAddedHideOnline')->with('user', $user)->withErrors(['取消失敗！'])->with('cancel_notice', '本次取消資訊沒有成功寫入，請再試一次。');
                }
            }
            else{
                return back()->with('message', '帳號密碼輸入錯誤');
            }
        }
        else{
            Log::error('User not found.');
        }

        return back()->with('message', 'error');
    }

    public function view_vipSelect(Request $request)
    {
        $user = $request->user();
        return view('new.dashboard.vipSelect')
            ->with('user', $user)->with('cur', $user);
    }

    public function viewuser2(Request $request, $uid = -1) {
        $user = $request->user();
        $bannedUsers = \App\Services\UserService::getBannedId();

        $vipDays=0;
        if($user->isVip()) {
            $vip_record = Carbon::parse($user->vip_record);
            $vipDays = $vip_record->diffInDays(Carbon::now());
        }

        $auth_check=0;
        if($user->isPhoneAuth()==1){
            $auth_check=1;
        }
        if (isset($user) && isset($uid)) {
            $targetUser = User::where('id', $uid)->where('accountStatus',1)->where('account_status_admin',1)->get()->first();
            if (!isset($targetUser)) {
                return view('errors.nodata');
            }
            // if(User::isBanned($uid)){
                // Session::flash('closed', true);
                // Session::flash('message', '此用戶已關閉資料');
                // return view('new.dashboard.viewuser', compact('user'));
            // }
            if ($user->id != $uid) {

                if(
                    //檢查性別
                    $user->engroup == $targetUser->engroup
                    //檢查是否被封鎖
//                    || User::isBanned($user->id)
                ){
                    return redirect()->route('listSeatch2');
                }
                Visited::visit($user->id, $targetUser);
            }


            $member_pic = MemberPic::where('member_id', $uid)->where('pic', '<>', $targetUser->meta->pic)->whereNull('deleted_at')->orderByDesc('created_at')->get();

            if($user->isVip()){
                $vipLevel = 1;
            }else{
                $vipLevel = 0;
            }

            $basic_setting = BasicSetting::where('vipLevel',$vipLevel)->where('gender',$user->engroup)->get()->first();

            if(isset($basic_setting['countSet'])){
                if($basic_setting['countSet']==-1){
                    $basic_setting['countSet'] = 10000;
                }
                $data['timeSet']  = (int)$basic_setting['timeSet'];
                $data['countSet'] = (int)$basic_setting['countSet'];
            }
            $blockadepopup = AdminCommonText::getCommonText(5);//id5封鎖說明popup
            $isVip = $user->isVip() ? '1':'0';

            $adminCommonTexts = AdminCommonText::whereIn('alias', ['report_reason', 'report_member', 'report_avatar', 'new_sweet', 'well_member', 'money_cert', 'alert_account', 'label_vip'])->get();
            $adminCommonTextArray = array();
            foreach($adminCommonTexts as $adminCommonText){
                $adminCommonTextArray[$adminCommonText->alias] = $adminCommonText;
            }

            /*編輯文案-檢舉會員訊息-START*/
            $report_reason = $adminCommonTextArray['report_reason'];
            /*編輯文案-檢舉會員訊息-END*/
            /*編輯文案-檢舉會員-START*/
            $report_member = $adminCommonTextArray['report_member'];
            /*編輯文案-檢舉會員-END*/
            /*編輯文案-檢舉大頭照-START*/
            $report_avatar = $adminCommonTextArray['report_avatar'];
            /*編輯文案-檢舉大頭照-END*/
            /*編輯文案-new_sweet-START*/
            $new_sweet = $adminCommonTextArray['new_sweet'];
            /*編輯文案-new_sweet-END*/
            /*編輯文案-well_member-START*/
            $well_member = $adminCommonTextArray['well_member'];
            /*編輯文案-well_member-END*/
            /*編輯文案-money_cert-START*/
            $money_cert = $adminCommonTextArray['money_cert'];
            /*編輯文案-money_cert-END*/
            /*編輯文案-alert_account-START*/
            $alert_account = $adminCommonTextArray['alert_account'];
            /*編輯文案-alert_account-END*/
            /*編輯文案-label_vip-START*/
            $label_vip = $adminCommonTextArray['label_vip'];
            /*編輯文案-label_vip-END*/

            

            /**
             * 效能調整：使用左結合以大幅降低處理時間，並且減少 query 次數，進一步降低時間及程式碼複雜度
             *
             * @author LZong <lzong.tw@gmail.com>
             */


            $query = \App\Models\Evaluation::select('evaluation.*')->from('evaluation as evaluation')->with('user')
                ->leftJoin('banned_users as b1', 'b1.member_id', '=', 'evaluation.from_id')
                ->leftJoin('banned_users_implicitly as b3', 'b3.target', '=', 'evaluation.from_id')
                ->leftJoin('users as u1', 'u1.id', '=', 'evaluation.from_id')
//                ->leftJoin('users as u2', 'u2.id', '=', 'evaluation.from_id')
//                ->leftJoin('user_meta as um', function($join) {
//                    $join->on('um.user_id', '=', 'evaluation.from_id')
//                        ->where('isWarned', 1); })
//                ->leftJoin('warned_users as wu', function($join) {
//                    $join->on('wu.member_id', '=', 'evaluation.from_id')
//                        ->where(function($query){
//                            $query->where('wu.expire_date', '>=', Carbon::now())
//                                ->orWhere('wu.expire_date', null); }); })
                ->whereNull('b1.member_id')
                ->whereNull('b3.target')
                ->whereNotNull('u1.id')
//                ->whereNotNull('u2.id')
                ->where('u1.accountStatus', 1)
                ->where('u1.account_status_admin', 1)
//                ->where('u2.accountStatus', 1)
//                ->where('u2.account_status_admin', 1)
//                ->whereNull('um.user_id')
//                ->whereNull('wu.member_id')
                ->orderBy('evaluation.created_at','desc')
                ->where('evaluation.to_id', $uid);

            $evaluation_data = $query->paginate(10);

            $evaluation_self = Evaluation::where('to_id',$uid)->where('from_id',$user->id)->first();
            /*編輯文案-被封鎖者看不到封鎖者的提示-START*/
//            $user_closed = AdminCommonText::where('alias','user_closed')->get()->first();
            /*編輯文案-被封鎖者看不到封鎖者的提示-END*/

            // todo: 此處程式碼有誤，應檢查檢視者是否被被檢視者封鎖，若是，才存入變數
//            if(User::isBanned($uid)){
//                Session::flash('message', $user_closed->content);
//            }
            if($uid == $user->id) {
                \App\Models\Evaluation::where('to_id',$uid)->update(['read'=>0]);
            }

            $to = $targetUser;
            $valueAddedServicesStatus['hideOnline'] = 0;
            $valueAddedServicesStatusRows = $to->valueAddedServiceStatus();
            if($valueAddedServicesStatusRows){
                foreach($valueAddedServicesStatusRows as $valueAddedServicesStatusRow){
                    $valueAddedServicesStatus[$valueAddedServicesStatusRow->service_name] = 1;
                }
            }
            $isSent3Msg = $user->isSent3Msg($uid);

            $isReadIntro = $user->isReadIntro;

            $pr = DB::table('pr_log')->where('user_id',$to->id)->where('active',1)->first();
            if(isset($pr)){
                $pr = $pr->pr;
            }else{
                $pr = '0';
            }

            //紀錄返回上一頁的url,避免發信後,按返回還在發信頁面
            if(isset($_SERVER['HTTP_REFERER'])){
                if(!str_contains($_SERVER['HTTP_REFERER'],'dashboard/chat2/chatShow') && !str_contains($_SERVER['HTTP_REFERER'],'dashboard/viewuser')){
                    session()->put('goBackPage',$_SERVER['HTTP_REFERER']);
                }
            }

            // die();
            return view('new.dashboard.viewuser', $data)
                    ->with('user', $user)
                    ->with('blockadepopup', $blockadepopup)
                    ->with('to', $to)
                    ->with('valueAddedServiceStatus', $valueAddedServicesStatus)
                    ->with('isSent3Msg', $isSent3Msg)
                    ->with('cur', $user)
                    ->with('member_pic',$member_pic)
                    ->with('isVip', $isVip)
                    ->with('engroup', $user->engroup)
                    ->with('report_reason',$report_reason->content)
                    ->with('report_member',$report_member->content)
                    ->with('report_avatar',$report_avatar->content)
                    ->with('new_sweet',$new_sweet->content)
                    ->with('well_member',$well_member->content)
                    ->with('money_cert',$money_cert->content)
                    ->with('alert_account',$alert_account->content)
                    ->with('label_vip',$label_vip->content)
                    // ->with('rating_avg',$rating_avg)
//                    ->with('user_closed',$user_closed->content)
                    ->with('evaluation_self',$evaluation_self)
                    ->with('evaluation_data',$evaluation_data)
                    ->with('vipDays',$vipDays)
                    ->with('isReadIntro',$isReadIntro)
                    ->with('auth_check',$auth_check)
                    ->with('is_banned',User::isBanned($user->id))
                    ->with('pr', $pr);
            }

    }

    public function getHideData(Request $request){
            
            $user = $request->user();
            $is_vip = $user->isVip();
            $uid = $request->uid;
            $targetUser = User::where('id', $uid)->where('accountStatus',1)->where('account_status_admin',1)->get()->first();
            /*七天前*/
            $date = date('Y-m-d H:m:s', strtotime('-7 days'));
            /*車馬費邀請次數*/
            if($targetUser->engroup==2) {
                $tip_count = Tip::where('to_id', $uid)->get()->count();
            }else{
                $tip_count = Tip::where('member_id', $uid)->get()->count();
            }
            /*是否封鎖我*/
            $is_block_mid = Blocked::where('blocked_id', $user->id)->where('member_id', $uid)->count() >= 1 ? '是' : '否';
            /*是否看過我*/
            $is_visit_mid = Visited::where('visited_id', $user->id)->where('member_id', $uid)->count() >= 1 ? '是' : '否';

            /*瀏覽其他會員次數*/
            $visit_other_count = Visited::where('member_id', $uid)->distinct('visited_id')->count();

            /*被瀏覽次數*/
            $be_visit_other_count = Visited::where('visited_id', $uid)->distinct('member_id')->count();

            /*過去7天瀏覽其他會員次數*/
            $visit_other_count_7 = Visited::where('member_id', $uid)->where('created_at', '>=', $date)->distinct('visited_id')->count();

            /*過去7天被瀏覽次數*/
            $be_visit_other_count_7 = Visited::where('visited_id', $uid)->where('created_at', '>=', $date)->distinct('member_id')->count();
           

            /*發信＆回信次數統計*/
            $messages_all = Message::select('id','to_id','from_id','created_at')->where('to_id', $uid)->orwhere('from_id', $uid)->orderBy('id')->get();
            $countInfo['message_count'] = 0;
            $countInfo['message_reply_count'] = 0;
            $countInfo['message_reply_count_7'] = 0;
            $send = [];
            $receive = [];
            foreach ($messages_all as $message) {
                //uid主動第一次發信
                if($message->from_id == $uid && array_get($send, $message->to_id) < $message->id){
                    $send[$message->to_id][]= $message->id;
                }
                //紀錄每個帳號第一次發信給uid
                if ($message->to_id == $uid && array_get($receive, $message->from_id) < $message->id) {
                    $receive[$message->from_id][] = $message->id;
                }
                if(!is_null(array_get($receive, $message->to_id))){
                    $countInfo['message_reply_count'] += 1;
                    if($message->created_at >= $date){
                        //計算七天內回信次數
                        $countInfo['message_reply_count_7'] += 1;
                    }
                }
            }
            $countInfo['message_count'] = count($send);

            $messages_7days = Message::select('id','to_id','from_id','created_at')->whereRaw('(to_id ='. $uid. ' OR from_id='.$uid .')')->where('created_at','>=', $date)->orderBy('id')->get();
            $countInfo['message_count_7'] = 0;
            $send = [];
            foreach ($messages_7days as $message) {
                //七天內uid主動第一次發信
                if($message->from_id == $uid && array_get($send, $message->to_id) < $message->id){
                    $send[$message->to_id][]= $message->id;
                }
            }
            $countInfo['message_count_7'] = count($send);

            /*發信次數*/
            $message_count = $countInfo['message_count'];
            /*過去7天發信次數*/
            $message_count_7 = $countInfo['message_count_7'];
            /*回信次數*/
            $message_reply_count = $countInfo['message_reply_count'];
            /*過去7天回信次數*/
            $message_reply_count_7 = $countInfo['message_reply_count_7'];
            /*過去7天罐頭訊息比例*/
            $date_start = date("Y-m-d",strtotime("-6 days", strtotime(date('Y-m-d'))));
            $date_end = date('Y-m-d');

            /**
             * 效能調整：使用左結合以大幅降低處理時間
             *
             * @author LZong <lzong.tw@gmail.com>
             */
            $query = Message::select('users.email','users.name','users.title','users.engroup','users.created_at','users.last_login','message.id','message.from_id','message.content','user_meta.about')
                ->join('users', 'message.from_id', '=', 'users.id')
                ->join('user_meta', 'message.from_id', '=', 'user_meta.user_id')
                ->leftJoin('banned_users as b1', 'b1.member_id', '=', 'message.from_id')
                ->leftJoin('banned_users_implicitly as b3', 'b3.target', '=', 'message.from_id')
                ->leftJoin('warned_users as wu', function($join) {
                    $join->on('wu.member_id', '=', 'message.from_id')
                        ->where('wu.expire_date', '>=', Carbon::now())
                        ->orWhere('wu.expire_date', null); })
                ->whereNull('b1.member_id')
                ->whereNull('b3.target')
                ->whereNull('wu.member_id')
                ->where('users.accountStatus', 1)
                ->where('users.account_status_admin', 1)
                ->where(function($query)use($date_start,$date_end) {
                    $query->where('message.from_id','<>',1049)
                        ->where('message.sys_notice', 0)
                        ->orWhereNull('message.sys_notice')
                        ->whereBetween('message.created_at', array($date_start . ' 00:00', $date_end . ' 23:59'));
                });
            $query->where('users.email',$targetUser->email);
            $results_a = $query->distinct('message.from_id')->get();

            if ($results_a != null) {
                $msg = array();
                $from_content = array();
                $user_similar_msg = array();

                $messages = Message::select('id','content','created_at')
                    ->where('from_id', $targetUser->id)
                    ->where('sys_notice', 0)
                    ->orWhereNull('sys_notice')
                    ->whereBetween('created_at', array($date_start . ' 00:00', $date_end . ' 23:59'))
                    ->orderBy('created_at','desc')
                    ->take(100)
                    ->get();

                foreach($messages as $row){
                    array_push($msg,array('id'=>$row->id,'content'=>$row->content,'created_at'=>$row->created_at));
                }

                array_push($from_content,  array('msg'=>$msg));

                $unique_id = array(); //過濾重複ID用
                //比對訊息
                foreach($from_content as $data) {
                    foreach ($data['msg'] as $word1) {
                        foreach ($data['msg'] as $word2) {
                            if ($word1['created_at'] != $word2['created_at']) {
                                if(strlen($word1['content']) > 200) {
                                    continue;
                                }
                                similar_text($word1['content'], $word2['content'], $percent);
                                if ($percent >= 70) {
                                    if(!in_array($word1['id'],$unique_id)) {
                                        array_push($unique_id,$word1['id']);
                                        array_push($user_similar_msg, array($word1['id'], $word1['content'], $word1['created_at'], $percent));
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $message_percent_7 = count($user_similar_msg) > 0 ? round( (count($user_similar_msg) / count($messages))*100 ).'%'  : '0%';

            

            /*每周平均上線次數*/
            $datetime1 = new \DateTime(now());
            $datetime2 = new \DateTime($targetUser->created_at);
            $diffDays = $datetime1->diff($datetime2)->days;
            $week = ceil($diffDays / 7);
            if($week == 0){
                $login_times_per_week = 0;
            }
            else{
                $login_times_per_week = round(($targetUser->login_times / $week), 0);
            }

            $last_login = $targetUser->last_login;

            $is_banned = null;

        
            $userHideOnlinePayStatus = ValueAddedService::status($uid,'hideOnline');
            if($userHideOnlinePayStatus == 1 /*&& $targetUser->is_hide_online != 0*/){
                $hideOnlineData = hideOnlineData::where('user_id',$uid)->where('deleted_at',null)->get()->first();
                if(isset($hideOnlineData)){
                    // $hideOnlineDays = now()->diffInDays($hideOnlineData->created_at);
                    $login_times_per_week = $hideOnlineData->login_times_per_week;
                    // $be_fav_count = $hideOnlineData->be_fav_count;//new add
                    // $fav_count = $hideOnlineData->fav_count;//new add
                    $tip_count = $hideOnlineData->tip_count;//new add
                    $message_count = $hideOnlineData->message_count;//new add
                    $message_count_7 = $hideOnlineData->message_count_7;
                    $message_reply_count = $hideOnlineData->message_reply_count;//new add
                    $message_reply_count_7 = $hideOnlineData->message_reply_count_7;
                    $message_percent_7 = $hideOnlineData->message_percent_7;
                    $visit_other_count = $hideOnlineData->visit_other_count;//new add
                    $visit_other_count_7 = $hideOnlineData->visit_other_count_7;
                    $be_visit_other_count = $hideOnlineData->be_visit_other_count;//new add
                    $be_visit_other_count_7 = $hideOnlineData->be_visit_other_count_7;//new add
                    // $blocked_other_count = $hideOnlineData->blocked_other_count;//new add
                    // $be_blocked_other_count = $hideOnlineData->be_blocked_other_count;//new add
                    $last_login = $hideOnlineData->updated_at; //new add

                    //此段僅測試用
                    //上正式機前起移除
                    // $message_count_7_old = $hideOnlineData->message_count_7;
                    // $message_reply_count_7_old = $hideOnlineData->message_reply_count_7;
                    // $visit_other_count_7_old = $hideOnlineData->visit_other_count_7;
                    //end

                    // for($x=0; $x<$hideOnlineDays; $x++) {

                    //     $message_count_7 = $message_count_7 - ($message_count_7 / 7);
                    //     $message_reply_count_7 = $message_reply_count_7 - ($message_reply_count_7 / 7);
                    //     $visit_other_count_7 = $visit_other_count_7 - ($visit_other_count_7 / 7);

                    //     if($message_count_7<0 && $message_reply_count_7<0 && $visit_other_count_7<0){
                    //         break;
                    //     }
                    // }

                    // $message_count_7 = round((int)$message_count_7);
                    // $message_reply_count_7 = round((int)$message_reply_count_7);
                    // $visit_other_count_7 = round((int)$visit_other_count_7);
                }
            }

        if($is_vip){
            $data = array(
                'login_times_per_week' => $login_times_per_week,
                'tip_count' => $tip_count,
                'is_vip' => 0,
                'is_block_mid' => $is_block_mid,
                'is_visit_mid' => $is_visit_mid,
                'visit_other_count' => $visit_other_count,
                'visit_other_count_7' => $visit_other_count_7,
                'be_visit_other_count' => $be_visit_other_count,
                'be_visit_other_count_7' => $be_visit_other_count_7,
                'message_count' => $message_count,
                'message_count_7' => $message_count_7,
                'message_reply_count' => $message_reply_count,
                'message_reply_count_7' => $message_reply_count_7,
                'message_percent_7' => $message_percent_7,
                'is_banned' => $is_banned,
                'userHideOnlinePayStatus' => $userHideOnlinePayStatus,
                'last_login' => $last_login
                //此段僅測試用
                //上正式機前起移除
                ,
                // 'message_count_7_old' => $message_count_7_old,
                // 'message_reply_count_7_old' => $message_reply_count_7_old,
                // 'visit_other_count_7_old' => $visit_other_count_7_old,
                // 'hideOnlineDays' => $hideOnlineDays
                //end
            );
        }else{
            $data = array(
                'login_times_per_week' => "<img src='/new/images/icon_35.png' />",
                'tip_count' => "<img src='/new/images/icon_35.png' />",
                'is_vip' => 0,
                'is_block_mid' => "<img src='/new/images/icon_35.png' />",
                'is_visit_mid' => "<img src='/new/images/icon_35.png' />",
                'visit_other_count' => "<img src='/new/images/icon_35.png' />",
                'visit_other_count_7' => "<img src='/new/images/icon_35.png' />",
                'be_visit_other_count' => "<img src='/new/images/icon_35.png' />",
                'be_visit_other_count_7' => "<img src='/new/images/icon_35.png' />",
                'message_count' => "<img src='/new/images/icon_35.png' />",
                'message_count_7' => "<img src='/new/images/icon_35.png' />",
                'message_reply_count' => "<img src='/new/images/icon_35.png' />",
                'message_reply_count_7' => "<img src='/new/images/icon_35.png' />",
                'message_percent_7' => "<img src='/new/images/icon_35.png' />",
                'is_banned' => "<img src='/new/images/icon_35.png' />",
                'userHideOnlinePayStatus' => "<img src='/new/images/icon_35.png' />",
                'last_login' => "<img src='/new/images/icon_35.png' />"
                //此段僅測試用
                //上正式機前起移除
                ,
                // 'message_count_7_old' => $message_count_7_old,
                // 'message_reply_count_7_old' => $message_reply_count_7_old,
                // 'visit_other_count_7_old' => $visit_other_count_7_old,
                // 'hideOnlineDays' => $hideOnlineDays
                //end
            );
        }
            
       
        
     
        return $data;
    }

    public function getBlockUser(Request $request){
        $user = $request->user();
        $is_vip = $user->isVip();
        if($is_vip){
            $uid = $request->uid;
            $bannedUsers = \App\Services\UserService::getBannedId();
            /*此會員封鎖多少其他會員*/
            $blocked_other_count = Blocked::with(['blocked_user'])
            ->join('users', 'users.id', '=', 'blocked.blocked_id')
            ->where('blocked.member_id', $uid)
            ->whereNotIn('blocked.blocked_id',$bannedUsers)
            ->whereNotNull('users.id')
            ->where('users.accountStatus', 1)
            ->where('users.account_status_admin', 1)
            ->count();
    
            /*此會員被多少會員封鎖*/
            $be_blocked_other_count = Blocked::with(['blocked_user'])
                ->join('users', 'users.id', '=', 'blocked.member_id')
                ->where('blocked.blocked_id', $uid)
                ->whereNotIn('blocked.member_id',$bannedUsers)
                ->whereNotNull('users.id')
                ->where('users.accountStatus', 1)
                ->where('users.account_status_admin', 1)
                ->count();
    
            $output = array(
                'blocked_other_count'=>$blocked_other_count,
                'be_blocked_other_count'=>$be_blocked_other_count
            );
        }else{
            $output = array(
                'blocked_other_count'=>'<img src="/new/images/icon_35.png">',
                'be_blocked_other_count'=>'<img src="/new/images/icon_35.png">'
            );
        }

        return json_encode($output);
    }

    public function getFavCount(Request $request){
        $user = $request->user();
        $is_vip = $user->isVip();
        if($is_vip){
            $uid = $request->uid;
            $bannedUsers = \App\Services\UserService::getBannedId();
            /*收藏會員次數*/
            $fav_count = MemberFav::select('member_fav.*')
            ->join('users', 'users.id', '=', 'member_fav.member_fav_id')
            ->whereNotNull('users.id')
            ->where('users.accountStatus', 1)
            ->where('users.account_status_admin', 1)
            ->where('member_fav.member_id', $uid)
            ->whereNotIn('member_fav.member_fav_id',$bannedUsers)
            ->get()->count();
    
            /*被收藏次數*/
            $be_fav_count = MemberFav::select('member_fav.*')
                ->join('users', 'users.id', '=', 'member_fav.member_id')
                ->whereNotNull('users.id')
                ->where('users.accountStatus', 1)
                ->where('users.account_status_admin', 1)
                ->where('member_fav.member_fav_id', $uid)
                ->whereNotIn('member_fav.member_id',$bannedUsers)
                ->get()->count();
    
            $output = array(
                'fav_count'=>$fav_count,
                'be_fav_count'=>$be_fav_count
            );
        }else{
            $output = array(
                'blocked_other_count'=>'<img src="/new/images/icon_35.png">',
                'be_blocked_other_count'=>'<img src="/new/images/icon_35.png">'
            );
        }

        return json_encode($output); 
    }
    
    public function getSearchData(Request $request){
        $vis = \App\Models\UserMeta::searchApi(
            $request->county,
            $request->district,
            $request->cup,
            $request->marriage,
            $request->budget,
            $request->income,
            $request->smoking,
            $request->drinking,
            $request->pic,
            $request->agefrom,
            $request->ageto,
            $request->user['engroup'],
            $request->umeta['city'],
            $request->umeta['area'],
            $request->umeta['blockdomain'],
            $request->umeta['blockdomainType'],
            $request->seqtime,
            $request->body,
            $request->user['id'],
            $request->exchange_period,
            $request->isBlocked,
            $request->userIsVip,
            $request->heightfrom,
            $request->heightto,
            $request->prRange_none,
            $request->prRange,
            $request->situation,
            $request->education,
            $request->isVip,
            $request->isWarned,
            $request->isPhoneAuth,
            $request->userIsAdvanceAuth,
            $request->page,
            $request->tattoo,
            $request->city2,
            $request->area2,
            $request->city3,
            $request->area3
        );

        $ssrData = '';
  
        $user = Auth::user();
    if (!empty($vis['singlePageData']) && isset($vis['singlePageData']) && sizeof($vis['singlePageData']) > 0){
            foreach ($vis['singlePageData'] as $vi){
            $visitor = $vi;
            try{
                $umeta = $visitor->user_meta;
                if(isset($umeta->city)){
                    $umeta->city = explode(",",$umeta->city);
                    $umeta->area = explode(",",$umeta->area);
                }
            }
            catch (\Exception $e){
                \Illuminate\Support\Facades\Log::info('Search error, visitor: ' . $vi);
            }
           
            $ssrData .='<li class="nt_fg">';
                $ssrData .='<div class="n_seicon">';
                   
                        $data = \App\Services\UserService::checkRecommendedUser($visitor);
                    

                    if(!$visitor->user_meta){
                        logger("Searched user no meta, id:" . $visitor->id);
                        continue;
                    }
                   
                    if($visitor->user_meta->isWarned == 1 || $visitor->isAdminWarned()){
                        $ssrData .='<div class="hoverTip">';
                            $ssrData .='<div class="tagText" data-toggle="popover" data-content="此會員為警示會員，與此會員交流務必提高警覺！">';
                            if($user->isVip()){
                                $ssrData .='<img src="/new/images/a5.png">';
                            }else{
                                $ssrData .='<img src="/new/images/b_5.png">';
                            }
                          
                            $ssrData .='</div>';
                        $ssrData .='</div>';
                        
                    }elseif(isset($data['description']) && $visitor->engroup == 2){
                        $ssrData .='<div class="hoverTip">';
                            $ssrData .='<div class="tagText" data-toggle="popover" data-content="新進甜心是指註冊未滿30天的新進會員，建議男會員可以多多接觸，不過要注意是否為八大行業人員。">';
                                if($user->isVip()){
                                    $ssrData .='<img src="/new/images/a1.png">';
                                }else{
                                    $ssrData .='<img src="/new/images/b_1.png">';
                                }

                            $ssrData .='</div>';
                        $ssrData .='</div>';
                    }elseif($visitor->isVip() && $visitor->engroup == 1){
                        $ssrData .='<div class="hoverTip">';
                            $ssrData .='<div class="tagText" data-toggle="popover" data-content="本站的付費會員。">';
                                if($user->isVip()){
                                    $ssrData .='<img src="/new/images/a4.png">';
                                }else{
                                    $ssrData .='<img src="/new/images/b_4.png">';
                                }
                            $ssrData .='</div>';
                        $ssrData .='</div>';
                    }else{
                        if($user->isVip()){
                            $ssr_var = 'xa_ssbg';
                        }else{
                            $ssr_var = '';
                        }
                        if($visitor->isPhoneAuth()){
                            $ssrData .='<div class="hoverTip '.$ssr_var.'">';                            
                            if($user->isVip()){
                                if($visitor->isAdvanceAuth() && $visitor->engroup==2){
                                    $ssrData .='<div class="tagText"  data-toggle="popover" data-content="本站的進階認證會員，本會員通過本站的嚴格驗證，基本資料正確無誤。">';
                                    $ssrData .='<img src="/new/images/c_03.png">';
                                    $ssrData .='</div> ';
                                    $ssrData .='<span>丨</span>';
                                }else if(!$visitor->isAdvanceAuth() && $visitor->engroup==2){
                                    $ssrData .='<div class="tagText"  data-toggle="popover" data-content="通過本站手機驗證的會員。">';
                                    $ssrData .='<img src="/new/images/c_09.png">';
                                    $ssrData .='</div>  ';
                                    $ssrData .='<span>丨</span>';
                                }
                               
                                $ssrData .='<div class="tagText"  data-toggle="popover" data-content="通過本站手機驗證的會員。">';
                                $ssrData .='<img src="/new/images/c_10.png">';
                                $ssrData .='</div>';
                            }else{
                                if($visitor->isAdvanceAuth() && $visitor->engroup==2){
                                    $ssrData .='<div class="tagText"  data-toggle="popover" data-content="本站的進階認證會員，本會員通過本站的嚴格驗證，基本資料正確無誤。">';
                                    $ssrData .='<img src="/new/images/b_8x.png">';
                                    $ssrData .='</div> ';
                                }else if(!$visitor->isAdvanceAuth() && $visitor->engroup==2){
                                    $ssrData .='<div class="tagText"  data-toggle="popover" data-content="通過本站手機驗證的會員。">';
                                    $ssrData .='<img src="/new/images/b_5x.png">';
                                    $ssrData .='</div>  ';
                                }else{
                                    $ssrData .='<div class="tagText"  data-toggle="popover" data-content="通過本站手機驗證的會員。">';
                                    $ssrData .='<img src="/new/images/b_6.png">';
                                    $ssrData .='</div>  ';  
                                } 
                            }
                            $ssrData .='</div>';
                        }
                     
                    }
          
                    if(isset($visitor->pr_log)){
                        $ssr_var = $visitor->pr_log->pr."%;"; 
                    }else{
                        $ssr_var = "0%;";
                    } 
                                                       
                 
                  
                    if($visitor->engroup == 1){
                        $ssrData .='<div class="tixright_a">';
                        $ssrData .='<div class="span zi_sc">大方指數</div>';
                        $ssrData .='<div class="font">';
                        $ssrData .='<div class="vvipjdt tm_new">';
                        $ssrData .='<div class="progress progress-striped vvipjdt_pre_a">';
                        $ssrData .='<div class="progress-bar progress_info_a" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                         aria-valuemax="100" style="width:'.$ssr_var.'"'; //need to check again
                                         $ssrData .='<span class="prfont_a">PR</span>';
                                         $ssrData .='</div>';
                                         $ssrData .='</div>';
                                         $ssrData .='</div>';
                                         $ssrData .='</div>';
                                         $ssrData .='</div>';
                    }
                    
                   
                    $ssrData .='</div>';
                
                    $isBlurAvatar = \App\Services\UserService::isBlurAvatar($visitor, $user);
                    if($isBlurAvatar) $ssr_var = 'blur_img';
                    if($visitor->user_meta->isAvatarHidden == 1){
                        $ssr_var2 = 'makesomeerror';
                    } else {
                        $ssr_var2 = $visitor->user_meta->pic;
                    }
                   
                    if ($visitor->engroup == 1){
                        $onerror="this.src='/new/images/male.png'" ;
                    } else {
                        $onerror="this.src='/new/images/female.png'";
                    }
                $ssrData .='<a href="/dashboard/viewuser/'.$visitor->id.'?time='.\Carbon\Carbon::now()->timestamp.'">';
                $ssrData .='<div class="nt_photo '.$ssr_var.'"><img class="lazy" src="'.$ssr_var2.'" data-original="'.$ssr_var2.'" onerror="'.$onerror.'"/></div>'; // need to check again

                $ssrData .='<div class="nt_bot nt_bgco">';
                $ssrData .='<h2>';
                $ssrData .='<font class="left">'.$visitor->name.'<span>'.$visitor->age().'歲</span></font>';
                            if($user->isVip()){
                                if($visitor->isOnline()){
                                    $ssrData .='<span class="onlineStatusSearch"></span>';
                                }
                            }else{
                                $ssrData .='<div class="onlineStatusNonVipSearch"><img src="/new/images/wsx.png"></div>';
                            }
                                
                          

                            $ssrData .='</h2>';
                            $ssrData .='<h3>';
                        
                            if(!empty($umeta->city)){
                                foreach($umeta->city as $key => $cityval){
                                    if ($key==0){
                                       $ssrData .=  $umeta->city[$key];
									   if($visitor->user_meta->isHideArea == 0){
										   $ssrData .=  $umeta->area[$key].'  ';
										   
									   }
                                    }else{
										
                                        $ssrData .=  '<span>'.$umeta->city[$key];
										if($visitor->user_meta->isHideArea == 0){
											$ssrData .= ($umeta->area[$key].'</span>');
										}
                                    }
                                }
                                    
                             
                            }
                                
                           
                            if($user->isVip()){
                                if($visitor->user_meta->isHideOccupation == 0 && !empty($visitor->user_meta->occupation) && $visitor->user_meta->occupation != 'null'){
                                    $ssrData .='<span style="margin-left: 0;">'.$visitor->user_meta->occupation.'</span>';
                                }
                            }else{
                                $ssrData .='<span style="margin-left: 10px;"><span style="padding-left: 5px;">職業</span><img src="/new/images/icon_35.png" class="nt_img"></span>';
                            }
                                
                          

                            if($user->engroup==1){
                                if($user->isVip()){
                                    $exchange_period_name = DB::table('exchange_period_name')->where('id',$visitor->exchange_period)->first();
                                    
                                    $ssrData .='<i class="j_lxx">丨</i><span>'.$exchange_period_name->name.'</span>';
                                }else{
                                    $ssrData .='<i class="j_lxx">丨</i><span>包養關係<img src="/new/images/icon_35.png" class="nt_img"></span>';
                                }
                            }
                            
                            $ssrData .='</h3>';
                            $ssrData .='<h3>最後上線時間：';
                            if($visitor->valueAddedServiceStatus('hideOnline')==1 && $visitor->is_hide_online==1){
                                $ssrData .= substr($visitor->hide_online_time,0,11);
                            }else{
                                $ssrData .= substr($visitor->last_login,0,11);
                            }
                            $ssrData .='</h3>';
                        $ssrData .='</div>';
                        $ssrData .='</a>';
                        $ssrData .='</li>';
                         }
        }else{
            $ssrData .='<div class="fengsicon search"><img src="/new/images/loupe.png" class="feng_img"><span>沒有資料</span></div>';
        }
        $output = array(
            "ssrData"=>$ssrData,
            "count"=>$vis['allPageDataCount'],
            'singleCount'=>$vis['singlePageCount']
        );
        return json_encode($output);
    }

    public function evaluation_self(Request $request)
    {
        $user = $request->user();
        $evaluation_data = Evaluation::select('evaluation.*')->from('evaluation as evaluation')->with('user')
            ->leftJoin('banned_users as b1', 'b1.member_id', '=', 'evaluation.to_id')
            ->leftJoin('banned_users_implicitly as b3', 'b3.target', '=', 'evaluation.to_id')
            ->leftJoin('users as u', 'u.id', '=', 'evaluation.to_id')
            ->whereNull('b1.member_id')
            ->whereNull('b3.target')
            ->where('u.accountStatus', 1)
            ->where('u.account_status_admin', 1)
            ->where('evaluation.from_id', $user->id)
            ->orderBy('evaluation.created_at','desc')
            ->paginate(15);
        return view('new.dashboard.evaluation_self')
            ->with('user', $user)
            ->with('cur', $user)
            ->with('evaluation_data',$evaluation_data);
    }

    public function evaluation_self_deleteAll(Request $request)
    {

        $self = $request->from_id;

        Evaluation::where('from_id',$self)->delete();

        return response()->json(['save' => 'ok']);
    }

    public function evaluation(Request $request, $uid)
    {
        $user = $request->user();
        $vipDays=0;
        if($user->isVip()) {
            $vip_record = Carbon::parse($user->vip_record);
            $vipDays = $vip_record->diffInDays(Carbon::now());
        }

        $auth_check=0;
        if($user->isPhoneAuth()==1){
            $auth_check=1;
        }
        if (isset($user) && isset($uid)) {

            $userBlockList = \App\Models\Blocked::select('blocked_id')->where('member_id', $uid)->get();
            $isBlockList = \App\Models\Blocked::select('member_id')->where('blocked_id', $uid)->get();
            $bannedUsers = \App\Services\UserService::getBannedId();
            $isAdminWarnedList = warned_users::select('member_id')->where('expire_date','>=',Carbon::now())->orWhere('expire_date',null)->get();
            $isWarnedList = UserMeta::select('user_id')->where('isWarned',1)->get();

            $evaluation_data = DB::table('evaluation')->where('to_id',$uid)
                ->whereNotIn('from_id',$userBlockList)
                ->whereNotIn('from_id',$isBlockList)
                ->whereNotIn('from_id',$bannedUsers)
                ->whereNotIn('from_id',$isAdminWarnedList)
                ->whereNotIn('from_id',$isWarnedList)
                ->paginate(15);

            $evaluation_self = DB::table('evaluation')->where('to_id',$uid)->where('from_id',$user->id)->first();
            return view('new.dashboard.evaluation')
                ->with('user', $user)
                ->with('to', $this->service->find($uid))
                ->with('cur', $user)
                ->with('evaluation_self',$evaluation_self)
                ->with('evaluation_data',$evaluation_data)
                ->with('vipDays',$vipDays)
                ->with('auth_check',$auth_check);
        }
    }

    public function evaluation_save(Request $request)
    {

        $evaluation_self = Evaluation::where('to_id',$request->input('eid'))->where('from_id',$request->input('uid'))->first();

        if(isset($evaluation_self)){
            DB::table('evaluation')->where('to_id',$request->input('eid'))->where('from_id',$request->input('uid'))->update(
                ['content' => $request->input('content'), 'rating' => $request->input('rating'), 'read' => 1, 'updated_at' => now()]
            );
        }else {

            $evaluation=Evaluation::create([
                'from_id' => $request->input('uid'), 'to_id' => $request->input('eid'), 'content' => $request->input('content'), 'rating' => $request->input('rating'), 'read' => 1, 'created_at' => now(), 'updated_at' => now()
            ]);
            //儲存評論照片
            $this->evaluation_pic_save($evaluation->id, $request->input('uid'), $request->file('images'));
        }
        if($request->ajax()) {
            echo '評價已完成';
            exit;
        }
        //return redirect('/dashboard/evaluation/'.$request->input('eid'))->with('message', '評價已完成');
        return back()->with('message', '評價已完成');
    }

    public function evaluation_pic_save($evaluation_id, $uid, $images)
    {
        if($files = $images) //$request->file('images')
        {
            foreach ($files as $file) {
                $now = Carbon::now()->format('Ymd');
                $input['imagename'] = $now . rand(100000000,999999999) . '.' . $file->getClientOriginalExtension();

                $rootPath = public_path('/img/Evaluation');
                $tempPath = $rootPath . '/' . substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/';

                if(!is_dir($tempPath)) {
                    File::makeDirectory($tempPath, 0777, true);
                }

                $destinationPath = '/img/Evaluation/'. substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/' . $input['imagename'];

                $img = Image::make($file->getRealPath());
                $img->resize(400, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($tempPath . $input['imagename']);

                //新增images到db
                $evaluationPic = new EvaluationPic();
                $evaluationPic->evaluation_id = $evaluation_id;//$request->input('evaluation_id'); //評價id
                $evaluationPic->member_id = $uid;//$request->input('uid');
                $evaluationPic->pic = $destinationPath;
                $evaluationPic->save();
            }
        }
    }

    public function evaluation_delete(Request $request)
    {
        Evaluation::where('id',$request->id)->delete();
        //EvaluationPic::where('evaluation_id',$request->id)->delete();

        return response()->json(['save' => 'ok']);
    }

    public function evaluation_re_content_save(Request $request)
    {
        EvaluationPic::where('evaluation_id',$request->id)->where('member_id',$request->eid)->delete();
        //儲存評論照片
        $this->evaluation_pic_save($request->input('id'), $request->input('eid'), $request->file('images'));

        DB::table('evaluation')->where('id',$request->input('id'))->update(
            ['re_content' => $request->input('re_content'), 're_created_at' => now()]
        );
        if($request->ajax()) {
            echo '評價回覆已完成';
            exit;
        }
//        return redirect('/dashboard/evaluation/'.$request->input('eid'))->with('message', '評價回覆已完成');
        return back()->with('message', '評價回覆已完成');
    }

    public function evaluation_re_content_delete(Request $request)
    {

        DB::table('evaluation')->where('id',$request->id)->update(
            ['re_content' => null]
        );
        EvaluationPic::where('evaluation_id',$request->id)->where('member_id',$request->userid)->delete();

        return response()->json(['save' => 'ok']);
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

    public function reportPost(Request $request){

        //先判定是否在站方封鎖名單裡面
        $aid = $request->input('aid');
        $uid = $request->input('uid');

        if (User::isBanned($aid)) {
            if ($request->ajax()) {
                echo '您目前被站方封鎖，無檢舉權限';
                exit;
            }
            return redirect(route("viewuser", ['uid' => $uid]))->withErrors([
                '您目前被站方封鎖，無檢舉權限'
            ]);
        }

        if(empty($this->customTrim($request->content))){
            if($request->ajax()) {
                exit;
            }
            return redirect('/dashboard/viewuser/'.$request->uid);
        }
        Reported::report($request->aid, $request->uid, $request->content, $request->file('reportedImages'));
        $user = $request->user();
        if($user->isVip()){
            $showMsg = '站務人員會檢視檢舉，可在瀏覽資料/封鎖名單查看被封鎖會員，若有其他狀況將以站內訊息通知檢舉人。';
        }else{
            $showMsg = '站務人員會檢視檢舉，可在瀏覽資料/封鎖名單查看被封鎖會員。';
        }
        if($request->ajax()) {
            echo $showMsg ;
            exit;
        }
        return back()->with('message', $showMsg); //'檢舉成功'
    }

    public function reportMsg(Request $request){
        $is_banned = User::isBanned($request->aid);
        if($is_banned && $request->ajax()){
            echo '您目前被站方封鎖，無檢舉權限';
            exit;
        }

        if(empty($this->customTrim($request->content))){
            $user = $request->user();
            if($request->ajax()) exit;
            return redirect('/dashboard/viewuser/'.$request->uid);
        }
        Message::reportMessage($request->id, $request->content, $request->file('images'));
        //        return redirect('/dashboard/viewuser/'.$request->uid)->with('message', '檢舉成功');
        if($request->ajax()) {
            echo '檢舉成功';
            exit;
        }
        return back()->with('message', '檢舉成功');
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
            $report_avatar = AdminCommonText::where('alias', 'report_avatar')->get()->first();
            if ( ! ReportedAvatar::findMember( $reporter_id , $pic_id ) )
            {
                if ($reporter_id !== $pic_id)
                {
                    return view('dashboard.reportAvatar', [
                        'reporter_id' => $reporter_id,
                        'reported_user_id' => $pic_id,
                        'user' => $user,
                        'report_avatar'=> $report_avatar->content ]);
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
            $report_reason = AdminCommonText::where('alias', 'report_reason')->get()->first();
            if ( ! ReportedPic::findMember( $reporter_id , $pic_id ) )
            {
                if( $reporter_id !== $uid ){
                    $target = User::findById($uid);
                    if(!$target){
                        return "<h1>很抱歉，您欲檢舉的會員並不存在。</h1>";
                    }
                    return view('dashboard.reportPic', [
                        'reporter_id' => $reporter_id,
                        'reported_pic_id' => $pic_id,
                        'user' => $user,
                        'target' => $target,
                        'uid' => $uid,
                        'report_reason'=>$report_reason->content]);
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

    public function reportPicNextNew(Request $request){
        $aid = $request->input('aid');
        $uid = $request->input('uid');
        if (User::isBanned($aid)) {
            if ($request->ajax()) {
                echo '您目前被站方封鎖，無檢舉權限';
                exit;
            }
            return redirect(route("viewuser", ['uid' => $uid]))->withErrors([
                '您目前被站方封鎖，無檢舉權限'
            ]);
        }

        if($request->picType=='avatar'){
            ReportedAvatar::report($request->aid, $request->uid, $request->content, $request->file('images'));
        }
        if($request->picType=='pic'){
            ReportedPic::report($request->aid, $request->pic_id, $request->content);
        }
        if($request->ajax()) {
            echo '檢舉成功';
            exit;
        }
        
        return back()->with('message', '檢舉成功');
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
        if ($aid !== $bid) {
            $isBlocked = Blocked::isBlocked($aid, $bid);
            if(!$isBlocked) {
                Blocked::block($aid, $bid);
            }
        }
        return back()->with('message', '封鎖成功');
    }

    public function postBlockAJAX(Request $request)
    {
        $bid = $request->sid;
        $aid = $request->uid;

        if ($aid !== $bid)
        {
            $isBlocked = Blocked::isBlocked($aid, $bid);
            if(!$isBlocked) {
                Blocked::block($aid, $bid);
                // 有收藏名單則刪除
                $isFav = MemberFav::where('member_id', $aid)->where('member_fav_id', $bid)->count();
                if($isFav > 0){
                    MemberFav::remove($aid, $bid);
                }
                // 對方的收藏名單也刪除
                $isFavved = MemberFav::where('member_id', $bid)->where('member_fav_id', $aid)->count();
                if($isFavved > 0){
                    MemberFav::remove($bid, $aid);
                }
                return response()->json(['save' => 'ok']);
            }
        }
        return response()->json(['save' => 'error']);
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

    public function unblockAJAX(Request $request)
    {
        $bid = $request->to;
        $aid = $request->uid;

        if($aid !== $bid)
        {
            Blocked::unblock($aid, $bid);
        }
        return response()->json(['save' => 'ok']);
    }
    public function unblockAll(Request $request)
    {
        Blocked::unblockAll($request->uid);
        return response()->json(['save' => 'ok']);
    }

    public function suspiciousUserAccount(Request $request)
    {
        $array['name'] = $request->user()->name;
        $array['user_id'] = $request->input('uid');
        $array['account_text'] = $request->input('account_txt');
        $this->suspiciousRepo->insert($array);
        return response()->json(['save' => 'ok']);
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

    public function postfavAJAX(Request $request)
    {
        $uid = $request->to;
        $aid = $request->uid;
        if ($aid !== $uid)
        {
            $isFav = MemberFav::where('member_id', $aid)->where('member_fav_id',$uid)->count();
            $isBlocked = Blocked::isBlocked($aid, $uid);
            if($isFav==0 && !$isBlocked) {
                MemberFav::fav($aid, $uid);
                return response()->json(['save' => 'ok']);
            }else if($isBlocked){
                return response()->json(['isBlocked' => 'true']);
            }else if($isFav>0){
                return response()->json(['isFav' => 'true']);
            }
        }
        return response()->json(['save' => 'error']);
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

    public function newer_manual(Request $request) {
        $user = $request->user();
        if ($user) {
            return view('new.dashboard.newer_manual')
                ->with('user', $user);
        }
    }

    public function anti_fraud_manual(Request $request) {
        $user = $request->user();
        if ($user) {
            if($user->isReadManual == 0)
                return view('new.dashboard.newer_manual')->with('user', $user);
            else
                return view('new.dashboard.anti_fraud_manual')->with('user', $user);
        }
    }

    public function web_manual(Request $request) {
        $user = $request->user();
        if ($user) {
            if($user->isReadManual == 0)
                return view('new.dashboard.newer_manual')->with('user', $user);
            else
                return view('new.dashboard.web_manual')->with('user', $user);
        }
    }

    public function is_read_manual(Request $request)
    {
        $user = $request->user();
        $user->isReadManual = 1 ;
        $user->save();
        return 'ok';
    }

    public function chat2(Request $request, $cid)
    {
        $user = $request->user();
        $admin = AdminService::checkAdmin();
        $m_time = '';
        $report_reason = AdminCommonText::where('alias', 'report_reason')->get()->first();
        $this->service->dispatchCheckECPay($this->userIsVip, $this->userIsFreeVip, $this->userVipData);
        //valueAddedService
        if($this->valueAddedServices['hideOnline'] == 1){
            //如未來service有多個以上則此段需設計並再改寫成ALL in one的方式
            $service_name = 'hideOnline';
            $valueAddedServiceData = \App\Models\ValueAddedService::getData($user->id,'hideOnline');
            if(is_object($valueAddedServiceData)){
                $this->dispatch(new CheckECpayForValueAddedService($valueAddedServiceData));
            }
            else{
                Log::info('ValueAddedService '.$service_name.' data null, user id: ' . $user->id);
            }

        }

        //紀錄返回上一頁的url
        if(isset($_SERVER['HTTP_REFERER'])){
            if(!str_contains($_SERVER['HTTP_REFERER'],'dashboard/chat2/chatShow')){
                session()->put('goBackPage_chat2',$_SERVER['HTTP_REFERER']);
            }
        }

        if (isset($user)) {
            $is_banned = User::isBanned($user->id);
            $isVip = $user->isVip();
            $tippopup = AdminCommonText::getCommonText(3);//id3車馬費popup說明
            $messages = Message::allToFromSender($user->id, $cid,true);
            $c_user_meta = UserMeta::where('user_id', $cid)->get()->first();
            //$messages = Message::allSenders($user->id, 1);
            if (isset($cid)) {
                $cid_user = $this->service->find($cid);
                if(!$cid_user){
                    return '<h1>該會員不存在。</h1>';
                }
                
                $cid_recommend_data = [];
                $forbid_msg_data = UserService::checkNewSugarForbidMsg($cid_user,$user);
                
                if(($cid_user->engroup) === ($user->engroup)){
                    return back();
                }

                if(!$user->isVip() && $user->engroup == 1){
                    $m_time = Message::select('created_at')->
                    where('from_id', $user->id)->
                    orderBy('created_at', 'desc')->first();
                    if(isset($m_time)){
                        $m_time = $m_time->created_at;
                    }
                }
                return view('new.dashboard.chatWithUser')
                    ->with('user', $user)
                    ->with('admin', $admin)
                    ->with('is_banned', $is_banned)
                    ->with('cmeta', $c_user_meta)
                    //->with('to', $this->service->find($cid))
                    ->with('to', $cid_user)
                    ->with('to_forbid_msg_data',$forbid_msg_data)                    
                    ->with('m_time', $m_time)
                    ->with('isVip', $isVip)
                    ->with('tippopup', $tippopup)
                    ->with('messages', $messages)
                    ->with('report_reason', $report_reason->content);
            }
            else {
                return view('new.dashboard.chatWithUser')
                    ->with('user', $user)
                    ->with('admin', $admin)
                    ->with('is_banned', $is_banned)
                    ->with('cmeta', $c_user_meta)
                    ->with('m_time', $m_time)
                    ->with('isVip', $isVip)
                    ->with('tippopup', $tippopup)
                    ->with('messages', $messages)
                    ->with('report_reason', $report_reason->content);
            }
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

    public function visited(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $visitors = Visited::findBySelf($user->id);
            //$visitors = Visited::unique(Visited::where('visited_id', $user->id)->distinct()->orderBy('created_at', 'desc')->paginate(15), "member_id", "created_at");
            return view('new.dashboard.visited')
                ->with('user', $user)
                ->with('visitors', $visitors);
        }
    }

    public function search(Request $request)
    {
        $user = $request->user();

        return view('dashboard.search')->with('user', $user);
    }
    public function search2(Request $request)
    {
        $input = $request->input();
        $search_page_key=session()->get('search_page_key',[]);
        if(!isset($input['page'])){
            foreach ($input as $key =>$value){
                session()->put('search_page_key.'.$key,array_get($input,$key,null));
            }
            foreach ($search_page_key as $key =>$value){
                if(count($input)){
                    session()->put('search_page_key.'.$key,array_get($input,$key,null));
                }
            }
        }

        $user = $request->user();
        $this->service->dispatchCheckECPay($this->userIsVip, $this->userIsFreeVip, $this->userVipData);
        //valueAddedService
        if($this->valueAddedServices['hideOnline'] == 1){
            //如未來service有多個以上則此段需設計並再改寫成ALL in one的方式
            $service_name = 'hideOnline';
            $valueAddedServiceData = \App\Models\ValueAddedService::getData($user->id,'hideOnline');
            if(is_object($valueAddedServiceData)){
                $this->dispatch(new CheckECpayForValueAddedService($valueAddedServiceData));
            }
            else{
                Log::info('ValueAddedService '.$service_name.' data null, user id: ' . $user->id);
            }

        }
        return view('new.dashboard.search')->with('user', $user);
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
            $bannedUsers = \App\Services\UserService::getBannedId();
            $blocks = \App\Models\Blocked::with(['blocked_user', 'blocked_user.meta'])
                ->join('users', 'users.id', '=', 'blocked.blocked_id')
                ->where('member_id', $user->id)
                ->whereNotIn('blocked_id',$bannedUsers)
                ->whereNotNull('users.id')
                ->where('users.accountStatus', 1)
                ->where('users.account_status_admin', 1)
                ->orderBy('blocked.created_at','desc')->paginate(15);

            return view('new.dashboard.block')
            ->with('blocks', $blocks)
            ->with('user', $user);
        }
    }

    public function block2(Request $request)
    {
        $user = $request->user();
        if ($user)
        {
            // blocked by user->id
            $blocks = \App\Models\Blocked::where('member_id', $user->id)->orderBy('created_at','desc')->paginate(15);

            $usersInfo = array();
            foreach($blocks as $blockUser){
                $id = $blockUser->blocked_id;
                $usersInfo[$id] = User::findById($id);
            }
            return view('dashboard.block')
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
        return '1|OK';
    }

    public function paymentInfoEC(Request $request) {
        return '1|OK';
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
                logger('User ' . $user->id . ' cancellation initiated.');
                $vip = Vip::findByIdWithDateDesc($user->id);
                $this->logService->cancelLog($vip);
                $this->logService->writeLogToDB();
                $file = $this->logService->writeLogToFile();
                logger('$before_cancelVip: '.$vip->updated_at);
                if( strpos(\Storage::disk('local')->get($file[0]), $file[1]) !== false) {
                    $array = Vip::cancel($user->id, 0);
                    if(isset($array["str"])){
                        $offVIP = $array["str"];
                    }
                    else{
                        $data = Vip::where('member_id', $user->id)->where('expiry', '!=', '0000-00-00 00:00:00')->get()->first();
                        $date = date('Y年m月d日', strtotime($data->expiry));
                        $offVIP = AdminCommonText::getCommonText(4);                        
                        $offVIP = str_replace('DATE', $date, $offVIP);
                        $offVIP = str_replace('LINE_ICON', AdminService::$line_icon_html, $offVIP);
                        $offVIP = str_replace('|$lineIcon|', AdminService::$line_icon_html, $offVIP);
                        $offVIP = str_replace('|$responseTime|', $date, $offVIP);
                        $offVIP = str_replace('|$reportTime|', $date, $offVIP);
                        $offVIP = str_replace('NOW_TIME', $date, $offVIP); 
                        logger('$expiry: ' . $data->expiry);
                        logger('base day: ' . $date);
                        logger('payment: ' . $data->payment);
                    }
                    logger('User ' . $user->id . ' cancellation finished.');
                    $request->session()->flash('cancel_notice', $offVIP);
                    $request->session()->save();
                    return redirect('/dashboard/new_vip#vipcanceled')->with('user', $user)->with('message', $offVIP);
                }
                else{
                    $log = new \App\Models\LogCancelVipFailed();
                    $log->user_id = $user->id;
                    $log->reason = 'File saving failed.';
                    $log->save();
                    return redirect('/dashboard/vip')->with('user', $user)->withErrors(['VIP 取消失敗！'])->with('cancel_notice', '本次VIP取消資訊沒有成功寫入，請再試一次。');
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

        return back()->with('message', 'error');
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

    //本月封鎖名單
    public function dashboard_banned(Request $request)
    {
        $user = $request->user();

        // $time = \Carbon\Carbon::now();
        //$count = banned_users::select('*')->where('banned_users.created_at','>=',\Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString())->count();
        
        $banned_users = banned_users::select('banned_users.reason','banned_users.created_at','banned_users.expire_date','users.name')
            ->where('banned_users.created_at','>=',\Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString())
            ->join('users','banned_users.member_id','=','users.id')
            ->orderBy('banned_users.created_at','desc');
         
        //隱形封鎖要出現在瀏覽資料/懲處名單中，封鎖原因為"廣告"
        $banned_users_implicitly = BannedUsersImplicitly::selectRaw('banned_users_implicitly.reason AS reason, banned_users_implicitly.created_at AS created_at, ""  AS expire_date ,users.name AS name')
            ->where('banned_users_implicitly.created_at','>=',\Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString())
            ->join('users','banned_users_implicitly.target','=','users.id')
            ->orderBy('banned_users_implicitly.created_at','desc');
         
        //取得資料總筆數
        $count = $banned_users->get()->count() + $banned_users_implicitly->get()->count();
        $getUnionList = $banned_users->union($banned_users_implicitly)->get();

        $page = $request->get('page');
        $perPage = 15;
        $banned_users = new LengthAwarePaginator($getUnionList->forPage($page, $perPage), $count, $perPage, $page,  ['path' => '/dashboard/banned/']);
        
        foreach ($banned_users as &$b){
            $b->name = $this->substr_cut($b->name);
        }

        return view('new.dashboard.banned')
            ->with('banned_user', $banned_users)
            ->with('user', $user)
            ->with('count',$count);
    }

    function substr_cut($user_name){
        //取得字串長度
        $strlen = mb_strlen($user_name, 'utf-8');
        //如果字串長度小於 2 則不做任何處理
        if ($strlen < 2) {
            return $user_name;
        } else {
            //mb_substr — 取得字串的部分
            $firstStr = mb_substr($user_name, 0, 1, 'utf-8');
            $lastStr = mb_substr($user_name, -1, 1, 'utf-8');
            //str_repeat — 重複一個字元
            return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
        }
    }

    /**
     * Check the user is banned or not then show notice page.
     */
    //本月警示名單
    


    public function warned(Request $request)
    {
        if($user = Auth::user()){
            $warned_users = warned_users::select('*')->where('member_id', \Auth::user()->id)->count();
            if($warned_users > 0){    
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

        $user = $request->user();
        $isVip = $user->isVip();
        $announcement = AdminAnnounce::where('en_group',$user->engroup)->orderBy('sequence','ASC')->get();

        return view('new.dashboard.announcement')
            ->with('user', $user)
            ->with('isVip', $isVip)
            ->with('announcement',$announcement);
//        return view('new.dashboard.announcement')
//                ->with('user', $request->user());
    }
    
	public function mem_member(Request $request)
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
        $visit_other_count  = Visited::where('member_id', $user_id)->distinct('visited_id')->count();
        /*被瀏覽次數*/
        $be_visit_other_count  = Visited::where('visited_id', $user_id)->distinct('member_id')->count();
        /*過去7天被瀏覽次數*/
        $be_visit_other_count_7  = Visited::where('visited_id', $user_id)->where('created_at', '>=', $date)->distinct('member_id')->count();

        /*發信次數*/
        $message_count = Message::where('from_id', $user_id)->count();

        $message_count_7 = Message::where('from_id', $user_id)->where('created_at', '>=', $date)->count();
        $report_reason = AdminCommonText::where('alias','report_reason')->get()->first();
        $report_member = AdminCommonText::where('alias','report_member')->get()->first();
        $report_avatar = AdminCommonText::where('alias','report_avatar')->get()->first();
        
        /*label*/
        $new_sweet = AdminCommonText::where('category_alias','label_text')->where('alias','new_sweet')->get()->first();
        $well_member = AdminCommonText::where('category_alias','label_text')->where('alias','well_member')->get()->first();
        $money_cert = AdminCommonText::where('category_alias','label_text')->where('alias','money_cert')->get()->first();
        $alert_account = AdminCommonText::where('category_alias','label_text')->where('alias','alert_account')->get()->first();
        $label_vip = AdminCommonText::where('category_alias','label_text')->where('alias','label_vip')->get()->first();
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
            'report_reason' => $report_reason->content,
            'report_member' => $report_member->content,
            'report_avatar' => $report_avatar->content,
            'new_sweet'     => $new_sweet->content,
            'well_member'     => $well_member->content,
            'money_cert'     => $money_cert->content,
            'label_vip'     => $label_vip->content,
            'alert_account'     => $alert_account->content,
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

    public function mem_updatevip(Request $request)
    {
        $mid = $request->user()->id ?? 689;
        $data = array(
            'mid'=>$mid,
        );
        return view('new.mem_updatevip', $data);
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

    public function cancelVip(Request $request){
        $acc = $request->get('acc');
        $pwd = $request->get('pwd');
        $userId = $request->get('userId');
        $user_count = User::where('id', $userId)->where('email', $acc)->get()->count();
        if($user_count>0){
            
            // dd( Auth::user()->remember_token);
            $u = User::where('email', $acc)->first();  

            
            if (Hash::check($pwd, $u->password)){
                // 不要輕易使用 DB 方式去修改資料庫，應盡可能使用現有的功能和 model 去處理資料，否則
                // 如這一部分程式而言，VIP 這個 model 在取消時還會進行 log 記錄，如果直接用 DB，將
                // 會造成取消 VIP 卻沒有任何記錄，updated_at 也不會有任何變動。
                // DB::table('member_vip')->where('member_id', $userId)->update(['active',0]);
                Vip::cancel($userId, 0);
                $data = array(
                    'code'=>'200',
                    'msg'=>'修改成功',
                );
            }else{
                $data = array(
                    'code'=>'400',
                    'msg' =>'修改失敗',
                );
            }
            
            return json_encode($data);
        }else{
            $data = array(
                'code'=>'400',
                'msg'=>'修改失敗',
            );
            return json_encode($data);
        }
    }

    public function addMessage(Request $request)
    {
        $id = $request->get('id');
        $msg = $request->get('msg');
        $mid = $request->user()->id;
        DB::table('message')->insert(
            ['from_id' => $mid, 'to_id' => $id, 'content'=>$msg, 'created_at'=>now(), 'updated_at'=>now()]
        );
        $data = array(
            'code'=>'200',
            'msg' =>'寄發訊息成功',
        );
        return json_encode($data);
        
        
    }
    
    public function addCollection(Request $request)
    {
        $id = $request->get('id');
        $msg = $request->get('msg');
        $mid = $request->user()->id;
        DB::table('member_fav')->insert(
            ['member_id' => $mid, 'member_fav_id' => $id, 'created_at'=>now(), 'updated_at'=>now()]
        );
        $data = array(
            'code'=>'200',
            'msg' =>'收藏成功',
        );
        return json_encode($data);
    }

    public function addReport(Request $request)
    {
        $id = $request->get('id');
        $msg = $request->get('msg');
        $mid = $request->user()->id;
        DB::table('reported')->insert(
            ['member_id' => $mid, 'reported' => $id, 'created_at'=>now(), 'updated_at'=>now()]
        );
        $data = array(
            'code'=>'200',
            'msg' =>'檢舉成功',
        );
        return json_encode($data);
    }


    public function addBlock(Request $request)
    {
        $id = $request->get('id');
        $msg = $request->get('msg');
        $mid = $request->user()->id;
        DB::table('blocked')->insert(
            ['member_id' => $mid, 'blocked_id' => $id, 'created_at'=>now(), 'updated_at'=>now()]
        );
        $data = array(
            'code'=>'200',
            'msg' =>'封鎖成功',
        );
        return json_encode($data);
    }

    public function addReportAvatar(Request $request)
    {
        $id = $request->get('id');
        $msg = $request->get('msg');
        $mid = $request->user()->id;
        DB::table('reported_avatar')->insert(
            ['reporter_id' => $mid, 'reported_user_id' => $id, 'created_at'=>now(), 'updated_at'=>now()]
        );
        $data = array(
            'code'=>'200',
            'msg' =>'檢舉大頭貼成功',
        );
        return json_encode($data);
    }

    public function getBlurryAvatar(Request $request) {
        $userId = $request->userId;
        $authId = auth()->id();
        if($userId == $authId){
            $avatar = UserMeta::where('user_id', $userId)->get()->first();

            $data = array(
                'code'=>'200',
                'data' => [
                    'blurryAvatar' => $avatar->blurryAvatar
                ],
                'msg' =>'成功',
            );
            return json_encode($data);
        }
    }

    public function blurryAvatar(Request $request) {
        $userId = $request->userId;
        $authId = auth()->id();
        if($userId == $authId){
            $avatar = UserMeta::where('user_id', $userId)->get()->first();
            $avatar->blurryAvatar = $request->input('blurrys');
            $avatar->save();

            $data = array(
                'code'=>'200',
                'msg' =>'成功',
            );
            return json_encode($data);
        }
    }

    public function blurryLifePhoto(Request $request) {
        $userId = $request->userId;
        $authId = auth()->id();
        if($userId == $authId){
            $avatar = UserMeta::where('user_id', $userId)->get()->first();
            $avatar->blurryLifePhoto = $request->input('blurrys');
            $avatar->save();

            $data = array(
                'code'=>'200',
                'msg' =>'成功',
            );
            return json_encode($data);
        }
    }

    public function member_auth(Request $request){
        $user = $request->user();
        return view('/auth/member_auth')
                ->with('user',$user)
                ->with('cur', $user);
    }

    public function member_auth_photo(Request $request){
        return view('/auth/member_auth_photo');
    }

    function getAge($birthday_date){
        $birthday = strtotime($birthday_date);
        //格式化出生時間年月日
        $byear=date('Y',$birthday);
        $bmonth=date('m',$birthday);
        $bday=date('d',$birthday);
        //格式化當前時間年月日
        $tyear=date('Y');
        $tmonth=date('m');
        $tday=date('d');
        //開始計算年齡
        $age=$tyear-$byear;

        if($bmonth>$tmonth || $bmonth==$tmonth && $bday>$tday){
            $age--;
        }
        return $age;
    }
    public function advance_auth(Request $request){
        $user = $request->user();
        $init_check_msg = $this->advance_auth_prechase();

        return view('/auth/advance_auth')
                ->with('user',$user)
                ->with('cur', $user)
                ->with('init_check_msg',$init_check_msg??null)
                ->with('user_pause_during_msg',$this->advance_auth_get_msg('user_pause'));
    }
    
    public function advance_auth_back(Request $request){
        $create = array(
            'member_id'=>$request->id,
            'reason'=>'進階驗證封鎖',
            'message_content'=>'1',
            'updated_at'=>now(),
            'created_at'=>now()
        );
        $status = banned_users::create($create);
        $data = array(
            'status'=>'success',
            'code'=>200
        );
        //寫入log
        DB::table('is_banned_log')->insert(['user_id' => $create['member_id'], 'reason' => $create['reason'], 'created_at' => $create['created_at']]);
        //新增Admin操作log
        $uCtrl = new UserController(app(\App\Services\UserService::class),app(\App\Services\AdminService::class));
        $uCtrl->insertAdminActionLog($create['member_id'], '封鎖會員');        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } 

    public function advance_auth_get_msg($type=null) {
        $msg = null;
        switch($type) {
            case 'user_pause':
                $chinese_num_arr = ['一','二','三','四','五','六','七','八','九'];
                $user_pause_during = config('memadvauth.user.pause_during');
                $msg = '驗證失敗需'.(($user_pause_during%1440 || $user_pause_during/1440>=10)?$user_pause_during.'分鐘':$chinese_num_arr[$user_pause_during/1440-1].'天').'後才能重新申請。';               
            break;
            case 'api_pause':
                $api_pause_during = config('memadvauth.api.pause_during');            
                $msg = '本日進階驗證功能維修，請 '.(intval($api_pause_during/60)?intval($api_pause_during/60).'hr':'').(($api_pause_during%60)?($api_pause_during%60).'分鐘':'').' 後再試。';
            break;           
        }
        
        return $msg;
    }

    public function advance_auth_prechase() {
        $user =Auth::user();
        $init_check_msg = null;
        if($user->engroup!=2) {
            $init_check_msg = '僅供女會員驗證' ;
        }   
        else if(!$user->isAdvanceAuth()) {
            if(!$user->isPhoneAuth() || !$user->getAuthMobile() || $user->getAuthMobile()=='0922222222' ) {
                $user->short_message()->delete();
                $init_check_msg = '請先通過 <a href="'.url('member_auth').'">手機驗證(<span class="obvious">點此前往</span>)</a>' ;
            } 
            else if($user->isForbidAdvAuth()) {
                $init_check_msg = '您的進階驗證功能有誤，請<a href="https://lin.ee/rLqcCns" target="_blank">點此 <img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="36" border="0" style="height: 36px; float: unset;"></a> 或點右下聯絡我們加站長 line 與站長聯絡。';
            }
            else if($user->isPauseAdvAuth()) {
                $init_check_msg = $this->advance_auth_get_msg('user_pause') ;
            }
            else if(LogAdvAuthApi::isPauseApi()) {
                $init_check_msg = $this->advance_auth_get_msg('api_pause') ;
            }
        } 

        return $init_check_msg;
    }
    
    public function advance_auth_precheck(Request $request){
        $user =$request->user();     
        $check_rs = null;
        if(!$request->id_serial) $check_rs[] = 'i';
        if(!$request->phone_number) $check_rs[] = 'p';
        if(!($request->year && $request->month && $request->day)) {
            $check_rs[] = 'b';
            $birth = null;
        }
        else {           
            $birth = $data['birth'] = date('Ymd', strtotime($request->year.'-'.$request->month.'-'.$request->day));
        }
        
        if($request->id_serial) {
            if(mb_substr(trim($request->id_serial),1,1)!=$user->engroup) $error_msg[] = '請輸入符合性別的身分證字號';
            else {
                $id_serial = strtoupper($request->id_serial);
                $letterConverter = [
                        'A'=>1,'I'=>39,'O'=>48,'B'=>10,'C'=>19,'D'=>28,'E'=>37,'F'=>46,'G'=>55,'H'=>64,'J'=>73,'K'=>82,
                        'L'=>2,'M'=>11,'N'=>20,'P'=>29,'Q'=>38,'R'=>47,'S'=>56,'T'=>65,'U'=>74,'V'=>83,'W'=>21,'X'=>3,'Y'=>12,'Z'=>30
                    ];                
                $weightArr = [8,7,6,5,4,3,2,1]; 
                if (preg_match("/^[a-zA-Z][1-2][0-9]{8}$/",$id_serial)){
                    $letterSegs = str_split($id_serial);
                    $total = $letterConverter[array_shift($letterSegs)];
                    $point = array_pop($letterSegs);
                    $len = count($letterSegs);
                    for($j=0; $j<$len; $j++){
                        $total += $letterSegs[$j]*$weightArr[$j];
                    }
                    
                    $last = (($total%10) == 0 )? 0: (10 - ( $total % 10 ));
                    if ($last != $point) {
                        $check_rs[] = 'i';
                    } 
                }  else {
                   $check_rs[] = 'i';
                }                
            }
        }
        
        if($request->phone_number) {
            if(preg_match("/^09[0-9]{8}$/",$request->phone_number)) {
                if($request->phone_number!=$user->getAuthMobile(true))
                {
                    $check_rs[] = 'p';
                }
            }
            else {
                $check_rs[] = 'p';
            }
        }  
        if($birth) {
            $age = $this->getAge($birth);
            /* 判斷是否小於18歲 */
            if($age<18){
                $check_rs[] = 'b18';
            }  
        }  

        if($user->engroup!=2) {
            $check_rs = ['s'];
        }

        return implode('_',$check_rs??[]);
    }
    
    public function advance_auth_process(Request $request){
        $LineToken = config('memadvauth.api.line_token');
        $api_check_cfg = config('memadvauth.api.check');
        $user =Auth::user();
        $init_check_msg = $this->advance_auth_prechase();
        $user_pause_during_msg = $this->advance_auth_get_msg('user_pause');
        if($init_check_msg) {
            return back();
        }           
        if(!UserService::isAdvAuthUsableByUser($user)) {
            return back();
        }
        
        $check_rs = $this->advance_auth_precheck($request)??'';

        if(!$check_rs) {
            $id_serial = $data['MemberNo'] = strtoupper($request->id_serial);
            $encode_id_serial = md5(sha1(md5($id_serial)));
            $phone_number = $data['phone_number'] = $request->phone_number;
             $birth = $data['birth'] = date('Ymd', strtotime($request->year.'-'.$request->month.'-'.$request->day));
            $format_birth = $request->year.'-'.$request->month.'-'.$request->day;
        }
        else {
            return back()->with('error_code', explode('_',$check_rs))
                    ->with('error_code_msg',['s'=>'僅供女會員驗證','i'=>'身分證字號','p'=>'門號','b'=>'生日','b18'=>'年齡未滿18歲，不得進行驗證']);
        }
        
        if(User::where('advance_auth_identity_encode',$encode_id_serial)->where('advance_auth_status',1)->where('advance_auth_phone',$phone_number)->where('advance_auth_birth',$format_birth)->count()) {
            $user->log_adv_auth_api()->create([
                    'birth'=>$data['birth']
                    ,'phone'=>$data['phone_number']
                    ,'identity_encode'=>$encode_id_serial
                    ,'is_duplicate'=>1
                ]);
            return back()->with('message', ['您的進階驗證功能有誤，<a href="https://lin.ee/rLqcCns" target="_blank">請點此 <img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="36" border="0" style="height: 36px; float: unset;"></a> 或點右下聯絡我們加站長 line 與站長聯絡。']);
        }        

        $data['api_base'] = 'https://'.config('memadvauth.service.host').(config('memadvauth.service.port')?':'.config('memadvauth.service.port'):'').'/';
        $output = $this->get_mid_clause($data);    

        //API資訊設定
        $data['BusinessNo'] = config('memadvauth.service.business_no');//'54666024';
        $data['ApiVersion'] = '1.0';
        $data['HashKeyNo'] = config('memadvauth.service.hash_key_no');//'12';
        $data['HashKey'] =config('memadvauth.service.hash_key');// '4341dcdf-0b14-475e-9b2a-3eb69650a12d';
        $data['VerifyNo'] = time();
        $data['ReturnParams'] = '';

        $InputParams_arr = array(
            'MemberNo'=>$id_serial,
            'Action'=>'ValidateMSISDNAdvance',
            'MIDInputParams'=>array(
                'Msisdn'=>$data['phone_number'],
                'Birthday'=>$data['birth'],
                'ClauseVer'=>$output->clausever??'',
                'ClauseTime'=> $output->lastUpdate??''
            )
        );
        
        $data['InputParams'] = json_encode($InputParams_arr, JSON_UNESCAPED_SLASHES);

        $data['return'] = $this->get_transaction($data);
        $output = $this->get_verify_result($data); 
        $OutputParams = json_decode($output["OutputParams"]??'', JSON_UNESCAPED_UNICODE);        
     
        if($OutputParams['MemberNo']??null) {
            $OutputParams['MemberNo'] = md5(sha1(md5($OutputParams['MemberNo'])));
            $output["OutputParams"] = json_encode($OutputParams);
        }
        $logArr = [
                    'birth'=>$data['birth']
                    ,'phone'=>$data['phone_number']
                    ,'identity_encode'=>$encode_id_serial
                    ,'return_response'=>json_encode($output)
                ];
                
        if(!$output) {
            $logArr['api_fault']=1;
            $user->log_adv_auth_api()->create($logArr);   
            return back()->with('message', ['系統目前無法進行驗證']);
        }                    
        
        $MIDOutputParams = json_decode($OutputParams["MIDOutputParams"]["MIDResp"]??'', JSON_UNESCAPED_UNICODE);
      
        $logArr['return_code'] = $MIDOutputParams["code"]??'';
        if($OutputParams["TimeStamp"]??null) $logArr['return_TimeStamp'] = $OutputParams["TimeStamp"];
        if(array_key_exists('fullcode',$MIDOutputParams??[])) $logArr['return_fullcode'] = $MIDOutputParams["fullcode"];
        $logAdvAuthApi = $user->log_adv_auth_api()->create($logArr);    
        
        $is_reach_s_pause = LogAdvAuthApi::countInInterval('small','pause') > $api_check_cfg['s']['pause_count'] ;
        $is_reach_l_pause = LogAdvAuthApi::countInInterval('large','pause')> $api_check_cfg['l']['pause_count'];
        $chinese_num_arr = ['一','二','三','四','五','六','七','八','九'];
        if(!LogAdvAuthApi::isPauseApi() && ($is_reach_s_pause || $is_reach_l_pause)) {
            $logAdvAuthApi->pause_api = 1;
            $logAdvAuthApi->save();
            $reason_of_pause = '';
            if($is_reach_s_pause) {
                $reason_of_pause = '因 '.$api_check_cfg['s']['interval'].' 分鐘內累計超過 '.$api_check_cfg['s']['pause_count'].' 筆';
            }
            
            if($is_reach_l_pause) {
                if($is_reach_s_pause) {
                    $reason_of_pause.='及';
                }
                else {
                    $reason_of_pause.='因';
                }

                $reason_of_pause.= (($api_check_cfg['l']['interval']%1440 || $api_check_cfg['l']['interval']/1440>=10)?$api_check_cfg['l']['interval'].'分鐘':$chinese_num_arr[$api_check_cfg['l']['interval']/1440-1].'天').'內累計超過 '.$api_check_cfg['l']['pause_count'].' 筆';
            }            
            if($reason_of_pause) $reason_of_pause.='，';
            Http::withToken($LineToken)->asForm()->post('https://notify-api.line.me/api/notify', [
                'message' =>$reason_of_pause.'已暫停進階驗證機制'
            ]);                
        }

        if(LogAdvAuthApi::countInInterval('small') > $api_check_cfg['s']['notify_count']  ) {
            $logAdvAuthApi->s_notify = 1;
            $logAdvAuthApi->save();
            Http::withToken($LineToken)->asForm()->post('https://notify-api.line.me/api/notify', [
                'message' => '進階認證 '.$api_check_cfg['s']['interval'].'  分鐘內累計已超過 '.$api_check_cfg['s']['notify_count'].'  筆'
            ]);            
        }  

        if(LogAdvAuthApi::countInInterval('large')>$api_check_cfg['l']['notify_count']) {
            $logAdvAuthApi->l_notify = 1;
            $logAdvAuthApi->save();
            Http::withToken($LineToken)->asForm()->post('https://notify-api.line.me/api/notify', [
                'message' => '進階認證'.(($api_check_cfg['l']['interval']%1440 || $api_check_cfg['l']['interval']/1440>=10)?$api_check_cfg['l']['interval'].'分鐘':$chinese_num_arr[$api_check_cfg['l']['interval']/1440-1].'天').'內累計已超過 '.$api_check_cfg['l']['notify_count'].' 筆'
            ]);              
        }          
        //驗證成功
        if($MIDOutputParams["code"]=="0000"){
            
            if(User::where('advance_auth_identity_encode',$encode_id_serial)->where('advance_auth_status',1)->count()) {
                $logAdvAuthApi->is_duplicate=1;
                $logAdvAuthApi->save();
                return back()->with('message', ['您的進階驗證功能有誤，<a href="https://lin.ee/rLqcCns" target="_blank">請點此 <img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="36" border="0" style="height: 36px; float: unset;"></a> 或點右下聯絡我們加站長 line 與站長聯絡。']);
            }
            $auth_date = date('Y-m-d H:i:s');
            $user->advance_auth_status = 1;
            $user->advance_auth_time = $auth_date;
            $user->advance_auth_identity_encode = $encode_id_serial;
            $user->advance_auth_birth = $format_birth;
            $user->advance_auth_phone = $request->phone_number;
            $user->save();
            $renew_meta = false;
            if(($user->meta->birthdate??'')!=$format_birth) {
                $user->meta->birthdate_old = $user->meta->birthdate;
                $user->meta->birthdate = $format_birth;
                $renew_meta = true;
            }
            
            if(($user->meta->phone??'')!=$phone_number) {
                $user->meta->phone = $phone_number;                
                $renew_meta = true;
            }   
            
            if($renew_meta) $user->meta->save();
            
            $user_active_mobile_query = $user->short_message()->where('active',1);
            $latest_user_active_mobile = $user_active_mobile_query->orderBy('createdate','DESC')->first();
            $phone_number_for_sms = substr_replace($phone_number,'+886',0,1);
            if($latest_user_active_mobile) {
                if($latest_user_active_mobile->mobile && $latest_user_active_mobile->mobile!=$phone_number && $latest_user_active_mobile->mobile!=$phone_number_for_sms){
                    $user_active_mobile_query->update(['active'=>0,'canceled_date'=>$auth_date,'canceled_by'=>'adv_auth']);
                    $user->short_message()->create(['mobile'=>$phone_number,'active'=>1]);
                }
                else {
                    $user_active_mobile_query->where('id','<>',$latest_user_active_mobile->id)->update(['active'=>0,'canceled_date'=>$auth_date,'canceled_by'=>'adv_auth']);
                }
            }
            else {
                $user->short_message()->create(['mobile'=>$phone_number,'active'=>1]);
            }
            
            $check_other_user_mobile_query = short_message::where('active',1)->where('member_id','<>',$user->id)
                ->where(function($query) use ($phone_number,$phone_number_for_sms){
                    $query->orwhere('mobile',$phone_number)
                        ->orwhere('mobile',$phone_number_for_sms);
                });
            
            if($check_other_user_mobile_query->count()) {
                $check_other_user_mobile_query->update(['active'=>0,'canceled_date'=>$auth_date,'canceled_by'=>'adv_auth']);
            }

            $userBanned = $user->getBannedOfAdvAuthQuery()->orderBy('created_at','DESC')->get()->first();
            $user_meta = $user->meta;

            $userWarned = $user->getWarnedOfAdvAuthQuery()->orderBy('created_at','DESC')->get()->first();                            
            $isWarnedUser = $user_meta->isWarnedType=='adv_auth'?$user_meta->isWarned:0;
            $banOrWarnCanceledMsg = [];
            $banOrWarnCanceledStr = '';
            if ($userBanned || $userWarned || $isWarnedUser) {
                if($userBanned) {
                    $checkLog = DB::table('is_banned_log')->where('user_id', $userBanned->member_id)->where('created_at', $userBanned->created_at)->first();
                    if(!$checkLog) {
                        //寫入log
                        DB::table('is_banned_log')->insert(['user_id' => $userBanned->member_id, 'reason' => $userBanned->reason, 'expire_date' => $userBanned->expire_date,'vip_pass'=>$userBanned->vip_pass,'adv_auth'=>$userBanned->adv_auth, 'created_at' => $userBanned->created_at]);
                    }
                    $userBanned->delete();
                    $banOrWarnCanceledMsg[] = '封鎖';
                }
                
                if($userWarned) {
                    $checkLog = DB::table('is_warned_log')->where('user_id', $userWarned->member_id)->where('created_at', $userWarned->created_at)->get()->first();
                    if(!$checkLog) {
                        //寫入log
                        DB::table('is_warned_log')->insert(['user_id' => $userWarned->member_id, 'reason' => $userWarned->reason, 'created_at' => $userWarned->created_at,'vip_pass'=>$userWarned->vip_pass,'adv_auth'=>$userWarned->adv_auth]);
                    }
                    $userWarned->delete();
                    $banOrWarnCanceledMsg[] = '警示';
                }
                
                if($isWarnedUser) {
                    $user->meta()->update(['isWarned'=>0,'isWarnedType'=>null]);
                    if(!in_array('警示',$banOrWarnCanceledMsg)) $banOrWarnCanceledMsg[] = '警示';
                }
                
                $banOrWarnCanceledStr = implode('/',$banOrWarnCanceledMsg);
            }

            return back()->with('message',['
                                            驗證成功：恭喜您，您的資料已經通過驗證，'.($banOrWarnCanceledStr?'成功解除'.$banOrWarnCanceledStr.'，':'').'
                                            系統會將您的手機號碼以及生日更新到您的基本資料。
                                            並獲得<img src="'.asset('new/images/b_7.png').'" class="adv_auth_icon" />進階驗證的標籤<img src="'.asset('new/images/b_7.png').'" class="adv_auth_icon" />          
                                    ']);
        }else{
            $fullcode = $MIDOutputParams["fullcode"];
            if($fullcode<=3644100 || $fullcode>=3645031
                || $fullcode==3645000 || $fullcode==3645001
                || $fullcode==3645000 || $fullcode==3645001    
            ) {
                $logAdvAuthApi->api_fault = 1;
                $logAdvAuthApi->save();
                return back()->with('message', ['系統目前無法進行驗證']);
            }
            else {
                if(!$user->isForbidAdvAuth() &&  $user->getEffectFaultAdvAuthApiQuery()->count()+1>=config('memadvauth.user.allow_fault')) {
                    $logAdvAuthApi->forbid_user = 1;
                }                
                $logAdvAuthApi->user_fault = 1;
                $logAdvAuthApi->save();

                if(($logAdvAuthApi->forbid_user??null)==1 ) {
                    return back()->with('message', ['您的進階驗證功能有誤，<a href="https://lin.ee/rLqcCns" target="_blank">請點此 <img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="36" border="0" style="height: 36px; float: unset;"></a> 或點右下聯絡我們加站長 line 與站長聯絡。']);
                }
              
                return back()->with('message', [
                    '<div>驗證失敗：抱歉驗證失敗，這是您輸入的資料：</div>
                                <div>身分證字號：'.$data['MemberNo'].'</div>
                                <div>手機號碼：'.$data['phone_number'].'</div>
                                <div>生日：'.str_replace('-','/',$format_birth).'</div>
                                <div>請確認無誤後，下次可申請的時間是 :'.Carbon::parse($logAdvAuthApi->created_at)->addMinutes(config('memadvauth.user.pause_during'))->format('Y/m/d H:i:s').'</div>
                         ']);                
            }
        }
    }
    
    public function advance_auth_result(Request $request){
        $data['BusinessNo'] = $request->BusinessNo;
        $data['ApiVersion'] = $request->ApiVersion;
        $data['HashKeyNo'] = $request->HashKeyNo;
        $data['VerifyNo'] = $request->VerifyNo;
        $data['MemberNoMapping'] = $request->MemberNoMapping;
        $data['Token'] = $request->Token;
        $res = $this->advance_auth_query($data);
        $auth_status = $request->ReturnCode;
        return view('/auth/advance_auth_result')
                    ->with('auth_status', $auth_status);
    }
    
    public function advance_auth_midclause(Request $request) {
        $user = $request->user();        
        return view('auth/advance_auth_midclause');
    }

    public function is_advance_auth(Request $request)
    {
        $count = User::where('id',$request->id)->where('advance_auth_status',1)->count();
        $res = $count >0 ? 1:0;
        return $res;
    }

    public function get_identify_no_do($data){
        //串聯資料
        $concat = $data['BusinessNo'].$data['ApiVersion'].$data['HashKeyNo'].$data['VerifyNo'].$data['InputParams'].$data['HashKey'];
        //調整編碼(還不確定原本編碼是否UTF8)
        $concat_utf16le = mb_convert_encoding($concat, "UTF-16LE", "UTF-8");
        $result = hash('sha256', $concat_utf16le);
        return $result;
    }

    public function get_identify_no_query($data){
        //串聯資料
        $concat = $data['BusinessNo'].$data['ApiVersion'].$data['HashKeyNo'].$data['VerifyNo'].$data['MemberNo'].$data['Token'].$data['HashKey'];
        //調整編碼(還不確定原本編碼是否UTF8)
        $concat_utf16le = mb_convert_encoding($concat, "UTF-16LE", "UTF-8");
        $result = hash('sha256', $concat_utf16le);
        return $result;
    }

    public function get_mid_clause($data){
        $api_url = $data['api_base'].config('memadvauth.service.uri');
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$api_url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $return_output = curl_exec ($ch);

        curl_close ($ch);
        $output = json_decode($return_output);

        return $output;
    }

    public function get_transaction($data){
        $api_url = $data['api_base'].'IDPortal/ServerSideTransaction';
        $BusinessNo = $data['BusinessNo'];
        $ApiVersion = $data['ApiVersion'];
        $HashKeyNo = $data['HashKeyNo'];
        $VerifyNo = $data['VerifyNo'];
        $IdentifyNo = $this->get_identify_no_do($data);;
        $InputParams = $data['InputParams'];
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                    "BusinessNo=$BusinessNo&ApiVersion=$ApiVersion&HashKeyNo=$HashKeyNo&VerifyNo=$VerifyNo&IdentifyNo=$IdentifyNo&InputParams=$InputParams");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $return_output = curl_exec ($ch);

        curl_close ($ch);

        $output = json_decode($return_output, JSON_UNESCAPED_UNICODE);

        return $output;
    }

    public function get_verify_result($data){
        $api_url = $data['api_base'].'IDPortal/ServerSideVerifyResult';
        if(!json_decode($data['return']['OutputParams'], JSON_UNESCAPED_UNICODE)) return;
        
        $Token = $data['Token']= json_decode($data['return']['OutputParams'], JSON_UNESCAPED_UNICODE)["Token"];
        $BusinessNo = $data['BusinessNo'];
        $ApiVersion = $data['ApiVersion'];
        $HashKeyNo = $data['HashKeyNo'];
        $VerifyNo = $data['VerifyNo'];
        $MemberNo = $data['MemberNo'];
        $IdentifyNo = $this->get_identify_no_query($data);
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                    "BusinessNo=$BusinessNo&ApiVersion=$ApiVersion&HashKeyNo=$HashKeyNo&VerifyNo=$VerifyNo&MemberNo=$MemberNo&Token=$Token&IdentifyNo=$IdentifyNo");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $return_output = curl_exec ($ch);
        curl_close($ch);

        $output = json_decode($return_output, JSON_UNESCAPED_UNICODE);
        return $output;
    }
    public function hint_auth1(Request $request){
        return view('/auth/hint_auth1');
    }

    public function hint_auth2(Request $request){
        return view('/auth/hint_auth2');
    }

    public function posts_list(Request $request)
    {
        $user = $this->user;
        if ($user && $user->engroup == 2){
            return back();
        }

//        $ban = banned_users::where('member_id', $user->id)->first();
//        $banImplicitly = \App\Models\BannedUsersImplicitly::where('target', $user->id)->first();
//        if($ban || $banImplicitly){
//            return back();
//        }

        $posts = Posts::selectraw('posts.top, users.id as uid, users.name as uname, users.engroup as uengroup, posts.is_anonymous as panonymous, user_meta.pic as umpic, posts.id as pid, posts.title as ptitle, posts.contents as pcontents, posts.updated_at as pupdated_at, posts.created_at as pcreated_at')
            ->selectRaw('(select updated_at from posts where (type="main" and id=pid) or reply_id=pid or reply_id in ((select distinct(id) from posts where type="sub" and reply_id=pid) )  order by updated_at desc limit 1) as currentReplyTime')
            ->selectRaw('(case when users.id=1049 then 1 else 0 end) as adminFlag')
            ->LeftJoin('users', 'users.id','=','posts.user_id')
            ->join('user_meta', 'users.id','=','user_meta.user_id')
            ->where('posts.type','main')
            ->orderBy('posts.top','desc')
            ->orderBy('adminFlag','desc')
            ->orderBy('currentReplyTime','desc')
            ->paginate(10);

        $data = array(
//            'posts' => null
            'posts' => $posts
        );

        if ($user)
        {
            // blocked by user->id
            $blocks = \App\Models\Blocked::where('member_id', $user->id)->paginate(15);

            $usersInfo = array();
            foreach($blocks as $blockUser){
                $id = $blockUser->blocked_id;
                $usersInfo[$id] = User::findById($id);
            }
            
        }

        //檢查是否為連續兩個月以上的VIP會員
        $checkUserVip=0;
        $isVip =Vip::where('member_id',auth()->user()->id)->where('active',1)->where('free',0)->first();
        if($isVip){
//            $months = Carbon::parse($isVip->created_at)->diffInMonths(Carbon::now());
//            if($months>=2 || $isVip->payment=='cc_quarterly_payment' || $isVip->payment=='one_quarter_payment'){
                $checkUserVip=1;
//            }
        }

        return view('/dashboard/posts_list', $data)
        ->with('checkUserVip', $checkUserVip)
        ->with('blocks', $blocks)
        ->with('users', $usersInfo)
        ->with('user', $user);
    }

    public function post_detail(Request $request)
    {
//        return redirect(url('/dashboard/posts_list'));
        $user = $request->user();

        $pid = $request->pid;
        //$this->post_views($pid);
        $postDetail = Posts::selectraw('posts.top, users.id as uid, users.name as uname, users.engroup as uengroup, posts.is_anonymous as panonymous, posts.views as uviews, user_meta.pic as umpic, posts.id as pid, posts.title as ptitle, posts.contents as pcontents, posts.updated_at as pupdated_at,  posts.created_at as pcreated_at')
            ->LeftJoin('users', 'users.id','=','posts.user_id')
            ->join('user_meta', 'users.id','=','user_meta.user_id')
            ->where('posts.id', $pid)->first();

        if(!$postDetail) {
            $request->session()->flash('message', '找不到文章：' . $pid);
            $request->session()->reflash();
            return redirect()->route('posts_list');
        }

        $replyDetail = Posts::selectraw('users.id as uid, users.name as uname, users.engroup as uengroup, posts.is_anonymous as panonymous, posts.views as uviews, user_meta.pic as umpic, posts.id as pid, posts.title as ptitle, posts.contents as pcontents, posts.updated_at as pupdated_at,  posts.created_at as pcreated_at')
            ->LeftJoin('users', 'users.id','=','posts.user_id')
            ->join('user_meta', 'users.id','=','user_meta.user_id')
            ->orderBy('pcreated_at','desc')
            ->where('posts.reply_id', $pid)->get();

        //檢查是否為連續兩個月以上的VIP會員
        $checkUserVip=0;
        $isVip =Vip::where('member_id',auth()->user()->id)->where('active',1)->where('free',0)->first();
        if($isVip){
//            $months = Carbon::parse($isVip->created_at)->diffInMonths(Carbon::now());
//            if($months>=2 || $isVip->payment=='cc_quarterly_payment' || $isVip->payment=='one_quarter_payment'){
                $checkUserVip=1;
//            }
        }
        return view('/dashboard/post_detail', compact('postDetail','replyDetail', 'checkUserVip'))->with('user', $user);
    }

    public function getPosts(Request $request)
    {
        $page = $request->page;
        $perPage = 10;
        $startPost = $page*$perPage;
        
        /*撈取資料*/
    }

    public function posts(Request $request)
    {
//        return redirect(url('/dashboard/posts_list'));
        $user = $this->user;
        if ($user && $user->engroup == 2){
            return back();
        }
        $url = $request->fullUrl();
        //echo $url;

        if(str_contains($url, '?img')) {
            $tabName = 'm_user_profile_tab_4';
        }
        else {
            $tabName = 'm_user_profile_tab_1';
        }

        $member_pics = DB::table('member_pic')->select('*')->where('member_id',$user->id)->get()->take(6);

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
                return view('/dashboard/posts')
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
                return view('/dashboard/posts')
                    ->with('user', $user)
                    ->with('tabName', $tabName)
                    ->with('cur', $user)
                    ->with('year', $year)
                    ->with('month', $month)
                    ->with('day', $day)
                    ->with('member_pics', $member_pics);
            }else{
                return view('/dashboard/posts')
                    ->with('user', $user)
                    ->with('tabName', $tabName)
                    ->with('cur', $user)
                    ->with('year', $year)
                    ->with('month', $month)
                    ->with('day', $day)
                    ->with('member_pics', $member_pics);
            }
        }
    }

    public function postsEdit($id, $editType='all')
    {
        $postInfo = Posts::find($id);
        return view('/dashboard/posts_edit',compact('postInfo','editType'));
    }

    public function doPosts(Request $request)
    {
        $user=$request->user();

        if($request->get('action') == 'update'){
            Posts::find($request->get('post_id'))->update(['title'=>$request->get('title'),'contents'=>$request->get('contents')]);
            return redirect($request->get('redirect_path'))->with('message','修改成功');

        }else{
            $posts = new Posts;
            $posts->user_id = $user->id;
            $posts->title = $request->get('title');
            $posts->type = $request->get('type','main');
            $posts->contents=$request->get('contents');
            $posts->save();
            return redirect('/dashboard/posts_list')->with('message','發表成功');
        }
    }

    public function posts_reply(Request $request)
    {
        $posts = new Posts;
        $posts->reply_id = $request->get('reply_id');
        $posts->user_id = $request->get('user_id');
        $posts->type = $request->get('type','sub');
        $posts->contents   = str_replace('..','',$request->get('contents'));
        $posts->tag_user_id = $request->get('tag_user_id');
        $posts->save();

        return back()->with('message', '留言成功!');
    }

    public function posts_delete(Request $request)
    {
        $posts = Posts::where('id',$request->get('pid'))->first();
        if($posts->user_id!== auth()->user()->id){
            return response()->json(['msg'=>'留言刪除失敗 不可刪除別人的留言!']);
        }else{
            $postsType = $posts->type;
            $posts->delete();

            if($postsType=='main')
                return response()->json(['msg'=>'刪除成功!','postType'=>'main','redirectTo'=>'/dashboard/posts_list']);
            else
                return response()->json(['msg'=>'留言刪除成功!','postType'=>'sub']);
        }
    }

    public function post_views($pid)
    {
        $views = Posts::where('id', $pid)->first()->views;
        $update = array(
            'views'=>$views+1,
        );
        Posts::where('id', $pid)->update($update);
    }
    
    public function postAcceptor(Request $request)
    {
        
        /***************************************************
         * Only these origins are allowed to upload images *
         ***************************************************/
        // $accepted_origins = array("http://localhost", "http://localsugargarden.org", "http://192.168.1.1", "http://example.com");

        /*********************************************
         * Change this line to set the upload folder *
         *********************************************/
        $imageFolder = 'images/';
// dump('1');
        reset ($_FILES);
        $temp = current($_FILES);
        if (is_uploaded_file($temp['tmp_name'])){
            // dump($_SERVER['HTTP_ORIGIN']);
            // if (isset($_SERVER['HTTP_ORIGIN'])) {
            // // same-origin requests won't set an origin. If the origin is set, it must be valid.
            // if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
            //     header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            // } else {
            //     header("HTTP/1.1 403 Origin Denied");
            //     return;
            // }
            // }
            /*
            If your script needs to receive cookies, set images_upload_credentials : true in
            the configuration and enable the following two headers.
            // */
            // header('Access-Control-Allow-Credentials: true');
            // header('P3P: CP="There is no P3P policy."');

            // // // Sanitize input
            // if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
            //     header("HTTP/1.1 400 Invalid file name.");
            //     return;
            // }

            // // // Verify extension
            // if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
            //     header("HTTP/1.1 400 Invalid extension.");
            //     return;
            // }

            // Accept upload if there was no origin, or if it is an accepted origin
            $filetowrite = $imageFolder . $temp['name'];
            move_uploaded_file($temp['tmp_name'], $filetowrite);

            // Respond to the successful upload with JSON.
            // Use a location key to specify the path to the saved image resource.
            // { location : '/your/uploaded/image/file'}
// dd($filetowrite);
            echo json_encode(array('location' => $filetowrite));
            
        } else {
            // Notify editor that the upload failed
            @header("HTTP/1.1 500 Server Error");
        }
    }

    public function forum(Request $request)
    {
        $user=$request->user();
        if ($user && $user->engroup == 2) {
            return back();
        }

        $posts_list = Posts::selectraw('
         posts.id as pid,
         users.id as uid,
         users.engroup,
         user_meta.pic as umpic
         ')->selectRaw('
            (select count(*) from posts left join users on users.id = posts.user_id where (posts.type="main")) as posts_num, 
            (select count(*) from posts where (type="sub" and reply_id in (select id from posts where (type="main") ) )) as posts_reply_num
            ')
            ->LeftJoin('users', 'users.id','=','posts.user_id')
            ->join('user_meta', 'users.id','=','user_meta.user_id')
            ->groupBy('users.id')
            ->take(6)->get();

        $post_forum = Forum::where('user_id', $user->id)->first();

        $posts = Forum::selectraw('
         users.id as uid, 
         users.name as uname, 
         users.engroup as uengroup, 
         user_meta.pic as umpic, 
         forum_posts.id as pid,
         forum.id as f_id,
         forum.status as f_status,
         forum.title as f_title,
         forum.sub_title as f_sub_title
         ')
            ->selectRaw('(select updated_at from forum_posts where (type="main" and id=pid and forum_id = f_id and deleted_at is null) or reply_id=pid or reply_id in ((select distinct(id) from forum_posts where type="sub" and reply_id=pid and forum_id = f_id and deleted_at is null) )  order by updated_at desc limit 1) as currentReplyTime')
            ->selectRaw('(select count(*) from forum_posts where (type="main" and user_id=uid and forum_id = f_id and deleted_at is null)) as posts_num, (select count(*) from forum_posts where (type="sub" and forum_id = f_id and deleted_at is null and reply_id in (select id from forum_posts where (type="main" and user_id = uid and forum_id = f_id and deleted_at is null)) )) as posts_reply_num')
            ->LeftJoin('users', 'users.id','=','forum.user_id')
            ->join('user_meta', 'users.id','=','user_meta.user_id')
            ->leftJoin('forum_posts', 'forum_posts.user_id','=', 'users.id')
//            ->where('forum.status', 1)
            ->orderBy('forum.status', 'desc')
            ->orderBy('currentReplyTime','desc')
            ->groupBy('forum.id')
            ->paginate(10);

        $data = array(
            //            'posts' => null
            'posts' => $posts,
            'post_forum' => $post_forum,
            'posts_list' => $posts_list
        );

        //檢查是否為連續兩個月以上的VIP會員
        $checkUserVip=0;
        $isVip =Vip::where('member_id',auth()->user()->id)->where('active',1)->where('free',0)->first();
        if($isVip){
//            $months = Carbon::parse($isVip->created_at)->diffInMonths(Carbon::now());
//            if($months>=2 || $isVip->payment=='cc_quarterly_payment' || $isVip->payment=='one_quarter_payment'){
                $checkUserVip=1;
//            }
        }

        return view('/dashboard/forum', $data)
            ->with('checkUserVip', $checkUserVip)
            ->with('user', $user);
    }

    public function ForumEdit($uid)
    {
        $user = $this->user;
        $forumInfo = Forum::where('user_id', $uid)->first();
        return view('/dashboard/forum_edit', compact('forumInfo'))->with('user', $user);
    }

    public function doForum(Request $request)
    {
        $user=$request->user();

        if($request->get('action') == 'update'){
            Forum::find($request->get('forum_id'))->update(['title'=>$request->get('title'),'sub_title'=>$request->get('sub_title')]);
            return redirect('/dashboard/forum')->with('message','修改成功');

        }else{
            $postsForum = new Forum();
            $postsForum->user_id = $user->id;
            $postsForum->title = $request->get('title');
            $postsForum->sub_title=$request->get('sub_title');
            $postsForum->save();
            return redirect('/dashboard/forum')->with('message','新增成功');
        }
    }

    public function doForumPosts(Request $request)
    {
        $user=$request->user();

        if($request->get('action') == 'update'){
            ForumPosts::find($request->get('id'))->update(['title'=>$request->get('title'),'contents'=>$request->get('contents')]);
            return redirect($request->get('redirect_path'))->with('message','修改成功');

        }else{
            $posts = new ForumPosts();
            $posts->forum_id = $request->get('forum_id');
            $posts->user_id = $user->id;
            $posts->title = $request->get('title');
            $posts->type = $request->get('type','main');
            $posts->contents=$request->get('contents');
            $posts->save();
            //            return redirect('/dashboard/posts_list')->with('message','發表成功');
            return redirect('/dashboard/forum_personal/'.$user->id)->with('message','發表成功');
        }
    }

    public function forumPostsEdit($id, $editType='all')
    {
        $postInfo = ForumPosts::find($id);
        return view('/dashboard/forum_posts_edit',compact('postInfo','editType'));
    }

    public function forum_personal(Request $request)
    {
        //        return redirect(url('/dashboard/posts_list'));
        $user = $request->user();

        $uid = $request->uid;

        if($user->id != $uid && $uid != 1049) {
            $checkPostMangeStatus = ForumManage::where('user_id', $user->id)->where('apply_user_id', $uid)->first();
            if(!isset($checkPostMangeStatus)){
                return back()->with('message', '您無法進入此討論區');
            }elseif($checkPostMangeStatus->status == 0 && isset($checkPostMangeStatus)) {
                return back()->with('message', '此討論區尚在申請中');
            }elseif ($checkPostMangeStatus->status == 2) {
                return back()->with('message', '您無法進入此討論區');
            }
        }

        $post_forum = Forum::where('user_id', $uid)->first();

        $posts_personal_all = ForumPosts::selectraw('
        users.id as uid, 
        users.name as uname, 
        users.engroup as uengroup, 
        forum_posts.is_anonymous as panonymous, 
        forum_posts.views as uviews, 
        forum_posts.top,
        user_meta.pic as umpic, 
        forum_posts.id as pid, 
        forum_posts.forum_id as f_id, 
        forum_posts.title as ptitle, 
        forum_posts.contents as pcontents, 
        forum_posts.updated_at as pupdated_at,  
        forum_posts.created_at as pcreated_at,
        (select count(*) from forum_posts where (type="sub" and forum_id = f_id and deleted_at is null and reply_id in (pid, EXISTS (select id from forum_posts where (type="sub" and reply_id = pid and forum_id = f_id and deleted_at is null ))) )) as posts_reply_num
        ')
            ->LeftJoin('users', 'users.id','=','forum_posts.user_id')
            ->leftJoin('forum', 'forum.user_id', 'forum_posts.user_id')
            ->join('user_meta', 'users.id','=','user_meta.user_id')
            ->where('forum.status', 1)
            ->where('forum_posts.user_id', $uid)
            ->where('forum_posts.type', 'main')
            ->orderBy('forum_posts.top', 'desc')
            ->orderBy('pupdated_at', 'desc')
            ->paginate(10);

        //        if(!$postDetail) {
        //            $request->session()->flash('message', '找不到文章：' . $pid);
        //            $request->session()->reflash();
        //            return redirect()->route('posts_list');
        //        }

        //        $replyDetail = Posts::selectraw('users.id as uid, users.name as uname, users.engroup as uengroup, posts.is_anonymous as panonymous, posts.views as uviews, user_meta.pic as umpic, posts.id as pid, posts.title as ptitle, posts.contents as pcontents, posts.updated_at as pupdated_at,  posts.created_at as pcreated_at')
        //            ->LeftJoin('users', 'users.id','=','posts.user_id')
        //            ->join('user_meta', 'users.id','=','user_meta.user_id')
        //            ->orderBy('pcreated_at','desc')
        //            ->where('posts.reply_id', $pid)->get();

        //檢查是否為連續兩個月以上的VIP會員
        $checkUserVip=0;
        $isVip =Vip::where('member_id',auth()->user()->id)->where('active',1)->where('free',0)->first();
        if($isVip){
//            $months = Carbon::parse($isVip->created_at)->diffInMonths(Carbon::now());
//            if($months>=2 || $isVip->payment=='cc_quarterly_payment' || $isVip->payment=='one_quarter_payment'){
                $checkUserVip=1;
//            }
        }
        return view('/dashboard/forum_personal', compact('posts_personal_all','post_forum', 'checkUserVip'))->with('user', $user);
    }

    public function forum_manage(Request $request)
    {

        $user = $request->user();

        $posts_forum = Forum::where('user_id', $user->id)->first();

        if(!$posts_forum) {
            return back()->with('message', '您的討論區不存在。');
        }

        $posts_manage_users = ForumManage::select('forum_manage.user_id','users.name','forum_manage.status')
            ->leftJoin('users', 'users.id','=','forum_manage.user_id')
            ->where('forum_manage.apply_user_id', $user->id)
            ->where('status','<>', 2)
            ->paginate(15);
        //檢查是否為連續兩個月以上的VIP會員
        $checkUserVip=0;
        $isVip =Vip::where('member_id',auth()->user()->id)->where('active',1)->where('free',0)->first();
        if($isVip){
//            $months = Carbon::parse($isVip->created_at)->diffInMonths(Carbon::now());
//            if($months>=2 || $isVip->payment=='cc_quarterly_payment' || $isVip->payment=='one_quarter_payment'){
                $checkUserVip=1;
//            }
        }

        return view('/dashboard/forum_manage', compact( 'posts_manage_users', 'posts_forum', 'checkUserVip'))->with('user', $user);
    }

    public function forum_manage_toggle(Request $request)
    {
        $user = $request->user();
        $uid = $request->uid;
        $auid = $request->auid;
        $status = $request->status;
        $fid = Forum::where('user_id', $auid)->first();
        $checkData = ForumManage::where('forum_id', $fid->id)->where('user_id', $uid)->where('apply_user_id', $auid)->first();
        if($status==0){
            if(!isset($checkData)){
                ForumManage::insert(['forum_id'=>$fid->id, 'user_id' => $uid, 'apply_user_id' => $auid, 'status'=> 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
                $msg = '申請成功';
//                ForumManage::insert([
//                    'forum_id'=>$fid->id,
//                    'user_id' => $uid,
//                    'apply_user_id' => $auid,
//                    'created_at' => Carbon::now(),
//                    'updated_at' => Carbon::now()
//                ]);
//                $msg = '申請成功';
            }else{
                $msg = '已重複申請';
            }

        }else if($status==1){
            if(isset($checkData)){
                ForumManage::where('user_id', $uid)->where('apply_user_id', $auid)->update(['status' => $status, 'updated_at' => Carbon::now()]);
                $msg = '該會員已通過';
            }else{
                $msg = 'error';
            }

        }else if($status==2){
            if(isset($checkData)){
                ForumManage::where('user_id', $uid)->where('apply_user_id', $auid)->update(['status' => $status, 'updated_at' => Carbon::now()]);
                $msg = '已拒絕該會員申請';
            }else{
                $msg = 'error';
            }

        }
        else if($status==3){
            if(isset($checkData)){
                ForumManage::where('user_id', $uid)->where('apply_user_id', $auid)->delete();
                if($auid = $user->id){
                    $msg = '已移除該會員';
                }else {
                    $msg = '已取消申請';
                }
            }else{
                $msg = 'error';
            }
        }
        else{
            $msg = 'error';
        }

        echo json_encode(['message'=>$msg]);
    }

    public function forum_manage_chat($auid, $uid)
    {
        $user = $this->user;

        if($auid != $uid) {

            $forumInfo = Forum::select('forum.*')
                ->leftJoin('users', 'users.id','=','forum.user_id')
                ->where('forum.user_id', $auid)->first();

            $checkStatus = ForumManage::select('forum_manage.status','users.name', 'forum_manage.user_id', 'forum_manage.apply_user_id')
                ->leftJoin('users', 'users.id','=','forum_manage.apply_user_id')
                ->where('forum_manage.user_id', $uid)
                ->where('forum_manage.apply_user_id', $auid)
                ->where('forum_manage.status','<>', 2)
                ->get()->first();

            $uidInfo = User::where('id', $uid)->first();

        }

        if(!isset($checkStatus)) {
            return redirect()->route('forum')->with('message', '您已無法進入此聊天室');
        }

        //檢查是否為連續兩個月以上的VIP會員
        $checkUserVip=0;
        $isVip =Vip::where('member_id',auth()->user()->id)->where('active',1)->where('free',0)->first();
        if($isVip){
//            $months = Carbon::parse($isVip->created_at)->diffInMonths(Carbon::now());
//            if($months>=2 || $isVip->payment=='cc_quarterly_payment' || $isVip->payment=='one_quarter_payment'){
                $checkUserVip=1;
//            }
        }

        return view('/dashboard/forum_manage_chat', compact('checkStatus', 'forumInfo', 'uidInfo', 'checkUserVip'))->with('user', $user);
    }

    public function forum_posts($fid)
    {
        $user = $this->user;
        if ($user && $user->engroup == 2){
            return back();
        }

        if ($user) {
            return view('/dashboard/forum_posts')
                ->with('user', $user)
                ->with('cur', $user)
                ->with('fid', $fid);
        }
    }

    public function forum_post_detail(Request $request)
    {
        //        return redirect(url('/dashboard/posts_list'));
        $user = $request->user();

        $pid = $request->pid;
        //$this->post_views($pid);
        $postDetail = ForumPosts::selectraw('forum_posts.forum_id, users.id as uid, users.name as uname, users.engroup as uengroup, forum_posts.is_anonymous as panonymous, forum_posts.views as uviews, user_meta.pic as umpic, forum_posts.id as pid, forum_posts.title as ptitle, forum_posts.contents as pcontents, forum_posts.updated_at as pupdated_at,  forum_posts.created_at as pcreated_at')
            ->LeftJoin('users', 'users.id','=','forum_posts.user_id')
            ->join('user_meta', 'users.id','=','user_meta.user_id')
            ->where('forum_posts.id', $pid)->first();

        if(!$postDetail) {
            $request->session()->flash('message', '找不到文章：' . $pid);
            $request->session()->reflash();
            return redirect()->route('forum');
        }

        $replyDetail = ForumPosts::selectraw('forum_posts.forum_id, users.id as uid, users.name as uname, users.engroup as uengroup, forum_posts.is_anonymous as panonymous, forum_posts.views as uviews, user_meta.pic as umpic, forum_posts.id as pid, forum_posts.title as ptitle, forum_posts.contents as pcontents, forum_posts.updated_at as pupdated_at,  forum_posts.created_at as pcreated_at')
            ->LeftJoin('users', 'users.id','=','forum_posts.user_id')
            ->join('user_meta', 'users.id','=','user_meta.user_id')
            ->orderBy('pcreated_at','desc')
            ->where('forum_posts.reply_id', $pid)->get();

        //檢查是否為連續兩個月以上的VIP會員
        $checkUserVip=0;
        $isVip =Vip::where('member_id',auth()->user()->id)->where('active',1)->where('free',0)->first();
        if($isVip){
//            $months = Carbon::parse($isVip->created_at)->diffInMonths(Carbon::now());
//            if($months>=2 || $isVip->payment=='cc_quarterly_payment' || $isVip->payment=='one_quarter_payment'){
                $checkUserVip=1;
//            }
        }
        return view('/dashboard/forum_post_detail', compact('postDetail','replyDetail', 'checkUserVip'))->with('user', $user);
    }

    public function forum_posts_reply(Request $request)
    {
        $posts = new ForumPosts;
        $posts->forum_id = $request->get('forum_id');
        $posts->reply_id = $request->get('reply_id');
        $posts->user_id = $request->get('user_id');
        $posts->type = $request->get('type','sub');
        $posts->contents   = str_replace('..','',$request->get('contents'));
        $posts->tag_user_id = $request->get('tag_user_id');
        $posts->save();

        return back()->with('message', '留言成功!');
    }

    public function forum_posts_delete(Request $request)
    {
        $posts = ForumPosts::where('id',$request->get('pid'))->first();
        if($posts->user_id!== auth()->user()->id){
            return response()->json(['msg'=>'留言刪除失敗 不可刪除別人的留言!']);
        }else{
            $postsType = $posts->type;
            $posts->delete();

            if($postsType=='main')
                return response()->json(['msg'=>'刪除成功!','postType'=>'main','redirectTo'=>'/dashboard/forum_personal/'.auth()->user()->id]);
            else
                return response()->json(['msg'=>'留言刪除成功!','postType'=>'sub']);
        }
    }
    public function sms_add_view(Request $request){
        return view('/sms/sms_add_view');
    }

    public function sms_add_list(Request $request){
        $data['lists'] = DB::select("SELECT * FROM message_post ORDER BY createdAt DESC");

        // dd($data);
        return view('/sms/sms_list', $data);
    }

    public function sms_add(Request $request){
        $message = $request->message;
        $insert_result = DB::insert("INSERT INTO message_post (message) VALUES ('$message')");

        if($insert_result){
            $data = array(
                'code'=>'200',
                'msg'=>'success'
            );
        }else{
            $data = array(
                'code'=>'400',
                'msg'=>'failed'
            );
        }

        return json_encode($data);
    }

    public function checkTourRead(Request $request)
    {
        $user_id = $request->uid;
        $page = $request->page;
        $step = $request->step;

        $checkData = DB::table('tour_read')->where('user_id',$user_id)->where('page',$page)->where('step',$step)->where('isRead',1)->first();
        if(isset($checkData)){

            $isRead =1;
        }else{
            $isRead =0;
            //DB::table('tour_read')->insert(['user_id' => $user_id,'page'=>$page,'step'=>$step,'isRead'=>1]);
        }
        return response()->json(['isRead'=>$isRead]);
    }

    public function letTourRead(Request $request)
    {
        $user_id = $request->uid;
        $page = $request->page;
        $step = $request->step;

        $checkData = DB::table('tour_read')->where('user_id',$user_id)->where('page',$page)->where('step',$step)->where('isRead',1)->first();
        if(!isset($checkData)){
            DB::table('tour_read')->insert(['user_id' => $user_id,'page'=>$page,'step'=>$step,'isRead'=>1]);
            $result='ok';
        }else{
            $result='error';
        }
        return $result;
    }

    public function adminMsgRead(Request $request, $id)
    {
        $user = $request->user();
        $message=Message::find($id);
        Message::read($message, $user->id);
        return response()->json(array(
            'status' => 1,
            'msg' => 'success',
        ), 200);
    }
    public function adminMsgPage(Request $request) {
        $user = $request->user();
        $admin = AdminService::checkAdmin();
        $uid=$user->id;
        $sid=$admin->id;
        $sys_notice = intval(!$request->manual);
        $includeDeleted=false;
        $query = Message::whereNotNull('id');
        $query = $query->where(function ($query) use ($uid,$sid,$includeDeleted) {
            $whereArr1 = [['to_id', $uid],['from_id', $sid]];
            $whereArr2 = [['from_id', $uid],['to_id', $sid]];
            if(!$includeDeleted) {
                array_push($whereArr1,['is_single_delete_1','<>',$uid],['is_row_delete_1','<>',$uid]);
                array_push($whereArr2,['is_single_delete_1','<>',$uid],['is_row_delete_1','<>',$uid]);
            }
            $query->where($whereArr1);
        });
        if($sys_notice) $query->where('sys_notice',1);
        else $query->where('sys_notice',0)->orWhereNull('sys_notice');
        $query = $query->orderBy('created_at', 'desc')->orderBy('read');
        if(!$sys_notice) $query->appends(['manual'=>intval(!$sys_notice)]);

        $unreadCount=0;
        $readCount=0;
        foreach($query->get() as $msg) {
            if($msg->read=='Y'){
                $readCount++;
            }else{
                $unreadCount++;
            }
        }
        $admin_msgData=$query->paginate(10);

        return view('/new/dashboard/adminMsgPage',compact('admin_msgData','readCount', 'unreadCount'))
            ->with('user', $user)
            ->with('admin', $admin)
            ->with('msg_spoken',$sys_notice?'系統來訊通知':'站長來訊通知');

    }

    public function personalPage(Request $request) {
        $admin = AdminService::checkAdmin();
        $user = \View::shared('user');

        $vipStatus = '您目前還不是VIP，<a class="red" href="../dashboard/new_vip">立即成為VIP!</a>';
        $picTypeNameStrArr = ['avatar'=>'大頭照','member_pic'=> '生活照']; 
        $user->load('vip');
        $existHeaderImage = $user->existHeaderImage(); 
        $latest_pic_act_log = $vipStatusMsgType 
        = $vipStatusPicTime = $vipStatusPicStr
        = $firstRemindingLog = $lastPicRecoverLog
        =null;
        if($user->engroup==2 || $user->isFreeVip()) {
            $latest_pic_act_log = $user->log_free_vip_pic_acts()->orderBy('created_at','DESC')->first();              
            if($latest_pic_act_log && in_array($latest_pic_act_log->sys_react??null,LogFreeVipPicAct::$needFirstRemindSysReacts)) {
                $lastPicRecoverLog = $user->log_free_vip_pic_acts()->where([['id','<>',$latest_pic_act_log->id],['created_at','<',$latest_pic_act_log->created_at]])->whereIn('sys_react',LogFreeVipPicAct::$reachRuleSysReacts)->orderBy('created_at', 'DESC')->first();
                $firstRemindingLogQuery = $user->log_free_vip_pic_acts()->where([['created_at','<=',$latest_pic_act_log->created_at]])->where('sys_react','reminding')->orderBy('created_at');
                if($lastPicRecoverLog) $firstRemindingLogQuery->where('created_at','>',($lastPicRecoverLog->created_at??'0000-00-00 00:00:00'));
                $firstRemindingLog =  $firstRemindingLogQuery->first();   
            }
            if($latest_pic_act_log && in_array($latest_pic_act_log->sys_react??null,LogFreeVipPicAct::$replaceByFirstRemindSysReacts)) {
                if($firstRemindingLog) $latest_pic_act_log = $firstRemindingLog;
            } 

            if($latest_pic_act_log ) {
                $vipStatusMsgType = $latest_pic_act_log->sys_react??null;
                $vipStatusPicTime = ($latest_pic_act_log->created_at??null)?Carbon::parse($latest_pic_act_log->created_at):null; 
                $vipStatusPicStr =  $picTypeNameStrArr[$latest_pic_act_log->pic_type??'']??'';            
            }
        }

        
        if($user->isVip()) {
            $vipStatus='您已是 VIP';
            $vip_record = Carbon::parse($user->vip_record);
            $vipDays = $vip_record->diffInDays(Carbon::now());
            if(!$user->isFreeVip()) {               
                $vip = $user->vip->first();               
                if($vip->payment){

                    switch ($vip->payment_method){
                        case 'CREDIT':
                            $payment = '信用卡繳費';
                            break;
                        case 'ATM':
                            $payment = 'ATM繳費';
                            break;
                        case 'CVS':
                            $payment = '超商代碼繳費';
                            break;
                        case 'BARCODE':
                            $payment = '超商條碼繳費';
                            break;
                        default:
                            $payment = '';
                    }
                    if(\App::environment('local')){
                        $envStr = '_test';
                    }
                    else{
                        $envStr = '';
                    }
                    if(substr($vip->payment,0,3) == 'cc_' && $vip->business_id == Config::get('ecpay.payment'.$envStr.'.MerchantID')){

                        $ecpay = new \App\Services\ECPay_AllInOne();
                        $ecpay->MerchantID = Config::get('ecpay.payment'.$envStr.'.MerchantID');
                        $ecpay->ServiceURL = Config::get('ecpay.payment'.$envStr.'.ServiceURL');//定期定額查詢
                        $ecpay->HashIV = Config::get('ecpay.payment'.$envStr.'.HashIV');
                        $ecpay->HashKey = Config::get('ecpay.payment'.$envStr.'.HashKey');
                        $ecpay->Query = [
                            'MerchantTradeNo' => $vip->order_id,
                            'TimeStamp' => 	time()
                        ];
                        $paymentData = $ecpay->QueryPeriodCreditCardTradeInfo(); //信用卡定期定額
                        $last = last($paymentData['ExecLog']);
                        $lastProcessDate = str_replace('%20', ' ', $last['process_date']);
                        $lastProcessDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $lastProcessDate);

                        //計算下次扣款日
                        if($vip->payment == 'cc_quarterly_payment'){
                            $periodRemained = 92;
                        }else {
                            $periodRemained = 30;
                        }
                        $nextProcessDate = substr($lastProcessDate->addDays($periodRemained),0,10);
                    }
                    $last_vip_log = null;                   

                    switch ($vip->payment){
                        case 'cc_monthly_payment':
                            if(!$vip->isPaidCanceled() && ($nextProcessDate??null)){
                                $nextProcessDate = '預計下次扣款日為 '.$nextProcessDate.' 扣款金額：'.$vip->amount;
                                $vipStatus='您目前的 VIP 是每月定期 '.$payment.'。'.$nextProcessDate;
                            }else if($vip->isPaidCanceled()){
                                $cancel_str = '';
                                $latest_vip_log = $user->getLatestVipLog();
                                if($latest_vip_log->isCancel()) {
                                    $cancel_str='您已經在 '.substr($latest_vip_log->created_at,0,10).' 取消續約，';
                                }
                                
                                $vipStatus='您是本站VIP。'.$cancel_str.'VIP到期日為 '. substr($vip->expiry,0,10).'。';
                            }
                            break;
                        case 'cc_quarterly_payment':
                            if(!$vip->isPaidCanceled() && ($nextProcessDate??null)){
                                $nextProcessDate = '預計下次扣款日為 '.$nextProcessDate.' 扣款金額：'.$vip->amount;
                                $vipStatus='您目前的 VIP 是每季定期 '.$payment.'。'.$nextProcessDate;
                            }else if($vip->isPaidCanceled()){
                                $cancel_str = '';
                                $latest_vip_log = $user->getLatestVipLog();
                                if($latest_vip_log->isCancel()) {
                                    $cancel_str='您已經在 '.substr($latest_vip_log->created_at,0,10).' 取消續約，';
                                }
                                
                                $vipStatus='您是本站VIP。'.$cancel_str.'VIP到期日為 '. substr($vip->expiry,0,10).'。';                                
                            }
                            break;
                        case 'one_month_payment':
                            $vipStatus='您目前的 VIP 是單次支付本月費用 '.$payment.'，到期日為'. substr($vip->expiry,0,10);
                            break;
                        case 'one_quarter_payment':
                            $vipStatus='您目前的 vip 是單次支付本季費用 '.$payment.'，到期日為'. substr($vip->expiry,0,10);
                            break;
                    }
                }
            }else{
                $vipStatus = '您目前為免費VIP';

                 if($vipStatusMsgType) {
                     switch($vipStatusMsgType) {
                         case 'reminding':
                            if(!$existHeaderImage) {
                                $vipStatus = '您於 '.$vipStatusPicTime->format('Y/m/d H:i').' 分刪除'.$vipStatusPicStr.'。請於 '.$vipStatusPicTime->addSeconds(1800)->format('Y/m/d H:i').' 前補足大頭照+生活照三張。否則您的 vip 權限會被取消。';
                            }
                         break;
                         case 'remain':
                            if($existHeaderImage && $vipStatusPicTime->diffInSeconds(Carbon::now()) <= 86400 ) {
                                $vipStatus = '您於  '.$vipStatusPicTime->format('Y/m/d H:i').' 上傳大頭照+生活照三張，已成為本站vip！';
                            }
                         break;                     
                     }
                    
            
                 }   
            }
        }
        else if($user->engroup==2) //不是VIP的女性會員 
        {
            $checkFreeVipMemPicLog =
            $checkFreeVipAvatarLog =
            $avatarLogReact = 
            $avatarLogOp = 
            $avatarLogTime = 
            $avatarLogId = 
            $mempicLogReact = 
            $mempicLogOp = 
            $mempicLogId = 
            $mempicLogTime = null;
            
            if($vipStatusMsgType) {
                 switch($vipStatusMsgType) {
                     case 'reminding':
                        if(!$existHeaderImage) {
                            if($vipStatusPicTime) {
                                $vip_remain_deadline = Carbon::parse($vipStatusPicTime)->addSeconds(1800)->format('Y/m/d H:i');
                                if($vip_remain_deadline<Carbon::now()->format('Y/m/d H:i')) {
                                    $vipStatus = '您於 '.$vipStatusPicTime->format('Y/m/d H:i').' 分刪除'.$vipStatusPicStr.'。且未於 '.$vip_remain_deadline.' 前補足大頭照+生活照三張。故將暫停您的 vip 權限。'."若欲取回 vip 權限，請補足大頭照+生活照三張，系統通過審核後會回復。";            
                                }
                            }
                        }
                    break;
                    case 'recovering':
                    case 'upgrade':
                        $expect_recover_date = Carbon::parse($vipStatusPicTime)->addSeconds(86400)->format('Y/m/d H:i');
                        $delPicStr = '';
                        $delPicLogTime = null;

                        if($firstRemindingLog) {
                            $delPicStr = $picTypeNameStrArr[$firstRemindingLog->pic_type];
                            
                            $delPicLogTime =  Carbon::parse($firstRemindingLog->created_at);
                        }

                        if($expect_recover_date>= Carbon::now()->format('Y/m/d H:i')) {
                            $vipStatus = '您'.($delPicLogTime?'於 '.$delPicLogTime->format('Y/m/d H:i').' 分刪除'.($delPicStr??$vipStatusPicStr).'。':'')
                                .'於 '.$vipStatusPicTime->format('Y/m/d H:i').($existHeaderImage?' 補足':'上傳').'大頭照+生活照三張。';
                            if(!$existHeaderImage ) {
                                $vipStatus.='但通過審核的照片數量仍未達免費VIP的標準，請再補足大頭照+生活照三張，以獲得VIP權限。';
                            }    
                            else  {  
                                $vipStatus.='須通過系統審核，預計於'.$expect_recover_date.'獲得 vip 權限。';            
                            }
                        }
                     break;                     
                }
            
            }
        }
        
        $vasStatus = '';
        if($user->valueAddedServiceStatus('hideOnline') == 1) {
            $vasStatus = '您已購買隱藏付費功能';
            $vas = $user->vas->where('service_name','hideOnline')->first();
            if($vas->payment){
                if(\App::environment('local')){
                    $envStr = '_test';
                }
                else{
                    $envStr = '';
                }
                if(substr($vas->payment,0,3) == 'cc_' && $vas->business_id == Config::get('ecpay.payment'.$envStr.'.MerchantID')){

                    $ecpay = new \App\Services\ECPay_AllInOne();
                    $ecpay->MerchantID = Config::get('ecpay.payment'.$envStr.'.MerchantID');
                    $ecpay->ServiceURL = Config::get('ecpay.payment'.$envStr.'.ServiceURL');//定期定額查詢
                    $ecpay->HashIV = Config::get('ecpay.payment'.$envStr.'.HashIV');
                    $ecpay->HashKey = Config::get('ecpay.payment'.$envStr.'.HashKey');
                    $ecpay->Query = [
                        'MerchantTradeNo' => $vas->order_id,
                        'TimeStamp' => 	time()
                    ];
                    $paymentData = $ecpay->QueryPeriodCreditCardTradeInfo(); //信用卡定期定額
                    $last = last($paymentData['ExecLog']);
                    $lastProcessDate = str_replace('%20', ' ', $last['process_date']);
                    $lastProcessDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $lastProcessDate);

                    //計算下次扣款日
                    if($vas->payment == 'cc_quarterly_payment'){
                        $periodRemained = 92;
                    }else {
                        $periodRemained = 30;
                    }
                    $nextProcessDate = substr($lastProcessDate->addDays($periodRemained),0,10);
                }
                $payment = '信用卡繳費';
                switch ($vas->payment){
                    case 'cc_monthly_payment':
                         if(!$vas->isPaidCanceled() && $nextProcessDate??null){
                            $nextProcessDate = '預計下次扣款日為 '.$nextProcessDate.' 扣款金額：'.$vas->amount;
                            $vasStatus='您目前的隱藏付費是每月定期 '.$payment.'。'.$nextProcessDate;
                        }else if($vas->isPaidCanceled()){
                            $cancel_str = '';
                            $latest_vas_log = $user->getLatestVasLog();
                            if($latest_vas_log->isCancel()) {
                                $cancel_str='您已經在 '.substr($latest_vas_log->created_at,0,10).' 取消續約，';
                            }
                            
                            $vasStatus='您目前為隱藏付費者。'.$cancel_str.'隱藏付費到期日為 '. substr($vas->expiry,0,10).'。';                              
                        }
                        break;
                    case 'cc_quarterly_payment':
                         if(!$vas->isPaidCanceled() && $nextProcessDate??null){
                            $nextProcessDate = '預計下次扣款日為 '.$nextProcessDate.' 扣款金額：'.$vas->amount;
                            $vasStatus='您目前的隱藏付費功能是每季定期 '.$payment.'。'.$nextProcessDate;
                        }else if($vas->isPaidCanceled()){
                            //$nextProcessDate = '已停止扣款，隱藏付費功能到期日為' . substr($vas->expiry,0,10);
                            $cancel_str = '';
                            $latest_vas_log = $user->getLatestVasLog();
                            if($latest_vas_log->isCancel()) {
                                $cancel_str='您已經在 '.substr($latest_vas_log->created_at,0,10).' 取消續約，';
                            }
                            
                            $vasStatus='您目前為隱藏付費者。'.$cancel_str.'隱藏付費到期日為 '. substr($vas->expiry,0,10).'。';                                   
                        }
                        break;
                    case 'one_month_payment':
                        $vasStatus='您目前的隱藏付費功能是單次支付本月費用 '.$payment.'，到期日為'. substr($vas->expiry,0,10);
                        break;
                    case 'one_quarter_payment':
                        $vasStatus='您目前的隱藏付費功能是單次支付本季費用 '.$payment.'，到期日為'. substr($vas->expiry,0,10);
                        break;
                }
            }
         
        }
        else {
            $vasStatus = '您尚未購買隱藏付費功能';
        }
 
        $user_isBannedOrWarned = User::select(
            'm.isWarned',
            'm.isWarnedType',
            'b.id as banned_id',
            'b.expire_date as banned_expire_date',
            'b.reason as banned_reason',
            'b.created_at as banned_created_at',
            'b.vip_pass as banned_vip_pass',
            'b.adv_auth as banned_adv_auth',
            'w.id as warned_id',
            'w.expire_date as warned_expire_date',
            'w.reason as warned_reason',
            'w.created_at as warned_created_at',
            'w.vip_pass as warned_vip_pass',
            'w.adv_auth as warned_adv_auth')
            ->from('users as u')
            ->leftJoin('user_meta as m','u.id','m.user_id')
            ->leftJoin('banned_users as b','u.id','b.member_id')
            ->leftJoin('warned_users as w','u.id','w.member_id')
            ->where('u.id',$user->id)
            ->get()->first();
        //封鎖
        $isBannedStatus = '';
        if($user_isBannedOrWarned->banned_expire_date != null){
            $datetime1 = new \DateTime(now());
            $datetime2 = new \DateTime($user_isBannedOrWarned->banned_expire_date);
            $datetime3 = new \DateTime($user_isBannedOrWarned->banned_created_at);
            $diffDays = $datetime2->diff($datetime3)->days;
        }

        if(!empty($user_isBannedOrWarned->banned_id) && $user_isBannedOrWarned->banned_adv_auth==1) {
            $isBannedStatus = '您目前<span class="main_word">已被系統封鎖</span>，';
            if($user_isBannedOrWarned->banned_expire_date > now()) {
                $isBannedStatus.='預計至 '.substr($user_isBannedOrWarned->banned_expire_date,0,16).' 日解除，';
            }   
            if($user_isBannedOrWarned->banned_reason??'') {
                $isBannedStatus.='原因是<span class="main_word"> ' . $user_isBannedOrWarned->banned_reason . '</span>，';
            }
         
            $isBannedStatus.= '做完進階驗證可解除<a class="red" href="'.url('advance_auth').'"> [請點我進行驗證]</a>。';
        }
        else if($user_isBannedOrWarned->banned_vip_pass == 1 && $user_isBannedOrWarned->banned_expire_date == null){
            $isBannedStatus = '您目前<span class="main_word">已被站方封鎖</span>，原因是 <span class="main_word">' . $user_isBannedOrWarned->banned_reason . '</span>，若要解除請升級VIP解除，並同意如有再犯，站方有權利不退費並永久封鎖。同意 [<a href="../dashboard/new_vip" class="red">請點我</a>]';
        }else if($user_isBannedOrWarned->banned_vip_pass == 1 && $user_isBannedOrWarned->banned_expire_date > now()){
            $isBannedStatus .= '您從 '.substr($user_isBannedOrWarned->banned_created_at,0,10).' <span class="main_word">被站方封鎖 '.$diffDays.'天</span>，預計至 '.substr($user_isBannedOrWarned->banned_expire_date,0,16).' 日解除，原因是<span class="main_word"> '.$user_isBannedOrWarned->banned_reason.'</span>，若要解除請升級VIP解除，並同意如有再犯，站方有權利不退費並永久封鎖。同意 [<a href="../dashboard/new_vip" class="red">請點我</a>]';
        }else if(!empty($user_isBannedOrWarned->banned_id) && $user_isBannedOrWarned->banned_expire_date == null) {
            $isBannedStatus = '您目前<span class="main_word">已被站方封鎖</span>，原因是<span class="main_word"> ' . $user_isBannedOrWarned->banned_reason . '</span>，如有需要反應請點右下聯絡我們聯絡站長。';
        }else if(!empty($user_isBannedOrWarned->banned_id) && $user_isBannedOrWarned->banned_expire_date > now() ) {

            $isBannedStatus .= '您從 '.substr($user_isBannedOrWarned->banned_created_at,0,10).' <span class="main_word">被站方封鎖'.$diffDays.'天</span>，預計至 '.substr($user_isBannedOrWarned->banned_expire_date,0,16).' 日解除，原因是 <span class="main_word">'.$user_isBannedOrWarned->banned_reason.'</span>，如有需要反應請點右下聯絡我們聯絡站長。';
        }

//        $isBannedImplicitlyStatus = '';
//        $banned_users_implicitly_data = BannedUsersImplicitly::where('target',$user->id)->first();
//        if($banned_users_implicitly_data){
//            $isBannedImplicitlyStatus = '您目前已被站方封鎖，原因是 ' . $banned_users_implicitly_data->reason . '，如有需要反應請點右下聯絡我們聯絡站長。';
//        }

        //警示
        $adminWarnedStatus = '';
        if($user_isBannedOrWarned->warned_expire_date != null){
            $datetime1 = new \DateTime(now());
            $datetime2 = new \DateTime($user_isBannedOrWarned->warned_expire_date);
            $datetime3 = new \DateTime($user_isBannedOrWarned->warned_created_at);
            $diffDays = $datetime2->diff($datetime3)->days;
        }

        if(!empty($user_isBannedOrWarned->warned_id) && $user_isBannedOrWarned->warned_adv_auth==1) {
            $adminWarnedStatus = '您目前<span class="main_word">已被系統警示</span>，';
            if($user_isBannedOrWarned->warned_expire_date > now()) {
                $adminWarnedStatus.='預計至 '.substr($user_isBannedOrWarned->warned_expire_date,0,16).' 日解除，';
            }   
            if($user_isBannedOrWarned->warned_reason??'') {
                $adminWarnedStatus.='原因是<span class="main_word"> ' . $user_isBannedOrWarned->warned_reason . '</span>，';
            }            
            $adminWarnedStatus.= '做完進階驗證可解除<a class="red" href="'.url('advance_auth').'"> [請點我進行驗證]</a>。';
        }
        else if($user_isBannedOrWarned->warned_vip_pass == 1 && $user_isBannedOrWarned->warned_expire_date == null) {
            $adminWarnedStatus = '您目前<span class="main_word">已被站方警示</span>，原因是<span class="main_word"> ' . $user_isBannedOrWarned->warned_reason . '</span>，若要解鎖請升級VIP解除，並同意如有再犯，站方有權不退費並永久警示。同意[<a href="../dashboard/new_vip" class="red">請點我</a>]';
        }else if($user_isBannedOrWarned->warned_vip_pass == 1 && $user_isBannedOrWarned->warned_expire_date > now()) {
            $adminWarnedStatus .= '您從 '.substr($user_isBannedOrWarned->warned_created_at,0,10).' <span class="main_word">被站方警示 '.$diffDays.'天</span>，預計至 '.substr($user_isBannedOrWarned->warned_expire_date,0,16).' 日解除，原因是<span class="main_word"> '.$user_isBannedOrWarned->warned_reason.'</span>，若要解鎖請升級VIP解除，並同意如有再犯，站方有權不退費並永久警示。同意[<a href="../dashboard/new_vip" class="red">請點我</a>]';
        }else if(!empty($user_isBannedOrWarned->warned_id) && $user_isBannedOrWarned->warned_expire_date == null) {
            $adminWarnedStatus = '您目前<span class="main_word">已被站方警示</span>，原因是<span class="main_word"> ' . $user_isBannedOrWarned->warned_reason . '</span>，如有需要反應請點右下聯絡我們聯絡站長。';
        }else if(!empty($user_isBannedOrWarned->warned_id) && $user_isBannedOrWarned->warned_expire_date > now() ) {
            $adminWarnedStatus .= '您從 '.substr($user_isBannedOrWarned->warned_created_at,0,10).' <span class="main_word">被站方警示 '.$diffDays.'天</span>，預計至 '.substr($user_isBannedOrWarned->warned_expire_date,0,16).' 日解除，原因是<span class="main_word"> '.$user_isBannedOrWarned->warned_reason.'</span>，如有需要反應請點右下聯絡我們聯絡站長。';
        }

        $isWarnedStatus = '';
        if($user_isBannedOrWarned->isWarned==1){
            if($user_isBannedOrWarned->isWarnedType!='adv_auth') {
                $isWarnedAuthStr = '手機驗證';
                $isWarnedAuthUrl = '../member_auth';
                $ps_str = 'PS:此對系統針對八大行業的自動警示機制，帶來不便敬請見諒。';
            }
            else {
                $isWarnedAuthStr = '進階驗證';
                $isWarnedAuthUrl = url('advance_auth');
                $ps_str = '';
            }
            
            $isWarnedStatus = '您目前<span class="main_word">已被系統自動警示</span>，做完'.$isWarnedAuthStr.'即可解除<a class="red" href="'.$isWarnedAuthUrl.'">[請點我進行認證]</a>。'.$ps_str;
        }


        //本月封鎖數
        $banned_users = banned_users::select('id')
            ->where('created_at','>=',\Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString())->count();

        //隱形封鎖
        $banned_users_implicitly = BannedUsersImplicitly::select('id')
            ->where('created_at','>=',\Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString())->count();

        //取得封鎖資料總筆數
        $bannedCount = $banned_users + $banned_users_implicitly;

        //本月被檢舉人數
//        $reportedCount = User::select(['a.id'])->from('users as a')
//            ->leftJoin('reported as b','a.id','b.reported_id')->where('b.created_at','>=',\Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString())
//            ->leftJoin('member_pic as c','a.id','c.member_id')
//            ->join('reported_pic as d','c.id','d.reported_pic_id')->where('d.created_at','>=',\Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString())
//            ->leftJoin('reported_avatar as e','a.id','e.reported_user_id')->where('e.created_at','>=',\Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString())
//            ->leftJoin('message as m','a.id','m.to_id')->where('m.isReported',1)->where('m.updated_at','>=',\Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString())
//            ->distinct()
//            ->count('a.id');

        //本月警示人數
        $warnedCount = warned_users::select('id','member_id')->where('created_at','>=',\Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString())->distinct()->count('member_id');

        //個人檢舉紀錄
        $reported = Reported::select('reported.id','reported.reported_id as rid','reported.content as reason', 'reported.created_at as reporter_time','u.name','m.isWarned','b.id as banned_id','b.expire_date as banned_expire_date','w.id as warned_id','w.expire_date as warned_expire_date')
            ->selectRaw('"reported" as reported_type')
            ->leftJoin('users as u', 'u.id','reported.reported_id')->where('u.id','!=',null)
            ->leftJoin('user_meta as m','u.id','m.user_id')
            ->leftJoin('banned_users as b','u.id','b.member_id')
            ->leftJoin('warned_users as w','u.id','w.member_id');
//        $reported = $reported->addSelect(DB::raw("'reported' as table_name"));
        $reported = $reported->where('reported.member_id',$user->id)->where('reported.hide_reported_log',0)->get();

        $reported_pic = ReportedPic::select('reported_pic.id','member_pic.member_id as rid','reported_pic.content as reason','reported_pic.created_at as reporter_time','u.name','m.isWarned','b.id as banned_id','b.expire_date as banned_expire_date','w.id as warned_id','w.expire_date as warned_expire_date')
            ->selectRaw('"reportedPic" as reported_type');
//        $reported_pic = $reported_pic->addSelect(DB::raw("'reported_pic' as table_name"));
        $reported_pic = $reported_pic->join('member_pic','member_pic.id','=','reported_pic.reported_pic_id')
            ->leftJoin('users as u', 'u.id','member_pic.member_id')->where('u.id','!=',null)
            ->leftJoin('user_meta as m','u.id','m.user_id')
            ->leftJoin('banned_users as b','u.id','b.member_id')
            ->leftJoin('warned_users as w','u.id','w.member_id')
            ->where('reported_pic.reporter_id',$user->id)->where('reported_pic.hide_reported_log',0)->get();

        $reported_avatar = ReportedAvatar::select('reported_avatar.id','reported_avatar.reported_user_id as rid', 'reported_avatar.content as reason', 'reported_avatar.created_at as reporter_time','u.name','m.isWarned','b.id as banned_id','b.expire_date as banned_expire_date','w.id as warned_id','w.expire_date as warned_expire_date')
            ->selectRaw('"reportedAvatar" as reported_type')
            ->leftJoin('users as u', 'u.id','reported_avatar.reported_user_id')->where('u.id','!=',null)
            ->leftJoin('user_meta as m','u.id','m.user_id')
            ->leftJoin('banned_users as b','u.id','b.member_id')
            ->leftJoin('warned_users as w','u.id','w.member_id');
//        $reported_avatar = $reported_avatar->addSelect(DB::raw("'reported_avatar' as table_name"));
        $reported_avatar = $reported_avatar->where('reported_avatar.reporter_id',$user->id)->where('reported_avatar.hide_reported_log',0)->get();

        $reported_message = Message::select('message.id','message.from_id as rid', 'message.reportContent as reason', 'message.updated_at as reporter_time','u.name','m.isWarned','b.id as banned_id','b.expire_date as banned_expire_date','w.id as warned_id','w.expire_date as warned_expire_date')
            ->selectRaw('"reportedMessage" as reported_type')
            ->leftJoin('users as u', 'u.id','message.from_id')->where('u.id','!=',null)
            ->leftJoin('user_meta as m','u.id','m.user_id')
            ->leftJoin('banned_users as b','u.id','b.member_id')
            ->leftJoin('warned_users as w','u.id','w.member_id');
//        $reported_message = $reported_message->addSelect(DB::raw("'message' as table_name"));
        $reported_message = $reported_message->where('message.to_id',$user->id)->where('message.isReported',1)->where('message.hide_reported_log',0)->get();

        $collection = collect([$reported, $reported_pic, $reported_avatar, $reported_message]);
        $report_all = $collection->collapse()->unique('rid')->sortByDesc('reporter_time');

        $reportedStatus = array();
            foreach ($report_all as $row) {
                if (isset($row->rid) && !empty($row->rid)) {
                    $content_1 = '您於 ' . substr($row->reporter_time, 0, 10) . ' 檢舉了 <a href=../dashboard/viewuser/' . $row->rid . '?time=' . \Carbon\Carbon::now()->timestamp . '>' . $row->name . '</a>，檢舉緣由是 ' . $row->reason;
                    $content_2 = '';

                    //封鎖
                    $reporter_isBannedStatus = 0;
                    $reporter_isBannedStatus_expire = '';
//
                    if (!empty($row->banned_id) && $row->banned_expire_date == null) {
                        $reporter_isBannedStatus = 1;
                    } else if (!empty($row->banned_id) && $row->banned_expire_date > now()) {
                        $reporter_isBannedStatus = 1;
                        $datetime1 = new \DateTime(now());
                        $datetime2 = new \DateTime($row->banned_expire_date);
                        $diffDays = $datetime1->diff($datetime2)->days;
                        $reporter_isBannedStatus_expire = $diffDays;
                    }

                    //警示
                    $reporter_isAdminWarnedStatus = 0;
                    $reporter_isAdminWarnedStatus_expire = '';
                    if (!empty($row->warned_id) && $row->warned_expire_date == null) {
                        $reporter_isAdminWarnedStatus = 1;
                    } else if (!empty($row->warned_id) && $row->warned_expire_date > now()) {
                        $reporter_isAdminWarnedStatus = 1;
                        $datetime1 = new \DateTime(now());
                        $datetime2 = new \DateTime($row->warned_expire_date);
                        $diffDays = $datetime1->diff($datetime2)->days;
                        $reporter_isAdminWarnedStatus_expire = $diffDays;
                    }

                    $reporter_isWarnedStatus = 0;
                    if ($row->isWarned == 1) {
                        $reporter_isWarnedStatus = 1;
                    }

                    if ($reporter_isBannedStatus == 1 /*|| $reporter_isBannedImplicitlyStatus == 1*/) {
                        $content_2 .= '目前該會員被處分為 封鎖 ';
                        if (!empty($reporter_isBannedStatus_expire)) {
                            $content_2 .= $reporter_isBannedStatus_expire . ' 日。';
                        }
                    }

                    if ($reporter_isAdminWarnedStatus == 1 || $reporter_isWarnedStatus == 1) {
                        $content_2 .= '目前該會員被處分為 警示 ';
                        if (!empty($reporter_isAdminWarnedStatus_expire)) {
                            $content_2 .= $reporter_isAdminWarnedStatus_expire . ' 日。';
                        }
                    }
                    if ($reporter_isBannedStatus == 1 || $reporter_isAdminWarnedStatus == 1 || $reporter_isWarnedStatus == 1) {
                        array_push($reportedStatus, array(/*'table' => $row->table_name, */'id' => $row->id, 'rid' => $row->rid, 'content' => $content_1, 'status' => $content_2, 'name' => $row->name, 'reported_type' => $row->reported_type));
                    }
                }
            }

        //你收藏的會員上線
        $uid = $user->id;
        $myFav =  MemberFav::select('a.id as rowid','a.member_id','a.member_fav_id','b.id','b.name','b.title','b.is_hide_online',\DB::raw("IF(b.is_hide_online = 1 or b.is_hide_online = 2, b.hide_online_time, b.last_login) as last_login"),'v.id as vid',\DB::raw('max(v.created_at) as visited_created_at'))
            ->where('a.member_id',$user->id)->from('member_fav as a')
            ->leftJoin('users as b','a.member_fav_id','b.id')->where('b.id','!=',null)
            ->leftJoin('visited as v', function ($join) use ($uid){
                $join->on('v.member_id','=','a.member_fav_id')
                    ->where('v.visited_id',$uid);
            })
            ->leftJoin('banned_users as b1', 'b1.member_id', '=', 'a.member_fav_id')
            ->leftJoin('banned_users_implicitly as b3', 'b3.target', '=', 'a.member_fav_id')
            ->leftJoin('blocked as b5', function($join) use($uid) {
                $join->on('b5.blocked_id', '=', 'a.member_fav_id')
                    ->where('b5.member_id', $uid); });
        $myFav = $myFav->whereNull('b1.member_id')
            ->whereNull('b3.target')
            ->whereNull('b5.blocked_id')
            ->where('b.accountStatus', 1)
            ->where('b.account_status_admin', 1)
            ->where('last_login', '>=', Carbon::now()->subDays(7))
            ->where('a.hide_member_id_log',0)
            ->groupBy('a.member_fav_id')
            ->get();


        //收藏你的會員上線
        $otherFav = MemberFav::select('a.id as rowid','a.member_id','a.member_fav_id','b.name','b.title','b.is_hide_online',\DB::raw("IF(b.is_hide_online = 1 or b.is_hide_online = 2, b.hide_online_time, b.last_login) as last_login"))
            ->where('a.member_fav_id',$user->id)->from('member_fav as a')
            ->leftJoin('users as b','a.member_id','b.id')->where('b.id','!=',null)
            ->leftJoin('banned_users as b1', 'b1.member_id', '=', 'a.member_id')
            ->leftJoin('banned_users_implicitly as b3', 'b3.target', '=', 'a.member_id')
            ->leftJoin('blocked as b5', function($join) use($uid) {
                $join->on('b5.blocked_id', '=', 'a.member_id')
                    ->where('b5.member_id', $uid); });
        $otherFav = $otherFav->whereNull('b1.member_id')
            ->whereNull('b3.target')
            ->whereNull('b5.blocked_id')
            ->where('b.accountStatus', 1)
            ->where('b.account_status_admin', 1)
            ->where('last_login', '>=', Carbon::now()->subDays(7))
            ->where('a.hide_member_fav_id_log',0)
            ->get();

        //msg
        $msgMemberCount = Message_new::allSenders($user->id, $user->isVip(), 'all');

        $queryBE = \App\Models\Evaluation::select('evaluation.*')->from('evaluation as evaluation')->with('user')
                ->leftJoin('blocked as b1', 'b1.blocked_id', '=', 'evaluation.from_id')
//                ->leftJoin('user_meta as um', function($join) {
//                    $join->on('um.user_id', '=', 'e.from_id')
//                        ->where('isWarned', 1); })
//                ->leftJoin('warned_users as wu', function($join) {
//                    $join->on('wu.member_id', '=', 'e.from_id')
//                        ->where(function($query){
//                            $query->where('wu.expire_date', '>=', Carbon::now())
//                                ->orWhere('wu.expire_date', null); }); })
//                ->whereNull('um.user_id')
//                ->whereNull('wu.member_id')
                ->orderBy('evaluation.created_at','desc')
                ->where('b1.member_id', $uid)
                ->where('evaluation.to_id', $uid)
                ->where('evaluation.read', 1)
                ->get();

        $isBannedEvaluation = sizeof($queryBE) > 0? true : false;

        $queryHE = \App\Models\Evaluation::select('evaluation.*')->from('evaluation as evaluation')
                ->orderBy('evaluation.created_at','desc')
                ->where('evaluation.to_id', $uid)
                ->where('evaluation.read', 1)
                ->get();
        
        $arrayHE = [];
        foreach ($queryHE as $k1 => $v1) {
            $tmp = false;
            foreach ($queryBE as $k2 => $v2) {
                if($v1->from_id == $v2->from_id) {
                    $tmp = true;
                    break;
                }
            }
            if(!$tmp) array_push($arrayHE, $v1);
        }

        $isHasEvaluation = sizeof($arrayHE) > 0? true : false;

        $query = Message::whereNotNull('id');
        $query = $query->where(function ($query) use ($uid,$admin) {
            $whereArr1 = [['to_id', $uid],['from_id', $admin->id]];
            array_push($whereArr1,['is_single_delete_1','<>',$uid],['is_row_delete_1','<>',$uid]);
            $query->where($whereArr1);
        });        
        $admin_msg_entrys =  $query->orderBy('created_at', 'desc')->get();
		$admin_msgs = [];
        $admin_msgs_sys = [];

		foreach($admin_msg_entrys->where('sys_notice',0) as $admin_msg_entry) {
            $admin_msg_entry->content = str_replace('NAME', $user->name, $admin_msg_entry->content);
            $admin_msg_entry->content = str_replace('|$report|', $user->name, $admin_msg_entry->content);
            $admin_msg_entry->content = str_replace('LINE_ICON', AdminService::$line_icon_html, $admin_msg_entry->content);
            $admin_msg_entry->content = str_replace('|$lineIcon|', AdminService::$line_icon_html, $admin_msg_entry->content);         
            $admin_msg_entry->content = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $admin_msg_entry->content);
            $admin_msg_entry->content = str_replace('|$reportTime|', date("Y-m-d H:i:s"), $admin_msg_entry->content);
            $admin_msg_entry->content = str_replace('NOW_TIME', date("Y-m-d H:i:s"), $admin_msg_entry->content);  
            $admin_msgs[] = $admin_msg_entry;
		}
        $i=0;
		foreach($admin_msg_entrys->where('sys_notice','1') as $admin_msg_entry) {
			$admin_msgs_sys[] = $admin_msg_entry;
			$i++;
			if($i>=3) break;
		}        


        //僅顯示30天內的評價
        $evaluation_30days = \App\Models\Evaluation::selectRaw('evaluation.*, b1.blocked_id, b.name')->from('evaluation as evaluation')
            ->leftJoin('blocked as b1', function($join) {
                $join->on('b1.blocked_id', '=', 'evaluation.from_id');
                $join->on('b1.member_id', '=', 'evaluation.to_id');
            })
            ->leftJoin('users as b','evaluation.from_id','b.id')
            ->orderBy('evaluation.created_at','desc')
            ->where('evaluation.to_id', $uid)
            ->where('evaluation.created_at', '>=', Carbon::now()->subDays(30));

        $evaluation_30days_list=$evaluation_30days->where('evaluation.hide_evaluation_to_id', 0)->get();
        $evaluation_30days_unread_count=$evaluation_30days->where('evaluation.read', 1)->get()->count();


        //舊會員上線，就在上線第 3,6,10 次 (以此功能上線開始計算)在會員專屬頁通知。
        //新會員：做完新手教學，填寫完基本資料，於第一次進入專屬頁面時跳通知，之後就在上線第 3,6,10 次在會員專屬頁通知。
        $showLineNotifyPop=false;
        if(is_null($user->line_notify_token)){
            if(in_array($user->line_notify_alert,[3,6,10])){
                $showLineNotifyPop=true;
            }
            if($user->created_at>='2021-07-23' && $user->line_notify_alert<=2){
                $showLineNotifyPop=true;
            }
        }
        $login_times=$user->line_notify_alert;
        if($showLineNotifyPop){
            $showLineNotifyPop= session()->get('alreadyPopUp_lineNotify') == $login_times.'_Y' ? false : true;
        }

        //是否有系統提示訊息
        $announceRead = AnnouncementRead::select('announcement_id')->where('user_id', $user->id)->get();
        $announcement = AdminAnnounce::where('en_group', $user->engroup)->whereNotIn('id', $announceRead)->orderBy('sequence', 'asc')->get();
        $announcePopUp='N';
        if(isset($announcement) && count($announcement) > 0 && !session()->get('announceClose')){
            $announcePopUp='Y';
        }
        /*
        //在新進甜心第4次登入顯示只接受VIP會員傳訊的提醒
        $showNewSugarForbidMsgNotify=false ;
        if($user->log_user_login()->count()==4) {
            $showNewSugarForbidMsgNotify = true;
        }
        */

        if (isset($user)) {
            $data = array(
                'vipStatus' => $vipStatus,
                'vasStatus'=> $vasStatus,
                'isBannedStatus' => $isBannedStatus,
//                'isBannedImplicitlyStatus' => $isBannedImplicitlyStatus,
                'adminWarnedStatus' => $adminWarnedStatus,
                'isWarnedStatus' => $isWarnedStatus,
                'bannedCount' => $bannedCount,
//                'reportedCount' => $reportedCount,
                'warnedCount' => $warnedCount,
                'reportedStatus' => $reportedStatus,
                'msgMemberCount' => $msgMemberCount,
                'isBannedEvaluation' => $isBannedEvaluation,
                'isHasEvaluation' => $isHasEvaluation,
                'evaluation_30days' => $evaluation_30days_list,
                'evaluation_30days_unread_count' => $evaluation_30days_unread_count,
                'showLineNotifyPop'=>$showLineNotifyPop,
                'announcePopUp'=>$announcePopUp,
                //'showNewSugarForbidMsgNotify'=>$showNewSugarForbidMsgNotify,
            );
            $allMessage = \App\Models\Message::allMessage($user->id);
            return view('new.dashboard.personalPage', $data)
                ->with('myFav', $myFav)
                ->with('otherFav',$otherFav)
                ->with('admin_msgs',$admin_msgs)
                ->with('admin_msgs_sys',$admin_msgs_sys)
                ->with('admin',$admin)
                ->with('allMessage', $allMessage);
        }
    }

    public function report_delete(Request $request)
    {
//        $table = $request->table;
        $rid = $request->id;
        $user = \View::shared('user');
        $uid = $user->id;
        $status='';

        //檢舉紀錄所有表格一併清除
        $result1=DB::table('message')->where('from_id',$rid)->where('to_id',$uid)->update(['isReported' => 0, 'reportContent' => null]);
        $result2=DB::table('reported')->where('reported_id',$rid)->where('member_id',$uid)->delete();
        $result3=DB::table('reported_avatar')->where('reported_user_id',$rid)->where('reporter_id',$uid)->delete();
        $query = ReportedPic::select('reported_pic.id')
            ->join('member_pic','reported_pic.reported_pic_id','member_pic.id')
            ->where('member_pic.member_id',$rid)->where('reported_pic.reporter_id',$uid)->get();
        if($query) {
            foreach ($query as $row) {
                $result4=ReportedPic::where('id', $row->id)->delete();
            }
        }

        if($result1 || $result2 || $result3 || $result4){
            $status = 'ok';
        }else{
            $status = 'error';
        }
        $data = array(
            'save' => $status
        );

        return json_encode($data);
    }

    public function multipleLogin(Request $request){
        $isExist = \DB::table('multiple_logins')->where(['original_id' => $request->original_id, 'new_id' => $request->new_id])->get();
        if(count($isExist) > 0){
            return response()->json(array(
                'status' => 1,
                'msg' => 'exists',
            ), 200);
        }
        \DB::table('multiple_logins')
            ->insert(['original_id' => $request->original_id,
                      'new_id' => $request->new_id,
                      'created_at' => Carbon::now(),
                      'updated_at' => Carbon::now(),]);

        return response()->json(array(
            'status' => 1,
            'msg' => 'success',
        ), 200);
    }

    public function savecfp(Request $request){
        $cfp = new \App\Models\CustomFingerPrint;
        $cfp->hash = $request->hash;
        $cfp->host = request()->getHttpHost();
        $cfp->save();
        $cfp_user = new \App\Models\CFP_User;
        $cfp_user->cfp_id = $cfp->id;
        $cfp_user->user_id = $request->user()->id;
        $cfp_user->save();

        return response()->json(array(
            'status' => 1,
            'msg' => 'success',
        ), 200);
    }

    public function checkcfp(Request $request){
        $this->service->checkcfp($request->hash, $request->user()->id);

        return response()->json(array(
            'status' => 1,
            'msg' => 'success',
        ), 200);
    }

    public function search_key_reset(){
        $search_page_key=session()->get('search_page_key',[]);
        foreach ($search_page_key as $key =>$value){
            session()->put('search_page_key.'.$key,null);
        }
        //logger(session()->get('search_page_key',[]));
    }

    public function closeNoticeNewEvaluation(Request $request){
        $user_id = $request->id;
        $user = User::select('id', 'notice_has_new_evaluation')->where('id', $user_id)->first();
        $user->notice_has_new_evaluation = 0;
        if ($user->save()) {
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

    public function personalPageHideRecordLog(Request $request){
        $updateType = $request->type;
        $user_id = $request->user_id;
        $items = $request->deleteItems;
        $sys_remind = $request->sys_remind;
        switch ($updateType){
            case 'myFavRecord' : //不顯示我收藏的會員上線
                MemberFav::where('member_id',$user_id)->whereIn('id', $items)->update(['hide_member_id_log'=>1]);
                break;
            case 'myFavRecord2' : //不顯示收藏我的會員上線
                MemberFav::where('member_fav_id',$user_id)->whereIn('id', $items)->update(['hide_member_fav_id_log'=>1]);
                break;
            case 'evaluationRecord' : //不顯示評價我的評價紀錄
                Evaluation::where('to_id',$user_id)->whereIn('id', $items)->update(['hide_evaluation_to_id'=>1]);
                break;
            case 'reportedRecord' : //不顯示檢舉紀錄
                foreach ($items as $item){
                    //檢舉類型
                    $rowid=explode("_", $item)[0];
                    $reportedType=explode("_", $item)[1];
                    if($reportedType=='reported'){
                        //個人檢舉紀錄
                        Reported::where('member_id',$user_id)->where('id', $rowid)->update(['hide_reported_log'=>1]);

                    }else if ($reportedType=='reportedPic') {
                        //檢舉照片
                        ReportedPic::where('reporter_id',$user_id)->where('id', $rowid)->update(['hide_reported_log'=>1]);

                    }else if ($reportedType=='reportedAvatar'){
                        //檢舉大頭照
                        ReportedAvatar::where('reporter_id',$user_id)->where('id', $rowid)->update(['hide_reported_log'=>1]);

                    }else if ($reportedType=='reportedMessage'){
                        //檢舉訊息
                        Message::where('to_id',$user_id)->where('id', $rowid)->update(['hide_reported_log'=>1]);
                    }
                }
                break;
			case 'admin_msgs':
				$admin_id = AdminService::checkAdmin()->id;
				$messages = Message::where([['to_id',$user_id],['from_id',$admin_id]])->whereIn('id', $items)->get();
				foreach($messages  as $message) {
					Message::deleteSingleMessage($message, $user_id, $admin_id, $message->created_at, $message->content, 0);
				}
				
				$admin_msg_entrys = Message::allToFromSender($user_id,$admin_id);
				$admin_msgs = [];
				$i=0;
                if($sys_remind) $admin_msg_entrys = $admin_msg_entrys->where('sys_notice',1);
                else $admin_msg_entrys = $admin_msg_entrys->where('sys_notice',0)->orWhereNull('sys_notice');
				foreach($admin_msg_entrys as $admin_msg_entry) {
					$admin_msgs[] = $admin_msg_entry;
					$i++;
					if($i>=3) break;
				}	
				return json_encode($admin_msgs);
			break;
        }
    }
	
	public function switchOtherEngroup() {
		$user = \View::shared('user');
		if(!$user->isVip()) return redirect()->back();
		$toEngroup = $user->id;
		switch($user->engroup) {
			case 2:
				$toEngroup =1;
			break;
			case 1:
				$toEngroup =2;
			break;			
		}
        
        if(!User::find($toEngroup)) {
            DB::table('users')->insert([
                'email' => ($toEngroup==1?'boy':'girl').'.email@email.email',
                'id'=>$toEngroup,
                'name'=>'甜心'.($toEngroup==1?'爹地':'寶貝'),
                'title'=>'甜心'.($toEngroup==1?'爹地':'寶貝'),
                'engroup'=>$toEngroup,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'), 
                'noticeRead'=>1,
                'isReadManual'=>1,
                'isReadIntro'=>1,
                'notice_has_new_evaluation'=>0,
            ]);
            DB::table('user_meta')->insert([
                'user_id'=>$toEngroup,
                'is_active'=>'1',
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),                 
                'city'=>'臺北市',
                'area'=>'中正',
                'budget'=>'基礎',
                'birthdate'=>'1990-09-01',
                'height'=>'165',
                'weight'=>'60',
                'cup'=>($toEngroup==2?'B':''),
                'about'=>'這是模擬用的系統會員帳號',
                'style'=>'期待的約會模式',
                'situation'=>($toEngroup==2?'學生':''),
                'occupation'=>($toEngroup==2?'學生':''),
                'education'=>'大學',
                'marriage'=>'單身',
                'drinking'=>'不喝',
                 'smoking'=>'不抽',
                'pic'=>'/new/images/'.($toEngroup==2?'fe':'').'male.png',
                'assets'=>($toEngroup==1?'100':''),
                'income'=>($toEngroup==1?'50萬以下':''),                
            ]);
            DB::table('short_message')->insert([
                'member_id'=>$toEngroup,
                'active'=>1,
                'createdate'=>date('Y-m-d H:i:s'),               
            ]);  
            DB::table('banned_users')->insert([
                'member_id'=>$toEngroup,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),                 
            ]);             
            if($toEngroup==2) {
                for($i=0;$i<3;$i++) {
                    DB::table('member_pic')->insert([
                        'member_id'=>$toEngroup,
                        'isHidden'=>1,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s'),                
                    ]);                
                }
                
                DB::table('member_vip')->insert([
                    'member_id'=>$toEngroup,
                    'business_id'=>111,
                    'amount'=>0,
                    'expiry'=>'0000-00-00 00:00:00',  
                    'active'=>1,
                    'free'=>1,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s'), 
                ]); 

                DB::table('exchange_period_temp')->insert([
                    'user_id'=>$toEngroup,
                    'created_at'=>date('Y-m-d H:i:s'),               
                ]);                 
            }
        }

        if($this->service->switchToUser($toEngroup))

			return redirect()->back()->with('message', '成功切換使用者');
		else 
			return redirect()->back()->with('message', '無法切換使用者');
		
	}
	
	public function switchEngroupBack() {
		$user = \View::shared('user');
		if(!$user->isVip()) return redirect()->back();

        $this->service->switchUserBack();

        return redirect()->back();	
		
	}	

    public function messageBoard_showList(Request $request)
    {
        $user = $this->user;
        $entrance=true;
        if($user->engroup==2 && ( !$user->isVip() || !$user->isPhoneAuth() )){
            $entrance=false;
        }elseif($user->engroup==1 && !$user->isVip()){
            $entrance=false;
        }

        if($entrance==false){
            return redirect('/dashboard')->with('messageBoard_enter_limit', $entrance);
        }

        $data['isAdminWarned']=$user->isAdminWarned();
        $data['isBanned']= User::isBanned($user->id);
        $record_pre=MessageBoard::where('user_id', $user->id)->orderBy('created_at','desc')->first();
        if($record_pre && (date("Y-m-d H:i:s") <= date("Y-m-d H:i:s",strtotime("+3 hours", strtotime($record_pre->created_at)))) ){
            $data['post_too_frequently']= true;
        }

        $userMeta=UserMeta::findByMemberId($user->id);
        $type='';
        if($data['isAdminWarned'] || $data['isBanned'] || $userMeta->isWarned==1){
            if($data['isAdminWarned'] && $data['isBanned'])
                $type='警示/封鎖';
            elseif ($data['isAdminWarned'] || $userMeta->isWarned==1)
                $type='警示';
            elseif ($data['isBanned'])
                $type='封鎖';
            return redirect('/dashboard')->with('messageBoard_msg', '您目前為'.$type.'狀態，無法使用留言板功能');
        }

        $userBlockList = \App\Models\Blocked::select('blocked_id')->where('member_id', $user->id)->get();
        $bannedUsers = \App\Services\UserService::getBannedId();

        $nowTime= date("Y-m-d H:i:s");
        $getLists_others = MessageBoard::selectRaw('users.id as uid, users.name as uname, users.engroup as uengroup, user_meta.pic as umpic, user_meta.city, user_meta.area')
            ->selectRaw('message_board.id as mid, message_board.title as mtitle, message_board.contents as mcontents, message_board.updated_at as mupdated_at, message_board.created_at as mcreated_at')
            ->LeftJoin('users', 'users.id','=','message_board.user_id')
            ->LeftJoin('user_meta', 'users.id','=','user_meta.user_id')
            ->where('users.engroup',$user->engroup==1 ? 2 :1)
            ->whereNotIn('message_board.user_id',$userBlockList)
            ->whereNotIn('message_board.user_id',$bannedUsers)
            ->whereRaw('(message_board.message_expiry_time >="'.$nowTime.'" OR message_board.message_expiry_time is NULL)')
            ->where('message_board.hide_by_admin',0)
            ->orderBy('message_board.created_at','desc')
            ->paginate(10, ['*'], 'othersDataPage')
            ->appends(array_merge(request()->except(['othersDataPage','msgBoardType']),['msgBoardType'=>'others_page']));

        $getLists_myself = MessageBoard::selectRaw('users.id as uid, users.name as uname, users.engroup as uengroup, user_meta.pic as umpic, user_meta.city, user_meta.area')
            ->selectRaw('message_board.id as mid, message_board.title as mtitle, message_board.contents as mcontents, message_board.updated_at as mupdated_at, message_board.created_at as mcreated_at')
            ->LeftJoin('users', 'users.id','=','message_board.user_id')
            ->LeftJoin('user_meta', 'users.id','=','user_meta.user_id')
            ->where('users.id',$user->id)
            ->orderBy('message_board.created_at','desc')
            ->paginate(10, ['*'], 'myselfDataPage')
            ->appends(array_merge(request()->except(['myselfDataPage','msgBoardType']),['msgBoardType'=>'my_page']));

        return view('/dashboard/messageBoard_list', compact('getLists_others', 'getLists_myself', 'data'))
            ->with('user', $user);
    }

    public function messageBoard_post_detail(Request $request)
    {
        $user = $request->user();
        $pid = $request->pid;

        $postDetail =MessageBoard::selectRaw('users.id as uid, users.name as uname, users.engroup as uengroup, user_meta.pic as umpic, user_meta.city, user_meta.area')
            ->selectRaw('message_board.id as mid, message_board.title as mtitle, message_board.contents as mcontents, message_board.updated_at as mupdated_at, message_board.created_at as mcreated_at')
            ->LeftJoin('users', 'users.id','=','message_board.user_id')
            ->LeftJoin('user_meta', 'users.id','=','user_meta.user_id')
            ->where('message_board.id', $pid)->first();

        $images=MessageBoardPic::where('msg_board_id',$pid)->get();

        if(!$postDetail) {
            $request->session()->flash('message', '找不到該留言：' . $pid);
            $request->session()->reflash();
            return  redirect('/MessageBoard/showList');
        }

        return view('/dashboard/messageBoard_detail', compact('postDetail', 'images'))->with('user', $user);
    }

    public function messageBoard_posts(Request $request)
    {
        $user = $this->user;
        return view('/dashboard/messageBoard_post')->with('user', $user);
    }

    public function messageBoard_edit($id)
    {
        $user=auth()->user();
        $editInfo =MessageBoard::selectRaw('users.id as uid, users.name as uname, users.engroup as uengroup, user_meta.pic as umpic, user_meta.city, user_meta.area')
            ->selectRaw('message_board.id as mid, message_board.title as mtitle, message_board.contents as mcontents, message_board.set_period as mperiod, message_board.updated_at as mupdated_at, message_board.created_at as mcreated_at')
            ->LeftJoin('users', 'users.id','=','message_board.user_id')
            ->LeftJoin('user_meta', 'users.id','=','user_meta.user_id')
            ->where('message_board.id', $id)->first();

        if(!$editInfo) {
            session()->flash('message', '找不到該留言：' . $id);
            session()->reflash();
            return redirect()->route('messageBoard_list');
        }

        if ($user->id != $editInfo->uid){
            return redirect()->route('messageBoard_list');
        }

        $images=MessageBoardPic::where('msg_board_id',$id)->get();
        $imagesGroup=array();
        foreach ($images as $key => $value) {
            if(file_exists(public_path($value->pic))){
                $imagePath = $value->pic;
                $imagesGroup['type'][$key] = \App\Helpers\fileUploader_helper::mime_content_type(ltrim($imagePath, '/'));
                $imagesGroup['name'][$key] = Arr::last(explode('/', $value->pic));
                $imagesGroup['size'][$key] = str_starts_with($value->pic, 'http') ? null :filesize(ltrim($imagePath, '/'));
                $imagesGroup['local'][$key] = $imagePath;
                $imagesGroup['file'][$key] = $imagePath;
                $imagesGroup['data'][$key] = [
                    'url' => $imagePath,
                    'thumbnail' =>$imagePath,
                    'renderForce' => true
                ];
            }
        }
        $images=$imagesGroup;

        return view('/dashboard/messageBoard_edit',compact('editInfo','images'))->with('user', $user);
    }

    public function messageBoard_doPosts(Request $request)
    {
        $user=$request->user();
        $fileuploaderListImages = $request->get('fileuploader-list-images');

        if($request->get('action') == 'edit'){
            MessageBoard::find($request->get('mid'))->update(['title'=>$request->get('title'),'contents'=>$request->get('contents')]);
            MessageBoard::setMessageTime($request->get('mid'), $request->get('set_period'));
            //儲存留言板照片
            $this->msg_board_pic_save($request->get('mid'), $user->id, $fileuploaderListImages, $request->file('images'));
            if($request->ajax()) {
                return response()->json([
                    'message' => '修改成功',
                    'return_url' => '/MessageBoard/post_detail/'.$request->get('mid')
                ]);                
            }
            return redirect('/MessageBoard/post_detail/'.$request->get('mid'))->with('message','修改成功');
        }else{

            if($user->isAdminWarned() || User::isBanned($user->id)){
                return back()->with('message','您目前為警示/封鎖狀態，無法新增留言');
            }
            $record_pre=MessageBoard::where('user_id', $user->id)->orderBy('created_at','desc')->first();
            if($record_pre && (date("Y-m-d H:i:s") <= date("Y-m-d H:i:s",strtotime("+3 hours", strtotime($record_pre->created_at)))) ){
                return back()->with('message','您好，由於系統偵測到您的留言頻率太高(每封留言最低間隔 3hr)，為維護系統運作效率，請降低留言頻率。');
            }

            $posts = new MessageBoard();
            $posts->user_id = $user->id;
            $posts->title = $request->get('title');
            $posts->contents=$request->get('contents');
            $posts->save();
            MessageBoard::setMessageTime($posts->id, $request->get('set_period'));

            //儲存留言板照片
            $this->msg_board_pic_save($posts->id, $user->id, null, $request->file('images'));
            if($request->ajax()) {
                return response()->json([
                    'message' => '新增成功',
                    'return_url' => '/MessageBoard/post_detail/'.$posts->id
                ]);
            }            
            return redirect('/MessageBoard/post_detail/'.$posts->id)->with('message','新增成功');
        }
    }

    public function messageBoard_delete($mid)
    {
        $posts = MessageBoard::where('id',$mid)->first();
        if($posts->user_id!== auth()->user()->id){
            return response()->json(['msg'=>'刪除失敗,不可刪除別人的留言!']);
        }
        if($posts){
            $messageBoardImages=MessageBoardPic::selectRaw('id,pic')->where('msg_board_id',$posts->id)->get();
            foreach ($messageBoardImages as $key => $dbImage) {
                if (file_exists(public_path().$dbImage->pic)) {
                    unlink(public_path().$dbImage->pic);
                }
                $dbImage->delete();
            }
            $posts->delete();
        }

        return redirect('/MessageBoard/showList')->with('message','留言刪除成功');
    }


    public function msg_board_pic_save($msg_board_id, $uid, $images, $newImages)
    {
        $messageBoardImages=MessageBoardPic::selectRaw('id,pic')->where('msg_board_id',$msg_board_id)->get();
        $nowImageList=array();
        $images=json_decode($images, true);
        if($images){
            foreach ($images as $imageList){
                $nowImageList[]=array_get($imageList,'file');
            }
        }

        foreach ($messageBoardImages as $key => $dbImage){
            if(in_array(array_get($dbImage,'pic'), $nowImageList)){
                continue;
            }else{
                //移除照片
                if(file_exists($dbImage->file)){
                    unlink($dbImage->file);
                }
                $dbImage->delete();
            }
        }

        //新增新加入照片
        if($files = $newImages)
        {
            foreach ($files as $file) {
                $now = Carbon::now()->format('Ymd');
                $input['imagename'] = $now . rand(100000000,999999999) . '.' . $file->getClientOriginalExtension();

                $rootPath = public_path('/img/MessageBoard');
                $tempPath = $rootPath . '/' . substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/';

                if(!is_dir($tempPath)) {
                    File::makeDirectory($tempPath, 0777, true);
                }

                $destinationPath = '/img/MessageBoard/'. substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/' . $input['imagename'];

                $img = Image::make($file->getRealPath());
                $img->resize(400, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($tempPath . $input['imagename']);

                //新增images到db
                $evaluationPic = new MessageBoardPic();
                $evaluationPic->msg_board_id = $msg_board_id;
                $evaluationPic->member_id = $uid;
                $evaluationPic->pic = $destinationPath;
                $evaluationPic->pic_origin_name = $file->getClientOriginalName();
                $evaluationPic->save();
            }
        }
    }

    public function reportMessageBoardAJAX(Request $request){
        $msg_id=$request->msg_id;
        $isReported = ReportedMessageBoard::where('user_id', auth()->user()->id)->where('message_board_id',$msg_id)->first();
        if(!$isReported) {
            ReportedMessageBoard::create(['user_id'=>auth()->user()->id, 'message_board_id'=>$msg_id]);
            return response()->json(['msg' => '檢舉留言成功']);
        }else{
            return response()->json(['msg' => '該留言已經檢舉過了']);
        }
    }
    
    public function setTinySetting(Request $request) {
        $user=$request->user();
        $cat = $request->catalog;
        $value = $request->value;
        $is_ajax = $request->ajax();
        
        if(!$user || !$cat) {
            if($is_ajax) {
                return response()->json(['msg' => '儲存失敗']);
            }
            else {
                return redirect()->back()->with('message','儲存失敗');
            }             
        }
        
        if(UserTinySetting::updateOrInsert(['user_id'=>$user->id,'cat'=>$cat],['value'=>$value,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]))
        {
            if($is_ajax) {
                return response()->json(['msg' => '儲存成功']);
            }
            else {
                return redirect()->back()->with('message','儲存成功');
            }
        }
        else {
            if($is_ajax) {
                return response()->json(['msg' => '儲存失敗']);
            }
            else {
                return redirect()->back()->with('message','儲存失敗');
            }            
        }
        
    }
    
    public function getTinySetting(Request $request) {
        $user=$request->user();
        $cat = $request->catalog;
        if(!$cat) return null;

        $setting = UserTinySetting::where([['user_id',$user->id],['cat',$cat]])->orderByDesc('id')->first();
        if($setting) return $setting->value;
    }
    
    public function listSearchIgnore(Request $request,SearchIgnoreService $service) {
        $user = auth()->user();
        $data['service'] = $service->fillPagingEntrys();
        return view('/new/dashboard/search_ignore_list',$data)->with('user', $user);
    }
    
    public function addSearchIgnore(Request $request,SearchIgnoreService $service)  {
        if(!$request->target??null) return;
            
        $ignore_data['ignore_id'] = $request->target;
        
        return $service->create($ignore_data)?1:0;
    }
    
    public function delSearchIgnore(Request $request,SearchIgnoreService $service) {
        if(!$request->target??null) return $service->delMemberAll()?1:0;
        return $service->delByIgnoreId($request->target)?1:0;
    }
}



