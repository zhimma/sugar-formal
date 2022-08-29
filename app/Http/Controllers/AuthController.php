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
use App\Models\ReportedAvatar;
use App\Models\ReportedPic;
use App\Models\LogUserLogin;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Models\Evaluation;
use App\Models\EvaluationPic;
use App\Models\ValueAddedService;
use App\Models\hideOnlineData;
use App\Models\Tip;
use App\Services\SearchIgnoreService;
use App\Services\AdminService;
use App\Models\LogFreeVipPicAct;
use App\Models\BannedUsersImplicitly;
use App\Models\AdminAnnounce;
use App\Models\AnnouncementRead;
use Session;
use App\Services\EnvironmentService;

class AuthController extends Controller
{
    public function __construct(UserService $userService, VipLogService $logService)
    {
        $this->service = $userService;
        $this->logService = $logService;
        $this->middleware('auth:api')->except('login', 'register', 'registerMode');
    }

    public function registerMode()
    {
        return response()->json(['status' => 0, 'registerMode' => true]);
    }

    public function login(Request $request)
    {
        Log::info('start_AuthController_login');
        $credentials = request(['email', 'password']);
        if ($token = auth('api')->attempt($credentials)) {
            $user = User::select('id', 'last_login')->withOut(['vip', 'user_meta'])->where('email', $request->email)->get()->first();

            //移至LogSuccessfulLoginListener
            /*
            // 更新 login_times
            User::where('id', $user->id)->update(['login_times'=>$user->login_times + 1]);
            // 新增登入紀錄
            LogUserLogin::create([
                'user_id' => $user->id, 
                'userAgent' => 'App', 
                'ip' => $request->ip(), 
                'created_at' => date('Y-m-d H:i:s')]
            );
            */
            
            return response()->json(['status' => 0, 'token' => $token]);
        }
        return response()->json(['status' => 1, 'message' => 'invalid credentials'], 401);

        /*
        $credentials = request(['email', 'password']);

        $exits = User::where('email', $credentials['email'])->first();
        if ($exits && $exits->email !== $credentials['email']) {
            return response()->json(['status' => 1, 'message' => 'invalid credentials'], 401);
        }

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['status' => 1, 'message' => 'invalid credentials'], 401);
        }

        return response()->json(['status' => 0, 'token' => $token]);
        */
    }

    public function register(Request $request)
    {
        $response = array();
        $req = $request->all();
        if (isset($req['email']) 
            && !empty($req['email']) 
            && isset($req['password']) 
            && !empty($req['password'])
        ) {
            if (!isset($req['name']) || empty($req['name'])) {
                $response['status'] = 1;
                $response['message'] = "欄位不正確";
            }
            if (!isset($req['name']) || empty($req['name'])) {
                $response['status'] = 1;
                $response['message'] = "欄位不正確";
            }
            if (!isset($req['title']) || empty($req['title'])) {
                $response['status'] = 1;
                $response['message'] = "欄位不正確";
            }
            if (!isset($req['engroup']) || empty($req['engroup'])) {
                $response['status'] = 1;
                $response['message'] = "欄位不正確";
            }
            if (empty($req['exchange_period'])) {
                $req['exchange_period'] = 2;
            }

            // Custom validation.
            Validator::extend('not_contains', function($attribute, $value, $parameters)
            {
                $words = array('站長', '管理員');
                foreach ($words as $word) {
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
            $modelUser->registered_from_mobile = 1; // for 手機註冊使用者

            if ($modelUser->save()) {   
                // 新註冊不須顯示修改提示，故須先將註記資料存入
                DB::table('exchange_period_temp')->insert(['user_id'=>$modelUser->id,'created_at'=> now()]);
                $this->service->create($modelUser, $req['password']);
                $response['status'] = 0;
                $response['message'] = "註冊成功";
            } else {
                $response['status'] = 1;
                $response['message'] = "註冊失敗";
                return response()->json($response);
            }
        } else {
            $response['status'] = 1;
            $response['message'] = "欄位不正確";
        }
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
        $seqtime = 1; // [搜索排列順序(降冪)] 1:登入時間 2:註冊時間
        $body = ""; // [體型] 瘦, 標準, 微胖, 胖
        $exchange_period = ""; // [包養關係] 1: 長期為主 2: 長短皆可 3: 單次為主
        $isBlocked = 1;
        $heightfrom = "";
        $heightto = "";
        $prRange_none = "";
        $prRange = "";
        $situation = "";
        $education = "";
        $isVip = "";
        $isWarned = null;
        $isPhoneAuth = "";
        $tattoo = null;
        $city2 = null;
        $area2 = null; 
        $city3 = null;
        $area3 = null;
        $weight = "";

        $user = auth('api')->user();
        
        $umeta = $user->meta_();
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
        if (isset($_GET['isBlocked'])) $isBlocked = $_GET['isBlocked'];
        if (isset($_GET['heightfrom'])) $heightfrom = $_GET['heightfrom'];
        if (isset($_GET['heightto'])) $heightto = $_GET['heightto'];
        if (isset($_GET['prRange_none'])) $prRange_none = $_GET['prRange_none'];
        if (isset($_GET['prRange'])) $prRange = $_GET['prRange'];
        if (isset($_GET['situation'])) $situation = $_GET['situation'];
        if (isset($_GET['education'])) $education = $_GET['education'];
        if (isset($_GET['isVip'])) $isVip = $_GET['isVip'];
        if (isset($_GET['isWarned'])) $isWarned = $_GET['isWarned'];
        if (isset($_GET['isPhoneAuth'])) $isPhoneAuth = $_GET['isPhoneAuth'];
        if (isset($_GET['tattoo'])) $tattoo = $_GET['tattoo'];
        if (isset($_GET['city2'])) $city2 = $_GET['city2'];
        if (isset($_GET['area2'])) $area2 = $_GET['area2'];
        if (isset($_GET['city3'])) $city3 = $_GET['city3'];
        if (isset($_GET['area3'])) $area3 = $_GET['area3'];
        if (isset($_GET['weight'])) $weight = $_GET['weight'];
        $userIsVip = $user->isVIP();
        $userIsAdvanceAuth = isset($_GET['isAdvanceAuth'])?1:0;

        $vis = UserMeta::search(
            $county, 
            $district, 
            $cup, 
            $marriage, 
            $budget, 
            $income, 
            $smoking, 
            $drinking, 
            $photo, 
            $agefrom, 
            $ageto, 
            $user->engroup, 
            $umeta->city, 
            $umeta->area, 
            $umeta->blockdomain, 
            $umeta->blockdomainType, 
            $seqtime, 
            $body, 
            $user->id, 
            $exchange_period, 
            $isBlocked, 
            $userIsVip, 
            $heightfrom, 
            $heightto, 
            $prRange_none, 
            $prRange, 
            $situation,
            $education,
            $isVip,
            $isWarned,
            $isPhoneAuth,
            $userIsAdvanceAuth,
            $tattoo,
            $city2,
            $area2,
            $city3,
            $area3,
            $weight,
            $user->registered_from_mobile
        );

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
            $v['isPhoneAuth'] = $targetUser->isPhoneAuth(); // 是否手機認證
            $v['isAdvanceAuth'] = $targetUser->isAdvanceAuth(); // 是否進階認證
            $v['isOnline'] = $targetUser->isOnline(); // 是否上線
            $v['hideOnline'] = $targetUser->valueAddedServiceStatus('hideOnline'); // 付費隱藏上線
            $v['isBlurAvatar'] = \App\Services\UserService::isBlurAvatar($targetUser, $user); // 是否大頭照模糊

            $exchange_period_name = DB::table('exchange_period_name')->where('id', $v->exchange_period)->first();
            if (isset($exchange_period_name)) {
                $v['exchange_period_name'] = $exchange_period_name->name;
            }
        }
        
        return response()->json(['status' => 0, 'vis' => $vis, 'city' => $umeta->city, 'area' => $umeta->area]);
    }

    public function districts()
    {
        $area_data = '{"臺北市": ["中正區", "大同區", "中山區", "萬華區", "信義區", "松山區", "大安區", "南港區", "北投區", "內湖區", "士林區", "文山區"],"新北市": ["板橋區", "新莊區", "泰山區", "林口區", "淡水區", "金山區", "八里區", "萬里區", "石門區", "三芝區", "瑞芳區", "汐止區", "平溪區", "貢寮區", "雙溪區", "深坑區", "石碇區", "新店區", "坪林區", "烏來區", "中和區", "永和區", "土城區", "三峽區", "樹林區", "鶯歌區", "三重區", "蘆洲區", "五股區"],"基隆市": ["仁愛區", "中正區", "信義區", "中山區", "安樂區", "暖暖區", "七堵區"],"桃園市": ["桃園區", "中壢區", "平鎮區", "八德區", "楊梅區", "蘆竹區", "龜山區", "龍潭區", "大溪區", "大園區", "觀音區", "新屋區", "復興區"],"新竹縣": ["竹北市", "竹東鎮", "新埔鎮", "關西鎮", "峨眉鄉", "寶山鄉", "北埔鄉", "橫山鄉", "芎林鄉", "湖口鄉", "新豐鄉", "尖石鄉", "五峰鄉"],"新竹市": ["東區", "北區", "香山區"],"苗栗縣": ["苗栗市", "通霄鎮", "苑裡鎮", "竹南鎮", "頭份鎮", "後龍鎮", "卓蘭鎮", "西湖鄉", "頭屋鄉", "公館鄉", "銅鑼鄉", "三義鄉", "造橋鄉", "三灣鄉", "南庄鄉", "大湖鄉", "獅潭鄉", "泰安鄉"],"臺中市": ["中區", "東區", "南區", "西區", "北區", "北屯區", "西屯區", "南屯區", "太平區", "大里區", "霧峰區", "烏日區", "豐原區", "后里區", "東勢區", "石岡區", "新社區", "和平區", "神岡區", "潭子區", "大雅區", "大肚區", "龍井區", "沙鹿區", "梧棲區", "清水區", "大甲區", "外埔區", "大安區"],"南投縣": ["南投市", "埔里鎮", "草屯鎮", "竹山鎮", "集集鎮", "名間鄉", "鹿谷鄉", "中寮鄉", "魚池鄉", "國姓鄉", "水里鄉", "信義鄉", "仁愛鄉"],"彰化縣": ["彰化市", "員林鎮", "和美鎮", "鹿港鎮", "溪湖鎮", "二林鎮", "田中鎮", "北斗鎮", "花壇鄉", "芬園鄉", "大村鄉", "永靖鄉", "伸港鄉", "線西鄉", "福興鄉", "秀水鄉", "埔心鄉", "埔鹽鄉", "大城鄉", "芳苑鄉", "竹塘鄉", "社頭鄉", "二水鄉", "田尾鄉", "埤頭鄉", "溪州鄉"],"雲林縣": ["斗六市", "斗南鎮", "虎尾鎮", "西螺鎮", "土庫鎮", "北港鎮", "莿桐鄉", "林內鄉", "古坑鄉", "大埤鄉", "崙背鄉", "二崙鄉", "麥寮鄉", "臺西鄉", "東勢鄉", "褒忠鄉", "四湖鄉", "口湖鄉", "水林鄉", "元長鄉"],"嘉義縣": ["太保市", "朴子市", "布袋鎮", "大林鎮", "民雄鄉", "溪口鄉", "新港鄉", "六腳鄉", "東石鄉", "義竹鄉", "鹿草鄉", "水上鄉", "中埔鄉", "竹崎鄉", "梅山鄉", "番路鄉", "大埔鄉", "阿里山鄉"],"嘉義市": ["東區", "西區"],"臺南市": ["中西區", "東區", "南區", "北區", "安平區", "安南區", "永康區", "歸仁區", "新化區", "左鎮區", "玉井區", "楠西區", "南化區", "仁德區", "關廟區", "龍崎區", "官田區", "麻豆區", "佳里區", "西港區", "七股區", "將軍區", "學甲區", "北門區", "新營區", "後壁區", "白河區", "東山區", "六甲區", "下營區", "柳營區", "鹽水區", "善化區", "大內區", "山上區", "新市區", "安定區"],"高雄市": ["楠梓區", "左營區", "鼓山區", "三民區", "鹽埕區", "前金區", "新興區", "苓雅區", "前鎮區", "小港區", "旗津區", "鳳山區", "大寮區", "鳥松區", "林園區", "仁武區", "大樹區", "大社區", "岡山區", "路竹區", "橋頭區", "梓官區", "彌陀區", "永安區", "燕巢區", "田寮區", "阿蓮區", "茄萣區", "湖內區", "旗山區", "美濃區", "內門區", "杉林區", "甲仙區", "六龜區", "茂林區", "桃源區", "那瑪夏區"],"屏東縣": ["屏東市", "潮州鎮", "東港鎮", "恆春鎮", "萬丹鄉", "長治鄉", "麟洛鄉", "九如鄉", "里港鄉", "鹽埔鄉", "高樹鄉", "萬巒鄉", "內埔鄉", "竹田鄉", "新埤鄉", "枋寮鄉", "新園鄉", "崁頂鄉", "林邊鄉", "南州鄉", "佳冬鄉", "琉球鄉", "車城鄉", "滿州鄉", "枋山鄉", "霧台鄉", "瑪家鄉", "泰武鄉", "來義鄉", "春日鄉", "獅子鄉", "牡丹鄉", "三地門鄉"],"宜蘭縣": ["宜蘭市", "羅東鎮", "蘇澳鎮", "頭城鎮", "礁溪鄉", "壯圍鄉", "員山鄉", "冬山鄉", "五結鄉", "三星鄉", "大同鄉", "南澳鄉"],"花蓮縣": ["花蓮市", "鳳林鎮", "玉里鎮", "新城鄉", "吉安鄉", "壽豐鄉", "秀林鄉", "光復鄉", "豐濱鄉", "瑞穗鄉", "萬榮鄉", "富里鄉", "卓溪鄉"],"臺東縣": ["臺東市", "成功鎮", "關山鎮", "長濱鄉", "海端鄉", "池上鄉", "東河鄉", "鹿野鄉", "延平鄉", "卑南鄉", "金峰鄉", "大武鄉", "達仁鄉", "綠島鄉", "蘭嶼鄉", "太麻里鄉"],"澎湖縣": ["馬公市", "湖西鄉", "白沙鄉", "西嶼鄉", "望安鄉", "七美鄉"],"金門縣": ["金城鎮", "金湖鎮", "金沙鎮", "金寧鄉", "烈嶼鄉", "烏坵鄉"],"連江縣": ["南竿鄉", "北竿鄉", "莒光鄉", "東引鄉"],"海外": ["大陸", "日韓", "東南亞", "歐美", "其他"]}';
        $area_data_json = json_decode($area_data, true);
        return response()->json(['status' => 0, 'data' => $area_data_json]);
    }

    public function getHideData($user, $uid) {
        $targetUser = User::where('id', $uid)->where('accountStatus',1)->where('account_status_admin',1)->get()->first();
        /*七天前*/
        $date = date('Y-m-d H:m:s', strtotime('-7 days'));
        /*車馬費邀請次數*/
        if ($targetUser->engroup == 2) {
            $tip_count = Tip::where('to_id', $uid)->get()->count();
        } else {
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
            // uid主動第一次發信
            if ($message->from_id == $uid && array_get($send, $message->to_id) < $message->id) {
                $send[$message->to_id][]= $message->id;
            }
            // 紀錄每個帳號第一次發信給uid
            if ($message->to_id == $uid && array_get($receive, $message->from_id) < $message->id) {
                $receive[$message->from_id][] = $message->id;
            }
            if (!is_null(array_get($receive, $message->to_id))) {
                $countInfo['message_reply_count'] += 1;
                if ($message->created_at >= $date) {
                    // 計算七天內回信次數
                    $countInfo['message_reply_count_7'] += 1;
                }
            }
        }
        $countInfo['message_count'] = count($send);

        $messages_7days = Message::select('id','to_id','from_id','created_at')->whereRaw('(to_id ='. $uid. ' OR from_id='.$uid .')')->where('created_at','>=', $date)->orderBy('id')->get();
        $countInfo['message_count_7'] = 0;
        $send = [];
        foreach ($messages_7days as $message) {
            // 七天內uid主動第一次發信
            if ($message->from_id == $uid && array_get($send, $message->to_id) < $message->id) {
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

            foreach ($messages as $row) {
                array_push($msg,array('id'=>$row->id,'content'=>$row->content,'created_at'=>$row->created_at));
            }

            array_push($from_content,  array('msg'=>$msg));

            $unique_id = array(); // 過濾重複ID用
            // 比對訊息
            foreach ($from_content as $data) {
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
        else {
            $login_times_per_week = round(($targetUser->login_times / $week), 0);
        }

        $last_login = $targetUser->last_login;

        $is_banned = null;

        $userHideOnlinePayStatus = ValueAddedService::status($uid,'hideOnline');
        if ($userHideOnlinePayStatus == 1) {
            $hideOnlineData = hideOnlineData::where('user_id',$uid)->where('deleted_at',null)->get()->first();
            if (isset($hideOnlineData)) {
                $login_times_per_week = $hideOnlineData->login_times_per_week;
                $tip_count = $hideOnlineData->tip_count;
                $message_count = $hideOnlineData->message_count;
                $message_count_7 = $hideOnlineData->message_count_7;
                $message_reply_count = $hideOnlineData->message_reply_count;
                $message_reply_count_7 = $hideOnlineData->message_reply_count_7;
                $message_percent_7 = $hideOnlineData->message_percent_7;
                $visit_other_count = $hideOnlineData->visit_other_count;
                $visit_other_count_7 = $hideOnlineData->visit_other_count_7;
                $be_visit_other_count = $hideOnlineData->be_visit_other_count;
                $be_visit_other_count_7 = $hideOnlineData->be_visit_other_count_7;
                $last_login = $hideOnlineData->updated_at;
            }
        }

        $data = array(
            'login_times_per_week' => $login_times_per_week,
            'tip_count' => $tip_count,
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
        );
        return $data;
    }

    public function getBlockUser($uid) {
        $target_user = User::find($uid);
        if($target_user->valueAddedServiceStatus('hideOnline')) {
            $data = hideOnlineData::select('user_id', 'blocked_other_count', 'be_blocked_other_count')->where('user_id', $uid)->first();
            /*此會員封鎖多少其他會員*/
            $blocked_other_count = $data->blocked_other_count;
            /*此會員被多少會員封鎖*/
            $be_blocked_other_count = $data->be_blocked_other_count;
        }
        else {
            $bannedUsers = \App\Services\UserService::getBannedId();
            /*此會員封鎖多少其他會員*/
            $blocked_other_count = Blocked::with(['blocked_user'])
                ->join('users', 'users.id', '=', 'blocked.blocked_id')
                ->leftJoin('user_meta as um', 'um.user_id', '=', 'blocked.blocked_id')
                ->leftJoin('warned_users as w2', 'w2.member_id', '=', 'blocked.blocked_id')
                ->where('um.isWarned',0)
                ->whereNull('w2.id')
                ->where('blocked.member_id', $uid)
                ->whereNotIn('blocked.blocked_id',$bannedUsers)
                ->whereNotNull('users.id')
                ->where('users.accountStatus', 1)
                ->where('users.account_status_admin', 1)
                ->count();
        
            /*此會員被多少會員封鎖*/
            $be_blocked_other_count = Blocked::with(['blocked_user'])
                    ->join('users', 'users.id', '=', 'blocked.member_id')
                    ->leftJoin('user_meta as um', 'um.user_id', '=', 'blocked.member_id')
                    ->leftJoin('warned_users as w2', 'w2.member_id', '=', 'blocked.member_id')
                    ->where('um.isWarned',0)
                    ->whereNull('w2.id')
                    ->where('blocked.blocked_id', $uid)
                    ->whereNotIn('blocked.member_id',$bannedUsers)
                    ->whereNotNull('users.id')
                    ->where('users.accountStatus', 1)
                    ->where('users.account_status_admin', 1)
                    ->count();
        }
        $output = array(
                'blocked_other_count' => $blocked_other_count,
                'be_blocked_other_count' => $be_blocked_other_count
        );
        return $output;
    }

    public function getFavCount($uid) {
        $target_user = User::find($uid);
        if ($target_user->valueAddedServiceStatus('hideOnline')) {
            $data = hideOnlineData::select('user_id', 'fav_count', 'be_fav_count')->where('user_id', $uid)->first();
            /*收藏會員次數*/
            $fav_count = $data->fav_count;
            /*被收藏次數*/
            $be_fav_count = $data->be_fav_count;
        }
        else {
            $bannedUsers = \App\Services\UserService::getBannedId();
            /*收藏會員次數*/
            $fav_count = MemberFav::select('member_fav.*')
                ->join('users', 'users.id', '=', 'member_fav.member_fav_id')
                ->leftJoin('user_meta as um', 'um.user_id', '=', 'member_fav.member_fav_id')
                ->leftJoin('warned_users as w2', 'w2.member_id', '=', 'member_fav.member_fav_id')
                ->where('um.isWarned',0)
                ->whereNull('w2.id')
                ->whereNotNull('users.id')
                ->where('users.accountStatus', 1)
                ->where('users.account_status_admin', 1)
                ->where('member_fav.member_id', $uid)
                ->whereNotIn('member_fav.member_fav_id', $bannedUsers)
                ->get()->count();
        
            /*被收藏次數*/
            $be_fav_count = MemberFav::select('member_fav.*')
                    ->join('users', 'users.id', '=', 'member_fav.member_id')
                    ->leftJoin('user_meta as um', 'um.user_id', '=', 'member_fav.member_id')
                    ->leftJoin('warned_users as w2', 'w2.member_id', '=', 'member_fav.member_id')
                    ->where('um.isWarned',0)
                    ->whereNull('w2.id')
                    ->whereNotNull('users.id')
                    ->where('users.accountStatus', 1)
                    ->where('users.account_status_admin', 1)
                    ->where('member_fav.member_fav_id', $uid)
                    ->whereNotIn('member_fav.member_id', $bannedUsers)
                    ->get()->count();
        }
        $output = array(
            'fav_count' => $fav_count,
            'be_fav_count' => $be_fav_count
        );
        return $output; 
    }

    public function viewuser(Request $request, $uid = -1)
    {
        $user = $request->user();

        if (isset($user) && isset($uid)) {
            $targetUser = User::where('id', $uid)->where('accountStatus', 1)->get()->first();
            if (!isset($targetUser)) {
                return response()->json(['status' => 1, 'data' => '沒有此用戶資料']);
            }
            // if(User::isBanned($uid)) {
            //     Session::flash('closed', true);
            //     Session::flash('message', '此用戶已關閉資料');
            //     return response()->json(['status' => 1, 'data' => '此用戶已被封鎖']);
            // }
            if ($user->id != $uid) {
                Visited::visit($user->id, $targetUser);
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
                // 取得 cur 會員照片
                $cur = $this->service->find($uid);
                $pics_arr = array();
                if (isset($cur)) {
                    $pics = MemberPic::getSelf($cur->id);
                    foreach ($pics as $pic) {
                        if ($pic->isHidden != 1) {
                            array_push($pics_arr, $pic);
                        }
                    }
                }

                // 轉換 city, area 成矩陣
                if (isset($cur)) {
                    // 合併至 $cur->user_meta
                    if (isset($cur->user_meta->city) || isset($cur->user_meta->area)) {
                        $cur->user_meta->city = explode(",", $cur->user_meta->city);
                        $cur->user_meta->area = explode(",", $cur->user_meta->area);
                    }
                }

                // 判斷是否為新進甜心用
                $cur->checkRecommendedUser = $checkRecommendedUser;

                // 取得 cur 進階資料
                $hideData = $this->getHideData($user, $cur->id);
                $blockUserData = $this->getBlockUser($cur->id);
                $favCountData = $this->getFavCount($cur->id);
                $cur->favedCount = $favCountData["be_fav_count"]; // 被收藏次數
                $cur->favCount = $favCountData["fav_count"]; // 收藏會員次數
                $cur->tipCount = $hideData["tip_count"]; // 車馬費邀請次數
                $cur->isBlocked = $hideData["is_block_mid"]; // 是否封鎖我
                $cur->isSeen = $hideData["is_visit_mid"]; // 是否看過我
                $cur->visitCount = $hideData["visit_other_count"]; // 瀏覽其他會員次數
                $cur->visitedCount = $hideData["be_visit_other_count"]; // 被瀏覽次數
                $cur->visitedsevenCount = $hideData["be_visit_other_count_7"]; // 過去7天被瀏覽次數
                $cur->isVIP = $cur->isVip(); // 是否為 VIP
                $cur->isAdminWarned = $cur->isAdminWarned(); // 是否為警示帳戶
                $cur->isPhoneAuth = $cur->isPhoneAuth(); // 是否手機認證
                $cur->isAdvanceAuth = $cur->isAdvanceAuth(); // 是否進階認證
                $cur->isBlockedOther = Blocked::isBlocked($user->id, $cur->id); // 是否封鎖對方
                $cur->isFav = MemberFav::where('member_id', $user->id)->where('member_fav_id', $cur->id)->count(); // 是否收藏
                $cur->isOnline = $cur->isOnline(); // 是否上線
                $cur->last_login = $hideData["last_login"];
                $cur->search_ignore = $user->search_ignore()->where('ignore_id', $cur->id)->count(); // 略過狀態
                $cur->isBlurAvatar = \App\Services\UserService::isBlurAvatar($cur, $user); // 是否模糊大頭照
                $cur->isBlurLifePhoto = \App\Services\UserService::isBlurLifePhoto($cur, $user); // 是否模糊生活照

                Log::debug('$cur->id: '.$cur->id);
                Log::debug('$cur->implicitlyBanned: '.$cur->implicitlyBanned);
                Log::debug('$cur->banned: '.$cur->banned);
                if(($cur->banned || $cur->implicitlyBanned) && $cur->id != 1049) {
                    $cur->isBanned = 1;
                } else {
                    $cur->isBanned = 0;
                }  

                /* 過去7天瀏覽其他會員次數 */
                $cur->visit_other_count_7 = $hideData["visit_other_count_7"];
                /* 每周平均上線次數 */
                $cur->login_times_per_week = $hideData["login_times_per_week"];
                /* 此會員封鎖多少其他會員 */
                $cur->blocked_other_count = $blockUserData["blocked_other_count"];
                /* 此會員被多少會員封鎖 */
                $cur->be_blocked_other_count = $blockUserData["be_blocked_other_count"];
                /* 發信次數 */
                $cur->message_count = $hideData['message_count'];
                /* 過去7天發信次數 */
                $cur->message_count_7 = $hideData['message_count_7'];
                /* 回信次數 */
                $cur->message_reply_count = $hideData['message_reply_count'];
                /* 過去7天回信次數 */
                $cur->message_reply_count_7 = $hideData['message_reply_count_7'];
                /* 過去7天罐頭訊息比例 */
                $cur->message_percent_7 = $hideData['message_percent_7'];

                // 平均評價資料
                /**
                 * 效能調整：使用左結合以大幅降低處理時間，並且減少 query 次數，進一步降低時間及程式碼複雜度
                 */
                $query = \App\Models\Evaluation::select('*')->from('evaluation')->with('user')
                    ->leftJoin('banned_users as b1', 'b1.member_id', '=', 'evaluation.from_id')
                    ->leftJoin('banned_users_implicitly as b3', 'b3.target', '=', 'evaluation.from_id')
                    ->leftJoin('blocked as b7', function($join) use($uid) {
                        $join->on('b7.member_id', '=', 'evaluation.from_id')
                            ->where('b7.blocked_id', $uid); })
                    // ->leftJoin('user_meta as um', function($join) {
                    //     $join->on('um.user_id', '=', 'e.from_id')
                    //         ->where('isWarned', 1); })
                    ->leftJoin('warned_users as wu', function($join) {
                        $join->on('wu.member_id', '=', 'evaluation.from_id')
                            ->where(function($query){
                                $query->where('wu.expire_date', '>=', Carbon::now())
                                    ->orWhere('wu.expire_date', null); }); })
                    ->leftJoin('is_warned_log as iw', 'iw.user_id', '=', 'evaluation.from_id')
                    ->whereNull('b1.member_id')
                    ->whereNull('b3.target')
                    ->whereNull('b7.member_id')
                    // ->whereNull('um.user_id')
                    ->whereNull('wu.member_id')
                    ->whereNull('iw.user_id')
                    ->where('evaluation.to_id', $uid);
                $rating_avg = $query->avg('rating');
                $cur->rating_avg = floatval($rating_avg);
                if ($cur->tattoo->count()) {
                    $cur->tattoo = $cur->tattoo->first();
                }
                
                // 包養關係
                $exchange_period_name = DB::table('exchange_period_name')->where('id', $cur->exchange_period)->first();
                if (isset($exchange_period_name)) {
                    $cur['exchangePeriodName'] = $exchange_period_name->name;
                }

                // PR 大方指數
                $pr = DB::table('pr_log')->where('user_id', $cur->id)->where('active', 1)->first();
                if (isset($pr)) {
                    $cur->pr = $pr->pr;
                } else {
                    $cur->pr = '0';
                }
                
                // 取得 user 詳細資料
                if (isset($user)) {
                    // 合併至 $user->user_meta
                    // 轉換 city, area 成矩陣
                    if (isset($user->user_meta->city) || isset($user->user_meta->area)) {
                        $user->user_meta->city = explode(",", $user->user_meta->city);
                        $user->user_meta->area = explode(",", $user->user_meta->area);
                    }
                    $user->user_meta->isPhoneAuth = $user->isPhoneAuth(); // 是否手機認證
                    $user->user_meta->isAdvanceAuth = $user->isAdvanceAuth(); // 是否進階認證
                    $user->user_meta->isVIP = $user->isVip();
                    $vipDays = 0;
                    if ($user->isVip()) {
                        $vip_record = Carbon::parse($user->vip_record);
                        $vipDays = $vip_record->diffInDays(Carbon::now());
                    }
                    $user->user_meta->vipDays = $vipDays;
                    $user->user_meta->isAdminWarned = $user->isAdminWarned(); // 是否為警示帳戶
                    $user->user_meta->isSent3Msg = $user->isSent3Msg($cur->id); // 是否發三次訊息
                    $evaluation_self = DB::table('evaluation')->where('to_id', $uid)->where('from_id', $user->id)->first();
                    $user->user_meta->isEvaluation = isset($evaluation_self);
                    $user->user_meta->isBanned = User::isBanned($user->id); // 是否被封鎖
                }

                return response()->json(['status' => 0, 'cur_pics' => $pics_arr, 'user' => $user, 'cur' => $cur, 'checkRecommendedUser' => $checkRecommendedUser]);
            }
        }
    }

    public function chatviewMore(Request $request, $type = 1)
    {
        $user = $request->user();

        $isVip = $user->isVip();

        $data = null;
        if ($type == 1) {
            $data = Message_new::allSendersAJAX($user->id, $isVip, 7);
        } elseif ($type == 2) {
            $data = Message_new::allSendersAJAX($user->id, $isVip, 30);
        } else {
            $data = Message_new::allSendersAJAX($user->id, $isVip, 'all');
        }

        if (!is_array($data)) {
            $data = array_values(['No data']);
        }
        $totalMsgCount = sizeof($data);
        if ($totalMsgCount == 1 && $data[0] == 'No data') {

        } else {
            // 先依據時間排序訊息
            usort($data, function($a, $b)
            {
                return strcmp($a["created_at"], $b["created_at"]);
            });

            $count = 0;
            foreach ($data as $key => $value) {
                if ($isVip) {
                    $data[$key]['isMsgVisiable'] = true;
                } else {
                    if ($value['user_name'] == '站長') {
                        $data[$key]['isMsgVisiable'] = true;
                    } else {
                        $count += 1;
                        if ($count <= 10) {
                            $data[$key]['isMsgVisiable'] = true;
                        } else {
                            $data[$key]['isMsgVisiable'] = false;
                        }
                    }
                }

                if (isset($value['to_id'])) {
                    $data[$key]['isOnline'] = $user->id_($value['to_id'])->isOnline(); // 是否上線
                    $data[$key]['engroup'] = $user->id_($value['to_id'])->engroup;
                    $data[$key]['engroup_change'] = $user->id_($value['to_id'])->engroup_change;
                }
            }
        }

        // $date = null;
        // if ($type == 1) {
        //     $date = \Carbon\Carbon::parse("7 days ago");
        // } elseif ($type == 2) {
        //     $date = \Carbon\Carbon::parse("30 days ago");
        // }
        // if (isset($date)) {
        //     foreach ($data as $key => $value) {
        //         $created_at = \Carbon\Carbon::parse($data[$key]["created_at"]);
        //         if ($created_at->gte($date)) {

        //         } else {
        //             unset($data[$key]);
        //         }
        //     }
        // }
        
        if (isset($data)) {
            $user['aw_relation'] = $user->aw_relation;
            return response()->json(array(
                'status' => 1,
                'user' => $user,
                'msg' => $data,
                'noVipCount' => Config::get('social.limit.show-chat')
            ), 200);
        }
        return response()->json(array(
            'status' => 2,
            'msg' => 'fail'
        ), 500);
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
            $messages = Message::allToFromSender($user->id, $cid, true);

            foreach ($messages as $msg) {
                // 新增已讀功能
                if ($msg->from_id == $cid && $msg->read == 'N') {
                    $msg->read = 'Y';
                    $msg->save();
                }

                $msg['images'] = $msg['pic'];
                $msg['pic'] = $to_user_pic;
                $msg['engroup'] = $to_user->engroup;

                $parentMsg = null;
                if($msg->parent_msg??null) {
                    $parentMsg = Message::find($msg->parent_msg);
                }
                if(!($parentMsg??null) && $msg->parent_client_id??null) {
                    $parentMsg = Message::where('client_id', $msg->parent_client_id)->first();
                }
                $msg['parentMsg'] = $parentMsg;
            }

            if (isset($cid)) {
                $to = $this->service->find($cid);
                $isBlurAvatar = \App\Services\UserService::isBlurAvatar($to, $user);
                if (!$user->isVip() && $user->engroup == 1) {
                    $m_time = Message::select('created_at')->
                    where('from_id', $user->id)->
                    orderBy('created_at', 'desc')->first();
                    if (isset($m_time)) {
                        $m_time = $m_time->created_at;
                    }
                }
                return response()->json(['user' => $user, 'isBlurAvatar' => $isBlurAvatar, 'to' => $to, 'm_time' => $m_time, 'isVip' => $isVip, 'messages' => $messages]);
            } 
            return response()->json(['user' => $user, 'm_time' => $m_time, 'isVip' => $isVip, 'messages' => $messages]);
        }
    }

    public function message_pic_save($msg_id, $images)
    {
        if($files = $images)
        {
            $images_ary = array();
            foreach ($files as $key => $file) {
                $now = Carbon::now()->format('Ymd');
                $input['imagename'] = $now . rand(100000000,999999999) . '.' . $file->getClientOriginalExtension();

                $rootPath = public_path('/img/Message');
                $tempPath = $rootPath . '/' . substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/';

                if(!is_dir($tempPath)) {
                    File::makeDirectory($tempPath, 0777, true);
                }

                $destinationPath = '/img/Message/'. substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/' . $input['imagename'];

                $img = Image::make($file->getRealPath());
                $img->resize(400, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($tempPath . $input['imagename']);

                // 整理images
                // $images_ary[$key]= $destinationPath;
                $images_ary[$key]['origin_name']= $file->getClientOriginalName();
                $images_ary[$key]['file_path']= $destinationPath;

            }
            return Message::updateOrCreate(['id'=> $msg_id], ['pic'=>json_encode($images_ary)]);
        }
    }

    public function postChat(Request $request)
    {
        $banned = banned_users::where('member_id', $request->user()->id)
            ->whereNotNull('expire_date')
            ->orderBy('expire_date', 'asc')->get()->first();
        if (isset($banned)) {
            $date = \Carbon\Carbon::parse($banned->expire_date);
            return response()->json(['status' => 1, 'banned' => $banned, 'days' => $date->diffInDays() + 1]);
        }
        $payload = $request->all();

        $to_user = User::findById($payload['to']);
        $forbid_msg_data = UserService::checkNewSugarForbidMsg($to_user, $request->user());
        if($forbid_msg_data) {
            $new_sugar_error_msg = '新進甜心只接收 vip 信件，'.$forbid_msg_data['user_type_str'].'會員要於 '.$forbid_msg_data['end_date'].' 後方可發信給這位女會員'; 
            return response()->json(['status' => 1, 'msg' => $new_sugar_error_msg]);       
        }

        if (!is_null($request->file('images')) && count($request->file('images')) > 0) {
            // 上傳訊息照片
            $messageInfo = Message::create([
                'from_id' => $request->user()->id,
                'to_id' => $payload['to'],
                'parent_msg'=>($payload['parent']??null)
            ]);
            $this->message_pic_save($messageInfo->id, $request->file('images'));
            return response()->json(['status' => 0, 'to' => $payload['to'], 'images' => 'ok']);
        }

        if (!isset($payload['msg'])) {
            return response()->json(['status' => 1, 'msg' => '請勿僅輸入空白！']);
        }
        if (!$request->user()->isVIP() || $request->user()->engroup == 2) {
            $m_time = Message::select('created_at')->
                where('from_id', $request->user()->id)->
                orderBy('created_at', 'desc')->first();
            if (isset($m_time)) {
                $diffInSecs = abs(strtotime(date("Y-m-d H:i:s")) - strtotime($m_time->created_at));
                // 調整系統發訊頻率 30 -> 8
                if ($diffInSecs < 8) {
                    return response()->json(['status' => 1, 'msg' => '您好，由於系統偵測到您的發訊頻率太高(每 8 秒限一則訊息)。為維護系統運作效率，請降低發訊頻率。']);
                }
            }
        }
        // Message::post($request->user()->id, $payload['to'], $payload['msg']);
        $postArr = $payload;
        $postArr['from_id'] = $request->user()->id;
        Message::postByArr($postArr);
        return response()->json(['status' => 0, 'to' => $payload['to'], 'msg' => $payload['msg']]);
    }

    public function deleteSingle(Request $request) {
        $uid = $request->user()->id;
        $sid = $request->sid; // 對應 to_id
        $ct_time = $request->ct_time; // 對應 created_at
        $content = $request->content; // 對應 content
        Message::deleteSingle($uid, $sid, $ct_time, $content);
        return response()->json(['save' => 'ok']);
    }

    public function postBlockAJAX(Request $request)
    {
        $bid = $request->sid;
        $aid = $request->uid;

        if ($aid !== $bid) {
            $isBlocked = Blocked::isBlocked($aid, $bid);
            if (!$isBlocked) {
                Blocked::block($aid, $bid);
                // 有收藏名單則刪除
                $isFav = MemberFav::where('member_id', $aid)->where('member_fav_id', $bid)->count();
                if ($isFav>0) {
                    MemberFav::remove($aid, $bid);
                }
                return response()->json(['save' => 'ok']);
            }
        }
        return response()->json(['save' => 'error']);
    }

    public function deleteBetweenGET($uid, $sid) 
    {
        Message::deleteBetween($uid, $sid);
        return response()->json(['save' => 'ok']);
    }

    public function deleteBetweenGetAll($uid, $sid) 
    {
        $ids = explode(',', $uid);
        foreach ($ids as $id) {
            Message::deleteBetween($sid,$id);
        }
        return response()->json(['save' => 'ok']);
    }

    public function unblockAJAX(Request $request)
    {
        $bid = $request->to;
        $aid = $request->uid;

        if ($aid !== $bid) {
            Blocked::unblock($aid, $bid);
        }
        return response()->json(['save' => 'ok']);
    }

    public function postfavAJAX(Request $request)
    {
        $uid = $request->to;
        $aid = $request->uid;
        if ($aid !== $uid) {
            $isFav = MemberFav::where('member_id', $aid)->where('member_fav_id', $uid)->count();
            $isBlocked = Blocked::isBlocked($aid, $uid);
            if ($isFav == 0 && !$isBlocked) {
                MemberFav::fav($aid, $uid);
                return response()->json(['save' => 'ok']);
            } else if ($isBlocked) {
                return response()->json(['isBlocked' => 'true']);
            } else if ($isFav>0) {
                return response()->json(['isFav' => 'true']);
            }
        }
        return response()->json(['save' => 'error']);
    }

    public function removefavAJAX(Request $request)
    {
        if ($request->userId !== $request->favUserId) {
            MemberFav::remove($request->userId, $request->favUserId);
            return response()->json(['save' => 'ok']);
        }
        return response()->json(['save' => 'error']);
    }

    private function customTrim($str) 
    {
        $search = array(" ","　","\n","\r","\t");
        $replace = array("","","","","");
        return str_replace($search, $replace, $str);
    }

    public function reportPost(Request $request)
    {
        if (User::isBanned($request->aid)) {
            // 您目前被站方封鎖，無檢舉權限
            return response()->json(['save' => 'banned']);
        }
        if (empty($this->customTrim($request->content))) {
            return response()->json(['save' => 'error']);
        }
        Reported::report($request->aid, $request->uid, $request->content, $request->file('images'));
        return response()->json(['save' => 'ok']);
    }

    public function chatSet(Request $request) 
    {
        $user = UserMeta::where('user_id', $request->uid)->first();
        if ($user) {
            $user->update([
                'notifmessage' => $request->notifyMessage,
                'notifhistory' => $request->notifyHistory
            ]);
            return response()->json(['save' => 'ok']);
        }
    }

    public function evaluation_pic_save($evaluation_id, $uid, $images)
    {
        if($files = $images) // $request->file('images')
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

                // 新增images到db
                $evaluationPic = new EvaluationPic();
                $evaluationPic->evaluation_id = $evaluation_id; // $request->input('evaluation_id'); //評價id
                $evaluationPic->member_id = $uid; // $request->input('uid');
                $evaluationPic->pic = $destinationPath;
                $evaluationPic->save();
            }
        }
    }

    public function evaluationSave(Request $request)
    {
        // $evaluation_self = DB::table('evaluation')->where('to_id',$request->input('eid'))->where('from_id',$request->input('uid'))->first();

        // if (isset($evaluation_self)) {
        //     DB::table('evaluation')->where('to_id',$request->input('eid'))->where('from_id',$request->input('uid'))->update(
        //         ['content' => $request->input('content'), 'rating' => $request->input('rating'), 'updated_at' => now()]
        //     );
        // } else {
        //     $evaluation = Evaluation::create([
        //         'from_id' => $request->input('uid'), 'to_id' => $request->input('eid'), 'content' => $request->input('content'), 'rating' => $request->input('rating'), 'read' => 1, 'created_at' => now(), 'updated_at' => now()
        //     ]);
        //     // 儲存評論照片
        //     $this->evaluation_pic_save($evaluation->id, $request->input('uid'), $request->file('images'));
        // }

        $evaluation = Evaluation::create([
            'from_id' => $request->input('uid'), 'to_id' => $request->input('eid'), 'content' => $request->input('content'), 'rating' => $request->input('rating'), 'read' => 1, 'created_at' => now(), 'updated_at' => now()
        ]);
        // 儲存評論照片
        $this->evaluation_pic_save($evaluation->id, $request->input('uid'), $request->file('images'));
        
        return response()->json(['save' => 'ok']);
    }

    public function evaluation(Request $request, $uid)
    {
        $user = $request->user();
        if (isset($user) && isset($uid)) {
            $userBlockList = \App\Models\Blocked::select('blocked_id')->where('member_id', $uid)->get();
            
            /*
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
            */

            $hadWarned = DB::table('is_warned_log')->select('user_id')->distinct()->get();
            $isAdminWarnedList = warned_users::select('member_id')->where('expire_date', '>=', Carbon::now())->orWhere('expire_date',null)->groupBy('member_id')->get();

            /**
             * 效能調整：使用左結合以大幅降低處理時間，並且減少 query 次數，進一步降低時間及程式碼複雜度
             */
            $query = \App\Models\Evaluation::select('evaluation.*')->from('evaluation as evaluation')->with('user')
                ->leftJoin('banned_users as b1', 'b1.member_id', '=', 'evaluation.from_id')
                ->leftJoin('banned_users_implicitly as b3', 'b3.target', '=', 'evaluation.from_id')
                ->leftJoin('users as u1', 'u1.id', '=', 'evaluation.from_id')
                ->leftJoin('user_meta as um', 'um.user_id', '=', 'evaluation.from_id')
                ->leftJoin('warned_users as w2', 'w2.member_id', '=', 'evaluation.from_id')
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
                ->where('um.isWarned',0)
                ->whereNull('w2.id')
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
            $evaluation_data = $query->get();

            $evaluation_self = Evaluation::where('to_id',$uid)->where('from_id',$user->id)->first();

            foreach ($evaluation_data as $row) {
                $from_user = \App\Models\User::findById($row->from_id);
                $to_user = \App\Models\User::findById($row->to_id);
                $row->from_user_name = $from_user->name;
                $row->to_user_name = $to_user->name;
                $row->evaluation_pics = EvaluationPic::where('evaluation_id', $row->id)->where('member_id', $row->from_id)->get();
            }

            return response()->json(['status' => 0, 'isAdminWarnedList' => $isAdminWarnedList , 'hadWarned' => $hadWarned, 'userBlockList' => $userBlockList, 'evaluation_self' => $evaluation_self, 'evaluation_data' => $evaluation_data]);
        }
    }

    public function evaluationDelete(Request $request)
    {
        DB::table('evaluation')->where('id',$request->id)->delete();
        return response()->json(['save' => 'ok']);
    }

    public function reportPic(Request $request) 
    {
        if (User::isBanned($request->aid)) {
            // 您目前被站方封鎖，無檢舉權限
            return response()->json(['save' => 'banned']);
        }
        if (empty($this->customTrim($request->content))) {
            return response()->json(['save' => 'error']);
        }
        if ($request->picType == 'avatar') {
            ReportedAvatar::report($request->aid, $request->uid, $request->content, $request->file('images'));
        }
        if ($request->picType == 'pic') {
            ReportedPic::report($request->aid, $request->pic_id, $request->content);
        }
        return response()->json(['save' => 'ok']);
    }

    public function reportMsg(Request $request)
    {
        if (User::isBanned($request->aid)) {
            // 您目前被站方封鎖，無檢舉權限
            return response()->json(['save' => 'banned']);
        }
        if (empty($this->customTrim($request->content))) {
            return response()->json(['save' => 'error']);
        }
        Message::reportMessage($request->id, $request->content, $request->file('images'));
        return response()->json(['save' => 'ok']);
    }

    public function addSearchIgnore(Request $request, SearchIgnoreService $service) {
        if(!$request->target??null) return;
        $ignore_data['ignore_id'] = $request->target;
        return $service->create($ignore_data)?1:0;
    }
    
    public function delSearchIgnore(Request $request, SearchIgnoreService $service) {
        if(!$request->target??null) return $service->delMemberAll()?1:0;
        return $service->delByIgnoreId($request->target)?1:0;
    }

    public function unsendChat(Request $request) {       
        $payload = $request->all();
        $unsend_id = $payload['unsend_msg']??null;
        $unsend_client_id = $payload['unsend_msg_client']??null;
        $user = $request->user();

        if($user->isVIP() && !isset($user->banned) && !isset($user->implicitlyBanned)) {
            if($unsend_id) {
                $msg = Message::find($unsend_id);
            }
            else if($unsend_client_id) {
                $msg = Message::where('client_id', $unsend_client_id)->first();
            }
            if($msg) {
                $msg->unsend = 1;
                $msg->save();
                $msg->delete();
                return response()->json(['error' => 0, 'content' => $msg]);
            }
            else {
                return response()->json(['error' => 1, 'content' => '收回訊息失敗']);
            }
        }
        else {
            return response()->json(['error' => 1, 'content' => '非VIP無法收回訊息']);
        }
    }

    public function personal(Request $request) {
        $admin = AdminService::checkAdmin();
        // $user = \View::shared('user');
        $user = $request->user();

        $vipStatus = '您目前還不是VIP，<a class="red" href="../dashboard/new_vip">立即成為VIP!</a>';
        $picTypeNameStrArr = ['avatar' => '大頭照', 'member_pic'=> '生活照']; 
        $user->load('vip');
        $existHeaderImage = $user->existHeaderImage(); 
        $latest_pic_act_log = $vipStatusMsgType
        = $vipStatusPicTime = $vipStatusPicStr
        = $firstRemindingLog = $lastPicRecoverLog
        = null;
        if ($user->engroup == 2 || $user->isFreeVip()) {
            $latest_pic_act_log = $user->log_free_vip_pic_acts()->orderBy('created_at', 'DESC')->first();              
            if($latest_pic_act_log && in_array($latest_pic_act_log->sys_react??null, LogFreeVipPicAct::$needFirstRemindSysReacts)) {
                $lastPicRecoverLog = $user->log_free_vip_pic_acts()->where([['id', '<>',$latest_pic_act_log->id],['created_at', '<', $latest_pic_act_log->created_at]])->whereIn('sys_react', LogFreeVipPicAct::$reachRuleSysReacts)->orderBy('created_at', 'DESC')->first();
                $firstRemindingLogQuery = $user->log_free_vip_pic_acts()->where([['created_at', '<=', $latest_pic_act_log->created_at]])->where('sys_react', 'reminding')->orderBy('created_at');
                if($lastPicRecoverLog) $firstRemindingLogQuery->where('created_at', '>', ($lastPicRecoverLog->created_at??'0000-00-00 00:00:00'));
                $firstRemindingLog =  $firstRemindingLogQuery->first();   
            }
            if ($latest_pic_act_log && in_array($latest_pic_act_log->sys_react??null, LogFreeVipPicAct::$replaceByFirstRemindSysReacts)) {
                if($firstRemindingLog) $latest_pic_act_log = $firstRemindingLog;
            } 

            if ($latest_pic_act_log ) {
                $vipStatusMsgType = $latest_pic_act_log->sys_react??null;
                $vipStatusPicTime = ($latest_pic_act_log->created_at??null) ? Carbon::parse($latest_pic_act_log->created_at) : null; 
                $vipStatusPicStr =  $picTypeNameStrArr[$latest_pic_act_log->pic_type??'']??'';            
            }
        }

        if ($user->isVip()) {
            $vipStatus = '您已是 VIP';
            $vip_record = Carbon::parse($user->vip_record);
            $vipDays = $vip_record->diffInDays(Carbon::now());
            if (!$user->isFreeVip()) {               
                $vip = $user->vip->first();               
                if ($vip->payment) {
                    switch ($vip->payment_method) {
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
                    if (EnvironmentService::isLocalOrTestMachine()) {
                        $envStr = '_test';
                    }
                    else {
                        $envStr = '';
                    }
                    if (substr($vip->payment, 0, 3) == 'cc_' && $vip->business_id == Config::get('ecpay.payment'.$envStr.'.MerchantID')) {

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

                        // 計算下次扣款日
                        if ($vip->payment == 'cc_quarterly_payment') {
                            $periodRemained = 92;
                        } else {
                            $periodRemained = 30;
                        }
                        $nextProcessDate = substr($lastProcessDate->addDays($periodRemained),0,10);
                    }
                    $last_vip_log = null;

                    switch ($vip->payment) {
                        case 'cc_monthly_payment':
                            if (!$vip->isPaidCanceled() && ($nextProcessDate??null)) {
                                $vipStatus = '您目前是每月持續付費的VIP，下次付費時間是'.$nextProcessDate.'。';
                            } else if ($vip->isPaidCanceled()) {
                                $cancel_str = '';
                                $latest_vip_log = $user->getLatestVipLog();
                                if ($latest_vip_log->isCancel()) {
                                    $cancel_str = '已於 '.substr($latest_vip_log->created_at,0,10).' 申請取消。';
                                }
                                
                                $vipStatus = '您目前是每月持續付費的VIP，'.$cancel_str.'VIP到期時間為 '. substr($vip->expiry,0,10).'。';
                            }
                            break;
                        case 'cc_quarterly_payment':
                            if (!$vip->isPaidCanceled() && ($nextProcessDate??null)) {
                                $vipStatus = '您目前是每月持續付費的VIP，下次付費時間是'.$nextProcessDate.'。';
                            } else if ($vip->isPaidCanceled()) {
                                $cancel_str = '';
                                $latest_vip_log = $user->getLatestVipLog();
                                if ($latest_vip_log->isCancel()) {
                                    $cancel_str = '已於 '.substr($latest_vip_log->created_at,0,10).' 申請取消，';
                                }
                                
                                $vipStatus = '您目前是每季持續付費的VIP，'.$cancel_str.'VIP到期日為 '. substr($vip->expiry,0,10).'。';
                            }
                            break;
                        case 'one_month_payment':
                            $vipStatus = '您目前是單次付費的VIP，VIP到期時間為'. substr($vip->expiry, 0, 10);
                            break;
                        case 'one_quarter_payment':
                            $vipStatus = '您目前是單次付費的VIP，VIP到期時間為'. substr($vip->expiry, 0, 10);
                            break;
                    }
                }
            } else {
                $vipStatus = '您目前為免費VIP';

                 if ($vipStatusMsgType) {
                     switch ($vipStatusMsgType) {
                         case 'reminding':
                            if (!$existHeaderImage) {
                                $vipStatus = '您於 '.$vipStatusPicTime->format('Y/m/d H:i').' 分刪除'.$vipStatusPicStr.'。請於 '.$vipStatusPicTime->addSeconds(1800)->format('Y/m/d H:i').' 前補足大頭照+生活照三張。否則您的 vip 權限會被取消。';
                            }
                         break;
                         case 'remain':
                            if ($existHeaderImage && $vipStatusPicTime->diffInSeconds(Carbon::now()) <= 86400) {
                                $vipStatus = '您於  '.$vipStatusPicTime->format('Y/m/d H:i').' 上傳大頭照+生活照三張，已成為本站vip！';
                            }
                         break;                     
                     }
                    
            
                 }   
            }
        }
        else if ($user->engroup == 2) // 不是VIP的女性會員 
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
            $vipStatus = '您目前不是VIP。女會員只要上傳頭像照+三張生活照即可取得免費的vip，強烈建議您上傳照片升級，<a class="red" href="/dashboard_img">點此上傳照片升級!</a>';

            if ($vipStatusMsgType) {
                 switch ($vipStatusMsgType) {
                     case 'reminding':
                        if (!$existHeaderImage) {
                            if ($vipStatusPicTime) {
                                $vip_remain_deadline = Carbon::parse($vipStatusPicTime)->addSeconds(1800)->format('Y/m/d H:i');
                                if ($vip_remain_deadline < Carbon::now()->format('Y/m/d H:i')) {
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

                        if ($firstRemindingLog) {
                            $delPicStr = $picTypeNameStrArr[$firstRemindingLog->pic_type];
                            
                            $delPicLogTime = Carbon::parse($firstRemindingLog->created_at);
                        }

                        if ($expect_recover_date >= Carbon::now()->format('Y/m/d H:i')) {
                            $vipStatus = '您'.($delPicLogTime ? '於 '.$delPicLogTime->format('Y/m/d H:i').' 分刪除'.($delPicStr??$vipStatusPicStr).'。' : '')
                                .'於 '.$vipStatusPicTime->format('Y/m/d H:i').($existHeaderImage?' 補足':'上傳').'大頭照+生活照三張。';
                            if (!$existHeaderImage) {
                                $vipStatus .= '但通過審核的照片數量仍未達免費VIP的標準，請再補足大頭照+生活照三張，以獲得VIP權限。';
                            }    
                            else {  
                                $vipStatus .= '須通過系統審核，預計於'.$expect_recover_date.'獲得 vip 權限。';            
                            }
                        }
                     break;                     
                }
            
            }
        }
        
        $vasStatus = '';

        if ($user->valueAddedServiceStatus('hideOnline') == 1) {
            $vasStatus = '您目前已購買隱藏功能。';
            $vas = $user->vas->where('service_name','hideOnline')->first();
            if ($vas->payment) {
                if (EnvironmentService::isLocalOrTestMachine()) {
                    $envStr = '_test';
                }
                else {
                    $envStr = '';
                }
                if (substr($vas->payment, 0, 3) == 'cc_' && $vas->business_id == Config::get('ecpay.payment'.$envStr.'.MerchantID')) {
                    $ecpay = new \App\Services\ECPay_AllInOne();
                    $ecpay->MerchantID = Config::get('ecpay.payment'.$envStr.'.MerchantID');
                    $ecpay->ServiceURL = Config::get('ecpay.payment'.$envStr.'.ServiceURL'); // 定期定額查詢
                    $ecpay->HashIV = Config::get('ecpay.payment'.$envStr.'.HashIV');
                    $ecpay->HashKey = Config::get('ecpay.payment'.$envStr.'.HashKey');
                    $ecpay->Query = [
                        'MerchantTradeNo' => $vas->order_id,
                        'TimeStamp' => 	time()
                    ];
                    $paymentData = $ecpay->QueryPeriodCreditCardTradeInfo(); // 信用卡定期定額
                    
                    $last = last($paymentData['ExecLog']);
                    $lastProcessDate = str_replace('%20', ' ', $last['process_date']);
                    $lastProcessDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $lastProcessDate);
                    // 計算下次扣款日
                    if ($vas->payment == 'cc_quarterly_payment') {
                        $periodRemained = 92;
                    } else {
                        $periodRemained = 30;
                    }
                    $nextProcessDate = substr($lastProcessDate->addDays($periodRemained),0,10);
                    
                }
                $payment = '信用卡繳費';
                $vas_status = '隱藏功能設定：';
                if ($user->is_hide_online == 1) {
                    $vas_status .= '隱藏(您的上線狀態凍結於'.substr($user->hide_online_time, 0, 11).')';
                }
                if ($user->is_hide_online == 2) {
                      $vas_status .= '消失(其他會員無法查詢到您的資料)';
                }
                if ($user->is_hide_online == 0) {
                    $vas_status = '關閉(您目前沒有啟動隱藏功能)';
                }
                switch ($vas->payment) {
                    case 'cc_monthly_payment':
                         if (!$vas->isPaidCanceled() && $nextProcessDate??null) {
                            $vasStatus .= '是每月持續付費，下次付費時間是'.$nextProcessDate.'。'.$vas_status;
                        } else if ($vas->isPaidCanceled()) {
                            $cancel_str = '';
                            $latest_vas_log = $user->getLatestVasLog();
                            if ($latest_vas_log->isCancel()) {
                                $cancel_str = '已於 '.substr($latest_vas_log->created_at,0,10).' 申請取消。';
                            }
                            
                            $vasStatus .= '是每月持續付費，'.$cancel_str.'隱藏功能到期時間為 '. substr($vas->expiry,0,10).'。'.$vas_status;
                        }
                        break;
                    case 'cc_quarterly_payment':
                         if (!$vas->isPaidCanceled() && $nextProcessDate??null) {
                             $vasStatus.='是每季持續付費，下次付費時間是'.$nextProcessDate.'。'.$vas_status;
                         } else if($vas->isPaidCanceled()) {
                            $cancel_str = '';
                            $latest_vas_log = $user->getLatestVasLog();
                            if($latest_vas_log->isCancel()) {
                                $cancel_str = '已於 '.substr($latest_vas_log->created_at,0,10).' 申請取消。';
                            }
                            
                            $vasStatus .= '是每季持續付費。'.$cancel_str.'隱藏功能到期時間為 '. substr($vas->expiry,0,10).'。'.$vas_status;
                        }
                        break;
                    case 'one_month_payment':
                        $vasStatus .= '是單次付費，到期時間為 '. substr($vas->expiry,0,10).$vas_status;
                        break;
                    case 'one_quarter_payment':
                        $vasStatus .= '是單次付費，到期時間為 '. substr($vas->expiry,0,10).$vas_status;
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
            ->leftJoin('user_meta as m', 'u.id', 'm.user_id')
            ->leftJoin('banned_users as b', 'u.id', 'b.member_id')
            ->orderBy('b.id', 'desc')
            ->leftJoin('warned_users as w', 'u.id', 'w.member_id')
            ->orderBy('w.id', 'desc')
            ->where('u.id', $user->id)
            ->get()->first();
        // 封鎖
        $isBannedStatus = '';
        if($user_isBannedOrWarned->banned_expire_date != null){
            $datetime1 = new \DateTime(now());
            $datetime2 = new \DateTime($user_isBannedOrWarned->banned_expire_date);
            $datetime3 = new \DateTime($user_isBannedOrWarned->banned_created_at);
            $diffDays = $datetime2->diff($datetime3)->days;
        }

        if (!empty($user_isBannedOrWarned->banned_id) && $user_isBannedOrWarned->banned_adv_auth==1) {
            $isBannedStatus = '您目前<span class="main_word">已被系統封鎖</span>，';
            if ($user_isBannedOrWarned->banned_expire_date > now()) {
                $isBannedStatus .= '預計至 '.substr($user_isBannedOrWarned->banned_expire_date,0,16).' 日解除，';
            }   
            if ($user_isBannedOrWarned->banned_reason??'') {
                $isBannedStatus .= '原因是<span class="main_word"> ' . $user_isBannedOrWarned->banned_reason . '</span>，';
            }
            $isBannedStatus .= '做完進階驗證可解除<a class="red" href="'.url('advance_auth').'"> [請點我進行驗證]</a>。';
        } else if ($user_isBannedOrWarned->banned_vip_pass == 1 && $user_isBannedOrWarned->banned_expire_date == null) {
            $isBannedStatus = '您目前<span class="main_word">已被站方封鎖</span>，原因是 <span class="main_word">' . $user_isBannedOrWarned->banned_reason . '</span>，若要解除請升級VIP解除，並同意如有再犯，站方有權利不退費並永久封鎖。同意 [<a href="../dashboard/new_vip" class="red">請點我</a>]';
        } else if ($user_isBannedOrWarned->banned_vip_pass == 1 && $user_isBannedOrWarned->banned_expire_date > now()) {
            $isBannedStatus .= '您從 '.substr($user_isBannedOrWarned->banned_created_at,0,10).' <span class="main_word">被站方封鎖 '.$diffDays.'天</span>，預計至 '.substr($user_isBannedOrWarned->banned_expire_date,0,16).' 日解除，原因是<span class="main_word"> '.$user_isBannedOrWarned->banned_reason.'</span>，若要解除請升級VIP解除，並同意如有再犯，站方有權利不退費並永久封鎖。同意 [<a href="../dashboard/new_vip" class="red">請點我</a>]';
        } else if (!empty($user_isBannedOrWarned->banned_id) && $user_isBannedOrWarned->banned_expire_date == null) {
            $isBannedStatus = '您目前<span class="main_word">已被站方封鎖</span>，原因是<span class="main_word"> ' . $user_isBannedOrWarned->banned_reason . '</span>，如有需要反應請點右下聯絡我們聯絡站長。';
        } else if (!empty($user_isBannedOrWarned->banned_id) && $user_isBannedOrWarned->banned_expire_date > now()) {
            $isBannedStatus .= '您從 '.substr($user_isBannedOrWarned->banned_created_at,0,10).' <span class="main_word">被站方封鎖'.$diffDays.'天</span>，預計至 '.substr($user_isBannedOrWarned->banned_expire_date,0,16).' 日解除，原因是 <span class="main_word">'.$user_isBannedOrWarned->banned_reason.'</span>，如有需要反應請點右下聯絡我們聯絡站長。';
        }

        // 警示
        $adminWarnedStatus = '';
        if ($user_isBannedOrWarned->warned_expire_date != null) {
            $datetime1 = new \DateTime(now());
            $datetime2 = new \DateTime($user_isBannedOrWarned->warned_expire_date);
            $datetime3 = new \DateTime($user_isBannedOrWarned->warned_created_at);
            $diffDays = $datetime2->diff($datetime3)->days;
        }

        if (!empty($user_isBannedOrWarned->warned_id) && $user_isBannedOrWarned->warned_adv_auth==1) {
            $adminWarnedStatus = '您目前<span class="main_word">已被系統警示</span>，';
            if ($user_isBannedOrWarned->warned_expire_date > now()) {
                $adminWarnedStatus .= '預計至 '.substr($user_isBannedOrWarned->warned_expire_date,0,16).' 日解除，';
            }   
            if ($user_isBannedOrWarned->warned_reason??'') {
                $adminWarnedStatus .= '原因是<span class="main_word"> ' . $user_isBannedOrWarned->warned_reason . '</span>，';
            }            
            $adminWarnedStatus.= '做完進階驗證可解除<a class="red" href="'.url('advance_auth').'"> [請點我進行驗證]</a>。';
        } else if ($user_isBannedOrWarned->warned_vip_pass == 1 && $user_isBannedOrWarned->warned_expire_date == null) {
            $adminWarnedStatus = '您目前<span class="main_word">已被站方警示</span>，原因是<span class="main_word"> ' . $user_isBannedOrWarned->warned_reason . '</span>，若要解鎖請升級VIP解除，並同意如有再犯，站方有權不退費並永久警示。同意[<a href="../dashboard/new_vip" class="red">請點我</a>]';
        } else if ($user_isBannedOrWarned->warned_vip_pass == 1 && $user_isBannedOrWarned->warned_expire_date > now()) {
            $adminWarnedStatus .= '您從 '.substr($user_isBannedOrWarned->warned_created_at,0,10).' <span class="main_word">被站方警示 '.$diffDays.'天</span>，預計至 '.substr($user_isBannedOrWarned->warned_expire_date,0,16).' 日解除，原因是<span class="main_word"> '.$user_isBannedOrWarned->warned_reason.'</span>，若要解鎖請升級VIP解除，並同意如有再犯，站方有權不退費並永久警示。同意[<a href="../dashboard/new_vip" class="red">請點我</a>]';
        } else if (!empty($user_isBannedOrWarned->warned_id) && $user_isBannedOrWarned->warned_expire_date == null) {
            $adminWarnedStatus = '您目前<span class="main_word">已被站方警示</span>，原因是<span class="main_word"> ' . $user_isBannedOrWarned->warned_reason . '</span>，如有需要反應請點右下聯絡我們聯絡站長。';
        } else if (!empty($user_isBannedOrWarned->warned_id) && $user_isBannedOrWarned->warned_expire_date > now() ) {
            $adminWarnedStatus .= '您從 '.substr($user_isBannedOrWarned->warned_created_at,0,10).' <span class="main_word">被站方警示 '.$diffDays.'天</span>，預計至 '.substr($user_isBannedOrWarned->warned_expire_date,0,16).' 日解除，原因是<span class="main_word"> '.$user_isBannedOrWarned->warned_reason.'</span>，如有需要反應請點右下聯絡我們聯絡站長。';
        }

        $isWarnedStatus = '';
        if ($user_isBannedOrWarned->isWarned == 1) {
            if ($user_isBannedOrWarned->isWarnedType!='adv_auth') {
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

        // 本月封鎖數
        $banned_users = banned_users::select('id')
            ->where('created_at', '>=', \Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString())->count();

        // 隱形封鎖
        $banned_users_implicitly = BannedUsersImplicitly::select('id')
            ->where('created_at', '>=', \Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString())->count();

        // 取得封鎖資料總筆數
        $bannedCount = $banned_users + $banned_users_implicitly;

        // 本月警示人數
        $warnedCount = warned_users::select('id', 'member_id')->where('created_at', '>=', \Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString())->distinct()->count('member_id');

        // 個人檢舉紀錄
        $reported = Reported::select('reported.id', 'reported.reported_id as rid', 'reported.content as reason', 'reported.created_at as reporter_time', 'u.name', 'm.isWarned', 'b.id as banned_id', 'b.expire_date as banned_expire_date', 'w.id as warned_id', 'w.expire_date as warned_expire_date')
            ->selectRaw('"reported" as reported_type')
            ->leftJoin('users as u', 'u.id', 'reported.reported_id')->where('u.id', '!=', null)
            ->leftJoin('user_meta as m', 'u.id', 'm.user_id')
            ->leftJoin('banned_users as b', 'u.id', 'b.member_id')
            ->leftJoin('warned_users as w', 'u.id', 'w.member_id');
        $reported = $reported->where('reported.member_id', $user->id)->where('reported.hide_reported_log', 0)->get();

        $reported_pic = ReportedPic::select('reported_pic.id', 'member_pic.member_id as rid', 'reported_pic.content as reason', 'reported_pic.created_at as reporter_time', 'u.name', 'm.isWarned', 'b.id as banned_id', 'b.expire_date as banned_expire_date', 'w.id as warned_id', 'w.expire_date as warned_expire_date')
            ->selectRaw('"reportedPic" as reported_type');
        $reported_pic = $reported_pic->join('member_pic', 'member_pic.id', '=', 'reported_pic.reported_pic_id')
            ->leftJoin('users as u', 'u.id', 'member_pic.member_id')->where('u.id', '!=', null)
            ->leftJoin('user_meta as m', 'u.id', 'm.user_id')
            ->leftJoin('banned_users as b', 'u.id', 'b.member_id')
            ->leftJoin('warned_users as w', 'u.id', 'w.member_id')
            ->where('reported_pic.reporter_id', $user->id)->where('reported_pic.hide_reported_log', 0)->get();

        $reported_avatar = ReportedAvatar::select('reported_avatar.id', 'reported_avatar.reported_user_id as rid', 'reported_avatar.content as reason', 'reported_avatar.created_at as reporter_time', 'u.name', 'm.isWarned', 'b.id as banned_id', 'b.expire_date as banned_expire_date', 'w.id as warned_id', 'w.expire_date as warned_expire_date')
            ->selectRaw('"reportedAvatar" as reported_type')
            ->leftJoin('users as u', 'u.id','reported_avatar.reported_user_id')->where('u.id', '!=', null)
            ->leftJoin('user_meta as m', 'u.id','m.user_id')
            ->leftJoin('banned_users as b', 'u.id','b.member_id')
            ->leftJoin('warned_users as w', 'u.id','w.member_id');
        $reported_avatar = $reported_avatar->where('reported_avatar.reporter_id', $user->id)->where('reported_avatar.hide_reported_log', 0)->get();

        $reported_message = Message::select('message.id', 'message.from_id as rid', 'message.reportContent as reason', 'message.updated_at as reporter_time', 'u.name', 'm.isWarned', 'b.id as banned_id', 'b.expire_date as banned_expire_date', 'w.id as warned_id', 'w.expire_date as warned_expire_date')
            ->selectRaw('"reportedMessage" as reported_type')
            ->leftJoin('users as u', 'u.id', 'message.from_id')->where('u.id', '!=', null)
            ->leftJoin('user_meta as m', 'u.id', 'm.user_id')
            ->leftJoin('banned_users as b', 'u.id', 'b.member_id')
            ->leftJoin('warned_users as w', 'u.id', 'w.member_id');
        $reported_message = $reported_message->where('message.to_id', $user->id)->where('message.isReported', 1)->where('message.hide_reported_log', 0)->get();

        $collection = collect([$reported, $reported_pic, $reported_avatar, $reported_message]);
        $report_all = $collection->collapse()->unique('rid')->sortByDesc('reporter_time');

        $reportedStatus = array();
            foreach ($report_all as $row) {
                if (isset($row->rid) && !empty($row->rid)) {
                    $content_1 = '您於 ' . substr($row->reporter_time, 0, 10) . ' 檢舉了 <a href=../dashboard/viewuser/' . $row->rid . '>' . $row->name . '</a>，檢舉緣由是 ' . $row->reason;
                    $content_2 = '';

                    // 封鎖
                    $reporter_isBannedStatus = 0;
                    $reporter_isBannedStatus_expire = '';

                    if (!empty($row->banned_id) && $row->banned_expire_date == null) {
                        $reporter_isBannedStatus = 1;
                    } else if (!empty($row->banned_id) && $row->banned_expire_date > now()) {
                        $reporter_isBannedStatus = 1;
                        $datetime1 = new \DateTime(now());
                        $datetime2 = new \DateTime($row->banned_expire_date);
                        $diffDays = $datetime1->diff($datetime2)->days;
                        $reporter_isBannedStatus_expire = $diffDays;
                    }

                    // 警示
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

                    if ($reporter_isBannedStatus == 1) {
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
                        array_push($reportedStatus, array('id' => $row->id, 'rid' => $row->rid, 'content' => $content_1, 'status' => $content_2, 'name' => $row->name, 'reported_type' => $row->reported_type));
                    }
                }
            }

        // 你收藏的會員上線
        $uid = $user->id;
        $myFav = MemberFav::select('a.id as rowid', 'a.member_id', 'a.member_fav_id', 'b.id', 'b.name', 'b.title', 'b.is_hide_online', \DB::raw("IF(b.is_hide_online = 1 or b.is_hide_online = 2, b.hide_online_time, b.last_login) as last_login"), 'v.id as vid', \DB::raw('max(v.created_at) as visited_created_at'))
            ->where('a.member_id', $user->id)->from('member_fav as a')
            ->leftJoin('users as b', 'a.member_fav_id','b.id')->where('b.id', '!=', null)
            ->leftJoin('visited as v', function ($join) use ($uid) {
                $join->on('v.member_id', '=', 'a.member_fav_id')
                    ->where('v.visited_id', $uid);
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

        // 收藏你的會員上線
        $otherFav = MemberFav::select('a.id as rowid', 'a.member_id', 'a.member_fav_id', 'b.name', 'b.title', 'b.is_hide_online', \DB::raw("IF(b.is_hide_online = 1 or b.is_hide_online = 2, b.hide_online_time, b.last_login) as last_login"))
            ->where('a.member_fav_id', $user->id)->from('member_fav as a')
            ->leftJoin('users as b','a.member_id', 'b.id')->where('b.id', '!=', null)
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
            ->where('a.hide_member_fav_id_log', 0)
            ->get();

        // msg
        $msgMemberCount = Message_new::allSenders($user->id, $user->isVip(), 'all');

        $queryBE = \App\Models\Evaluation::select('evaluation.*')->from('evaluation as evaluation')->with('user')
                ->leftJoin('blocked as b1', 'b1.blocked_id', '=', 'evaluation.from_id')
                ->orderBy('evaluation.created_at', 'desc')
                ->where('b1.member_id', $uid)
                ->where('evaluation.to_id', $uid)
                ->where('evaluation.read', 1)
                ->get();

        $isBannedEvaluation = sizeof($queryBE) > 0 ? true : false;

        $queryHE = \App\Models\Evaluation::select('evaluation.*')->from('evaluation as evaluation')
                ->orderBy('evaluation.created_at', 'desc')
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

        $isHasEvaluation = sizeof($arrayHE) > 0 ? true : false;

        $query = Message::whereNotNull('id');
        $query = $query->where(function ($query) use ($uid,$admin) {
            $whereArr1 = [['to_id', $uid], ['from_id', $admin->id]];
            array_push($whereArr1, ['is_single_delete_1','<>', $uid], ['is_row_delete_1', '<>', $uid]);
            $query->where($whereArr1);
        });        
        $admin_msg_entrys = $query->orderBy('created_at', 'desc')->get();
		$admin_msgs = [];
        $admin_msgs_sys = [];

		foreach ($admin_msg_entrys->where('sys_notice', 0) as $admin_msg_entry) {
            $admin_msg_entry->content = str_replace('NAME', $user->name, $admin_msg_entry->content);
            $admin_msg_entry->content = str_replace('|$report|', $user->name, $admin_msg_entry->content);
            $admin_msg_entry->content = str_replace('LINE_ICON', AdminService::$line_icon_html, $admin_msg_entry->content);
            $admin_msg_entry->content = str_replace('|$lineIcon|', AdminService::$line_icon_html, $admin_msg_entry->content);         
            $admin_msg_entry->content = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $admin_msg_entry->content);
            $admin_msg_entry->content = str_replace('|$reportTime|', date("Y-m-d H:i:s"), $admin_msg_entry->content);
            $admin_msg_entry->content = str_replace('NOW_TIME', date("Y-m-d H:i:s"), $admin_msg_entry->content);  
            $admin_msgs[] = $admin_msg_entry;
		}
        $i = 0;
		foreach ($admin_msg_entrys->where('sys_notice', '1') as $admin_msg_entry) {
			$admin_msgs_sys[] = $admin_msg_entry;
			$i++;
			if($i >= 3) break;
		}        

        // 僅顯示30天內的評價
        $evaluation_30days = \App\Models\Evaluation::selectRaw('evaluation.*, b1.blocked_id, b.name')->from('evaluation as evaluation')
            ->leftJoin('blocked as b1', function($join) {
                $join->on('b1.blocked_id', '=', 'evaluation.from_id');
                $join->on('b1.member_id', '=', 'evaluation.to_id');
            })
            ->leftJoin('users as b', 'evaluation.from_id', 'b.id')
            ->orderBy('evaluation.created_at', 'desc')
            ->where('evaluation.to_id', $uid)
            ->where('evaluation.created_at', '>=', Carbon::now()->subDays(30));

        $evaluation_30days_list = $evaluation_30days->where('evaluation.hide_evaluation_to_id', 0)->get();
        $evaluation_30days_unread_count = $evaluation_30days->where('evaluation.read', 1)->get()->count();

        // 舊會員上線，就在上線第 3,6,10 次 (以此功能上線開始計算)在會員專屬頁通知。
        // 新會員：做完新手教學，填寫完基本資料，於第一次進入專屬頁面時跳通知，之後就在上線第 3,6,10 次在會員專屬頁通知。
        $showLineNotifyPop = false;
        if (is_null($user->line_notify_token)) {
            if (in_array($user->line_notify_alert, [3, 6, 10])) {
                $showLineNotifyPop = true;
            }
            if ($user->created_at >= '2021-07-23' && $user->line_notify_alert <= 2) {
                $showLineNotifyPop = true;
            }
        }
        $login_times = $user->line_notify_alert;
        if ($showLineNotifyPop) {
            $showLineNotifyPop = session()->get('alreadyPopUp_lineNotify') == $login_times.'_Y' ? false : true;
        }

        // 是否有系統提示訊息
        $announceRead = AnnouncementRead::select('announcement_id')->where('user_id', $user->id)->get();
        $announcement = AdminAnnounce::where('en_group', $user->engroup)->whereNotIn('id', $announceRead)->orderBy('sequence', 'asc')->get();
        $announcePopUp = 'N';
        if (isset($announcement) && count($announcement) > 0 && !session()->get('announceClose')) {
            $announcePopUp = 'Y';
        }

        if (isset($user)) {
            $data = array(
                'vipStatus' => $vipStatus,
                'vasStatus' => $vasStatus,
                'isBannedStatus' => $isBannedStatus,
                'adminWarnedStatus' => $adminWarnedStatus,
                'isWarnedStatus' => $isWarnedStatus,
                'bannedCount' => $bannedCount,
                'warnedCount' => $warnedCount,
                'reportedStatus' => $reportedStatus,
                'msgMemberCount' => $msgMemberCount,
                'isBannedEvaluation' => $isBannedEvaluation,
                'isHasEvaluation' => $isHasEvaluation,
                'evaluation_30days' => $evaluation_30days_list,
                'evaluation_30days_unread_count' => $evaluation_30days_unread_count,
                'showLineNotifyPop' => $showLineNotifyPop,
                'announcePopUp' => $announcePopUp,
                'user_isBannedOrWarned' => $user_isBannedOrWarned,
            );
            $allMessage = \App\Models\Message::allMessage($user->id);

            return response()->json([
                'user_hideOnline' => $user->valueAddedServiceStatus('hideOnline'),
                'user_line_notify_token' => $user->line_notify_token,
                'data' => $data, 
                'myFav' => $myFav, 
                'otherFav' => $otherFav, 
                'admin_msgs' => $admin_msgs, 
                'admin_msgs_sys' => $admin_msgs_sys, 
                'admin' => $admin, 
                'allMessage' => $allMessage]);
        }
    }
}
