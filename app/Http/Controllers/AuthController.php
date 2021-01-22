<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Tymon\JWTAuth\Facades\JWTAuth;
// use JWTAuth;
// use Tymon\JWTAuth\Exceptions\JWTException;
// use Illuminate\Support\Facades\Auth;
use App\Models\UserMeta;
use App\Models\User;
use App\Models\Visited;
use App\Models\BasicSetting;
use App\Services\UserService;
use App\Services\VipLogService;
use App\Models\MemberPic;
use App\Models\Message_new;
use Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Models\Message;
use App\Models\SimpleTables\banned_users;
use App\Models\Blocked;
use App\Models\MemberFav;
use Illuminate\Support\Facades\DB;
use App\Models\Reported;
use App\Models\SimpleTables\warned_users;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function __construct(UserService $userService, VipLogService $logService)
    {
        $this->service = $userService;
        $this->logService = $logService;
        $this->middleware('auth:api')->except('login','register');
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        $exits = User::where('email', $credentials['email'])->first();
        if($exits && $exits->email !== $credentials['email']){
            return response()->json(['status' => 1, 'message' => 'invalid credentials'], 401);
        }

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['status' => 1, 'message' => 'invalid credentials'], 401);
        }

        return response()->json(['status' => 0, 'token' => $token]);
    }

    public function register(Request $request)
    {
        $response = array();
        // if($request->has('data') && !empty($request->input('data'))){
            // $req                 = json_decode($request->input('data'), true);
            $req                 = $request->all();
            if(isset($req['email']) && !empty($req['email'])  
                && isset($req['password']) && !empty($req['password'])){
                if(!isset($req['name']) || empty($req['name'])) {
                    $response['status'] = 1;
                    $response['message'] = "栏位不正确";
                }
                if(!isset($req['name']) || empty($req['name'])) {
                    $response['status'] = 1;
                    $response['message'] = "栏位不正确";
                }
                if(!isset($req['title']) || empty($req['title'])) {
                    $response['status'] = 1;
                    $response['message'] = "栏位不正确";
                }
                if(!isset($req['engroup']) || empty($req['engroup'])) {
                    $response['status'] = 1;
                    $response['message'] = "栏位不正确";
                }
                if(empty($req['exchange_period'])){
                    $req['exchange_period']=2;
                }

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
                    'title'    => ['required', 'max:255', 'not_contains'],
                    'engroup'  => ['required'],
                    'email'    => 'required|email|max:255|unique:users',
                    'password' => 'required|min:6|confirmed',
                    'agree'    => 'required',
                ];
                $messages = [
                    'not_contains'  => '請勿使用包含「站長」或「管理員」的字眼做為暱稱！',
                    'agree.required'=> '您必須同意本站的使用條款和隱私政策，才可完成註冊。',
                    'required'      => ':attribute不可為空',    
                    'email.email'   => 'E-mail格式錯誤',
                    'email.unique'  => '此 E-mail 已被註冊',
                    'min:6' =>'密碼欄位需6個字元以上',
                    'password.confirmed' => '密碼確認錯誤'
                ];
                $attributes = [
                    'name'      => '暱稱',
                    'title'     => '標題',
                    'engroup'   => '帳號類型',
                    'email'     => 'E-mail信箱',
                    'password'  => '密碼',
                    'exchange_period'   => '包養關係',
                ];
                
                $validator = \Validator::make($req, $rules, $messages, $attributes);

                if ($validator->fails()) {
                    $response['status'] = 1;
                    $response['message'] = $validator->messages();
                    return response()->json($response);
                }

                $modelUser = new User;
                $modelUser->name = $req['name'];
                $modelUser->email = $req['email'];
                $modelUser->password = bcrypt($req['password']);
                $modelUser->title = $req['title'];
                $modelUser->engroup = $req['engroup'];
                $modelUser->exchange_period = $req['exchange_period'];
                // $modelUser->city = "臺北市";
                // $modelUser->area = "中山區";

                if( $modelUser->save() ){   
                    
                    //新註冊不須顯示修改提示，故須先將註記資料存入
                    DB::table('exchange_period_temp')->insert(['user_id'=>$modelUser->id,'created_at'=> now()]);
                    $this->service->create($modelUser, $req['password']);

                    $response['status'] = 0;
                    $response['message'] = "註冊成功";
                }else{
                    $response['status'] = 1;
                    $response['message'] = "註冊失敗";
                    return response()->json($response);
                }
            }else{
                $response['status'] = 1;
                $response['message'] = "栏位不正确";
            }
        // }
        // else{
        //     $response['status'] = 1;
        //     $response['message'] = "栏位不正确";
        // }
        return response()->json($response);
    }

    public function me()
    {
        $user = auth('api')->user();
        $user->isVip = $user->isVip();
        $user->notifyMessage = $user->meta_()->notifmessage;
        $user->notifyHistory = $user->meta_()->notifhistory;
        return response()->json($user);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['status' => 0]);
    }

    public function search()
    {
        // 備註：paging 時, 需要在網址多塞 page 參數

        $county = ""; // 縣市
        $district = "";// 區域
        $cup = ""; // [cup] A, B, C, D, E, F
        $marriage = ""; // [婚姻] 已婚, 分居, 單身, 有男友
        $budget = ""; // [預算] 基礎, 進階, 高級, 最高, 可商議
        $income = ""; // [年收入] 50萬以下, 50~100萬, 100-200萬, 200-300萬, 300萬以上
        $smoking = ""; // [抽菸] 不抽, 偶爾抽, 常抽
        $drinking = ""; // [喝酒] 不喝, 偶爾喝, 常喝
        $photo = ""; // pic [是否有照片] on
        $agefrom = ""; // [年齡] 歲
        $ageto = ""; // [年齡] 歲
        $seqtime = "1"; // [搜索排列順序(降冪)] 1:登入時間 2:註冊時間
        $body = ""; // [體型] 瘦, 標準, 微胖, 胖
		$exchange_period = ""; // [包養關係] 1: 長期為主 2: 長短皆可 3: 單次為主

        $user=auth('api')->user();
        
        $umeta=$user->meta_();
        if (isset($umeta->city)) {
            $umeta->city = explode(",", $umeta->city);
            $umeta->area = explode(",", $umeta->area);
        }

        if (isset($_GET['county'])) $county = $_GET['county'];
        if (isset($_GET['district'])) $district = $_GET['district'];
        // if (isset($_GET['cup'])) $cup = $_GET['cup'];
		if (isset($_GET['cup']) && strlen($_GET['cup']) != 0) $cup = explode(",", $_GET['cup']);
        if (isset($_GET['marriage'])) $marriage = $_GET['marriage'];
        if (isset($_GET['budget'])) $budget = $_GET['budget'];
        if (isset($_GET['income'])) $income = $_GET['income'];
        if (isset($_GET['smoking'])) $smoking = $_GET['smoking'];
        if (isset($_GET['drinking'])) $drinking = $_GET['drinking'];
        if (isset($_GET['pic'])) $photo = $_GET['pic'];
        if (isset($_GET['ageFrom'])) $agefrom = $_GET['ageFrom'];
        if (isset($_GET['ageTo'])) $ageto = $_GET['ageTo'];
        if (isset($_GET['seqtime'])) $seqtime = $_GET['seqtime'];
        // if (isset($_GET['body'])) $body = $_GET['body'];
		if (isset($_GET['body']) && strlen($_GET['body']) != 0) $body = explode(",", $_GET['body']);
		if (isset($_GET['exchange_period']) && strlen($_GET['exchange_period']) != 0) $exchange_period = explode(",", $_GET['exchange_period']);

        $vis = UserMeta::search($county, $district, $cup, $marriage, $budget, $income, $smoking, $drinking, $photo, $agefrom, $ageto, $user->engroup, $umeta->city, $umeta->area, $umeta->blockdomain, $umeta->blockdomainType, $seqtime, $body, $user->id, $exchange_period);

        foreach ($vis as $v) {
            $targetUser = User::where('id', $v->id)->get()->first();
            $checkRecommendedUser['description'] = null;
            $checkRecommendedUser['stars'] = null;
            $checkRecommendedUser['background'] = null;
            $checkRecommendedUser['title'] = null;
            $checkRecommendedUser['button'] = null;
            $checkRecommendedUser['height'] = null;
            try{
                $checkRecommendedUser = $this->service->checkRecommendedUser($targetUser);
            }
            catch (\Exception $e){
                Log::info('Current URL: '.url()->current());
                Log::debug('checkRecommendedUser() failed, $targetUser: '.$targetUser);
            }
            $v['checkRecommendedUser'] = $checkRecommendedUser;
            $v['isVIP'] = $targetUser->isVip();
            $v['isAdminWarned'] = $targetUser->isAdminWarned();
            $v['isPhoneAuth'] = $targetUser->isPhoneAuth();
			
			$exchange_period_name = DB::table('exchange_period_name')->where('id', $v->exchange_period)->first();
			if (isset($exchange_period_name)) {
				$v['exchange_period_name'] = $exchange_period_name->name;
			}
        }
        
        return response()->json(['status' => 0, 'vis' => $vis, 'city' => $umeta->city, 'area' => $umeta->area]);
    }

    public function districts() 
    {
        $area_data = '{"臺北市": ["中正區", "大同區", "中山區", "萬華區", "信義區", "松山區", "大安區", "南港區", "北投區", "內湖區", "士林區", "文山區"],"新北市": ["板橋區", "新莊區", "泰山區", "林口區", "淡水區", "金山區", "八里區", "萬里區", "石門區", "三芝區", "瑞芳區", "汐止區", "平溪區", "貢寮區", "雙溪區", "深坑區", "石碇區", "新店區", "坪林區", "烏來區", "中和區", "永和區", "土城區", "三峽區", "樹林區", "鶯歌區", "三重區", "蘆洲區", "五股區"],"基隆市": ["仁愛區", "中正區", "信義區", "中山區", "安樂區", "暖暖區", "七堵區"],"桃園市": ["桃園區", "中壢區", "平鎮區", "八德區", "楊梅區", "蘆竹區", "龜山區", "龍潭區", "大溪區", "大園區", "觀音區", "新屋區", "復興區"],"新竹縣": ["竹北市", "竹東鎮", "新埔鎮", "關西鎮", "峨眉鄉", "寶山鄉", "北埔鄉", "橫山鄉", "芎林鄉", "湖口鄉", "新豐鄉", "尖石鄉", "五峰鄉"],"新竹市": ["東區", "北區", "香山區"],"苗栗縣": ["苗栗市", "通霄鎮", "苑裡鎮", "竹南鎮", "頭份鎮", "後龍鎮", "卓蘭鎮", "西湖鄉", "頭屋鄉", "公館鄉", "銅鑼鄉", "三義鄉", "造橋鄉", "三灣鄉", "南庄鄉", "大湖鄉", "獅潭鄉", "泰安鄉"],"臺中市": ["中區", "東區", "南區", "西區", "北區", "北屯區", "西屯區", "南屯區", "太平區", "大里區", "霧峰區", "烏日區", "豐原區", "后里區", "東勢區", "石岡區", "新社區", "和平區", "神岡區", "潭子區", "大雅區", "大肚區", "龍井區", "沙鹿區", "梧棲區", "清水區", "大甲區", "外埔區", "大安區"],"南投縣": ["南投市", "埔里鎮", "草屯鎮", "竹山鎮", "集集鎮", "名間鄉", "鹿谷鄉", "中寮鄉", "魚池鄉", "國姓鄉", "水里鄉", "信義鄉", "仁愛鄉"],"彰化縣": ["彰化市", "員林鎮", "和美鎮", "鹿港鎮", "溪湖鎮", "二林鎮", "田中鎮", "北斗鎮", "花壇鄉", "芬園鄉", "大村鄉", "永靖鄉", "伸港鄉", "線西鄉", "福興鄉", "秀水鄉", "埔心鄉", "埔鹽鄉", "大城鄉", "芳苑鄉", "竹塘鄉", "社頭鄉", "二水鄉", "田尾鄉", "埤頭鄉", "溪州鄉"],"雲林縣": ["斗六市", "斗南鎮", "虎尾鎮", "西螺鎮", "土庫鎮", "北港鎮", "莿桐鄉", "林內鄉", "古坑鄉", "大埤鄉", "崙背鄉", "二崙鄉", "麥寮鄉", "臺西鄉", "東勢鄉", "褒忠鄉", "四湖鄉", "口湖鄉", "水林鄉", "元長鄉"],"嘉義縣": ["太保市", "朴子市", "布袋鎮", "大林鎮", "民雄鄉", "溪口鄉", "新港鄉", "六腳鄉", "東石鄉", "義竹鄉", "鹿草鄉", "水上鄉", "中埔鄉", "竹崎鄉", "梅山鄉", "番路鄉", "大埔鄉", "阿里山鄉"],"嘉義市": ["東區", "西區"],"臺南市": ["中西區", "東區", "南區", "北區", "安平區", "安南區", "永康區", "歸仁區", "新化區", "左鎮區", "玉井區", "楠西區", "南化區", "仁德區", "關廟區", "龍崎區", "官田區", "麻豆區", "佳里區", "西港區", "七股區", "將軍區", "學甲區", "北門區", "新營區", "後壁區", "白河區", "東山區", "六甲區", "下營區", "柳營區", "鹽水區", "善化區", "大內區", "山上區", "新市區", "安定區"],"高雄市": ["楠梓區", "左營區", "鼓山區", "三民區", "鹽埕區", "前金區", "新興區", "苓雅區", "前鎮區", "小港區", "旗津區", "鳳山區", "大寮區", "鳥松區", "林園區", "仁武區", "大樹區", "大社區", "岡山區", "路竹區", "橋頭區", "梓官區", "彌陀區", "永安區", "燕巢區", "田寮區", "阿蓮區", "茄萣區", "湖內區", "旗山區", "美濃區", "內門區", "杉林區", "甲仙區", "六龜區", "茂林區", "桃源區", "那瑪夏區"],"屏東縣": ["屏東市", "潮州鎮", "東港鎮", "恆春鎮", "萬丹鄉", "長治鄉", "麟洛鄉", "九如鄉", "里港鄉", "鹽埔鄉", "高樹鄉", "萬巒鄉", "內埔鄉", "竹田鄉", "新埤鄉", "枋寮鄉", "新園鄉", "崁頂鄉", "林邊鄉", "南州鄉", "佳冬鄉", "琉球鄉", "車城鄉", "滿州鄉", "枋山鄉", "霧台鄉", "瑪家鄉", "泰武鄉", "來義鄉", "春日鄉", "獅子鄉", "牡丹鄉", "三地門鄉"],"宜蘭縣": ["宜蘭市", "羅東鎮", "蘇澳鎮", "頭城鎮", "礁溪鄉", "壯圍鄉", "員山鄉", "冬山鄉", "五結鄉", "三星鄉", "大同鄉", "南澳鄉"],"花蓮縣": ["花蓮市", "鳳林鎮", "玉里鎮", "新城鄉", "吉安鄉", "壽豐鄉", "秀林鄉", "光復鄉", "豐濱鄉", "瑞穗鄉", "萬榮鄉", "富里鄉", "卓溪鄉"],"臺東縣": ["臺東市", "成功鎮", "關山鎮", "長濱鄉", "海端鄉", "池上鄉", "東河鄉", "鹿野鄉", "延平鄉", "卑南鄉", "金峰鄉", "大武鄉", "達仁鄉", "綠島鄉", "蘭嶼鄉", "太麻里鄉"],"澎湖縣": ["馬公市", "湖西鄉", "白沙鄉", "西嶼鄉", "望安鄉", "七美鄉"],"金門縣": ["金城鎮", "金湖鎮", "金沙鎮", "金寧鄉", "烈嶼鄉", "烏坵鄉"],"連江縣": ["南竿鄉", "北竿鄉", "莒光鄉", "東引鄉"]}';
        $area_data_json = json_decode($area_data, true);
        return response()->json(['status' => 0, 'data' => $area_data_json]);
    }

    public function viewuser(Request $request, $uid = -1)
    {
        $user = $request->user();

        if (isset($user) && isset($uid)) {
            $targetUser = User::where('id', $uid)->get()->first();
            if (!isset($targetUser)) {
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
            }
            catch (\Exception $e){
                Log::info('Current URL: '.url()->current());
                Log::debug('checkRecommendedUser() failed, $targetUser: '.$targetUser);
            }
            finally{
                $vipLevel = 1;
                if ($user->vip_record == '0000-00-00 00:00:00') {
                    $vipLevel = 0;
                }

                $basic_setting = BasicSetting::where('vipLevel', $vipLevel)->where('gender', $user->engroup)->get()->first();

                $data = array();

                if (isset($basic_setting['countSet'])) {
                    if ($basic_setting['countSet'] == -1) {
                        $basic_setting['countSet'] = 10000;
                    }
                    $data = array(
                        'timeSet'=> (int)$basic_setting['timeSet'],
                        'countSet'=> (int)$basic_setting['countSet'],
                    );
                }

                // 取得 cur 會員照片
                $cur = $this->service->find($uid);
                $pics_arr = array();
                if (isset($cur)) {
                    $pics = MemberPic::getSelf($cur->id);
                    foreach ($pics as $pic) {
                        if ($pic->isHidden != 1) {
                            array_push($pics_arr, $pic->pic);
                        }
                    }
                }

                // 取得 cur 詳細資料
                $cmeta = null;
                if (isset($cur)) {
                    $cmeta = $cur->meta_();
                    if (isset($cmeta->city) || isset($cmeta->area)) {
                        $cmeta->city = explode(",", $cmeta->city);
                        $cmeta->area = explode(",", $cmeta->area);

                        // if (str_contains($cmeta->city, ',')) {
                        //     $cmeta->city = explode(",", $cmeta->city);
                        // }
                        // if (str_contains($cmeta->area, ',')) {
                        //     $cmeta->area = explode(",", $cmeta->area);
                        // }
                    }
                }

                // 取得 cur 進階資料
                $cur->favedCount = $cur->favedCount(); // 被收藏次數
                $cur->favCount = $cur->favCount(); // 收藏會員次數
                $cur->tipCount = $cur->tipCount(); // 車馬費邀請次數
                $cur->msgCount = $cur->msgCount(); // 發信次數
                $cur->msgsevenCount = $cur->msgsevenCount(); // 過去7天發信次數
                $cur->isBlocked = $cur->isBlocked($user->id); // 是否封鎖我
                $cur->isSeen = $cur->isSeen($user->id); // 是否看過我
                $cur->visitCount = $cur->visitCount(); // 瀏覽其他會員次數
                $cur->visitedCount = $cur->visitedCount(); // 被瀏覽次數
                $cur->visitedsevenCount = $cur->visitedsevenCount(); // 過去7天被瀏覽次數
                $cur->isVIP = $cur->isVip(); // 是否為 VIP
                $cur->isAdminWarned = $cur->isAdminWarned(); // 是否為警示帳戶
                $cur->isPhoneAuth = $cur->isPhoneAuth(); // 是否手機認證
                $cur->isBlockedOther = Blocked::isBlocked($user->id, $cur->id); // 是否封鎖對方
				$cur->isSent3Msg = $user->isSent3Msg($cur->id);

                $evaluation_self = DB::table('evaluation')->where('to_id',$uid)->where('from_id',$user->id)->first();
                $cur->isEvaluation = isset($evaluation_self)? true : false;

				$exchange_period_name = DB::table('exchange_period_name')->where('id', $cur->exchange_period)->first();
				if (isset($exchange_period_name)) {
					$cur['exchangePeriodName'] = $exchange_period_name->name;
				}
                
                // 取得 user 詳細資料
                $umeta = null;
                if (isset($user)) {
                    $umeta = $user->meta_();
                    if (isset($umeta->city) || isset($umeta->area)) {
                        $umeta->city = explode(",", $umeta->city);
                        $umeta->area = explode(",", $umeta->area);
                    }
                    $umeta->isPhoneAuth = $user->isPhoneAuth();
                    $umeta->isVIP = $user->isVip();
                    $vipDays=0;
                    if($user->isVip()) {
                        $vip_record = Carbon::parse($user->vip_record);
                        $vipDays = $vip_record->diffInDays(Carbon::now());
                    }
                    $umeta->vipDays = $vipDays;
                }
                
                return response()->json(['status' => 0, 'umeta' => $umeta, 'cmeta' => $cmeta, 'cur_pics' => $pics_arr, 'data' => $data, 'user' => $user, 'cur' => $cur, 'checkRecommendedUser' => $checkRecommendedUser]);
            }
        }
    }

    public function chatviewMore(Request $request, $type = 1)
    {
        $user = $request->user();

        $data = null;
        if ($type == 1) {
            $data = Message_new::allSendersAJAX($user->id, $user->isVip(), 7);
        } elseif ($type == 2) {
            $data = Message_new::allSendersAJAX($user->id, $user->isVip(), 30);
        } elseif ($type == 3) {
            $data = Message_new::allSendersAJAX($user->id, $user->isVip(), 'all');
        }

        $isVip = $user->isVip();
        if (!$isVip) {
            $countVisible = 10;
        } else {
            $countVisible = -1;
        }

        $totalMsgCount = sizeof($data);
        if ($totalMsgCount == 1 && $data[0] == 'No data') {

        } else {
            foreach ($data as $key => $value) {
                if (!$isVip && $totalMsgCount > $countVisible) {
                    $data[$key]['isMsgVisiable'] = false;
                } else {
                    $data[$key]['isMsgVisiable'] = true;
                }
                if (isset($value['to_id'])) {
                    $data[$key]['engroup'] = $user->id_($value['to_id'])->engroup;
                    $data[$key]['engroup_change'] = $user->id_($value['to_id'])->engroup_change;
                }
                $totalMsgCount--;
            }
        }

        if (isset($data)) {
            return response()->json(array(
                'status' => 1,
                'msg' => $data,
                'noVipCount' => Config::get('social.limit.show-chat')
            ), 200);
        } else {
            return response()->json(array(
                'status' => 2,
                'msg' => 'fail'
            ), 500);
        }
    }
    
	public function chat2(Request $request, $cid)
    {
        $user = $request->user();
        $m_time = '';
        if (isset($user)) {
            // 取得 to_user 大頭照
            $to_user = $this->service->find($cid);
            $to_user_pic = '';

            if (isset($to_user)) {
                $to_user_pic = $to_user->meta_()->pic;
            }
            $isVip = $user->isVip();
            $messages = Message::allToFromSender($user->id, $cid);
			
			foreach ($messages as $msg) {
				$msg['pic'] = $to_user_pic;
                $msg['engroup'] = $to_user->engroup;
			}
			
            if (isset($cid)) {
                if (!$user->isVip() && $user->engroup == 1) {
                    $m_time = Message::select('created_at')->
                    where('from_id', $user->id)->
                    orderBy('created_at', 'desc')->first();
                    if (isset($m_time)) {
                        $m_time = $m_time->created_at;
                    }
                }
                return response()->json(['user' => $user, 'to' => $this->service->find($cid), 'm_time' => $m_time, 'isVip' => $isVip, 'messages' => $messages]);
            }
            else {
                return response()->json(['user' => $user, 'm_time' => $m_time, 'isVip' => $isVip, 'messages' => $messages]);
            }
        }
    }

    public function postChat(Request $request)
    {
        $banned = banned_users::where('member_id', $request->user()->id)
            ->whereNotNull('expire_date')
            ->orderBy('expire_date', 'asc')->get()->first();
        if(isset($banned)){
            $date = \Carbon\Carbon::parse($banned->expire_date);
            return response()->json(['status' => 1, 'banned' => $banned, 'days' => $date->diffInDays() + 1]);
        }
        $payload = $request->all();
        if(!isset($payload['msg'])){
            return response()->json(['status' => 1, 'msg' => '請勿僅輸入空白！']);
        }
        if(!$request->user()->isVIP()){
            $m_time = Message::select('created_at')->
                where('from_id', $request->user()->id)->
                orderBy('created_at', 'desc')->first();
            if(isset($m_time)) {
                $diffInSecs = abs(strtotime(date("Y-m-d H:i:s")) - strtotime($m_time->created_at));
                if ($diffInSecs < 30) {
                    return response()->json(['status' => 1, 'msg' => '您好，由於系統偵測到您的發訊頻率太高(每30秒限一則訊息)。為維護系統運作效率，請降低發訊頻率。']);
                }
            }
        }
        Message::post($request->user()->id, $payload['to'], $payload['msg']);
        return response()->json(['status' => 0, 'to' => $payload['to'], 'msg' => $payload['msg']]);
    }

    public function deleteSingle(Request $request) {
        $uid = $request->user()->id;
        $sid = $request->sid; // 對應 to_id
        $ct_time = $request->ct_time; // 對應 created_at
        $content = $request->content; // 對應 content
        // 為什麼要加上刪兩次才會成功的邏輯？
        Message::deleteSingle($uid, $sid, $ct_time, $content);
        return response()->json(['save' => 'ok']);
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
                //有收藏名單則刪除
                $isFav = MemberFav::where('member_id', $aid)->where('member_fav_id',$bid)->count();
                if($isFav>0){
                    MemberFav::remove($aid, $bid);
                }
                return response()->json(['save' => 'ok']);
            }
        }
        return response()->json(['save' => 'error']);
    }
	
	public function deleteBetweenGET($uid, $sid) {

        Message::deleteBetween($uid, $sid);

        return response()->json(['save' => 'ok']);
    }

    public function deleteBetweenGetAll($uid, $sid) {
        $ids = explode(',',$uid);
        foreach($ids as $id){
            Message::deleteBetween($sid,$id);
        }
        return response()->json(['save' => 'ok']);
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
	
	private function customTrim($str){
        $search = array(" ","　","\n","\r","\t");
        $replace = array("","","","","");
        return str_replace($search, $replace, $str);
    }
	
	public function reportPost(Request $request){
        if(empty($this->customTrim($request->content))){
            return response()->json(['save' => 'error']);
        }
        Reported::report($request->aid, $request->uid, $request->content);
        return response()->json(['save' => 'ok']);
    }
	
	public function chatSet(Request $request) {
        $user = UserMeta::where('user_id', $request->uid)->first();
        if ($user) {
            $user->update([
                'notifmessage' => $request->notifyMessage,
                'notifhistory' => $request->notifyHistory
            ]);
            return response()->json(['save' => 'ok']);
        }
    }

    public function evaluationSave(Request $request)
    {

        $evaluation_self = DB::table('evaluation')->where('to_id',$request->input('eid'))->where('from_id',$request->input('uid'))->first();

        if (isset($evaluation_self)) {
            // DB::table('evaluation')->where('to_id',$request->input('eid'))->where('from_id',$request->input('uid'))->update(
            //     ['content' => $request->input('content'), 'rating' => $request->input('rating'), 'updated_at' => now()]
            // );
            return response()->json(['save' => 'error', 'status' => 1, 'msg' => '已評價過!']);
        } else {
            DB::table('evaluation')->insert(
                ['from_id' => $request->input('uid'), 'to_id' => $request->input('eid'), 'content' => $request->input('content'), 'rating' => $request->input('rating'), 'created_at' => now(), 'updated_at' => now()]
            );
        }
        
        return response()->json(['save' => 'ok']);
    }

    public function evaluation(Request $request, $uid)
    {
        $user = $request->user();
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

            foreach($evaluation_data as $row) {
                $from_user = \App\Models\User::findById($row->from_id);
                $to_user = \App\Models\User::findById($row->to_id);
                $row->from_user_name = $from_user->name;
                $row->to_user_name = $to_user->name;
            }

            return response()->json(['status' => 0, 'evaluation_self' => $evaluation_self, 'evaluation_data' => $evaluation_data]);
        }
    }

    public function evaluationDelete(Request $request)
    {
        DB::table('evaluation')->where('id',$request->id)->delete();
        return response()->json(['save' => 'ok']);
    }
}
