<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderLog;
use App\Models\OrderPayFailNotify;
use App\Models\User;
use App\Models\ValueAddedService;
use App\Models\ValueAddedServiceLog;
use App\Models\Vip;
use App\Models\VipLog;
use App\Services\VipLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Yajra\DataTables\DataTablesServiceProvider;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends \App\Http\Controllers\BaseController
{
    public function __construct(VipLogService $logService)
    {
        $this->logService = $logService;
    }

    //
    public function index()
    {
        return view('admin.stats.order');
    }

    public function getOrderData(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::leftJoin('users','users.id','order.user_id')
                ->where('order.order_id', '<>', '')
                ->select([
                'order.*',
                'users.email'
            ])
            ;
            return Datatables::eloquent($data)->make(true);
        }
    }

    public function getOrderDataByUserId($user_id)
    {
        if ($user_id != '') {
            $data = Order::where('user_id', $user_id)
                ->where('order.order_id', '<>', '')
            ;
            return Datatables::eloquent($data)->make(true);
        }
    }

    public function getOrderLogListByOrderId(Request $request)
    {
        if ($request->ajax()) {
            $data = OrderLog::where('order_id',  $request->order_id)
                ->orderBy('created_at','desc')
                ->get();
            $html='';
            if(count($data)>0) {
                foreach ($data as $row) {
                    $html .= '<tr><td>' . $row->created_at . '</td><td>' . $row->content . '</td>';
                }
            }else{
                $html .= '<tr><td colspan="2">尚無歷程</td></tr>';
            }
            return response()->json(array(
                'detail' => $html
            ), 200);
        }
    }

//    public function orderGeneratorById(Request $request){
//        //VIP
//        $uid = $request->input('uid');
//        $user = User::select('id')->where(function($query) use ($uid) {
//            $query->where('id', $uid)
//                ->orWhere('email', $uid);
//        })->first();
//            $member_vip_log = VipLog::select('member_vip_log.*', 'users.email')->from('member_vip_log')
//                ->leftJoin('users', 'users.id', 'member_vip_log.member_id')
//                ->whereNotNull('users.id')
//                ->where('member_vip_log.member_id', $user->id)
//                ->OrderBy('member_vip_log.created_at', 'ASC')
//                ->get();
//
//            order::addEcPayOrder('SG1613810631', null);
//        $email = '';
//        if(count($member_vip_log)==0 && $user){
//            return back()->with('message', '查無此用戶紀錄');
//        }else{
//            foreach ($member_vip_log as $row) {
//                $email = $row->email;
//
//                if (strpos($row->member_name, 'order id') !== false && $row->action == 1) {
//                    //get order id
//                    $pieces = explode(' ', $row->member_name);
//                    $order_id = str_replace(',', '', $pieces[3]);
//                    //檢查沒有訂單再生成
//                    $checkOrder = Order::where('order_id', $order_id)->first();
//                    if (!isset($checkOrder)) {
//                        if (strpos($order_id, 'SG') !== false) {
//                            Order::addEcPayOrder($order_id, null);
//                        } else if (strpos($order_id, 'SG') === false && strlen($order_id) >= 10) {
//                            Order::addOtherOrder($order_id, $row->member_id, $row->created_at);
//                        }
//
//                        $prevID = $row->id;
//                        $prevUserID = $row->member_id;
//                        $prevOrderID = $order_id;
//                    }
//                }
//
//                if (isset($prevID) && $row->id > $prevID && isset($prevUserID) && $row->member_id == $prevUserID && $row->action == 0 && isset($prevOrderID) && $prevOrderID != '') {
//                    $currentOrder = Order::where('order_id', $prevOrderID)->first();
//                    if (strpos(strtolower($row->member_name), 'cancel') !== false && isset($currentOrder)) {
//                        //舊訂單自動判斷到期日
//                        if (strpos($prevOrderID, 'SG') === false) {
//                            $order_date = $currentOrder->order_date;
//                            //藍新 從訂單日推演到期日
//                            $start_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $order_date);
//                            $cancel_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at);
//
//                            $end_date = $cancel_date;
//
//                            $checkDays = $start_date->diffInDays($cancel_date);
//                            $payTimes = ceil($checkDays / 30); //無條件進位
//
//                            if ($payTimes > 0) {
//                                $dateArray = array();
//                                $dd = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $start_date)->toDateTimeString();
//                                for ($x = 0; $x <= $payTimes; $x++) {
//                                    $dd = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dd)->toDateTimeString();
//                                    $current_dd = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dd);
//                                    $diffDays = $current_dd->diffInDays($cancel_date);
//                                    if ($diffDays <= 7 && $end_date <= '2020-12-31 00:00:00') {
//                                        $end_date = $current_dd->addMonthNoOverflow(1);
//                                    } elseif ($diffDays > 7 && $diffDays <= 30 && $end_date <= '2020-12-31 00:00:00') {
//                                        $end_date = $current_dd;
//                                    }
//
//                                    if ($dd < $cancel_date) {
//                                        array_push($dateArray, array($dd));
//                                    }
//                                    $dd = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dd);
//                                    $dd = $dd->addMonthNoOverflow(1);
//                                }
//                                Order::where('order_id', $prevOrderID)->update(['pay_date' => json_encode($dateArray), 'order_expire_date' => $end_date]);
//                            } else if (substr($order_date, 0, 10) == substr($row->created_at, 0, 10)) {
//                                $dateArray = array();
//                                array_push($dateArray, array($start_date->toDateTimeString()));
//                                Order::where('order_id', $prevOrderID)->update(['pay_date' => json_encode($dateArray), 'order_expire_date' => $start_date->addMonthNoOverflow(1)]);
//
//                            }
//
//                            $prevID = '';
//                            $prevUserID = '';
//                            $prevOrderID = '';
//                        }
//                    }
//                }
//            }
//        }
//
//        //加值服務訂單
//        $ValueAddedServiceLog = ValueAddedServiceLog::where('member_id', $user->id)->distinct('order_id')->get();
//        if($ValueAddedServiceLog){
//            foreach($ValueAddedServiceLog as $row){
//                Order::addEcPayOrder($row->order_id, null);
//            }
//        }
//
//        //檢查藍新未取消的訂單紀錄 取消最終日統一為2020-12.31
//        $checkOrderByNull = Order::where('order_expire_date', null)->get();
//        if($checkOrderByNull) {
//            foreach ($checkOrderByNull as $row) {
//                $start_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->order_date);
//                if(strpos($row->order_id, 'SG') === false){
//                    $dateArray = array();
//                    //藍新 從訂單日推演到期日
//                    $cancel_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', '2020-12-31 00:00:00');
//
//                    $end_date = $cancel_date;
//
//                    $checkDays = $start_date->diffInDays($cancel_date);
//                    $payTimes = ceil($checkDays / 30)-1; //無條件進位
//
//                    if ($payTimes > 0) {
//                        $dd = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $start_date);
//                        for ($x = 0; $x <= $payTimes; $x++) {
//                            $current_dd = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dd);
//                            $dd = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dd)->toDateTimeString();
//
//                            $diffDays = $current_dd->diffInDays($cancel_date);
//                            if($diffDays <= 7 && $end_date <= '2020-12-31 00:00:00'){
//                                $end_date = $current_dd->addMonthNoOverflow(1);
//                            }elseif($diffDays>7 && $diffDays<=30 && $end_date <= '2020-12-31 00:00:00'){
//                                $end_date = $current_dd;
//                            }
//
//                            if ($dd < $cancel_date) {
//                                array_push($dateArray, array($dd));
//                            }
//                            $dd = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dd);
//                            $dd = $dd->addMonthNoOverflow(1);
//                        }
//                        Order::where('order_id', $row->order_id)->update(['pay_date' => json_encode($dateArray), 'order_expire_date' => $end_date]);
//                    }
//                }
//            }
//        }
//        return redirect('admin/order#'.$email)->with('message', '完成');
//    }

    public function orderEcPayCheck(Request $request){
        $order_id = $request->input('order_id');
        if(str_contains($order_id, 'TIP')){
            return back()->with('message', '車馬費訂單不適用此查詢系統');
        }
        //正式綠界訂單查詢
        $ecpay = new \App\Services\ECPay_AllInOne();
        $ecpay->MerchantID = Config::get('ecpay.payment.MerchantID');
        $ecpay->ServiceURL = Config::get('ecpay.payment.OrderQueryURL');
        $ecpay->HashIV = Config::get('ecpay.payment.HashIV');
        $ecpay->HashKey = Config::get('ecpay.payment.HashKey');
        $ecpay->Query = [
            'MerchantTradeNo' => $order_id,
            'TimeStamp' => time()
        ];
        $paymentData = $ecpay->QueryTradeInfo();
        
        if($paymentData['TradeStatus']==10200047){
            return back()->with('message','查無此訂單');
        }

        $ecpay->ServiceURL = Config::get('ecpay.payment.ServiceURL');//定期定額查詢
        $paymentPeriodInfo = $ecpay->QueryPeriodCreditCardTradeInfo();

        $result='';

        if($paymentData['TradeStatus']==1){

            $transactionType='';
            if($paymentData['PaymentType'] == 'Credit_CreditCard') {
                $transactionType = 'CREDIT'; //信用卡
            }
            elseif(str_contains($paymentData['PaymentType'], 'ATM')) {
                $transactionType = 'ATM'; //ATM
            }
            elseif($paymentData['PaymentType'] == 'BARCODE_BARCODE') {
                $transactionType = 'BARCODE'; //超商條碼
            }
            elseif ($paymentData['PaymentType'] == 'CVS_CVS') {
                $transactionType = 'CVS'; //超商代號
            }
            else {
                $transactionType = $paymentData['PaymentType']; //寫入回傳的PaymentType
            }

            //check order table
            $order = Order::where('order_id', $order_id)->first();
            if(!$order){
                Order::addEcPayOrder($order_id);
                $result .= '新增訂單資料<br>';
            }

            if(isset($paymentPeriodInfo) && $paymentPeriodInfo['ExecLog'] != ''){
                $last = last($paymentPeriodInfo['ExecLog']);
                $lastProcessDate = str_replace('%20', ' ', $last['process_date']);
                $lastProcessDate = Carbon::createFromFormat('Y/m/d H:i:s', $lastProcessDate);
                //付款日期有差異時更新訂單
                $currentOrder = Order::where('order_id', $order_id)->first();
                $current_order_pay_date = last(json_decode($currentOrder->pay_date));
                $current_order_pay_fail = '';
                if($currentOrder->pay_fail != ''){
                    $current_order_pay_fail = last(json_decode($currentOrder->pay_fail));
                }
                if($currentOrder &&
                    ((Carbon::parse($lastProcessDate)->toDateTimeString() != Carbon::parse($current_order_pay_date[0])->toDateTimeString() &&
                            (($currentOrder->pay_fail != '' && Carbon::parse($lastProcessDate)->toDateTimeString() != Carbon::parse($current_order_pay_fail[0])->toDateTimeString()) || $currentOrder->pay_fail=='')
                    ) ||
                    $currentOrder->ExecStatus == '' )
                ) {
                    Order::updateEcPayOrder($order_id);
                    $result .= '更新訂單資訊<br>';
                }
            }

            //check service
            if($paymentData['CustomField4']=='hideOnline'){
                //hideOnline
                $updateHideOnline='';
                //check user hideOnline status
                $hideOnline = ValueAddedService::where('service_name', $paymentData['CustomField4'])
                    ->where('member_id',$paymentData['CustomField1'])
                    ->where('order_id', $order_id)
                    ->first();

                if($hideOnline && $hideOnline->active==1){
                    $result .= '該會員當前已有hideOnline<br>';
                    if(str_contains($paymentData['CustomField3'], 'cc')) {
                        if ($paymentPeriodInfo['ExecStatus'] == 0) {
                            $thisOrder = Order::findByOrderId($order_id);
                            $order_expire_date = $thisOrder->order_expire_date;
                            ValueAddedService::where('member_id', $paymentData['CustomField1'])
                                ->where('service_name', $paymentData['CustomField4'])
                                ->update(['expiry' => $order_expire_date]);
                            ValueAddedServiceLog::addToLog($paymentData['CustomField1'], $paymentData['CustomField4'], 'Auto cancel, last process date: ' . Carbon::now() . ' 經由後台反查訂單取消，hideOnline 到期日更新', $order_id, '', 0);
                            $result .= '定期定額取消，hideOnline 到期日更新<br>';
                            OrderLog::addToLog($paymentData['CustomField1'], $order_id, '定期定額取消, hideOnline 到期日更新');
                        } else if ($paymentPeriodInfo['ExecStatus'] == 1) {
                            $last = last($paymentPeriodInfo['ExecLog']);
                            //最後一次扣款失敗
                            if ($last['RtnCode'] != 1) {
                                ValueAddedService::removeValueAddedService($paymentData['CustomField1'], $paymentData['CustomField4']);
                                ValueAddedServiceLog::addToLog($paymentData['CustomField1'], $paymentData['CustomField4'], 'Auto cancel, last process date: ' . Carbon::now() . ' 經由後台反查訂單扣款失敗，移除hideOnline權限', $order_id, '', 0);
                                $result .= '扣款失敗，hideOnline 權限移除<br>';
                                OrderLog::addToLog($paymentData['CustomField1'], $order_id, '扣款失敗，hideOnline 權限移除');
                            }
                        }
                    }
                }
                else{
                    //檢查交易日期與購買週期
                    if(str_contains($paymentData['CustomField3'], 'one')) {
                        $lastProcessDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $paymentData['PaymentDate']);
                        $lastProcessDateDiffDays = $lastProcessDate->diffInDays(Carbon::now());
                        if(str_contains($paymentData['CustomField3'], 'quarter')){
                            if($lastProcessDateDiffDays<90){
                                //效期內 更新hideOnline
                                $result .= 'hideOnline單次季付效期內<br>';
                                $updateHideOnline = 1;
                            }
                        }else if(str_contains($paymentData['CustomField3'], 'month')){
                            if($lastProcessDateDiffDays<30){
                                //效期內 更新hideOnline
                                $result .= 'hideOnline單次月付效期內<br>';
                                $updateHideOnline = 1;
                            }
                        }

                    }else if(str_contains($paymentData['CustomField3'], 'cc')) {
                        $last = last($paymentPeriodInfo['ExecLog']);
                        if($last['RtnCode']==1 && $paymentPeriodInfo['ExecStatus'] == 1){
                            //定期定額正常狀態中 更新hideOnline
                            if(str_contains($paymentData['CustomField3'], 'quarterly')){
                                $result .= 'hideOnline定期定額季付效期內<br>';
                                $updateHideOnline = 1;
                            }else if(str_contains($paymentData['CustomField3'], 'monthly')){
                                $result .= 'hideOnline定期定額月付效期內<br>';
                                $updateHideOnline = 1;
                            }
                        }

                    }

                    if($updateHideOnline == 1){
                        if(!$hideOnline || $hideOnline->active==0) {
                            ValueAddedService::upgrade($paymentData['CustomField1'], $paymentData['CustomField4'], $paymentData['MerchantID'], $paymentData['MerchantTradeNo'], $paymentData['TradeAmt'], '', 1, $paymentData['CustomField3'], $paymentData['CustomField2']);
                            $result .= '升級HideOnline<br>';
                            OrderLog::addToLog($paymentData['CustomField1'], $order_id, '升級HideOnline，此為經由後台反查升級');
                        }else{
                            //多重付費訂單
                            $result .= '此為效期內訂單，但非使用者當前使用訂單，請檢查是否重複下單<br>';
                            OrderLog::addToLog($paymentData['CustomField1'], $order_id, '此為效期內訂單，但非使用者當前使用訂單，請檢查是否重複下單');
                        }
                    }
                }
            }
            else if($paymentData['CustomField4']=='VVIP'){
                //VVIP
                $updateVVIP='';
                //check user VVIP status
                $VVIP = ValueAddedService::where('service_name', $paymentData['CustomField4'])
                    ->where('member_id',$paymentData['CustomField1'])
                    ->where('order_id', $order_id)
                    ->first();

                if($VVIP && $VVIP->active==1){
                    $result .= '該會員當前已有VVIP<br>';
                    if(str_contains($paymentData['CustomField3'], 'cc')) {
                        if ($paymentPeriodInfo['ExecStatus'] == 0) {
                            $thisOrder = Order::findByOrderId($order_id);
                            $order_expire_date = $thisOrder->order_expire_date;
                            ValueAddedService::where('member_id', $paymentData['CustomField1'])
                                ->where('service_name', $paymentData['CustomField4'])
                                ->update(['expiry' => $order_expire_date]);
                            ValueAddedServiceLog::addToLog($paymentData['CustomField1'], $paymentData['CustomField4'], 'Auto cancel, last process date: ' . Carbon::now() . ' 經由後台反查訂單取消，VVIP 到期日更新', $order_id, '', 0);
                            $result .= '定期定額取消，VVIP 到期日更新<br>';
                            OrderLog::addToLog($paymentData['CustomField1'], $order_id, '定期定額取消，VVIP 權限移除');
                        } else if ($paymentPeriodInfo['ExecStatus'] == 1) {
                            $last = last($paymentPeriodInfo['ExecLog']);
                            //最後一次扣款失敗
                            if ($last['RtnCode'] != 1) {
                                ValueAddedService::removeValueAddedService($paymentData['CustomField1'], $paymentData['CustomField4']);
                                ValueAddedServiceLog::addToLog($paymentData['CustomField1'], $paymentData['CustomField4'], 'Auto cancel, last process date: ' . Carbon::now() . ' 經由後台反查訂單扣款失敗，移除VVIP權限', $order_id, '', 0);
                                $result .= '扣款失敗，VVIP 權限移除<br>';
                                OrderLog::addToLog($paymentData['CustomField1'], $order_id, '扣款失敗，VVIP 權限移除');

                                //寫入訂單付款失敗通知紀錄 OrderPayFailNotify for VVIP
                                $lastPayFailDate = str_replace('%20', ' ', $last['process_date']);
                                $lastPayFailDate = Carbon::createFromFormat('Y/m/d H:i:s', $lastPayFailDate);
                                if(!OrderPayFailNotify::isExists($paymentData['CustomField1'], $order_id, $lastPayFailDate)){
                                    OrderPayFailNotify::addToData($paymentData['CustomField1'], $order_id, $lastPayFailDate);
                                }
                            }
                        }
                    }
                }
                else{
                    //檢查交易日期與購買週期
                    if(str_contains($paymentData['CustomField3'], 'cc')) {
                        $last = last($paymentPeriodInfo['ExecLog']);
                        if($last['RtnCode']==1 && $paymentPeriodInfo['ExecStatus'] == 1){
                            //定期定額正常狀態中 更新VVIP
                            if(str_contains($paymentData['CustomField3'], 'quarterly')){
                                $result .= 'VVIP定期定額季付效期內<br>';
                                $updateVVIP = 1;
                            }
                        }

                    }

                    if($updateVVIP == 1){
                        if(!$VVIP || $VVIP->active==0) {
                            ValueAddedService::upgrade($paymentData['CustomField1'], $paymentData['CustomField4'], $paymentData['MerchantID'], $paymentData['MerchantTradeNo'], $paymentData['TradeAmt'], '', 1, $paymentData['CustomField3'], $paymentData['CustomField2']);
                            $result .= '升級VVIP<br>';
                            OrderLog::addToLog($paymentData['CustomField1'], $order_id, '升級VVIP，此為經由後台反查升級');
                        }else{
                            //多重付費訂單
                            $result .= '此為效期內訂單，但非使用者當前使用訂單，請檢查是否重複下單<br>';
                            OrderLog::addToLog($paymentData['CustomField1'], $order_id, '此為效期內訂單，但非使用者當前使用訂單，請檢查是否重複下單');
                        }
                    }
                }
            }
            else{
                //vip
                $updateVip='';
                //check user vip status
                $vip = Vip::where('member_id', $paymentData['CustomField1'])
                    ->where('order_id', $order_id)
                    ->first();
                if($vip && $vip->active==1){
                    $result .= '該會員當前已有VIP<br>';
                    if(str_contains($paymentData['CustomField3'], 'cc') || $paymentData['CustomField3']=='') {
                        if ($paymentPeriodInfo['ExecStatus'] == 0) {
                            $thisOrder = Order::findByOrderId($order_id);
                            $order_expire_date = $thisOrder->order_expire_date;
                            Vip::where('member_id', $paymentData['CustomField1'])
                                ->where('order_id', $order_id)
                                ->update(['expiry' => $order_expire_date]);
                            VipLog::addToLog($paymentData['CustomField1'], 'Order ID: '.$order_id.' Auto cancel, last process date: ' . Carbon::now() . ' 經由後台反查訂單取消，VIP 到期日更新', '', 1, 0);
                            $result .= '定期定額取消，VIP 到期日更新<br>';
                            OrderLog::addToLog($paymentData['CustomField1'], $order_id, '定期定額取消, VIP 到期日更新');
                        } else if ($paymentPeriodInfo['ExecStatus'] == 1) {
                            $last = last($paymentPeriodInfo['ExecLog']);
                            //最後一次扣款失敗
                            if ($last['RtnCode'] != 1) {
                                $vip->removeVIP();
                                VipLog::addToLog($paymentData['CustomField1'], 'Order ID: '.$order_id.' Auto cancel, last process date: ' . Carbon::now() . ' 經由後台反查訂單扣款失敗，移除VIP權限', '', 1, 0);
                                $result .= '扣款失敗, VIP 權限移除<br>';
                                OrderLog::addToLog($paymentData['CustomField1'], $order_id, '扣款失敗, VIP 權限移除');
                            }
                        }
                    }
                }
                else{
                    //檢查交易日期與購買週期
                    if(str_contains($paymentData['CustomField3'], 'one')) {
                        $lastProcessDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $paymentData['PaymentDate']);
                        $lastProcessDateDiffDays = $lastProcessDate->diffInDays(Carbon::now());
                        if(str_contains($paymentData['CustomField3'], 'quarter')){
                            if($lastProcessDateDiffDays<90){
                                //效期內 更新VIP
                                $result .= 'VIP單次季付效期內<br>';
                                $updateVip = 1;
                            }
                        }else if(str_contains($paymentData['CustomField3'], 'month')){
                            if($lastProcessDateDiffDays<30){
                                //效期內 更新VIP
                                $result .= 'VIP單次月付效期內<br>';
                                $updateVip = 1;
                            }
                        }

                    }else if(str_contains($paymentData['CustomField3'], 'cc') || $paymentData['CustomField3']=='') {
                        $last = last($paymentPeriodInfo['ExecLog']);
                        //最後一次扣款正常
                        if($last['RtnCode']==1 && $paymentPeriodInfo['ExecStatus'] == 1){
                            //定期定額正常狀態中 更新VIP
                            if(str_contains($paymentData['CustomField3'], 'quarterly')){
                                $result .= 'VIP定期定額季付效期內<br>';
                                $updateVip = 1;
                            }else if(str_contains($paymentData['CustomField3'], 'monthly')){
                                $result .= 'VIP定期定額月付效期內<br>';
                                $updateVip = 1;
                            }else if($paymentData['CustomField3']==''){
                                $result .= 'VIP定期定額月付效期內<br>';
                                $updateVip = 1;
                            }
                        }
                    }

                    if($updateVip == 1){
                        if(!$vip || $vip->active ==0) {
                            $user = User::findById($paymentData['CustomField1']);
                            if ($user && !$user->isVVIP()) {
                                Vip::upgrade($paymentData['CustomField1'], $paymentData['MerchantID'], $paymentData['MerchantTradeNo'], $paymentData['TradeAmt'], '', 1, 0, $paymentData['CustomField3'], $transactionType, $paymentData['CustomField2']);
                                $result .= '升級VIP<br>';
                                OrderLog::addToLog($paymentData['CustomField1'], $order_id, '升級VIP，此為經由後台反查升級');
                            }
                        }else{
                            //多重付費訂單
                            $result .= '此為效期內訂單，但非使用者當前使用訂單，請檢查是否重複下單<br>';
                            OrderLog::addToLog($paymentData['CustomField1'], $order_id, '此為效期內訂單，但非使用者當前使用訂單，請檢查是否重複下單');
                        }
                    }

                }

            }



        }else{
            $result .= '此筆訂單交易失敗<br>';
        }

        if($result==''){
            $result='無異動';
        }

        $userInfo = User::select('users.name','users.engroup','users.email','user_meta.phone')
            ->leftJoin('user_meta', 'user_meta.user_id', 'users.id')
            ->where('users.id', $paymentData['CustomField1'])->first();



        if(isset($userInfo)) {
            //VIP帳號：起始時間,付費方式,種類,現狀
            //VIP起始時間,現狀,付費方式,種類
            $vipInfo = Vip::findByIdWithDateDesc($paymentData['CustomField1']);
            $vvipInfo = ValueAddedService::where('member_id', $paymentData['CustomField1'])->where('service_name', 'VVIP')->orderBy('created_at', 'desc')->first();
            $getUserInfo = User::findById($paymentData['CustomField1']);
            if (!is_null($vipInfo) && !$getUserInfo->isVVIP()) {
                $upgradeDay = date('Y-m-d', strtotime($vipInfo->created_at));
                $upgradeWay = '';
                if ($vipInfo->payment_method == 'CREDIT')
                    $upgradeWay = '信用卡';
                else if ($vipInfo->payment_method == 'ATM')
                    $upgradeWay = 'ATM';
                else if ($vipInfo->payment_method == 'CVS')
                    $upgradeWay = '超商代碼';
                else if ($vipInfo->payment_method == 'BARCODE')
                    $upgradeWay = '超商條碼';

                $upgradeKind = '';
                if ($vipInfo->payment == 'cc_quarterly_payment')
                    $upgradeKind = '持續季繳';
                else if ($vipInfo->payment == 'cc_monthly_payment')
                    $upgradeKind = '持續月繳';
                else if ($vipInfo->payment == 'one_quarter_payment')
                    $upgradeKind = '季繳一季';
                else if ($vipInfo->payment == 'one_month_payment')
                    $upgradeKind = '月繳一月';

                //VIP起始時間,現狀,付費方式,種類
                if (is_null($vipInfo->payment_method) && is_null($vipInfo->payment)) {
                    $upgradeWay = '手動升級';
                    $upgradeKind = '手動升級';
                }
                if ($vipInfo->free == 1) {
                    $upgradeWay = '免費';
                    $upgradeKind = '免費';
                }

                $isVipStatus = $getUserInfo->isVip() ? '是' : '否';
                $showVipInfo = $upgradeDay . ' / ' . $isVipStatus . ' / ' . $upgradeWay . ' / ' . $upgradeKind;
            }
            elseif(!is_null($vvipInfo)) {
                $upgradeDay = date('Y-m-d', strtotime($vvipInfo->created_at));
                $upgradeWay = '信用卡';
                $upgradeKind = '';
                if ($vvipInfo->payment == 'cc_quarterly_payment')
                    $upgradeKind = '持續季繳';
                else if ($vvipInfo->payment == 'cc_monthly_payment')
                    $upgradeKind = '持續月繳';
                else if ($vvipInfo->payment == 'one_quarter_payment')
                    $upgradeKind = '季繳一季';
                else if ($vvipInfo->payment == 'one_month_payment')
                    $upgradeKind = '月繳一月';

                $isVVIPStatus = $getUserInfo->isVVIP() ? '是' : '否';
                $showVipInfo = '(VVIP) ' . $upgradeDay . ' / ' . $isVVIPStatus . ' / ' . $upgradeWay . ' / ' . $upgradeKind;
            }
            else {
                $showVipInfo = '未曾加入 / 否 / 無 / 無';
            }
        }
        else{
            $showVipInfo = '';
        }

        return view('admin.stats.test')
            ->with('paymentData', $paymentData)
            ->with('paymentPeriodInfo', $paymentPeriodInfo)
            ->with('result', $result)
            ->with('userInfo', $userInfo)
            ->with('showVipInfo', $showVipInfo);
    }

    public function orderFunPointPayCheck(Request $request){
        $order_id = $request->input('order_id');
        if(str_contains($order_id, 'TIP')){
            return back()->with('message', '車馬費訂單不適用此查詢系統');
        }
        //正式綠界訂單查詢
        $ecpay = new \App\Services\ECPay_AllInOne();
        $ecpay->MerchantID = Config::get('funpoint.payment.MerchantID');
        $ecpay->ServiceURL = Config::get('funpoint.payment.OrderQueryURL');
        $ecpay->HashIV = Config::get('funpoint.payment.HashIV');
        $ecpay->HashKey = Config::get('funpoint.payment.HashKey');
        $ecpay->Query = [
            'MerchantTradeNo' => $order_id,
            'TimeStamp' => time()
        ];
        $paymentData = $ecpay->QueryTradeInfo();

        if($paymentData['TradeStatus']==10200047){
            return back()->with('message','查無此訂單');
        }

        $ecpay->ServiceURL = Config::get('funpoint.payment.ServiceURL');//定期定額查詢
        $paymentPeriodInfo = $ecpay->QueryPeriodCreditCardTradeInfo();

        $result='';

        if($paymentData['TradeStatus']==1){

            $transactionType='';
            if($paymentData['PaymentType'] == 'Credit_CreditCard') {
                $transactionType = 'CREDIT'; //信用卡
            }
            elseif(str_contains($paymentData['PaymentType'], 'ATM')) {
                $transactionType = 'ATM'; //ATM
            }
            elseif($paymentData['PaymentType'] == 'BARCODE_BARCODE') {
                $transactionType = 'BARCODE'; //超商條碼
            }
            elseif ($paymentData['PaymentType'] == 'CVS_CVS') {
                $transactionType = 'CVS'; //超商代號
            }
            else {
                $transactionType = $paymentData['PaymentType']; //寫入回傳的PaymentType
            }

            //check order table
            $order = Order::where('order_id', $order_id)->first();
            if(!$order){
                Order::addFunPointPayOrder($order_id);
                $result .= '新增訂單資料<br>';
            }

            if(isset($paymentPeriodInfo) && $paymentPeriodInfo['ExecLog'] != ''){
                $last = last($paymentPeriodInfo['ExecLog']);
                $lastProcessDate = str_replace('%20', ' ', $last['process_date']);
                $lastProcessDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $lastProcessDate);
                //付款日期有差異時更新訂單
                $currentOrder = Order::where('order_id', $order_id)->first();
                $current_order_pay_date = last(json_decode($currentOrder->pay_date));
                $current_order_pay_fail = '';
                if($currentOrder->pay_fail != ''){
                    $current_order_pay_fail = last(json_decode($currentOrder->pay_fail));
                }
                if($currentOrder &&
                    ((Carbon::parse($lastProcessDate)->toDateTimeString() != Carbon::parse($current_order_pay_date[0])->toDateTimeString() &&
                            (($currentOrder->pay_fail != '' && Carbon::parse($lastProcessDate)->toDateTimeString() != Carbon::parse($current_order_pay_fail[0])->toDateTimeString()) || $currentOrder->pay_fail=='')
                        ) ||
                        $currentOrder->ExecStatus == '' )
                ) {
                        Order::updateFunPointPayOrder($order_id);
                        $result .= '更新訂單資訊<br>';

                }
            }

            //check service
            if($paymentData['CustomField4']=='hideOnline'){
                //hideOnline
                $updateHideOnline='';
                //check user hideOnline status
                $hideOnline = ValueAddedService::where('service_name', $paymentData['CustomField4'])
                    ->where('member_id',$paymentData['CustomField1'])
                    ->where('order_id', $order_id)
                    ->first();

                if($hideOnline && $hideOnline->active==1){
                    $result .= '該會員當前已有hideOnline<br>';
                    if(str_contains($paymentData['CustomField3'], 'cc')) {
                        if ($paymentPeriodInfo['ExecStatus'] == 0) {
                            $thisOrder = Order::findByOrderId($order_id);
                            $order_expire_date = $thisOrder->order_expire_date;
                            ValueAddedService::where('member_id', $paymentData['CustomField1'])
                                ->where('service_name', $paymentData['CustomField4'])
                                ->update(['expiry' => $order_expire_date]);
                            ValueAddedServiceLog::addToLog($paymentData['CustomField1'], $paymentData['CustomField4'], 'Auto cancel, last process date: ' . Carbon::now() . ' 經由後台反查訂單取消，hideOnline 到期日更新', $order_id, '', 0);
                            $result .= '定期定額取消，hideOnline 到期日更新<br>';
                            OrderLog::addToLog($paymentData['CustomField1'], $order_id, '定期定額取消，hideOnline 到期日更新');
                        } else if ($paymentPeriodInfo['ExecStatus'] == 1) {
                            $last = last($paymentPeriodInfo['ExecLog']);
                            //最後一次扣款失敗
                            if ($last['RtnCode'] != 1) {
                                ValueAddedService::removeValueAddedService($paymentData['CustomField1'], $paymentData['CustomField4']);
                                ValueAddedServiceLog::addToLog($paymentData['CustomField1'], $paymentData['CustomField4'], 'Auto cancel, last process date: ' . Carbon::now() . ' 經由後台反查訂單扣款失敗，移除hideOnline權限', $order_id, '', 0);
                                $result .= '扣款失敗，hideOnline 權限移除<br>';
                                OrderLog::addToLog($paymentData['CustomField1'], $order_id, '扣款失敗，hideOnline 權限移除');
                            }
                        }
                    }
                }else{
                    //檢查交易日期與購買週期
                    if(str_contains($paymentData['CustomField3'], 'one')) {
                        $lastProcessDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $paymentData['PaymentDate']);
                        $lastProcessDateDiffDays = $lastProcessDate->diffInDays(Carbon::now());
                        if(str_contains($paymentData['CustomField3'], 'quarter')){
                            if($lastProcessDateDiffDays<90){
                                //效期內 更新hideOnline
                                $result .= 'hideOnline單次季付效期內<br>';
                                $updateHideOnline = 1;
                            }
                        }else if(str_contains($paymentData['CustomField3'], 'month')){
                            if($lastProcessDateDiffDays<30){
                                //效期內 更新hideOnline
                                $result .= 'hideOnline單次月付效期內<br>';
                                $updateHideOnline = 1;
                            }
                        }

                    }else if(str_contains($paymentData['CustomField3'], 'cc')) {
                        $last = last($paymentPeriodInfo['ExecLog']);
                        if($last['RtnCode']==1 && $paymentPeriodInfo['ExecStatus'] == 1){
                            //定期定額正常狀態中 更新hideOnline
                            if(str_contains($paymentData['CustomField3'], 'quarterly')){
                                $result .= 'hideOnline定期定額季付效期內<br>';
                                $updateHideOnline = 1;
                            }else if(str_contains($paymentData['CustomField3'], 'monthly')){
                                $result .= 'hideOnline定期定額月付效期內<br>';
                                $updateHideOnline = 1;
                            }
                        }

                    }

                    if($updateHideOnline == 1){
                        if(!$hideOnline || $hideOnline->active==0) {
                            ValueAddedService::upgrade($paymentData['CustomField1'], $paymentData['CustomField4'], $paymentData['MerchantID'], $paymentData['MerchantTradeNo'], $paymentData['TradeAmt'], '', 1, $paymentData['CustomField3'], $paymentData['CustomField2']);
                            $result .= '升級HideOnline<br>';
                            OrderLog::addToLog($paymentData['CustomField1'], $order_id, '升級HideOnline，此為經由後台反查升級');
                        }else{
                            //多重付費訂單
                            $result .= '此為效期內訂單，但非使用者當前使用訂單，請檢查是否重複下單<br>';
                            OrderLog::addToLog($paymentData['CustomField1'], $order_id, '此為效期內訂單，但非使用者當前使用訂單，請檢查是否重複下單');
                        }
                    }
                }
            }
            elseif($paymentData['CustomField4']=='VVIP'){
                //VVIP
                $updateVVIP='';
                //check user VVIP status
                $VVIP = ValueAddedService::where('service_name', $paymentData['CustomField4'])
                    ->where('member_id',$paymentData['CustomField1'])
                    ->where('order_id', $order_id)
                    ->first();

                if($VVIP && $VVIP->active==1){
                    $result .= '該會員當前已有VVIP<br>';
                    if(str_contains($paymentData['CustomField3'], 'cc')) {
                        if ($paymentPeriodInfo['ExecStatus'] == 0) {
                            $thisOrder = Order::findByOrderId($order_id);
                            $order_expire_date = $thisOrder->order_expire_date;
                            ValueAddedService::where('member_id', $paymentData['CustomField1'])
                                ->where('service_name', $paymentData['CustomField4'])
                                ->update(['expiry' => $order_expire_date]);
                            ValueAddedServiceLog::addToLog($paymentData['CustomField1'], $paymentData['CustomField4'], 'Auto cancel, last process date: ' . Carbon::now() . ' 經由後台反查訂單取消，VVIP 到期日更新', $order_id, '', 0);
                            $result .= '定期定額取消，VVIP 到期日更新<br>';
                            OrderLog::addToLog($paymentData['CustomField1'], $order_id, '定期定額取消，VVIP 到期日更新');
                        } else if ($paymentPeriodInfo['ExecStatus'] == 1) {
                            $last = last($paymentPeriodInfo['ExecLog']);
                            //最後一次扣款失敗
                            if ($last['RtnCode'] != 1) {
                                ValueAddedService::removeValueAddedService($paymentData['CustomField1'], $paymentData['CustomField4']);
                                ValueAddedServiceLog::addToLog($paymentData['CustomField1'], $paymentData['CustomField4'], 'Auto cancel, last process date: ' . Carbon::now() . ' 經由後台反查訂單扣款失敗，移除VVIP權限', $order_id, '', 0);
                                $result .= '扣款失敗，VVIP 權限移除<br>';
                                OrderLog::addToLog($paymentData['CustomField1'], $order_id, '扣款失敗，VVIP 權限移除');

                                //寫入訂單付款失敗通知紀錄 OrderPayFailNotify for VVIP
                                $lastPayFailDate = str_replace('%20', ' ', $last['process_date']);
                                $lastPayFailDate = Carbon::createFromFormat('Y/m/d H:i:s', $lastPayFailDate);
                                if(!OrderPayFailNotify::isExists($paymentData['CustomField1'], $order_id, $lastPayFailDate)){
                                    OrderPayFailNotify::addToData($paymentData['CustomField1'], $order_id, $lastPayFailDate);
                                }
                            }
                        }
                    }
                }else{
                    //檢查交易日期與購買週期
                    if(str_contains($paymentData['CustomField3'], 'cc')) {
                        $last = last($paymentPeriodInfo['ExecLog']);
                        if($last['RtnCode']==1 && $paymentPeriodInfo['ExecStatus'] == 1){
                            //定期定額正常狀態中 更新VVIP
                            if(str_contains($paymentData['CustomField3'], 'quarterly')){
                                $result .= 'VVIP定期定額季付效期內<br>';
                                $updateVVIP = 1;
                            }
                        }

                    }

                    if($updateVVIP == 1){
                        if(!$VVIP || $VVIP->active==0) {
                            ValueAddedService::upgrade($paymentData['CustomField1'], $paymentData['CustomField4'], $paymentData['MerchantID'], $paymentData['MerchantTradeNo'], $paymentData['TradeAmt'], '', 1, $paymentData['CustomField3'], $paymentData['CustomField2']);
                            $result .= '升級VVIP<br>';
                            OrderLog::addToLog($paymentData['CustomField1'], $order_id, '升級VVIP，此為經由後台反查升級');
                        }else{
                            //多重付費訂單
                            $result .= '此為效期內訂單，但非使用者當前使用訂單，請檢查是否重複下單<br>';
                            OrderLog::addToLog($paymentData['CustomField1'], $order_id, '此為效期內訂單，但非使用者當前使用訂單，請檢查是否重複下單');
                        }
                    }
                }
            }
            else{
                //vip
                $updateVip='';
                //check user vip status
                $vip = Vip::where('member_id', $paymentData['CustomField1'])
                    ->where('order_id', $order_id)
                    ->first();
                if($vip && $vip->active==1){
                    $result .= '該會員當前已有VIP<br>';
                    if(str_contains($paymentData['CustomField3'], 'cc') || $paymentData['CustomField3']=='') {
                        if ($paymentPeriodInfo['ExecStatus'] == 0) {
                            $thisOrder = Order::findByOrderId($order_id);
                            $order_expire_date = $thisOrder->order_expire_date;
                            Vip::where('member_id', $paymentData['CustomField1'])
                                ->where('order_id', $order_id)
                                ->update(['expiry' => $order_expire_date]);
                            VipLog::addToLog($paymentData['CustomField1'], 'Order ID: '.$order_id.' Auto cancel, last process date: ' . Carbon::now() . ' 經由後台反查訂單取消，VIP 到期日更新', '', 1, 0);
                            $result .= '定期定額取消，VIP 到期日更新<br>';
                            OrderLog::addToLog($paymentData['CustomField1'], $order_id, '定期定額取消，VIP 到期日更新');
                        } else if ($paymentPeriodInfo['ExecStatus'] == 1) {
                            $last = last($paymentPeriodInfo['ExecLog']);
                            //最後一次扣款失敗
                            if ($last['RtnCode'] != 1) {
                                $vip->removeVIP();
                                VipLog::addToLog($paymentData['CustomField1'], 'Order ID: '.$order_id.' Auto cancel, last process date: ' . Carbon::now() . ' 經由後台反查訂單扣款失敗，移除VIP權限', '', 1, 0);
                                $result .= '扣款失敗, VIP 權限移除<br>';
                                OrderLog::addToLog($paymentData['CustomField1'], $order_id, '扣款失敗，VIP 權限移除');
                            }
                        }
                    }
                }
                else{
                    //檢查交易日期與購買週期
                    if(str_contains($paymentData['CustomField3'], 'one')) {
                        $lastProcessDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $paymentData['PaymentDate']);
                        $lastProcessDateDiffDays = $lastProcessDate->diffInDays(Carbon::now());
                        if(str_contains($paymentData['CustomField3'], 'quarter')){
                            if($lastProcessDateDiffDays<90){
                                //效期內 更新VIP
                                $result .= 'VIP單次季付效期內<br>';
                                $updateVip = 1;
                            }
                        }else if(str_contains($paymentData['CustomField3'], 'month')){
                            if($lastProcessDateDiffDays<30){
                                //效期內 更新VIP
                                $result .= 'VIP單次月付效期內<br>';
                                $updateVip = 1;
                            }
                        }

                    }else if(str_contains($paymentData['CustomField3'], 'cc') || $paymentData['CustomField3']=='') {
                        $last = last($paymentPeriodInfo['ExecLog']);
                        if($last['RtnCode']==1 && $paymentPeriodInfo['ExecStatus'] == 1){
                            //定期定額正常狀態中 更新VIP
                            if(str_contains($paymentData['CustomField3'], 'quarterly')){
                                $result .= 'VIP定期定額季付效期內<br>';
                                $updateVip = 1;
                            }else if(str_contains($paymentData['CustomField3'], 'monthly')){
                                $result .= 'VIP定期定額月付效期內<br>';
                                $updateVip = 1;
                            }else if($paymentData['CustomField3']==''){
                                $result .= 'VIP定期定額月付效期內<br>';
                                $updateVip = 1;
                            }
                        }
                    }

                    if($updateVip == 1){
                        if(!$vip || $vip->active ==0) {
                            $result .= '升級VIP<br>';
                            $user = User::findById($paymentData['CustomField1']);
                            if ($user && !$user->isVVIP()) {
                                Vip::upgrade($paymentData['CustomField1'], $paymentData['MerchantID'], $paymentData['MerchantTradeNo'], $paymentData['TradeAmt'], '', 1, 0, $paymentData['CustomField3'], $transactionType, $paymentData['CustomField2']);
                                $result .= '升級VIP<br>';
                                OrderLog::addToLog($paymentData['CustomField1'], $order_id, '升級VIP，此為經由後台反查升級');
                            }
                        }else{
                            //多重付費訂單
                            $result .= '此為效期內訂單，但非使用者當前使用訂單，請檢查是否重複下單<br>';
                            OrderLog::addToLog($paymentData['CustomField1'], $order_id, '此為效期內訂單，但非使用者當前使用訂單，請檢查是否重複下單');
                        }
                    }

                }

            }



        }else{
            $result .= '此筆訂單交易失敗<br>';
        }

        if($result==''){
            $result='無異動';
        }

        $userInfo = User::select('users.name','users.engroup','users.email','user_meta.phone')
            ->leftJoin('user_meta', 'user_meta.user_id', 'users.id')
            ->where('users.id', $paymentData['CustomField1'])->first();

        if(isset($userInfo)) {
            //VIP帳號：起始時間,付費方式,種類,現狀
            //VIP起始時間,現狀,付費方式,種類
            $vipInfo = Vip::findByIdWithDateDesc($paymentData['CustomField1']);

            if (!is_null($vipInfo)) {
                $upgradeDay = date('Y-m-d', strtotime($vipInfo->created_at));
                $upgradeWay = '';
                if ($vipInfo->payment_method == 'CREDIT')
                    $upgradeWay = '信用卡';
                else if ($vipInfo->payment_method == 'ATM')
                    $upgradeWay = 'ATM';
                else if ($vipInfo->payment_method == 'CVS')
                    $upgradeWay = '超商代碼';
                else if ($vipInfo->payment_method == 'BARCODE')
                    $upgradeWay = '超商條碼';

                $upgradeKind = '';
                if ($vipInfo->payment == 'cc_quarterly_payment')
                    $upgradeKind = '持續季繳';
                else if ($vipInfo->payment == 'cc_monthly_payment')
                    $upgradeKind = '持續月繳';
                else if ($vipInfo->payment == 'one_quarter_payment')
                    $upgradeKind = '季繳一季';
                else if ($vipInfo->payment == 'one_month_payment')
                    $upgradeKind = '月繳一月';

                //VIP起始時間,現狀,付費方式,種類
                if (is_null($vipInfo->payment_method) && is_null($vipInfo->payment)) {
                    $upgradeWay = '手動升級';
                    $upgradeKind = '手動升級';
                }
                if ($vipInfo->free == 1) {
                    $upgradeWay = '免費';
                    $upgradeKind = '免費';
                }
                $getUserInfo = \App\Models\User::findById($paymentData['CustomField1']);//->isVip? '是':'否';
                $isVipStatus = $getUserInfo->isVip() ? '是' : '否';
                $showVipInfo = $upgradeDay . ' / ' . $isVipStatus . ' / ' . $upgradeWay . ' / ' . $upgradeKind;
            } else {
                $showVipInfo = '未曾加入 / 否 / 無 / 無';
            }
        }else{
            $showVipInfo = '';
        }

        return view('admin.stats.test')
            ->with('paymentData', $paymentData)
            ->with('paymentPeriodInfo', $paymentPeriodInfo)
            ->with('result', $result)
            ->with('userInfo', $userInfo)
            ->with('showVipInfo', $showVipInfo);
    }

    public function orderCheckByServiceNameOrOrderId(Request $request){

        $order = '';

        //for service_name + order_id
        if ($request->order_id != '' && $request->service_name != '' && str_contains($request->order_id, 'SG')){
            $order = Order::where('service_name', $request->service_name)
                ->where('order_id', $request->order_id)
                ->where('payment', 'like', 'cc_%')
                ->whereRaw('LENGTH(order_id) = 12')
                ->get();
        }
        //for service_name
        else if($request->order_id == '' && $request->service_name != ''){
            $order = Order::where('service_name', $request->service_name)
                ->where('payment', 'like', 'cc_%')
                ->whereRaw('LENGTH(order_id) = 12')
                ->get();
        }

        if($order && count($order)>0){
            $now = Carbon::now();
            foreach ($order as $row){

                $update = false;
                $addCancelAction = false;

                if($row->ExecStatus==''){
                    $update = true;
                }

                if ($row->payment == 'cc_quarterly_payment') {
                    $periodRemained = 92;
                } else {
                    $periodRemained = 30;
                }
                //取本機訂單最後扣款日
                $lastProcessDate = last(json_decode($row->pay_date));
                $theActualLastProcessDate = is_string($lastProcessDate[0]) ? Carbon::parse($lastProcessDate[0]) : $lastProcessDate[0];

                $lastPayFailDate = '';
                $theActualLastPayFailDateDate = '';
                if($row->pay_fail != '') {
                    $lastPayFailDate = last(json_decode($row->pay_fail));
                    $theActualLastPayFailDateDate = is_string($lastPayFailDate[0]) ? Carbon::parse($lastPayFailDate[0]) : $lastPayFailDate[0];
                }
                if ( (($now->diffInDays($theActualLastProcessDate) > $periodRemained && ($now->diffInDays($theActualLastPayFailDateDate) > $periodRemained && $row->pay_fail != '')) ||
                     ($now->diffInDays($theActualLastProcessDate) > $periodRemained && $row->pay_fail == '')) &&
                    $row->ExecStatus == 1) {
                    $update = true;
                }

                //執行更新動作
                if ($update == true && $row->payment_flow == 'ecpay'){
                    Order::updateEcPayOrder($row->order_id);
                }
                elseif ($update == true && $row->payment_flow == 'funpoint'){
                    Order::updateFunPointPayOrder($row->order_id);
                }

                //權限檢查 for checkOrder
                $checkOrder = Order::findByOrderId($row->order_id);
                //取本機訂單最後扣款日
                $lastProcessDate = last(json_decode($checkOrder->pay_date));
                $theActualLastProcessDate = is_string($lastProcessDate[0]) ? Carbon::parse($lastProcessDate[0]) : $lastProcessDate[0];
                if ($checkOrder && $checkOrder->service_name == 'VIP'){
                    $vip = Vip::findByIdWithDateDesc($checkOrder->user_id);
                    //扣款失敗 移除權限
                    if ($vip && $vip->active == 1  &&
                        $checkOrder->ExecStatus == 1 &&
                        $now->diffInDays($theActualLastProcessDate) > $periodRemained
                    ) {
                        if($vip->order_id == $checkOrder->order_id){
                            $vip->removeVIP();
                            VipLog::addToLog($checkOrder->user_id, 'Order ID: '.$checkOrder->order_id.' Auto cancel, last process date: ' . Carbon::now() . ' 經由後台反查訂單扣款失敗，移除 VIP 權限', '', 1, 0);
                            OrderLog::addToLog($checkOrder->user_id, $checkOrder->order_id, '扣款失敗，VIP 權限移除');
                        }
                        else{
                            VipLog::addToLog($checkOrder->user_id, 'Order ID: '.$checkOrder->order_id.' , last process date: ' . Carbon::now() . ' 經由後台反查訂單扣款失敗，非 VIP 當前訂單，請重新檢視使用者訂單狀況', '', 1, 0);
                            OrderLog::addToLog($checkOrder->user_id, $checkOrder->order_id, '扣款失敗，非 VIP 當前訂單，加入取消訂單列表，請重新檢視使用者訂單狀況');
                            //寫入取消訂單
                            $addCancelAction = true;
                        }

                    }
                    //定期定額取消 移除權限
                    elseif ($vip && $vip->active == 1 &&
                        $checkOrder->ExecStatus == 0 &&
                        $vip->order_id == $checkOrder->order_id &&
                        Carbon::parse($checkOrder->order_expire_date) < Carbon::now()
                    ){
                            $vip->removeVIP();
                            VipLog::addToLog($checkOrder->user_id, 'Order ID: ' . $checkOrder->order_id . ' Auto cancel, last process date: ' . Carbon::now() . ' 經由後台反查訂單取消，移除VIP權限', '', 1, 0);
                            OrderLog::addToLog($checkOrder->user_id, $checkOrder->order_id, '定期定額取消，VIP 權限移除');
                    }
                    //定期定額 付款日在期限內 恢復權限
                    elseif ($vip && $vip->active == 0 && $checkOrder->ExecStatus == 1 && $now->diffInDays($theActualLastProcessDate) < $periodRemained){

                        if($vip->order_id == $checkOrder->order_id) {
                            Vip::where('member_id', $checkOrder->user_id)
                                ->where('id', $vip->id)
                                ->update(array('active' => 1, 'expiry' => '0000-00-00 00:00:00'));
                            VipLog::addToLog($checkOrder->user_id, 'Order ID: ' . $checkOrder->order_id . ' Auto upgrade, last process date: ' . $theActualLastProcessDate->format('Y-m-d').' 定期定額繳費中，最後扣款日在期限內，VIP 權限恢復', '', 1, 0);
                            OrderLog::addToLog($checkOrder->user_id, $checkOrder->order_id, '定期定額繳費中，最後扣款日在期限內，VIP 權限恢復');
                        }
                        else{
                            VipLog::addToLog($checkOrder->user_id, 'Order ID: ' . $checkOrder->order_id . ' , last process date: ' . $theActualLastProcessDate->format('Y-m-d').' 定期定額繳費中，最後扣款日在期限內，但非 VIP 當前訂單，請重新檢視使用者訂單狀況', '', 1, 0);
                            OrderLog::addToLog($checkOrder->user_id, $checkOrder->order_id, '定期定額繳費中，最後扣款日在期限內，但非 VIP 當前訂單，加入取消訂單列表，請重新檢視使用者訂單狀況');
                            //寫入取消訂單
                            $addCancelAction = true;

                        }
                    }

                }
                elseif ($checkOrder && $checkOrder->service_name == 'VVIP' || $checkOrder->service_name == 'hideOnline'){
                    $ValueAddedServiceData = ValueAddedService::where('service_name', $checkOrder->service_name)
                        ->where('member_id', $checkOrder->user_id)
                        ->first();
                    //扣款失敗 移除權限
                    if ($ValueAddedServiceData && $ValueAddedServiceData->active == 1 &&
                        $checkOrder->ExecStatus == 1 &&
                        $now->diffInDays($theActualLastProcessDate) > $periodRemained
                    ){
                        if ($ValueAddedServiceData->order_id == $checkOrder->order_id){
                            ValueAddedService::removeValueAddedService($checkOrder->user_id, $checkOrder->service_name);
                            ValueAddedServiceLog::addToLog($checkOrder->user_id, $checkOrder->service_name, 'Auto cancel, last process date: ' . Carbon::now().' 經由後台反查訂單扣款失敗，移除 '.$checkOrder->service_name.' 權限', $checkOrder->order_id, $ValueAddedServiceData->txn_id, 0);
                            OrderLog::addToLog($checkOrder->user_id, $checkOrder->order_id, '扣款失敗，'.$checkOrder->service_name.' 權限移除');
                        }
                        else{
                            ValueAddedServiceLog::addToLog($checkOrder->user_id, $checkOrder->service_name, '經由後台反查訂單扣款失敗，非 '.$checkOrder->service_name.' 當前訂單，請重新檢視使用者訂單狀況', $checkOrder->order_id, $ValueAddedServiceData->txn_id, 0);
                            OrderLog::addToLog($checkOrder->user_id, $checkOrder->order_id, '扣款失敗，非 '.$checkOrder->service_name.' 當前訂單，寫入取消訂單列表，請重新檢視使用者訂單狀況');
                            //寫入取消訂單
                            $addCancelAction = true;
                        }
                    }
                    //定期定額取消 移除權限
                    elseif ($ValueAddedServiceData && $ValueAddedServiceData->active == 1 &&
                            $checkOrder->ExecStatus == 0 &&
                            $ValueAddedServiceData->order_id == $checkOrder->order_id &&
                            Carbon::parse($checkOrder->order_expire_date) < Carbon::now()
                    ){
                            ValueAddedService::removeValueAddedService($checkOrder->user_id, $checkOrder->service_name);
                            ValueAddedServiceLog::addToLog($checkOrder->user_id, $checkOrder->service_name, 'Auto cancel, last process date: ' . Carbon::now().' 經由後台反查訂單取消，移除 '.$checkOrder->service_name.' 權限', $checkOrder->order_id, $ValueAddedServiceData->txn_id, 0);
                    }
                    //定期定額 付款日在期限內 恢復權限
                    elseif ($ValueAddedServiceData && $ValueAddedServiceData->active == 0 &&
                            $checkOrder->ExecStatus == 1 &&
                            $now->diffInDays($theActualLastProcessDate) < $periodRemained
                    ){
                        if ($ValueAddedServiceData->order_id == $checkOrder->order_id){
                            ValueAddedService::where('member_id', $checkOrder->user_id)
                                ->where('service_name', $checkOrder->service_name)
                                ->update(array('active' => 1, 'expiry' => '0000-00-00 00:00:00'));
                            ValueAddedServiceLog::addToLog($checkOrder->user_id, $checkOrder->service_name, 'Auto upgrade, last process date:: ' . $theActualLastProcessDate->format('Y-m-d') . ' 定期定額繳費中，最後扣款日在期限內，權限自動回復', $checkOrder->order_id, $ValueAddedServiceData->txn_id, 0);
                            OrderLog::addToLog($checkOrder->user_id, $checkOrder->order_id, '定期定額繳費中，最後扣款日在期限內，'.$checkOrder->service_name.' 權限自動回復');
                        }
                        else{
                            ValueAddedServiceLog::addToLog($checkOrder->user_id, $checkOrder->service_name, 'Last process date:: ' . $theActualLastProcessDate->format('Y-m-d') . ' 定期定額繳費中，最後扣款日在期限內，但非 '.$checkOrder->service_name.' 當前訂單，請重新檢視使用者訂單狀況', $checkOrder->order_id, $ValueAddedServiceData->txn_id, 0);
                            OrderLog::addToLog($checkOrder->user_id, $checkOrder->order_id, '定期定額繳費中，最後扣款日在期限內，但非 '.$checkOrder->service_name.' 當前訂單，寫入取消訂單列表，請重新檢視使用者訂單狀況');
                            //寫入取消訂單
                            $addCancelAction = true;
                        }
                    }
                }

                //寫入取消訂單
                if($addCancelAction==true){
                    logger($checkOrder->order_id. '非 user: ' . $checkOrder->user_id . ' '.$checkOrder->service_name.' 當前使用之訂單，寫入取消列表。');
                    $order = Order::findByOrderId($checkOrder->order_id);
                    $this->logService->cancelLogForOrder($order);
                    $this->logService->writeLogToDB();
                    $file = $this->logService->writeLogToFile();
                    if( strpos(\Storage::disk('local')->get($file[0]), $file[1]) !== false) {
                        logger($checkOrder->order_id. '非 user id: ' . $checkOrder->user_id . ' '.$checkOrder->service_name.' 當前使用之訂單，寫入取消列表完成。');
                    }
                }

                //寫入訂單付款失敗通知紀錄 OrderPayFailNotify for VVIP
                if ($addCancelAction == false
                    && $checkOrder && $checkOrder->ExecStatus == 1
                    && $checkOrder->pay_fail != ''
                    && $checkOrder->service_name == 'VVIP'
                ){
                    $lastPayFailDate = last(json_decode($checkOrder->pay_fail));
                    $theActualLastPayFailDate = is_string($lastPayFailDate[0]) ? Carbon::parse($lastPayFailDate[0]) : $lastPayFailDate[0];
                    if(!OrderPayFailNotify::isExists($checkOrder->user_id, $checkOrder->order_id, $theActualLastPayFailDate)){
                        OrderPayFailNotify::addToData($checkOrder->user_id, $checkOrder->order_id, $theActualLastPayFailDate);
                        OrderLog::addToLog($checkOrder->user_id, $checkOrder->order_id, '經由後台檢查，VVIP 扣款失敗，加入提醒通知');
                    }
                }


            }
            return back()->with('message', '訂單檢查完成');
        }

        return back()->with('message', '檢查失敗');
    }

}
