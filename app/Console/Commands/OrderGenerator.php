<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\User;
use App\Models\ValueAddedServiceLog;
use App\Models\VipLog;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class OrderGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'OrderGenerator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Order Generator';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set("memory_limit","20G");
        //
        try {

            //VIP
            $member_vip_log = VipLog::select('member_vip_log.*')->from('member_vip_log')
                ->leftJoin('users','users.id','member_vip_log.member_id')
                ->where('users.last_login', '>',Carbon::now()->subDays(180))
                ->whereNotNull('users.id')
                ->OrderBy('member_vip_log.member_id')
                ->OrderBy('member_vip_log.created_at','ASC')
                ->get();

            foreach($member_vip_log as $row){

                if(strpos($row->member_name, 'order id') !== false && $row->action == 1){
                    //get order id
                    $pieces = explode(' ', $row->member_name);
                    $order_id = str_replace(',', '', $pieces[3]);
                    //檢查沒有訂單再生成
                    $checkOrder = Order::where('order_id', $order_id)->first();
                    if(!isset($checkOrder)) {
                        if (strpos($order_id, 'SG') !== false) {
                            Order::addEcPayOrder($order_id, null);
                        } else if(strpos($order_id, 'SG') === false && strlen($order_id)>=10) {
                            Order::addOtherOrder($order_id, $row->member_id, $row->created_at);
                        }

                        $prevID = $row->id;
                        $prevUserID = $row->member_id;
                        $prevOrderID = $order_id;
                    }
                }

                if( isset($prevID) && $row->id > $prevID && isset($prevUserID) && $row->member_id == $prevUserID && $row->action == 0 && isset($prevOrderID) && $prevOrderID != ''){
                    $currentOrder = Order::where('order_id', $prevOrderID)->first();
                    if( strpos(strtolower($row->member_name), 'cancel') !== false && isset($currentOrder)){
                        //舊訂單自動判斷到期日
                        if(strpos($prevOrderID, 'SG') === false){
                            $order_date = $currentOrder->order_date;
                            //藍新 從訂單日推演到期日
                            $start_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $order_date);
                            $cancel_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at);

                            $end_date = $cancel_date;

                            $checkDays = $start_date->diffInDays($cancel_date);
                            $payTimes = ceil($checkDays / 30); //無條件進位

                            if ($payTimes > 0) {
                                $dateArray = array();
                                $dd = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $start_date)->toDateTimeString();
                                for ($x = 0; $x <= $payTimes; $x++) {
                                    $dd = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dd)->toDateTimeString();
                                    $current_dd = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dd);
                                    $diffDays = $current_dd->diffInDays($cancel_date);
                                    if($diffDays <= 7 && $end_date <= '2020-12-31 00:00:00'){
                                        $end_date = $current_dd->addMonthNoOverflow(1);
                                    }elseif($diffDays>7 && $diffDays<=30 && $end_date <= '2020-12-31 00:00:00'){
                                        $end_date = $current_dd;
                                    }

                                    if ($dd < $cancel_date) {
                                        array_push($dateArray, array($dd));
                                    }
                                    $dd = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dd);
                                    $dd = $dd->addMonthNoOverflow(1);
                                }
                                Order::where('order_id', $prevOrderID)->update(['pay_date' => json_encode($dateArray), 'order_expire_date' => $end_date]);
                            }else if(substr($order_date,0,10) == substr($row->created_at,0,10)){
                                $dateArray = array();
                                array_push($dateArray, array($start_date->toDateTimeString()));
                                Order::where('order_id', $prevOrderID)->update(['pay_date' => json_encode($dateArray), 'order_expire_date' => $start_date->addMonthNoOverflow(1)]);

                            }

                            $prevID = '';
                            $prevUserID = '';
                            $prevOrderID = '';
                        }
                    }
                }
            }

            //加值服務訂單
            $ValueAddedServiceLog = ValueAddedServiceLog::distinct('order_id')->get();
            foreach($ValueAddedServiceLog as $row){
                Order::addEcPayOrder($row->order_id, null);
            }

            //檢查藍新未取消的訂單紀錄 取消最終日統一為2020-12.31
            $checkOrder = Order::where('order_expire_date', null)->get();
            if($checkOrder) {
                foreach ($checkOrder as $row) {
                    $start_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->order_date);
                    if(strpos($row->order_id, 'SG') === false){
                        $dateArray = array();
                        //藍新 從訂單日推演到期日
                        $cancel_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', '2020-12-31 00:00:00');

                        $end_date = $cancel_date;

                        $checkDays = $start_date->diffInDays($cancel_date);
                        $payTimes = ceil($checkDays / 30)-1; //無條件進位

                        if ($payTimes > 0) {
                            $dd = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $start_date);
                            for ($x = 0; $x <= $payTimes; $x++) {
                                $current_dd = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dd);
                                $dd = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dd)->toDateTimeString();

                                $diffDays = $current_dd->diffInDays($cancel_date);
                                if($diffDays <= 7 && $end_date <= '2020-12-31 00:00:00'){
                                    $end_date = $current_dd->addMonthNoOverflow(1);
                                }elseif($diffDays>7 && $diffDays<=30 && $end_date <= '2020-12-31 00:00:00'){
                                    $end_date = $current_dd;
                                }

                                if ($dd < $cancel_date) {
                                    array_push($dateArray, array($dd));
                                }
                                $dd = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dd);
                                $dd = $dd->addMonthNoOverflow(1);
                            }
                            Order::where('order_id', $row->order_id)->update(['pay_date' => json_encode($dateArray), 'order_expire_date' => $end_date]);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::info('OrderGenerator新增失敗'.$e->getMessage() .' LINE:'.$e->getLine());
        }
    }
}
