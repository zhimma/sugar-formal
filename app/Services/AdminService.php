<?php

namespace App\Services;

use App\Models\BannedUsersImplicitly;
use App\Models\Reported;
use App\Models\ReportedAvatar;
use App\Models\ReportedPic;
use App\Models\ValueAddedService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LogChatPay;
use App\Models\Vip;
use App\Models\Tip;
use App\Models\UserMeta;
use App\Models\Message;
use App\Models\MemberPic;
use App\Models\SimpleTables\banned_users;
use App\Repositories\UserRepository;
use PhpParser\Node\Expr\Cast\Object_;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminService
{
    /**
     * User model
     * @var User
     */
    public $model;

    /**
     * User Meta model
     * @var UserMeta
     */
    protected $userMeta;

    /**
     * Role Service
     * @var RoleService
     */
    protected $role;

    public static $line_icon_html='<a href="https://lin.ee/rLqcCns"><img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="26" border="0" style="all: initial;all: unset;height: 26px; float: unset;"></a>';

    /**
     * Check admin user existence.
     *
     * @return $admin or false
     */
    public static function checkAdmin(){
        //$banned_users = banned_users::select('member_id')->get();
        //$admin = User::where('name', 'like', '%'.'站長'.'%')
                        //->whereNotIn('id', $banned_users)
                        //->get()->first();
        // todo: 效能問題

        $admin = User::leftJoin('banned_users', 'users.id', '=', 'banned_users.member_id')
                        ->select('users.*')
                        ->where('users.name', 'like', '%'.'站長'.'%')
                        ->whereNull('banned_users.id')
                        ->first();

        //$admin = User::find(1049);
        if ($admin){
            return $admin;
        }
        else{
            return false;
        }
    }

    /**
     * Search advanced member data. (Advanced)
     *
     * @return $users data
     */
    public function advSearch(Request $request)
    {
        $name = $request->name ? $request->name : "";
        $email = $request->email ? $request->email : "";
        $keyword = $request->keyword ? $request->keyword : "";
        $phone = $request->phone ? $request->phone : "";
        $title = $request->title ? $request->title : "";
        $order_no = $request->order_no ? $request->order_no : "";
        $user['isBlocked']=0;
        $users = User::select('m.about','m.style','u.*')->from('users as u')
            ->leftJoin('user_meta as m','u.id','m.user_id')
            ->where('u.email', 'like', '%' . $email . '%')
            ->where('u.name', 'like', '%' . $name . '%');
        if($keyword){
            switch ($request->login_time){
                case '1week':
                    $date = date("Y-m-d H:i:s",strtotime("-1 week"));
                    break;
                case '2weeks':
                    $date = date("Y-m-d H:i:s",strtotime("-2 weeks"));
                    break;
                default:
                    $date = date("Y-m-d H:i:s",strtotime("-3 days"));
                    break;
            }
            $users = $users->where('u.last_login','>=' , $date);
            $users = $users->where(function($query) use ($keyword){
                $query->orWhere('m.about', 'like', '%'.$keyword.'%')
                    ->orWhere('m.style', 'like', '%'.$keyword.'%');
            });
        }

        if($phone){
            $users = $users->leftJoin('short_message','short_message.member_id','u.id')->where('short_message.mobile', 'like', '%' . $phone . '%')->where('short_message.active',1);
        }

        if($title){
            $users = $users->where('u.title', 'like', '%' . $title . '%');
        }

        if($order_no){
            $users = $users->leftJoin('order','order.user_id','u.id')->where('order.order_id', 'like', '%' . $order_no . '%');
        }

        if($request->time){
            $users = $users->orderBy($request->time, 'desc');
        }
        else{
            $users = $users->orderBy('u.last_login', 'desc');
        }

        //排序
        if($request->member_type =='vip') {
            $users->selectRaw('(select count(*) from member_vip where member_vip.member_id=u.id and member_vip.active=1 order by member_vip.id desc limit 1) as vip1');
            $users = $users->selectRaw('(select count(*) from member_value_added_service where member_id =u.id  and active = 1 and service_name = "VVIP" and (expiry = "0000-00-00 00:00:00" or expiry >= "' . Carbon::now() . '") order by created_at desc limit 1) as vvip1');
            $users = $users->selectRaw('(select count(*) from vvip_application where user_id=u.id and status=1 and vvip_application.deleted_at is null limit 1 ) as vvip2');
            $users = $users->selectRaw('(case when ((select count(*) from member_value_added_service where member_id =u.id  and active = 1 and service_name = "VVIP" and (expiry = "0000-00-00 00:00:00" or expiry >= "' . Carbon::now() . '") order by created_at desc limit 1)=1 and (select count(*) from vvip_application where user_id=u.id and status=1 and vvip_application.deleted_at is null limit 1 )=1) then 1 else 0 end) as vvip_condition');
            $users = $users->orderByRaw('vip1 desc,vvip_condition desc');
        }
        if($request->member_type =='vvip'){
            $users = $users->selectRaw('(case when ((select count(*) from member_value_added_service where member_id =u.id  and active = 1 and service_name = "VVIP" and (expiry = "0000-00-00 00:00:00" or expiry >= "' . Carbon::now() . '") order by created_at desc limit 1)=1 and (select count(*) from vvip_application where user_id=u.id and status=1 and vvip_application.deleted_at is null limit 1 )=1) then 1 else 0 end) as vvip_condition');
            $users = $users->orderByRaw('vvip_condition desc');
        }
        if($request->member_type =='banned'){
            $users->selectRaw('(select count(*) from banned_users where banned_users.member_id like u.id limit 1) as banned_1');
            $users->selectRaw('(select count(*) from banned_users_implicitly where target like u.id limit 1) as banned_2');
            $users->selectRaw('(case when ((select count(*) from banned_users where banned_users.member_id like u.id limit 1)=1 or (select count(*) from banned_users_implicitly where target like u.id limit 1)=1) then 1 else 0 end) as banned_condition');
            $users = $users->orderByRaw('banned_condition desc');
        }
        $users = $users->paginate(10);

        foreach ($users as $user){
            $user['isBlocked'] = banned_users::where('member_id', 'like', $user->id)->get()->first() == true  ? true : false;
            if($user['isBlocked'] == false){
                $user['isBlocked'] = BannedUsersImplicitly::select(\DB::raw('id, "隱性" as type'))->where('target', 'like', $user->id)->get()->first() == true ? true : false;
            }
            if($user['isBlocked']==true){
                $user['isBlocked']=1;
            }
            $user['vip'] = $user->isVip() ? 1 : 0;
            $user['vip_data'] = Vip::select('id', 'free', 'expiry', 'order_id', 'updated_at', 'created_at', 'payment_method')
                ->where('member_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get()->first();

            $user['vvip'] = $user->isVVIP() ? 1 : 0;
            $user['vvip_data'] = ValueAddedService::select('active', 'expiry', 'payment', 'created_at', 'updated_at', 'order_id')
                ->where('member_id', $user->id)
                ->where('service_name', 'VVIP')
                ->orderBy('created_at', 'desc')
                ->get()->first();
        }
        return $users;
    }

    /**
     * Search members' messages.
     *
     * @return result
     */
    public function searchMessage(Request $request){
            if ( $request->msg && $request->date_start && $request->date_end ) {
                $results = Message::where('content', 'like', '%' . $request->msg . '%')
                    ->whereBetween('created_at', array($request->date_start . ' 00:00', $request->date_end . ' 23:59'));
            } else if ( $request->msg ) {
                $results = Message::where('content', 'like', '%' . $request->msg . '%');
            } else if ( $request->date_start && $request->date_end ) {
                $results = Message::whereBetween('created_at', array($request->date_start . ' 00:00', $request->date_end . ' 23:59'));
            }
            else{
                return null;
            }

            if($request->time =='created_at'){
                $users = $users->orderBy('created_at', 'desc');
            }
            if($request->time =='login_time'){
                $users = $users->orderBy('last_login', 'desc');
            }
            $results = $results->get();
            return $results;
    }

    /**
     * Search members' messages and orders by send time.
     *
     * @return datas
     */
    public function searchMessageBySendTime(Request $request){
        try {
            if ( $request->msg && $request->date_start && $request->date_end ) {
                $results = Message::where('content', 'like', '%' . $request->msg . '%')
                    ->whereBetween('created_at', array($request->date_start . ' 00:00', $request->date_end . ' 23:59'));
            } else if ( $request->msg ) {
                $results = Message::where('content', 'like', '%' . $request->msg . '%');
            } else if ( $request->date_start && $request->date_end ) {
                $results = Message::whereBetween('created_at', array($request->date_start . ' 00:00', $request->date_end . ' 23:59'));
            }
            else{
                $results = null;
            }
        }
        finally{
            if($results != null){
                $return = $this->fillMessageDatas($results);
                $results = $return['results'];
                if($request->member_type =='vip'){
                    $results = collect($results)->sortBy('vip', true,true)->reverse()->toArray();
                }
                if($request->member_type =='banned'){
                    $results = collect($results)->sortBy('isBlocked')->reverse()->toArray();
                }
            }
            $datas = [
                'results' => $results,
                'users' => isset($return['users']) ? $return['users'] : null,
                'msg' => isset($request->msg) ? $request->msg : null,
                'date_start' => isset($request->date_start) ? $request->date_start : null,
                'date_end' => isset($request->date_end) ? $request->date_end : null
            ];
            return $datas;
        }
    }

    public function fillMessageDatas($results){
        $results = $results->orderBy('created_at', 'desc')->get();
        $to_id = array();
        $from_id = array();
        foreach ($results as $result){
            if(!in_array($result->to_id, $to_id)) {
                array_push($to_id, $result->to_id);
            }
            if(!in_array($result->from_id, $from_id)) {
                array_push($from_id, $result->from_id);
            }
            $result['isBlocked'] = banned_users::where('member_id', 'like', $result->from_id)->get()->first();
            if(!isset($result['isBlocked'])){
                $result['isBlocked'] = \App\Models\BannedUsersImplicitly::select(\DB::raw('id, "隱性" as type'))->where('target', 'like', $result->from_id)->get()->first();
            }
            $result['isBlockedReceiver'] = banned_users::where('member_id', 'like', $result->to_id)->get()->first();
            if(!isset($result['isBlockedReceiver'])){
                $result['isBlockedReceiver'] = \App\Models\BannedUsersImplicitly::select(\DB::raw('id, "隱性" as type'))->where('target', 'like', $result->to_id)->get()->first();
            }
            //被檢舉者近一月曾被不同人檢舉次數
            $tmp = $this->reports_month($result->from_id);
            $result['picsResult'] = $tmp['picsResult'];
            $result['messagesResult'] = $tmp['messagesResult'];
            $result['reportsResult'] = $tmp['reportsResult'];
        }
        $users = array();
        foreach ($to_id as $id){
            $users[$id] = array();
        }
        foreach ($from_id as $id){
            if(!array_key_exists($id, $to_id)){
                $users[$id] = array();
            }
        }
        foreach ($users as $id => &$user){
            $info = User::select('name','engroup', 'last_login')
                ->where('id', '=', $id)
                ->get()->first();
            if($info != null){
                $user['name'] = $info->name;
                $user['vip'] = Vip::vip_diamond($id);
                $user['tipcount'] = Tip::TipCount_ChangeGood($id);
                $user['engroup'] = $info->engroup;
                $user['last_login'] = $info->last_login;
            }
            else{
                $user = array();
                $user['name'] = "此會員不存在";
                $user['vip'] = false;
                $user['engroup'] = -1;
                $user['last_login'] = "0000-00-00 00:00:00";
            }
        }
        return ['results' => $results,
                'users' => $users];
    }

    public function fillReportedDatas($results){
        $results = $results->get();
        //member_id is reporter id
        $member_id = array();
        $reported_id = array();
        foreach ($results as &$result){
            if(!in_array($result->member_id, $member_id)) {
                array_push($member_id, $result->member_id);
            }
            if(!in_array($result->reported_id, $reported_id)) {
                array_push($reported_id, $result->reported_id);
            }
            $result['isBlocked'] = banned_users::where('member_id', 'like', $result->member_id)->get()->first();
            if(!isset($result['isBlocked'])){
                $result['isBlocked'] = \App\Models\BannedUsersImplicitly::select(\DB::raw('id, "隱性" as type'))->where('target', 'like', $result->member_id)->get()->first();
            }
            $result['isBlockedReceiver'] = banned_users::where('member_id', 'like', $result->reported_id)->get()->first();
            if(!isset($result['isBlockedReceiver'])){
                $result['isBlockedReceiver'] = \App\Models\BannedUsersImplicitly::select(\DB::raw('id, "隱性" as type'))->where('target', 'like', $result->reported_id)->get()->first();
            }
            //被檢舉者近一月曾被不同人檢舉次數
            $tmp = $this->reports_month($result->reported_id);
            $result['picsResult'] = $tmp['picsResult'];
            $result['messagesResult'] = $tmp['messagesResult'];
            $result['reportsResult'] = $tmp['reportsResult'];
        }
        $users = array();
        foreach ($member_id as $id){
            $users[$id] = array();
        }
        foreach ($reported_id as $id){
            if(!array_key_exists($id, $users)){
                $users[$id] = array();
            }
        }
        foreach ($users as $id => &$user){
            $info = User::select('name', 'engroup', 'last_login','exchange_period')
                ->where('id', '=', $id)
                ->get()->first();
            if($info != null){
                $user['name'] = $info->name;
                $user['engroup'] = $info->engroup;
                $user['last_login'] = $info->last_login;
                $user['vip'] = Vip::vip_diamond($id);
                $user['tipcount'] = Tip::TipCount_ChangeGood($id);
                $user['exchange_period'] = $info->exchange_period;
            }
            else{
                $user = array();
                $user['name'] = "此會員不存在";
                $user['engroup'] = -1;
                $user['vip'] = false;
                $user['last_login'] = "0000-00-00 00:00:00";
                $user['tipcount'] = 0;
                $user['exchange_period'] = "N/A";
            }
        }
        return ['results' => $results,
            'users' => $users];
    }

    public function fillReportedAvatarDatas($results){
        $reporter_id = array();
        $reported_user_id = array();
        foreach ($results as &$result){
            if(!in_array($result->reporter_id, $reporter_id)) {
                array_push($reporter_id, $result->reporter_id);
            }
            if(!in_array($result->reported_user_id, $reported_user_id)) {
                array_push($reported_user_id, $result->reported_user_id);
            }
            $result['isBlocked'] = banned_users::where('member_id', 'like', $result->reporter_id)->get()->first();
            if(!isset($result['isBlocked'])){
                $result['isBlocked'] = \App\Models\BannedUsersImplicitly::select(\DB::raw('id, "隱性" as type'))->where('target', 'like', $result->reporter_id)->get()->first();
            }
            $result['isBlockedReceiver'] = banned_users::where('member_id', 'like', $result->reported_user_id)->get()->first();
            if(!isset($result['isBlockedReceiver'])){
                $result['isBlockedReceiver'] = \App\Models\BannedUsersImplicitly::select(\DB::raw('id, "隱性" as type'))->where('target', 'like', $result->reported_user_id)->get()->first();
            }

            //被檢舉者近一月曾被不同人檢舉次數
            $tmp = $this->reports_month($result->reported_user_id);
            $result['picsResult'] = $tmp['picsResult'];
            $result['messagesResult'] = $tmp['messagesResult'];
            $result['reportsResult'] = $tmp['reportsResult'];

            $result['pic'] = UserMeta::select('pic')->where('user_id', $result->reported_user_id)->get()->first();
            if(!is_null($result['pic']))
                $result['pic'] = $result['pic']->pic;
        }
        $users = array();
        foreach ($reporter_id as $id){
            $users[$id] = array();
        }
        foreach ($reported_user_id as $id){
            if(!array_key_exists($id, $users)){
                $users[$id] = array();
            }
        }
        foreach ($users as $id => &$user){
            $info = User::select('name', 'engroup', 'last_login')
                ->where('id', '=', $id)
                ->get()->first();
            if($info != null){
                $user['name'] = $info->name;
                $user['engroup'] = $info->engroup;
                $user['last_login'] = $info->last_login;
                $user['vip'] = Vip::vip_diamond($id);
                $user['tipcount'] = Tip::TipCount_ChangeGood($id);
            }
            else{
                $user = array();
                $user['name'] = "此會員不存在";
                $user['engroup'] = -1;
                $user['vip'] = false;
                $user['last_login'] = "0000-00-00 00:00:00";
            }
        }
        return ['results' => $results,
            'users' => $users];
    }

    //被檢舉者近一月被不同人檢舉次數 照片/訊息/會員
    public function reports_month($id){
        $date_start =  date("Y-m-d H:i:s",strtotime("-1 month"));;
        $date_end = date("Y-m-d H:i:s");
        $avatarsResult = count(ReportedAvatar::whereBetween('created_at', array($date_start, $date_end))
            ->where('reported_user_id', $id)->groupBy('reporter_id')->get());
        $picid = MemberPic::select('id')->where('member_id', $id)->get();
        $picsResult = 0;
        foreach ($picid as $v) {
            $picsResult += count(ReportedPic::whereBetween('created_at', array($date_start, $date_end))
                ->where('reported_pic_id', $v->id)->groupBy('reporter_id')->get());
        }
        $result['picsResult'] = $picsResult + $avatarsResult;
        $result['messagesResult'] = count(Message::whereBetween('created_at', array($date_start, $date_end))
            ->where('from_id', $id)->where('isReported', 1)->groupBy('to_id')->get());
        $result['reportsResult'] = count(Reported::whereBetween('created_at', array($date_start, $date_end))
            ->where('reported_id', $id)->groupBy('member_id')->get());

        return $result;
    }

    public function fillReportedPicDatas($results){
        $reporter_id = array();
        $reported_user_id = array();
        foreach ($results as &$result){
            if(!in_array($result->reporter_id, $reporter_id)) {
                array_push($reporter_id, $result->reporter_id);
            }
            $temp = MemberPic::select('member_id', 'pic')->where('id', $result->reported_pic_id)->get()->first();
            if(isset($temp)){
                $result['reported_user_id'] = $temp->member_id;
                $result['pic'] = $temp->pic;
                if(!in_array($temp->member_id, $reported_user_id)) {
                    array_push($reported_user_id, $temp->member_id);
                }
                $result['isBlocked'] = banned_users::where('member_id', 'like', $result->reporter_id)->get()->first();
                if(!isset($result['isBlocked'])){
                    $result['isBlocked'] = \App\Models\BannedUsersImplicitly::select(\DB::raw('id, "隱性" as type'))->where('target', 'like', $result->reporter_id)->get()->first();
                }
                $result['isBlockedReceiver'] = banned_users::where('member_id', 'like', $result->reported_user_id)->get()->first();
                if(!isset($result['isBlockedReceiver'])){
                    $result['isBlockedReceiver'] = \App\Models\BannedUsersImplicitly::select(\DB::raw('id, "隱性" as type'))->where('target', 'like', $result->reported_user_id)->get()->first();
                }
                //被檢舉者近一月曾被不同人檢舉次數
                $tmp = $this->reports_month($result->reported_user_id);
                $result['picsResult'] = $tmp['picsResult'];
                $result['messagesResult'] = $tmp['messagesResult'];
                $result['reportsResult'] = $tmp['reportsResult'];
            }
            else{
                $result['name'] = "查無使用者";
                $result['pic'] = '照片已刪除或該筆資料不存在。';
                $result['vip'] = '照片已刪除或該筆資料不存在。';
            }

        }
        $users = array();
        foreach ($reporter_id as $id){
            $users[$id] = array();
        }
        foreach ($reported_user_id as $id){
            if(!array_key_exists($id, $users)){
                $users[$id] = array();
            }
        }
        foreach ($users as $id => &$user){
            $info = User::select('name', 'engroup', 'last_login')
                ->where('id', '=', $id)
                ->get()->first();
            if($info != null){
                $user['name'] = $info->name;
                $user['engroup'] = $info->engroup;
                $user['last_login'] = $info->last_login;
                $user['vip'] = Vip::vip_diamond($id);
                $user['tipcount'] = Tip::TipCount_ChangeGood($id);
            }
            else{
                $user = array();
                $user['name'] = "此會員不存在";
                $user['engroup'] = -1;
                $user['vip'] = false;
                $user['last_login'] = "0000-00-00 00:00:00";
            }
        }
        return ['results' => $results,
            'users' => $users];
    }

    /**
     * All of the reported data which contains picture, avator, merssage
     * and other user report about the user_id.
     * 
     * @return data set
     */
    public function reportedUserDetails(Request $request){

        $search_id = $request->reported_id;
        $date_start = $request->date_start ? $request->date_start : '0000-00-00';
        $date_end = $request->date_end ? $request->date_end. ' 23:59:59' : date('Y-m-d'). ' 23:59:59';

        $avatarsResult = ReportedAvatar::whereBetween('created_at', array($date_start, $date_end))
                                    ->orderBy('created_at', 'desc')->get();
        $picsResult = ReportedPic::whereBetween('created_at', array($date_start, $date_end))
                                    ->orderBy('created_at', 'desc')->get();
        $messagesResult = Message::whereBetween('created_at', array($date_start, $date_end))
                                    ->where('isReported', 1);
        $reportsResult = Reported::whereBetween('created_at', array($date_start, $date_end))
                                    ->orderBy('created_at', 'desc');


        $messages = $this->fillMessageDatas($messagesResult);
        $reports = $this->fillReportedDatas($reportsResult);
        $avatars = $this->fillReportedAvatarDatas($avatarsResult);
        $pics = $this->fillReportedPicDatas($picsResult);

        $reportedUsers = array();

        // 被檢舉會員的所有被檢舉資料
        $reportedDataSet = array( 'messages' => $messages['results'],
                                'reports' => $reports['results'],
                                'avatars' => $avatars['results'],
                                'pics' => $pics['results'] );

        foreach($reportedDataSet as $type => $reportedData){
            foreach($reportedData as $data){
                // 被檢舉id的欄位名稱
                switch($type){
                    case 'messages' :
                        $reported_id = $data->from_id;
                        break;
                    case 'reports' :
                        $reported_id = $data->reported_id;
                        break;
                    default :
                        $reported_id = $data->reported_user_id;
                        break;
                }

                if(!array_key_exists($reported_id, $reportedUsers)){
                    $reportedUsers[$reported_id] = array();
                    $reportedUsers[$reported_id]['messages'] =  array();
                    $reportedUsers[$reported_id]['reports'] =  array();
                    $reportedUsers[$reported_id]['avatars'] = array();
                    $reportedUsers[$reported_id]['pics'] =  array();
                    $reportedUsers[$reported_id]['count'] = 0;
                }
                array_push($reportedUsers[$reported_id][$type], $data);
                $reportedUsers[$reported_id]['count']++ ;
                $reportedUsers[$reported_id]['last_login'] = &$users[$reported_id]['last_login'];
            }
        }

        // Merge data of users by user id
        $users = $avatars['users'] + $pics['users'] + $messages['users'] + $reports['users'];

        // order by last_login desc
        uasort($reportedUsers, function($reportedUser, $reportedUser_next) use ($reportedUsers, $users){

            if( $reportedUser['last_login'] < $reportedUser_next['last_login'] )
                return -1;
            else
                return 1;
        });
        foreach($users as $id => &$user){
            $user['isBlocked'] = banned_users::where('member_id', 'like', $id)->get()->first();
            if(!isset($user['isBlocked'])){
                $user['isBlocked'] = \App\Models\BannedUsersImplicitly::select(\DB::raw('id, "隱性" as type'))->where('target', 'like', $id)->get()->first();
            }
        }

        return ['reportedUsers' => isset($search_id) ? $reportedUsers[$search_id] : $reportedUsers,
                'users' => $users];
    }

    public static function countReported($user_id){
        $avatarsResult = ReportedAvatar::where('reported_user_id', $user_id)->get()->count();
        $picsResult = ReportedPic::join('member_pic', 'member_pic.id', '=',
            'reported_pic.reported_pic_id')->where('member_id', $user_id)->get()->count();
        $messagesResult = Message::where('from_id', $user_id)->where('isReported', 1)->count();
        $reportsResult = Reported::where('member_id', $user_id)->get()->count();
        return $avatarsResult + $picsResult + $messagesResult + $reportsResult;
    }

    /**
     * Deletes selected members' messages.
     *
     * @return data set
     */
    public function deleteMessage(Request $request)
    {
        $admin = $this->checkAdmin();
        if(!$admin){
            return false;
        }
        if($request->msg_id == null){
            return null;
        }
        $msg_ids = is_array($request->msg_id) ? $request->msg_id : array($request->msg_id);
        $returnDatas = $this->preData($msg_ids);
        $messages = $returnDatas['msgs'];
        $msg_ids = $returnDatas['msg_ids'];
        if(Message::whereIn('id', $msg_ids)->delete()){
            $template = array(
                "head"   =>"你好，由於您在",
                "body"   =>"的訊息不符站方規定，故已刪除。"
            );
            //return redirect()->back()->withInput()->with('message', '訊息刪除成功');
            $request->session()->put('message', '訊息刪除成功，將會產生通知訊息發送給各發訊的會員，請檢查訊息內容，若無誤請按下送出。');
            $datas = ['admin' => $admin,
                'msgs' => $messages,
                'template' => $template];
            return $datas;
        }
        else{
            //return redirect()->back()->withInput()->withErrors(['出現錯誤，訊息刪除失敗']);
            return false;
        }
    }

    /**
     * Renders messages that needed to be edited.
     *
     * @return data set
     */
    public function renderMessages(Request $request){
        $admin = $this->checkAdmin();
        if(!$admin){
            return false;
        }
        $data = array();
        $data['ids'] = $request->msg_id;
        $data['originalMessage'] = $request->msg;
        $data['admin'] = $admin;
        return $data;
    }

    /**
     * Edits selected members' messages.
     *
     * @return Id
     */
    public function editMessageThenReturnIds(Request $request)
    {
        $message_ids = array();
        foreach ($request->msg_id as $message_id){
            $message = Message::where('id', $message_id)->get()->first();
            $message->content = str_replace($request->originalMessage, $request->replace, $message->content);
            $message->content = str_replace('LINE_ICON', AdminService::$line_icon_html, $message->content);
            $message->content = str_replace('|$lineIcon|', AdminService::$line_icon_html, $message->content);              
            $message->content = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $message->content);
            $message->content = str_replace('|$reportTime|', date("Y-m-d H:i:s"), $message->content);
            $message->content = str_replace('NOW_TIME', date("Y-m-d H:i:s"), $message->content); 
            array_push($message_ids, $message->id);
            $message->save();
        }
        return $message_ids;
    }

    public function sendEditedNotice(Request $request, $message_ids){
        $admin = $this->checkAdmin();
        if(!$admin){
            return false;
        }
        $template = array(
            "head"   =>"你好，由於您在",
            "body"   =>"的訊息不符站方規定，故已修改。"
        );
        $message_ids = is_array($message_ids) ? $message_ids : array($message_ids);
        $returnDatas = $this->preData($message_ids);
        //return redirect()->back()->withInput()->with('message', '訊息刪除成功');
        $request->session()->put('message', '訊息修改成功，將會產生通知訊息發送給各發訊的會員，請檢查訊息內容，若無誤請按下送出。');
        $datas = ['admin' => $admin,
                  'msgs' => $returnDatas['msgs'],
                  'template' => $template];
        return $datas;
    }

    /**
     * Renders target users' datas.
     *
     * @return Datas
     */
    public function preData($message_ids){
        $msg_ids = array();
        $msgs = array();
        foreach ($message_ids as $key => $msg_id){
            array_push($msg_ids, $msg_id);
            $m = Message::select('from_id', 'created_at')->where('id', $msg_id)->get()->first();
            $msgs[$msg_id]['from_id'] = $m->from_id;
            $msgs[$msg_id]['post_time'] = $m->created_at;
            $u = User::select('name')->where('id', $m->from_id)->get()->first();
            $msgs[$msg_id]['name'] = $u->name;
        }
        $datas = ['msg_ids' => $msg_ids,
                  'msgs' => $msgs];
        return $datas;
    }

    public function picPreData($ids, $type){
        if($type == 'pic'){
            $infos = array();
            foreach ($ids as $key => $id){
                // dd($id);
                $p = MemberPic::where('id', $id)->first();
                if(isset($p)){
                    $infos[$id]['post_time'] = $p->created_at;
                    $u = User::where('id', $p->member_id)->first();
                    if(isset($u)){
                        $infos[$id]['user_id'] = $u->id;
                        $infos[$id]['user_name'] = $u->name;
                    }
                }
                else{
                    continue;
                }
            }
            
            $datas = ['pic_ids' => $ids,
                'infos' => $infos];
// dd($datas);
            return $datas;
        }
        else if($type == 'avatar'){
            $infos = array();
            foreach ($ids as $key => $id){
                $infos[$id]['post_time'] = '';
                $u = User::select('name')->where('id', $id)->first();
                $infos[$id]['user_id'] = $id;
                $infos[$id]['user_name'] = $u->name;
            }
            $datas = ['pic_ids' => $ids,
                'infos' => $infos];
            return $datas;
        }
        return false;
    }
    public function deletePicture(Request $request)
    {
        $admin = $this->checkAdmin();

        if(!$admin){
            return false;
        }
        if($request->pic_id == null && $request->avatar_id == null){
            return null;
        }


        $pic_ids = is_array($request->pic_id) ? $request->pic_id : array($request->pic_id);

        $avatar_ids = is_array($request->avatar_id) ? $request->avatar_id : array($request->avatar_id);
        if($pic_ids[0] != null){

            $returnDatas1 = $this->picPreData($pic_ids, 'pic');
            $picInfos = $returnDatas1['infos'];
            $pic_ids = $returnDatas1['pic_ids'];

            if(MemberPic::whereIn('id', $pic_ids)->delete()){

            }
            else{
                //return redirect()->back()->withInput()->withErrors(['出現錯誤，訊息刪除失敗']);
                return false;
            }
        }
        if($avatar_ids[0] != null){
            $returnDatas2 = $this->picPreData($avatar_ids, 'avatar');
            $avatarInfos = $returnDatas2['infos'];
            $avatar_ids = $returnDatas2['pic_ids'];
            foreach( $avatar_ids as $user_id){
                $u = UserMeta::select('id', 'pic', 'updated_at')->where('user_id', $user_id)->get()->first();

                // 標記刪除
                \App\Models\AvatarDeleted::insert([
                    'user_id'     => $user_id,
                    'operator'    => Auth::user()->id,
                    'pic'         => $u->pic ?? "",
                    'created_at'  => now(),
                    'updated_at'  => now(),
                    'uploaded_at' => $u->updated_at,
                ]);

                // 操作紀錄
                \App\Models\AdminPicturesSimilarActionLog::insert([
                    'operator_id'   => Auth::user()->id,
                    'operator_role' => Auth::user()->roles->first()->id,
                    'target_id'     => $user_id,
                    'act'           => '刪除頭像',
                    'pic'           => $u->pic,
                    'ip'            => $request->ip(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
                $u->pic = null;
                $u->save();
            }
        }
        $template = [
            "pic" => [
                "head"   =>"您好，由於您在",
                "body"   =>"上傳的照片不適合網站主旨，故已刪除。請重新上傳。如有疑慮請與站長聯絡。"
            ],
            "avatar" => [
                "head"   =>"您好，由於您",
                "body"   =>"的大頭照不適合網站主旨，故已刪除。請重新上傳。如有疑慮請與站長聯絡。"
            ]
        ];
        //return redirect()->back()->withInput()->with('message', '訊息刪除成功');
        $request->session()->put('message', '照片刪除成功，將會產生通知訊息發送給各發訊的會員，請檢查訊息內容，若無誤請按下送出。');
        $datas = ['admin' => $admin,
            'msgs' => isset($picInfos) ? $picInfos : 0,
            'msgs2' => isset($avatarInfos) ? $avatarInfos : 0,
            'template' => $template];
        return $datas;
    }

    public function hidePicture(Request $request)
    {
        $admin = $this->checkAdmin();
        if(!$admin){
            return false;
        }
        if($request->pic_id == null && $request->avatar_id == null){
            return null;
        }
        $pic_ids = is_array($request->pic_id) ? $request->pic_id : array($request->pic_id);
        $avatar_ids = is_array($request->avatar_id) ? $request->avatar_id : array($request->avatar_id);
        if($pic_ids[0] != null){
            $returnDatas1 = $this->picPreData($pic_ids, 'pic');
            $picInfos = $returnDatas1['infos'];
            $pic_ids = $returnDatas1['pic_ids'];
            foreach( $pic_ids as $pic){
                $u = MemberPic::select('id', 'isHidden')->where('id', $pic)->get()->first();
                $u->isHidden = 1;
                $u->save();
            }
        }
        if($avatar_ids[0] != null){
            $returnDatas2 = $this->picPreData($avatar_ids, 'avatar');
            $avatarInfos = $returnDatas2['infos'];
            $avatar_ids = $returnDatas2['pic_ids'];
            foreach( $avatar_ids as $user_id){
                $u = UserMeta::select('id', 'isAvatarHidden')->where('user_id', $user_id)->get()->first();
                $u->isAvatarHidden = 1;
                $u->save();
            }
        }
        $template = [
            "pic" => [
                "head"   =>"您好，由於您在",
                "body"   =>"上傳的照片不適合網站主旨，故已隱藏。請重新上傳。如有疑慮請與站長聯絡。"
            ],
            "avatar" => [
                "head"   =>"您好，由於您",
                "body"   =>"的大頭照不適合網站主旨，故已隱藏。請重新上傳。如有疑慮請與站長聯絡。"
            ]
        ];
        //return redirect()->back()->withInput()->with('message', '訊息刪除成功');
        $request->session()->put('message', '照片隱藏成功，將會產生通知訊息發送給各發訊的會員，請檢查訊息內容，若無誤請按下送出。');
        $datas = ['admin' => $admin,
            'msgs' => isset($picInfos) ? $picInfos : 0,
            'msgs2' => isset($avatarInfos) ? $avatarInfos : 0,
            'template' => $template];
        return $datas;
    }

    public function deHidePicture(Request $request)
    {
        $admin = $this->checkAdmin();
        if(!$admin){
            return false;
        }
        if($request->pic_id == null && $request->avatar_id == null){
            return null;
        }
        $pic_ids = is_array($request->pic_id) ? $request->pic_id : array($request->pic_id);
        $avatar_ids = is_array($request->avatar_id) ? $request->avatar_id : array($request->avatar_id);
        if($pic_ids[0] != null){
            $returnDatas1 = $this->picPreData($pic_ids, 'pic');
            $picInfos = $returnDatas1['infos'];
            $pic_ids = $returnDatas1['pic_ids'];
            foreach( $pic_ids as $pic){
                $u = MemberPic::select('id', 'isHidden')->where('id', $pic)->get()->first();
                $u->isHidden = 0;
                $u->save();
            }
        }
        if($avatar_ids[0] != null){
            $returnDatas2 = $this->picPreData($avatar_ids, 'avatar');
            $avatarInfos = $returnDatas2['infos'];
            $avatar_ids = $returnDatas2['pic_ids'];
            foreach( $avatar_ids as $user_id){
                $u = UserMeta::select('id', 'isAvatarHidden')->where('user_id', $user_id)->get()->first();
                $u->isAvatarHidden = 0;
                $u->save();
            }
        }
        $template = [
            "pic" => [
                "head"   =>"您好，您在",
                "body"   =>"上傳的照片已解除隱藏。"
            ],
            "avatar" => [
                "head"   =>"您好，您",
                "body"   =>"的大頭照已解除隱藏。"
            ]
        ];
        //return redirect()->back()->withInput()->with('message', '訊息刪除成功');
        $request->session()->put('message', '照片解除隱藏成功。');
        $datas = ['admin' => $admin,
            'msgs' => isset($picInfos) ? $picInfos : 0,
            'msgs2' => isset($avatarInfos) ? $avatarInfos : 0,
            'template' => $template];
        return $datas;
    }

}
