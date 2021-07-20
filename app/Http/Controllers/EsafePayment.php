<?php
namespace App\Http\Controllers;

//載入SDK(路徑可依系統規劃自行調整)
use App\Services\EsafePay_AllInOne;
use App\Services\EsafePay_PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class EsafePayment extends BaseController
{
    public function performPayment(Request $request){
        /**
        *    Credit信用卡付款產生訂單範例
        */    
        try {      
            $obj = new EsafePay_AllInOne();

            if(\App::environment('local')){
                $envStr = '_test';
            }
            else{
                $envStr = '';
            }
            //服務參數
            $obj->ServiceURL    = Config::get('esalepay.payment'.$envStr.'.ActionURL');   //服務位置
            $obj->MerchantID    = Config::get('esalepay.payment'.$envStr.'.MerchantID_'.$request->transactionType);  //測試用MerchantID
            $obj->transPassword = Config::get('esalepay.payment'.$envStr.'.transPassword');
            //基本參數(請依系統規劃自行調整)
            
            $MerchantTradeNo = "SG".time() ;
            $obj->Send['web']               = $obj->MerchantID;
            $obj->Send['MN']                = 888;                                     //交易金額
            $obj->Send['OrderInfo']         = "SG-VIP(".$request->userId.")";            //交易內容
            $obj->Send['Td']                = $MerchantTradeNo;                         //訂單編號
            $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');                     //交易時間
            $obj->Send['ChooseSubPayment']  = $request->transactionType;;        //付款方式:Credit
            $obj->Send['Card_Type']         = '';
            $obj->Send['Term']              = '';
            //store(paycode,payment)
            $obj->Send['DueDate']           = date('Ymd', strtotime("+7 days"));
            $obj->Send['UserNo']            = $request->userId;
            $obj->Send['BillDate']          = date('Ymd'); //列帳日期
            //payment
            $obj->Send['ProductName1']      = 'SG-VIP'; //產品名稱
            $obj->Send['ProductPrice1']     = 888; //產品單價
            $obj->Send['ProductQuantity1']  = 1; //產品數量
            $obj->Send['AgencyType']        = 1;
            $obj->Send['AgencyBank']        = '';
            
            $obj->Send['ChkValue']          = $obj->getChkValue($obj->Send['web'] . $obj->transPassword . $obj->Send['MN'] . $obj->Send['Term']); //交易檢查碼（SHA1雜湊值並轉成大寫）
             //產生訂單(auto submit至ECPay)
            $obj->CheckOut();        
        } catch (\Exception $e) {

        } 
    }
    public function esafeCreditCard(Request $request)
    {
        $request->transactionType = EsafePay_PaymentMethod::CreditCard;
        return $this->performPayment($request);
    }
    public function esafePayment(Request $request)
    {
        $request->transactionType = EsafePay_PaymentMethod::PayMent;
        return $this->performPayment($request);
    }
    public function esafePayCode(Request $request)
    {
        $request->transactionType = EsafePay_PaymentMethod::PayCode;
        return $this->performPayment($request);
    }
    public function esafeWebATM(Request $request)
    {
        $request->transactionType = EsafePay_PaymentMethod::WebATM;
        return $this->performPayment($request);
    }
}
 
?>