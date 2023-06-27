<?php
namespace App\Http\Controllers;

//載入SDK(路徑可依系統規劃自行調整)
use App\Models\SimpleTables\banned_users;
use App\Models\SimpleTables\warned_users;
use App\Services\ECPay_AllInOne;
use App\Services\ECPay_PaymentMethod;
use App\Services\EnvironmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ECPayment extends BaseController
{
    public function performPayment(Request $request){
        /**
        *    Credit信用卡付款產生訂單範例
        */

        if($request->amount && $request->amount>0){
            $amount = $request->amount;
            $PeriodType = 'M';
            $Frequency = '1';
            $PeriodAmount = $request->amount;
            $ExecTimes = '99';
        }else {
            //default 舊系統付款方式保留
            $amount = 888;
            $PeriodType = 'M';
            $Frequency = '1';
            $PeriodAmount = '888';
            $ExecTimes = '99';
        }

        //new payment
        if($request->type == 'cc_quarterly_payment'){
            $amount = 3988;
            $PeriodType = 'M';
            $Frequency = '3';
            $PeriodAmount = '3988';
            $ExecTimes = '99';
        }else if($request->type == 'cc_monthly_payment'){
            $amount = 1899;
            $PeriodType = 'M';
            $Frequency = '1';
            $PeriodAmount = '1899';
            $ExecTimes = '99';
        }else if($request->type == 'one_quarter_payment'){
            $amount = 3988;
            $PeriodType = '';
            $Frequency = '';
            $PeriodAmount = '';
            $ExecTimes = '';
        }else if($request->type == 'one_month_payment'){
            $amount = 1899;
            $PeriodType = '';
            $Frequency = '';
            $PeriodAmount = '';
            $ExecTimes = '';
        }

        try {
            $obj = new ECPay_AllInOne();

            if(EnvironmentService::isLocalOrTestMachine()){
                $envStr = '_test';
            }
            else{
                $envStr = '';
            }

            //服務參數
            if(($request->choosePayment=='Credit' || $request->type == 'cc_quarterly_payment' || $request->type == 'cc_monthly_payment') && $request->choosePaymentFlow=='funpoint'){
                //信用卡付款導向funpoint金流 && 使用者選擇funpoint付款方式
                $obj->ServiceURL = Config::get('funpoint.payment' . $envStr . '.ActionURL');   //服務位置
                $obj->HashKey = Config::get('funpoint.payment' . $envStr . '.HashKey');     //測試用Hashkey
                $obj->HashIV = Config::get('funpoint.payment' . $envStr . '.HashIV');      //測試用HashIV
                $obj->MerchantID = Config::get('funpoint.payment' . $envStr . '.MerchantID');  //測試用MerchantID
            }else {
                $obj->ServiceURL = Config::get('ecpay.payment' . $envStr . '.ActionURL');   //服務位置
                $obj->HashKey = Config::get('ecpay.payment' . $envStr . '.HashKey');     //測試用Hashkey，請自行帶入ECPay提供的HashKey
                $obj->HashIV = Config::get('ecpay.payment' . $envStr . '.HashIV');      //測試用HashIV，請自行帶入ECPay提供的HashIV
                $obj->MerchantID = Config::get('ecpay.payment' . $envStr . '.MerchantID');  //測試用MerchantID，請自行帶入ECPay提供的MerchantID
            }
            $obj->EncryptType = '1';                                                 //CheckMacValue加密類型，請固定填入1，使用SHA256加密


            //基本參數(請依系統規劃自行調整)
            $MerchantTradeNo = "SG".time() ;
            $ClientBackURL = Config::get('ecpay.payment'.$envStr.'.ClientBackURL'); //付款完成通知回傳的網址

            $banned_users = banned_users::where('vip_pass', 1)->where('member_id', $request->userId)->first();
            $warned_users = warned_users::where('vip_pass', 1)->where('member_id', $request->userId)->first();
            if($banned_users){
                $ClientBackURL .= '#banned_vip_pass';
            }
            if($warned_users){
                $ClientBackURL .= '#warned_vip_pass';
            }

            $obj->Send['ReturnURL']         = Config::get('ecpay.payment'.$envStr.'.ReturnURL');    //付款完成通知回傳的網址
            $obj->Send['ClientBackURL']     = $ClientBackURL;
            $obj->Send['MerchantTradeNo']   = $MerchantTradeNo;                        //訂單編號
            $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');                     //交易時間
            $obj->Send['TotalAmount']       = $amount;                                     //交易金額
            $obj->Send['TradeDesc']         = "SG-VIP(".$request->userId.")";                                //交易描述
            $obj->Send['PaymentType']       = "aio";
            $obj->Send['NeedExtraPaidInfo'] = 'Y';

            if($request->type=='one_quarter_payment' || $request->type=='one_month_payment') {
                //測試機測ALL才會回傳paymentInfo 正式機不確定 暫以ALL來寫
                $obj->Send['ChoosePayment'] = ECPay_PaymentMethod::ALL;
                $obj->Send['PaymentInfoURL'] = Config::get('ecpay.payment' . $envStr . '.PaymentInfoURL');

                if($request->choosePayment=='Credit'){
                    if($request->choosePaymentFlow=='funpoint'){
                        $obj->Send['ChoosePayment'] = ECPay_PaymentMethod::Credit;
                    }else {
                        $obj->Send['IgnorePayment'] = "WebATM#ATM#CVS#BARCODE";
                    }
                }elseif($request->choosePayment=='ATM'){
                    $obj->Send['IgnorePayment']  = "WebATM#Credit#CVS#BARCODE" ;
                    $obj->Send['ExpireDate']  = 30 ;
                }elseif($request->choosePayment=='CVS'){
                    $obj->Send['IgnorePayment']  = "WebATM#Credit#ATM#BARCODE" ;
                    $obj->Send['StoreExpireDate']  = 43200 ;
                }elseif($request->choosePayment=='BARCODE'){
                    $obj->Send['IgnorePayment']  = "WebATM#Credit#ATM#CVS" ;
                    $obj->Send['StoreExpireDate']  = 30 ;
                }
            }else {
                $obj->Send['ChoosePayment'] = ECPay_PaymentMethod::Credit;             //付款方式:Credit
                $obj->SendExtend['Redeem'] = false ;           //是否使用紅利折抵，預設false
                $obj->SendExtend['UnionPay'] = false;          //是否為聯營卡，預設false;

                //Credit信用卡定期定額付款延伸參數(可依系統需求選擇是否代入)
                //以下參數不可以跟信用卡分期付款參數一起設定
                $obj->SendExtend['PeriodAmount'] = $PeriodAmount;    //每次授權金額，預設空字串
                $obj->SendExtend['PeriodType'] = $PeriodType;    //週期種類，預設空字串
                $obj->SendExtend['Frequency'] = $Frequency;    //執行頻率，預設空字串
                $obj->SendExtend['ExecTimes'] = $ExecTimes;    //執行次數，預設空字串
            }

            // $obj->Send['IgnorePayment']     = ECPay_PaymentMethod::GooglePay ;           //不使用付款方式:GooglePay
            $obj->Send['CustomField1'] = $request->userId;
            $obj->Send['CustomField3'] = $request->type;
            $obj->Send['CustomField4'] = "VIP";

            //CustomField2 記錄前次單次VIP付費者未到期剩餘天數
            if ($request->remainDays) {
                $obj->Send['CustomField2'] = $request->remainDays;
            }

            //訂單的商品資料
            array_push($obj->Send['Items'], array('Name' => "SG-VIP(" . $request->userId . ")", 'Price' => (int)$amount, 'Currency' => "元", 'Quantity' => (int)"1", 'URL' => ""));


            //Credit信用卡分期付款延伸參數(可依系統需求選擇是否代入)
            //以下參數不可以跟信用卡定期定額參數一起設定
            //$obj->SendExtend['CreditInstallment'] = '' ;    //分期期數，預設0(不分期)，信用卡分期可用參數為:3,6,12,18,24
        

            
            # 電子發票參數
            /*
            $obj->Send['InvoiceMark'] = ECPay_InvoiceState::Yes;
            $obj->SendExtend['RelateNumber'] = "Test".time();
            $obj->SendExtend['CustomerEmail'] = 'test@ecpay.com.tw';
            $obj->SendExtend['CustomerPhone'] = '0911222333';
            $obj->SendExtend['TaxType'] = ECPay_TaxType::Dutiable;
            $obj->SendExtend['CustomerAddr'] = '台北市南港區三重路19-2號5樓D棟';
            $obj->SendExtend['InvoiceItems'] = array();
            // 將商品加入電子發票商品列表陣列
            foreach ($obj->Send['Items'] as $info)
            {
                array_push($obj->SendExtend['InvoiceItems'],array('Name' => $info['Name'],'Count' =>
                    $info['Quantity'],'Word' => '個','Price' => $info['Price'],'TaxType' => ECPay_TaxType::Dutiable));
            }
            $obj->SendExtend['InvoiceRemark'] = '測試發票備註';
            $obj->SendExtend['DelayDay'] = '0';
            $obj->SendExtend['InvType'] = ECPay_InvType::General;
            */

            //產生訂單(auto submit至ECPay)
            $obj->CheckOut();        
        } catch (\Exception $e) {
            //echo $e->getMessage();
        }
    }

    public function performTipInvite(Request $request)
    {
        /**
         *    Credit信用卡付款產生訂單範例
         */
        try {
            $obj = new ECPay_AllInOne();

            if (EnvironmentService::isLocalOrTestMachine()) {
                $envStr = '_test';
            } else {
                $envStr = '';
            }

            //服務參數
            $obj->ServiceURL = Config::get('ecpay.payment' . $envStr . '.ActionURL');   //服務位置
            $obj->HashKey = Config::get('ecpay.payment' . $envStr . '.HashKey');     //測試用Hashkey，請自行帶入ECPay提供的HashKey
            $obj->HashIV = Config::get('ecpay.payment' . $envStr . '.HashIV');      //測試用HashIV，請自行帶入ECPay提供的HashIV
            $obj->MerchantID = Config::get('ecpay.payment' . $envStr . '.MerchantID');  //測試用MerchantID，請自行帶入ECPay提供的MerchantID
            $obj->EncryptType = '1';                                                 //CheckMacValue加密類型，請固定填入1，使用SHA256加密


            //基本參數(請依系統規劃自行調整)
            $MerchantTradeNo = "SGTIP" . time();
            $obj->Send['ReturnURL'] = Config::get('ecpay.payment' . $envStr . '.postChatpayReturnURL');    //付款完成通知回傳的網址
            $obj->Send['ClientBackURL'] = Config::get('ecpay.payment'.$envStr.'.ClientChatpayBackURL');
            $obj->Send['MerchantTradeNo'] = $MerchantTradeNo;                        //訂單編號
            $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');                     //交易時間
            $obj->Send['TotalAmount'] = 1788;                                     //交易金額
            $obj->Send['TradeDesc'] = "SG-車馬費(" . $request->userId . ")";                                //交易描述
            $obj->Send['ChoosePayment'] = ECPay_PaymentMethod::Credit;             //付款方式:Credit
            $obj->Send['NeedExtraPaidInfo'] = 'Y';
            // $obj->Send['IgnorePayment']     = ECPay_PaymentMethod::GooglePay ;           //不使用付款方式:GooglePay
            $obj->Send['CustomField1'] = $request->userId;
            $obj->Send['CustomField2'] = $request->to;

            //訂單的商品資料
            array_push($obj->Send['Items'], array('Name' => "SG-車馬費(" . $request->userId . ")", 'Price' => (int)"1788", 'Currency' => "元", 'Quantity' => (int)"1", 'URL' => ""));


            //Credit信用卡分期付款延伸參數(可依系統需求選擇是否代入)
            //以下參數不可以跟信用卡定期定額參數一起設定
            //$obj->SendExtend['CreditInstallment'] = '' ;    //分期期數，預設0(不分期)，信用卡分期可用參數為:3,6,12,18,24

            $obj->SendExtend['Redeem'] = false;           //是否使用紅利折抵，預設false
            $obj->SendExtend['UnionPay'] = false;          //是否為聯營卡，預設false;

            # 電子發票參數
            /*
            $obj->Send['InvoiceMark'] = ECPay_InvoiceState::Yes;
            $obj->SendExtend['RelateNumber'] = "Test".time();
            $obj->SendExtend['CustomerEmail'] = 'test@ecpay.com.tw';
            $obj->SendExtend['CustomerPhone'] = '0911222333';
            $obj->SendExtend['TaxType'] = ECPay_TaxType::Dutiable;
            $obj->SendExtend['CustomerAddr'] = '台北市南港區三重路19-2號5樓D棟';
            $obj->SendExtend['InvoiceItems'] = array();
            // 將商品加入電子發票商品列表陣列
            foreach ($obj->Send['Items'] as $info)
            {
                array_push($obj->SendExtend['InvoiceItems'],array('Name' => $info['Name'],'Count' =>
                    $info['Quantity'],'Word' => '個','Price' => $info['Price'],'TaxType' => ECPay_TaxType::Dutiable));
            }
            $obj->SendExtend['InvoiceRemark'] = '測試發票備註';
            $obj->SendExtend['DelayDay'] = '0';
            $obj->SendExtend['InvType'] = ECPay_InvType::General;
            */

            //產生訂單(auto submit至ECPay)
            $obj->CheckOut();
        } catch (\Exception $e) {
            //echo $e->getMessage();
        }
    }

    public function performPayBack(Request $request)
    {
        /**
         *    Credit信用卡付款產生訂單範例
         */
        try {
            $obj = new ECPay_AllInOne();

            if (EnvironmentService::isLocalOrTestMachine()) {
                $envStr = '_test';
            } else {
                $envStr = '';
            }

            //服務參數
            $obj->ServiceURL = Config::get('ecpay.payment' . $envStr . '.ActionURL');   //服務位置
            $obj->HashKey = Config::get('ecpay.payment' . $envStr . '.HashKey');     //測試用Hashkey，請自行帶入ECPay提供的HashKey
            $obj->HashIV = Config::get('ecpay.payment' . $envStr . '.HashIV');      //測試用HashIV，請自行帶入ECPay提供的HashIV
            $obj->MerchantID = Config::get('ecpay.payment' . $envStr . '.MerchantID');  //測試用MerchantID，請自行帶入ECPay提供的MerchantID
            $obj->EncryptType = '1';                                                 //CheckMacValue加密類型，請固定填入1，使用SHA256加密


            //基本參數(請依系統規劃自行調整)
            $MerchantTradeNo = "SGPayBack" . time();
            $obj->Send['ReturnURL'] = Config::get('ecpay.payment' . $envStr . '.postChatpayReturnURL');    //付款完成通知回傳的網址
            $obj->Send['ClientBackURL'] = Config::get('ecpay.payment'.$envStr.'.ClientBackURL');
            $obj->Send['MerchantTradeNo'] = $MerchantTradeNo;                        //訂單編號
            $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');                     //交易時間
            $obj->Send['TotalAmount'] = $request->amount;                                     //交易金額
            $obj->Send['TradeDesc'] = "SG-補刷卡費(" . $request->userId . ")";                                //交易描述
            $obj->Send['ChoosePayment'] = ECPay_PaymentMethod::Credit;             //付款方式:Credit
            $obj->Send['NeedExtraPaidInfo'] = 'Y';
            // $obj->Send['IgnorePayment']     = ECPay_PaymentMethod::GooglePay ;           //不使用付款方式:GooglePay
            $obj->Send['CustomField1'] = $request->userId;

            //訂單的商品資料
            array_push($obj->Send['Items'], array('Name' => "SG-補刷卡費(" . $request->userId . ")", 'Price' => (int)$request->amount, 'Currency' => "元", 'Quantity' => (int)"1", 'URL' => ""));


            //Credit信用卡分期付款延伸參數(可依系統需求選擇是否代入)
            //以下參數不可以跟信用卡定期定額參數一起設定
            //$obj->SendExtend['CreditInstallment'] = '' ;    //分期期數，預設0(不分期)，信用卡分期可用參數為:3,6,12,18,24

            $obj->SendExtend['Redeem'] = false;           //是否使用紅利折抵，預設false
            $obj->SendExtend['UnionPay'] = false;          //是否為聯營卡，預設false;

            # 電子發票參數
            /*
            $obj->Send['InvoiceMark'] = ECPay_InvoiceState::Yes;
            $obj->SendExtend['RelateNumber'] = "Test".time();
            $obj->SendExtend['CustomerEmail'] = 'test@ecpay.com.tw';
            $obj->SendExtend['CustomerPhone'] = '0911222333';
            $obj->SendExtend['TaxType'] = ECPay_TaxType::Dutiable;
            $obj->SendExtend['CustomerAddr'] = '台北市南港區三重路19-2號5樓D棟';
            $obj->SendExtend['InvoiceItems'] = array();
            // 將商品加入電子發票商品列表陣列
            foreach ($obj->Send['Items'] as $info)
            {
                array_push($obj->SendExtend['InvoiceItems'],array('Name' => $info['Name'],'Count' =>
                    $info['Quantity'],'Word' => '個','Price' => $info['Price'],'TaxType' => ECPay_TaxType::Dutiable));
            }
            $obj->SendExtend['InvoiceRemark'] = '測試發票備註';
            $obj->SendExtend['DelayDay'] = '0';
            $obj->SendExtend['InvType'] = ECPay_InvType::General;
            */

            //產生訂單(auto submit至ECPay)
            $obj->CheckOut();
        } catch (\Exception $e) {
            //echo $e->getMessage();
        }
    }

    public function performValueAddedService(Request $request){
        /**
         *   加值服務訂單
         */
        $PeriodType = '';
        $Frequency = '';
        $ExecTimes = '';

        //new payment
        if($request->payment == 'cc_quarterly_payment'){
            $PeriodType = 'M';
            $Frequency = '3';
            $ExecTimes = '99';
        }else if($request->payment == 'cc_monthly_payment') {
            $PeriodType = 'M';
            $Frequency = '1';
            $ExecTimes = '99';
        }

        $amount = $request->amount;

        try {
            $obj = new ECPay_AllInOne();

            if(EnvironmentService::isLocalOrTestMachine()){
                $envStr = '_test';
            }
            else{
                $envStr = '';
            }

            //服務參數
            $obj->ServiceURL  = Config::get('ecpay.payment'.$envStr.'.ActionURL');   //服務位置
            $obj->HashKey     = Config::get('ecpay.payment'.$envStr.'.HashKey');     //測試用Hashkey，請自行帶入ECPay提供的HashKey
            $obj->HashIV      = Config::get('ecpay.payment'.$envStr.'.HashIV');      //測試用HashIV，請自行帶入ECPay提供的HashIV
            $obj->MerchantID  = Config::get('ecpay.payment'.$envStr.'.MerchantID');  //測試用MerchantID，請自行帶入ECPay提供的MerchantID
            $obj->EncryptType = '1';                                                 //CheckMacValue加密類型，請固定填入1，使用SHA256加密


            //基本參數(請依系統規劃自行調整)
            $MerchantTradeNo = "SG".time() ;
            $obj->Send['ReturnURL']         = Config::get('ecpay.payment'.$envStr.'.postValueAddServiceReturnURL') ;    //付款完成通知回傳的網址
            $obj->Send['ClientBackURL']     = Config::get('ecpay.payment'.$envStr.'.ClientBackURL') ;
            $obj->Send['MerchantTradeNo']   = $MerchantTradeNo;                        //訂單編號
            $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');                     //交易時間
            $obj->Send['TotalAmount']       = $amount;                                     //交易金額
            $obj->Send['TradeDesc']         = "SG-".$request->service_name."(".$request->userId.")";                                //交易描述
            $obj->Send['PaymentType']       = "aio";
            $obj->Send['NeedExtraPaidInfo'] = 'Y';

            if($request->payment=='one_quarter_payment' || $request->payment=='one_month_payment') {
                //測試機測ALL才會回傳paymentInfo 正式機不確定 暫以ALL來寫
                $obj->Send['ChoosePayment'] = ECPay_PaymentMethod::ALL;
                $obj->Send['PaymentInfoURL'] = Config::get('ecpay.payment' . $envStr . '.PaymentInfoURL');

                if($request->choosePayment=='Credit'){
                    $obj->Send['IgnorePayment']  = "WebATM#ATM#CVS#BARCODE" ;
                }elseif($request->choosePayment=='ATM'){
                    $obj->Send['IgnorePayment']  = "WebATM#Credit#CVS#BARCODE" ;
                    $obj->Send['ExpireDate']  = 30 ;
                }elseif($request->choosePayment=='CVS'){
                    $obj->Send['IgnorePayment']  = "WebATM#Credit#ATM#BARCODE" ;
                    $obj->Send['StoreExpireDate']  = 43200 ;
                }elseif($request->choosePayment=='BARCODE'){
                    $obj->Send['IgnorePayment']  = "WebATM#Credit#ATM#CVS" ;
                    $obj->Send['StoreExpireDate']  = 30 ;
                }
            }else {
                $obj->Send['ChoosePayment'] = ECPay_PaymentMethod::Credit;             //付款方式:Credit
                $obj->SendExtend['Redeem'] = false ;           //是否使用紅利折抵，預設false
                $obj->SendExtend['UnionPay'] = false;          //是否為聯營卡，預設false;

                //Credit信用卡定期定額付款延伸參數(可依系統需求選擇是否代入)
                //以下參數不可以跟信用卡分期付款參數一起設定
                $obj->SendExtend['PeriodAmount'] = $amount ;    //每次授權金額，預設空字串
                $obj->SendExtend['PeriodType']   = $PeriodType ;    //週期種類，預設空字串
                $obj->SendExtend['Frequency']    = $Frequency ;    //執行頻率，預設空字串
                $obj->SendExtend['ExecTimes']    = $ExecTimes ;    //執行次數，預設空字串
            }

            // $obj->Send['IgnorePayment']     = ECPay_PaymentMethod::GooglePay ;           //不使用付款方式:GooglePay
            $obj->Send['CustomField1']      = $request->userId;
            $obj->Send['CustomField3']      = $request->payment;
            $obj->Send['CustomField4']      = $request->service_name;
            //CustomField2 記錄前次單次VIP付費者未到期剩餘天數
            if($request->remainDays){
                $obj->Send['CustomField2']  = $request->remainDays;
            }
            //VVIP定期定額紀錄申請方案
            if($request->plan){
                $obj->Send['CustomField2']  = $request->plan;
            }
            //訂單的商品資料
            array_push($obj->Send['Items'], array('Name' => "SG-".$request->service_name."(".$request->userId.")", 'Price' => (int)$amount, 'Currency' => "元", 'Quantity' => (int) "1", 'URL' => ""));


            //產生訂單(auto submit至ECPay)
            $obj->CheckOut();
        } catch (\Exception $e) {
            //echo $e->getMessage();
        }
    }

    public function performMobileVerify(Request $request)
    {
        /**
         *    Credit信用卡付款產生訂單範例
         */
        try {
            $obj = new ECPay_AllInOne();

            if (EnvironmentService::isLocalOrTestMachine()) {
                $envStr = '_test';
            } else {
                $envStr = '';
            }

            //服務參數
            $obj->ServiceURL = Config::get('ecpay.payment' . $envStr . '.ActionURL');   //服務位置
            $obj->HashKey = Config::get('ecpay.payment' . $envStr . '.HashKey');     //測試用Hashkey，請自行帶入ECPay提供的HashKey
            $obj->HashIV = Config::get('ecpay.payment' . $envStr . '.HashIV');      //測試用HashIV，請自行帶入ECPay提供的HashIV
            $obj->MerchantID = Config::get('ecpay.payment' . $envStr . '.MerchantID');  //測試用MerchantID，請自行帶入ECPay提供的MerchantID
            $obj->EncryptType = '1';                                                 //CheckMacValue加密類型，請固定填入1，使用SHA256加密


            //基本參數(請依系統規劃自行調整)
            $MerchantTradeNo = "SGMOBILE" . time();
            $obj->Send['ReturnURL'] = Config::get('ecpay.payment' . $envStr . '.postMobileVerifyReturnURL');    //付款完成通知回傳的網址
            $obj->Send['ClientBackURL'] = Config::get('ecpay.payment'.$envStr.'.ClientMobileVerifyBackURL');
            logger('paramerers EC 付款完成通知回傳的網址 =>'.$obj->Send['ReturnURL']);
            logger('paramerers EC ClientBackURL =>'.$obj->Send['ClientBackURL']);
            $obj->Send['MerchantTradeNo'] = $MerchantTradeNo;                  //訂單編號
            $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');      //交易時間
            $obj->Send['TotalAmount'] = 30;                                    //交易金額
            $obj->Send['TradeDesc'] = "SG-手機驗證(" . $request->userId . ")";  //交易描述
            $obj->Send['ChoosePayment'] = ECPay_PaymentMethod::Credit;  //付款方式:Credit
            $obj->Send['NeedExtraPaidInfo'] = 'Y';             //是否需要額外的付款資訊
            $obj->Send['CustomField1'] = $request->userId;

            //訂單的商品資料
            array_push($obj->Send['Items'], array('Name' => "SG-手機驗證(" . $request->userId . ")", 'Price' => (int)"30", 'Currency' => "元", 'Quantity' => (int)"1", 'URL' => ""));


            //Credit信用卡分期付款延伸參數(可依系統需求選擇是否代入)
            //以下參數不可以跟信用卡定期定額參數一起設定
            //$obj->SendExtend['CreditInstallment'] = '' ;    //分期期數，預設0(不分期)，信用卡分期可用參數為:3,6,12,18,24

            $obj->SendExtend['Redeem'] = false;           //是否使用紅利折抵，預設false
            $obj->SendExtend['UnionPay'] = false;          //是否為聯營卡，預設false;

            # 電子發票參數
            /*
            $obj->Send['InvoiceMark'] = ECPay_InvoiceState::Yes;
            $obj->SendExtend['RelateNumber'] = "Test".time();
            $obj->SendExtend['CustomerEmail'] = 'test@ecpay.com.tw';
            $obj->SendExtend['CustomerPhone'] = '0911222333';
            $obj->SendExtend['TaxType'] = ECPay_TaxType::Dutiable;
            $obj->SendExtend['CustomerAddr'] = '台北市南港區三重路19-2號5樓D棟';
            $obj->SendExtend['InvoiceItems'] = array();
            // 將商品加入電子發票商品列表陣列
            foreach ($obj->Send['Items'] as $info)
            {
                array_push($obj->SendExtend['InvoiceItems'],array('Name' => $info['Name'],'Count' =>
                    $info['Quantity'],'Word' => '個','Price' => $info['Price'],'TaxType' => ECPay_TaxType::Dutiable));
            }
            $obj->SendExtend['InvoiceRemark'] = '測試發票備註';
            $obj->SendExtend['DelayDay'] = '0';
            $obj->SendExtend['InvType'] = ECPay_InvType::General;
            */

            //產生訂單(auto submit至ECPay)
            $obj->CheckOut();
        } catch (\Exception $e) {
            //echo $e->getMessage();
        }
    }
}
 
?>
