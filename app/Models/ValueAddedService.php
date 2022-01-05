<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;

class ValueAddedService extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_value_added_service';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'service_name',
        'business_id',
        'order_id',
        'txn_id',
        'amount',
        'expiry',
        'active',
        'payment',
        'created_at'
    ];

    /*
    |--------------------------------------------------------------------------
    | relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class, 'member_id', 'id');
    }

    public static function getData($member_id,$service_name)
    {
        return ValueAddedService::where('member_id', $member_id)->where('service_name', $service_name)->where('active', 1)->orderBy('created_at', 'desc')->first();
    }

    public static function status($member_id,$service_name)
    {
        $status = ValueAddedService::where('member_id', $member_id)->where('service_name', $service_name)->first();

        if($status == NULL) return 0;

        if($status->active==1){
            if($status->expiry == '0000-00-00 00:00:00' || $status->expiry >= Carbon::now()){
                return 1;
            }
        }
        return 0;
    }

    public static function isPaidOnePayment($member_id,$service_name)
    {
        $status = ValueAddedService::where('member_id', $member_id)->where('service_name', $service_name)->where('payment','like','one_%')->first();

        if($status == NULL) return 0;

        if($status->active==1){
            if($status->expiry >= Carbon::now()){
                return 1;
            }
        }
        return 0;
    }

    public static function isPaidCancelNotOnePayment($member_id,$service_name)
    {
        $status = ValueAddedService::where('member_id', $member_id)->where('service_name', $service_name)->where('payment','like','cc_%')->first();

        if($status == NULL) return 0;

        if($status->active==1){
            if($status->expiry >= Carbon::now()){
                return 1;
            }
        }
        return 0;
    }

    public static function upgrade($member_id, $service_name, $business_id, $order_id, $amount, $txn_id, $active, $payment = null, $remain_days=0)
    {
        $valueAddedServiceData = ValueAddedService::findByIdAndServiceNameWithDateDesc($member_id,$service_name);

        if(!isset($valueAddedServiceData)){

            //新建資料從當前計算
            if($payment=='one_quarter_payment'){
                $expiry = Carbon::now()->addMonthsNoOverflow(3);
            }else if($payment=='one_month_payment'){
                $expiry = Carbon::now()->addMonthsNoOverflow(1);
            }

            $valueAddedService = new ValueAddedService();
            $valueAddedService->member_id = $member_id;
            $valueAddedService->service_name = $service_name;
            $valueAddedService->txn_id = $txn_id;
            $valueAddedService->business_id = $business_id;
            $valueAddedService->order_id = $order_id;
            $valueAddedService->amount = $amount;
            $valueAddedService->active = $active;
            $valueAddedService->payment = $payment;
            $valueAddedService->remain_days = $remain_days;

            //單次付款到期日
            if(isset($expiry)){
                $valueAddedService->expiry = $expiry;
            }else{
                $valueAddedService->expiry = '0000-00-00 00:00:00';
            }

            $valueAddedService->created_at = Carbon::now();
            $valueAddedService->save();

        }else{
            // 檢查重複升級
            if(isset(ValueAddedServiceLog::getLatestLog($member_id)->order_id) && ValueAddedServiceLog::getLatestLog($member_id)->order_id == $order_id){
                ValueAddedServiceLog::addToLog($member_id, $service_name,'Upgrade duplicated.', $order_id, $txn_id, 0);
                return 0;
            }
            // 舊資料更新 從原expiry計算
            if($payment == 'one_quarter_payment'){
                if($valueAddedServiceData->expiry < Carbon::now()) {
                    $expiry = Carbon::now()->addMonthsNoOverflow(3);
                }else{
                    $expiry = Carbon::createFromFormat('Y-m-d H:i:s', $valueAddedServiceData->expiry)->addMonthsNoOverflow(3);
                }
            }else if($payment == 'one_month_payment'){
                if($valueAddedServiceData->expiry < Carbon::now()) {
                    $expiry = Carbon::now()->addMonths(1);
                }else{
                    $expiry = Carbon::createFromFormat('Y-m-d H:i:s', $valueAddedServiceData->expiry)->addMonthsNoOverflow(1);
                }
            }

            $valueAddedServiceData->order_id = $order_id;
            $valueAddedServiceData->service_name = $service_name;
            $valueAddedServiceData->txn_id = $txn_id;
            $valueAddedServiceData->business_id = $business_id;
            $valueAddedServiceData->amount = $amount;
            $valueAddedServiceData->active = $active;
            $valueAddedServiceData->payment = $payment;
            $valueAddedServiceData->remain_days = $remain_days;

            //單次付款到期日
            if(isset($expiry)){
                $valueAddedServiceData->expiry = $expiry;
            }else{
                $valueAddedServiceData->expiry = '0000-00-00 00:00:00';
            }

            $valueAddedServiceData->save();

        }


        if($service_name=='hideOnline'){
            $HideOnlineData = \App\Models\hideOnlineData::where('user_id', $member_id)->where('deleted_at', null)->get()->first();
            User::where('id',$member_id)->update(['is_hide_online' => 1, 'hide_online_time' => $HideOnlineData->login_time]);
        }

        ValueAddedServiceLog::addToLog($member_id, $service_name,'Upgrade, payment: ' . $payment . ', service: ' . $service_name, $order_id, $txn_id, 0);
    }

    public static function findByIdAndServiceNameWithDateDesc($member_id,$service_name) {
        return ValueAddedService::where('member_id', $member_id)->where('service_name', $service_name)->orderBy('created_at', 'desc')->first();
    }

    public static function cancel($member_id, $service_name)
    {
        $curUser = User::findById($member_id);
        $user = ValueAddedService::where('member_id', $member_id)
            ->where('service_name', $service_name)
            ->orderBy('created_at', 'desc')->get();

        if($user[0]->expiry == '0000-00-00 00:00:00'){
            // 未設定到期日區間
            // 取得現在時間
            $now = \Carbon\Carbon::now();
            // 從最近一筆 VIP 資料取得資料變更日期
            $latestUpdatedAt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user[0]->updated_at);
            // 確實複製變數，而不單純用 =，避免出現只將記憶體位置指向 $daysDiff, $baseDate，
            // 造成兩個變數實際上指向同一物件的問題發生
            // 將現在時間做為基準日
            $baseDate = clone $now;
            $daysDiff = clone $now;
            $daysDiff = $daysDiff->diffInDays($latestUpdatedAt);
            // 依照付款類形計算不同的取消當下距預計下一週期扣款日的天數
            if($user[0]->payment == 'cc_quarterly_payment'){
                $periodRemained = 92 - ($daysDiff % 92);
            }else {
                $periodRemained = 30 - ($daysDiff % 30);
            }
            // 基準日加上得出的天數再加 1 (不加 1 到期日會少一天)，即為取消後的到期日
            $expiryDate = $baseDate->addDays($periodRemained + 1);
            /**
             * Debugging codes.
             * $output = new \Symfony\Component\Console\Output\ConsoleOutput();
             * $output->writeln('$daysDiff: ' . $daysDiff);
             * $output->writeln('$periodRemained: ' . $periodRemained);
             * $output->writeln('$expiryDate: ' . $expiryDate);
             */
            // 如果是使用綠界付費，且取消日距預計下次扣款日小於七天，則到期日再加一個週期
            // 3137610: 正式商店編號
            // 2000132: 測試商店編號
            if(($user[0]->business_id == '3137610' || $user[0]->business_id == '2000132') && $now->diffInDays($expiryDate) <= 7) {
                // addMonthsNoOverflow(): 避免如 10/31 加了一個月後變 12/01 的情形出現
                if($user[0]->payment=='cc_quarterly_payment'){
                    $expiryDate = $expiryDate->addMonthsNoOverflow(3);
                }else {
                    $expiryDate = $expiryDate->addMonthNoOverflow(1);
                }
                if($service_name == "hideOnline"){
                    $str = $curUser->name . ' 您好，您已取消本站 付費隱藏 續費。但由於您的扣款時間是每月'. $latestUpdatedAt->day .'號，取消時間低於七個工作天，作業不及。所以本次還是會正常扣款，下一週期就會停止扣款。造成不便敬請見諒。';
                }
            }

            if(!\App::environment('local')) {
                //訂單更新到期日
                $order = Order::where('order_id', $user[0]->order_id)->get()->first();
                if (strpos($user[0]->order_id, 'SG') !== false && isset($order)) {

                    //此訂單如有剩餘天數則加回到期日
                    if ($order->remain_days > 0) {
                        $expiryDate = $expiryDate->addDay($order->remain_days);
                        Order::where('order_id', $user[0]->order_id)->update(['order_expire_date' => $expiryDate]);
                    }

                } else {

                    Order::addEcPayOrder($user[0]->order_id, $expiryDate);

                }
            }else {
                //測試機更新剩餘天數至到期日
                //此測試訂單如有剩餘天數則加回到期日
                //上正式機前這段請移除
                // if ($user[0]->remain_days > 0) {
                //     $expiryDate = $expiryDate->addDays($user[0]->remain_days);
                // }
            }

            foreach ($user as $u){
                $u->expiry = $expiryDate->startOfDay()->toDateTimeString();
                $u->save();
            }

            ValueAddedServiceLog::addToLog($member_id, $service_name,'Cancelled, expiry: ' . $expiryDate, $user[0]->order_id, $user[0]->txn_id,0);

            return [true, "str"  => $str ?? null];
        }
    }

    public static function removeValueAddedService($member_id, $service_name)
    {
        if($service_name=='hideOnline'){
            User::where('id',$member_id)->update(['is_hide_online' => 0]);
        }

        return ValueAddedService::where('member_id', $member_id)
            ->where('service_name', $service_name)
            ->update(array('active' => 0, 'expiry' => null));
    }

    public static function addHideOnlineData($member_id)
    {
        $user = User::findById($member_id);
        $bannedUsers = \App\Services\UserService::getBannedId();
        //存快照
        $register_time = $user->created_at;
        $login_time = Carbon::now();
        /*每周平均上線次數*/
        $datetime1 = new \DateTime(now());
        $datetime2 = new \DateTime($user->created_at);
        $diffDays = $datetime1->diff($datetime2)->days;
        $week = ceil($diffDays / 7);
        if ($week == 0) {
            $login_times_per_week = 0;
        } else {
            $login_times_per_week = round(($user->login_times / $week), 0);
        }

        /*收藏會員次數*/
        $fav_count = MemberFav::select('member_fav.*')
            ->join('users', 'users.id', '=', 'member_fav.member_fav_id')
            ->whereNotNull('users.id')
            ->where('users.accountStatus', 1)
            ->where('users.account_status_admin', 1)
            ->where('member_fav.member_id', $member_id)
            ->whereNotIn('member_fav.member_fav_id',$bannedUsers)
            ->get()->count();

        /*被收藏次數*/
        $be_fav_count = MemberFav::select('member_fav.*')
            ->join('users', 'users.id', '=', 'member_fav.member_id')
            ->whereNotNull('users.id')
            ->where('users.accountStatus', 1)
            ->where('users.account_status_admin', 1)
            ->where('member_fav.member_fav_id', $member_id)
            ->whereNotIn('member_fav.member_id',$bannedUsers)
            ->get()->count();

//        $be_fav_count = MemberFav::where('member_fav_id', $user->id)->get()->count();
//        $fav_count = MemberFav::where('member_id', $user->id)->get()->count();
        $tip_count = Tip::where('to_id', $user->id)->get()->count();
        /*七天前*/
        $date = date('Y-m-d H:m:s', strtotime('-7 days'));
        /*發信＆回信次數統計*/
        $messages_all = Message::select('id', 'to_id', 'from_id', 'created_at')->where('to_id', $user->id)->orwhere('from_id', $user->id)->orderBy('id')->get();
        $countInfo['message_count'] = 0;
        $countInfo['message_reply_count'] = 0;
        $countInfo['message_reply_count_7'] = 0;
        $send = [];
        $receive = [];
        foreach ($messages_all as $message) {
            //user_id主動第一次發信
            if ($message->from_id == $user->id && array_get($send, $message->to_id) < $message->id) {
                $send[$message->to_id][] = $message->id;
            }
            //紀錄每個帳號第一次發信給uid
            if ($message->to_id == $user->id && array_get($receive, $message->from_id) < $message->id) {
                $receive[$message->from_id][] = $message->id;
            }
            if (!is_null(array_get($receive, $message->to_id))) {
                $countInfo['message_reply_count'] += 1;
                if ($message->created_at >= $date) {
                    //計算七天內回信次數
                    $countInfo['message_reply_count_7'] += 1;
                }
            }
        }
        $countInfo['message_count'] = count($send);

        $messages_7days = Message::select('id', 'to_id', 'from_id', 'created_at')->whereRaw('(to_id =' . $user->id . ' OR from_id=' . $user->id . ')')->where('created_at', '>=', $date)->orderBy('id')->get();
        $countInfo['message_count_7'] = 0;
        $send = [];
        foreach ($messages_7days as $message) {
            //七天內uid主動第一次發信
            if ($message->from_id == $user->id && array_get($send, $message->to_id) < $message->id) {
                $send[$message->to_id][] = $message->id;
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
        $date_start = date("Y-m-d", strtotime("-6 days", strtotime(date('Y-m-d'))));
        $date_end = date('Y-m-d');

        /**
         * 效能調整：使用左結合以大幅降低處理時間
         *
         * @author LZong <lzong.tw@gmail.com>
         */
        $query = Message::select('users.email', 'users.name', 'users.title', 'users.engroup', 'users.created_at', 'users.last_login', 'message.id', 'message.from_id', 'message.content', 'user_meta.about')
            ->join('users', 'message.from_id', '=', 'users.id')
            ->join('user_meta', 'message.from_id', '=', 'user_meta.user_id')
            ->leftJoin('banned_users as b1', 'b1.member_id', '=', 'message.from_id')
            ->leftJoin('banned_users_implicitly as b3', 'b3.target', '=', 'message.from_id')
            ->leftJoin('warned_users as wu', function ($join) {
                $join->on('wu.member_id', '=', 'message.from_id')
                    ->where('wu.expire_date', '>=', Carbon::now())
                    ->orWhere('wu.expire_date', null);
            })
            ->whereNull('b1.member_id')
            ->whereNull('b3.target')
            ->whereNull('wu.member_id')
            ->where('users.accountStatus', 1)
            ->where('users.account_status_admin', 1)
            ->where(function ($query) use ($date_start, $date_end) {
                $query->where('message.from_id', '<>', 1049)
                    ->where('message.sys_notice','<>', 1)
                    ->whereBetween('message.created_at', array($date_start . ' 00:00', $date_end . ' 23:59'));
            });
        $query->where('users.email', $user->email);
        $results_a = $query->distinct('message.from_id')->get();

        if ($results_a != null) {
            $msg = array();
            $from_content = array();
            $user_similar_msg = array();

            $messages = Message::select('id', 'content', 'created_at')
                ->where('from_id', $user->id)
                ->where('sys_notice','<>',1)
                ->whereBetween('created_at', array($date_start . ' 00:00', $date_end . ' 23:59'))
                ->orderBy('created_at', 'desc')
                ->take(100)
                ->get();

            foreach ($messages as $row) {
                array_push($msg, array('id' => $row->id, 'content' => $row->content, 'created_at' => $row->created_at));
            }

            array_push($from_content, array('msg' => $msg));

            $unique_id = array(); //過濾重複ID用
            //比對訊息
            foreach ($from_content as $data) {
                foreach ($data['msg'] as $word1) {
                    foreach ($data['msg'] as $word2) {
                        if ($word1['created_at'] != $word2['created_at']) {
                            similar_text($word1['content'], $word2['content'], $percent);
                            if ($percent >= 70) {
                                if (!in_array($word1['id'], $unique_id)) {
                                    array_push($unique_id, $word1['id']);
                                    array_push($user_similar_msg, array($word1['id'], $word1['content'], $word1['created_at'], $percent));
                                }
                            }
                        }
                    }
                }
            }
        }
        $message_percent_7 = count($user_similar_msg) > 0 ? round((count($user_similar_msg) / count($messages)) * 100) . '%' : '0%';
        /*瀏覽其他會員次數*/
        $visit_other_count = Visited::where('member_id', $user->id)->distinct('visited_id')->count();
        /*被瀏覽次數*/
        $be_visit_other_count = Visited::where('visited_id', $user->id)->distinct('member_id')->count();
        /*過去7天瀏覽其他會員次數*/
        $visit_other_count_7 = Visited::where('member_id', $user->id)->where('created_at', '>=', $date)->distinct('visited_id')->count();
        /*過去7天被瀏覽次數*/
        $be_visit_other_count_7 = Visited::where('visited_id', $user->id)->where('created_at', '>=', $date)->distinct('member_id')->count();

        /*此會員封鎖多少其他會員*/

        $blocked_other_count = Blocked::with(['blocked_user'])
            ->join('users', 'users.id', '=', 'blocked.blocked_id')
            ->where('blocked.member_id', $user->id)
            ->whereNotIn('blocked.blocked_id',$bannedUsers)
            ->whereNotNull('users.id')
            ->where('users.accountStatus', 1)
            ->where('users.account_status_admin', 1)
            ->count();

        /*此會員被多少會員封鎖*/
        $be_blocked_other_count = Blocked::with(['blocked_user'])
            ->join('users', 'users.id', '=', 'blocked.member_id')
            ->where('blocked.blocked_id', $user->id)
            ->whereNotIn('blocked.member_id',$bannedUsers)
            ->whereNotNull('users.id')
            ->where('users.accountStatus', 1)
            ->where('users.account_status_admin', 1)
            ->count();


        //寫入hide_online_data

        //先刪後增 softDelete
        hideOnlineData::where('user_id', $user->id)->delete();
        hideOnlineData::insert([
            'user_id' => $user->id,
            'created_at' => Carbon::now(),
            'register_time' => $register_time,
            'login_time' => $login_time,
            'login_times_per_week' => $login_times_per_week,
            'be_fav_count' => $be_fav_count,
            'fav_count' => $fav_count,
            'tip_count' => $tip_count,
            'message_count' => $message_count,
            'message_count_7' => $message_count_7,
            'message_reply_count' => $message_reply_count,
            'message_reply_count_7' => $message_reply_count_7,
            'message_percent_7' => $message_percent_7,
            'visit_other_count' => $visit_other_count,
            'visit_other_count_7' => $visit_other_count_7,
            'be_visit_other_count' => $be_visit_other_count,
            'be_visit_other_count_7' => $be_visit_other_count_7,
            'blocked_other_count' => $blocked_other_count,
            'be_blocked_other_count' => $be_blocked_other_count
        ]);
    }

}
