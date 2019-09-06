<?php

namespace App\Services;

abstract class EsafePay_PaymentMethod {

    /**
     * 不指定付款方式。
     */
    const ALL = 'ALL';
    /**
     * 信用卡付費。
     */
    const CreditCard = 'CreditCard';
    /**
     * 銀聯卡付費。
     */
    const UnionPay = 'UnionPay';
    /**
     * 超商付費(條碼)。
     */
    const PayMent = 'PayMent';
    /**
     * 超商付費（代碼）。
     */
    const PayCode = 'PayCode';
    /**
     * 網路 ATM。
     */
    const WebATM = 'WebATM';
    /**
     * 自動櫃員機。
     */
    const ATM = 'ATM';

    
}

/**
 * 電子發票開立註記。
 */
abstract class EsafePay_InvoiceState {
    /**
     * 需要開立電子發票。
     */
    const Yes = 'Y';

    /**
     * 不需要開立電子發票。
     */
    const No = '';
}

class EsafePay_AllInOne {

    public $ServiceURL    = 'ServiceURL';   //服務位置
    public $merchantID    = 'S1234567890'; //商家代號（信用卡）（可登入商家專區至「服務設定」中查詢Buysafe服務的代碼）
    public $transPassword = 'abcd1234'; //交易密碼（可登入商家專區至「密碼修改」處設定，此密碼非後台登入密碼）
    public $isProduction  = false; //是否為正式平台（true為正式平台，false為測試平台）
    
    function __construct() {
        $this->Send = array(
            'web' => $this->merchantID,//商家代號
            'MN' => 888, //交易金額
            'OrderInfo' => '', //交易內容
            'Td' => '', //商家訂單編號
            'sna' => '', //消費者姓名
            'sdt' => '', //消費者電話（不可有特殊符號）
            'email' => '', //消費者Email
            'note1' => '', //備註1（自行應用）
            'note2' => '', //備註2（自行應用）
            'ChooseSubPayment' => '',
            'Card_Type' => '', //交易類別(信用卡交易:請帶空字串""或"0"，銀聯卡交易:請帶"1"))
            'Country_Type' => '', //語言類別(中文:請帶空字串""，英文:請帶"EN"，日文:請帶"JIS")
            'Term' => '', //分期期數
            'CargoFlag' => '', //空白 or 0 不需搭配物流、1 搭配物流
            'StoreID' => '', //空白(紅陽端提供選擇) or 參考emap_711
            'StoreName' => '', //空白(紅陽端提供選擇) or 參考emap_711
            'BuyerCid' => '', //買方統一編號
            'DonationCode' => '', //捐贈碼
            "InvoiceMark"       => EsafePay_InvoiceState::No,
            //'ChkValue' => getChkValue($web . $transPassword . $MN . $Term), //交易檢查碼（SHA1雜湊值並轉成大寫）
        );
    }

    /**
     * 檢查交易檢查碼是否正確（SHA1雜湊值）
     */
    function getChkValue($string){
        return strtoupper(sha1($string));
    }

    //產生訂單
    function CheckOut($target = "_self") {
        EsafePay_Send::CheckOut($target,$this->Send,'ServiceURL',$this->ServiceURL);
    }

}

abstract class EsafePay_Aio
{

    protected static function ServerPost($parameters ,$ServiceURL) {
        $ch = curl_init();

        if (FALSE === $ch) {
            throw new \Exception ('curl failed to initialize');
        }

        curl_setopt($ch, CURLOPT_URL, $ServiceURL);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
        $rs = curl_exec($ch);

        if (FALSE === $rs) {
            throw new \Exception (curl_error($ch), curl_errno($ch));
        }

        curl_close($ch);

        return $rs;
    }

    protected static function HtmlEncode($target = "_self", $arParameters, $ServiceURL, $paymentButton = '') {
        //生成表單，自動送出
        $szHtml =  '<!DOCTYPE html>';
        $szHtml .= '<html>';
        $szHtml .=     '<head>';
        $szHtml .=         '<meta charset="utf-8">';
        $szHtml .=     '</head>';
        $szHtml .=     '<body>';
        $szHtml .=         "<form id=\"__ecpayForm\" method=\"post\" target=\"{$target}\" action=\"{$ServiceURL}\">";

        foreach ($arParameters as $keys => $value) {
            $szHtml .=         "<input type=\"hidden\" name=\"{$keys}\" value=\"". htmlentities($value) . "\" />";
        }

        

        if(!empty($paymentButton))
        {
            $szHtml .=          "<input type=\"submit\" id=\"__paymentButton\" value=\"{$paymentButton}\" />";
        }

        $szHtml .=         '</form>';

        if(empty($paymentButton))
        {
            $szHtml .=         '<script type="text/javascript">document.getElementById("__ecpayForm").submit();</script>';
        }

        $szHtml .=     '</body>';
        $szHtml .= '</html>';

        return $szHtml;
    }
}

class EsafePay_Send extends EsafePay_Aio
{
    //付款方式物件
    public static $PaymentObj ;

    protected static function process($arParameters = array(),$arExtend = array())
    {
        //宣告付款方式物件
        $PaymentMethod    = 'App\Services\EsafePay_'.$arParameters['ChooseSubPayment'];
        
        self::$PaymentObj = new $PaymentMethod;

        // //檢查參數
        $arParameters = self::$PaymentObj->check_string($arParameters);
        //過濾
        $arExtend = self::$PaymentObj->filter_string($arParameters);
        //合併共同參數及延伸參數
        return $arExtend ;
    }

    static function CheckOut($target = "_self",$arParameters = array(),$arExtend = array(),$ServiceURL=''){
        $arParameters = self::process($arParameters,$arExtend);
        //生成表單，自動送出
        $szHtml = parent::HtmlEncode($target, $arParameters, $ServiceURL, '') ;
        echo $szHtml ;
        exit;
    }
}

Abstract class EsafePay_Verification
{
   
    //檢查共同參數
    public function check_string($arParameters = array()){

        $arErrors = array();
        if (strlen($arParameters['web']) == 0) {
            array_push($arErrors, 'web is required.');
        }
        if (strlen($arParameters['web']) > 12) {
            array_push($arErrors, 'web max langth as 12.');
        }

        if (strlen($arParameters['ReturnURL']) == 0) {
            array_push($arErrors, 'ReturnURL is required.');
        }
        if (strlen($arParameters['ClientBackURL']) > 200) {
            array_push($arErrors, 'ClientBackURL max langth as 200.');
        }

        if (strlen($arParameters['Td']) == 0) {
            array_push($arErrors, 'Td is required.');
        }
        if (strlen($arParameters['Td']) > 20) {
            array_push($arErrors, 'Td max langth as 20.');
        }
        if (strlen($arParameters['MerchantTradeDate']) == 0) {
            array_push($arErrors, 'MerchantTradeDate is required.');
        }
        if (strlen($arParameters['MN']) == 0) {
            array_push($arErrors, 'MN is required.');
        }
        if (strlen($arParameters['OrderInfo']) == 0) {
            array_push($arErrors, 'OrderInfo is required.');
        }
        if (strlen($arParameters['OrderInfo']) > 200) {
            array_push($arErrors, 'OrderInfo max langth as 200.');
        }
        if (strlen($arParameters['ChooseSubPayment']) == 0) {
            array_push($arErrors, 'ChooseSubPayment is required.');
        }

        // 檢查CheckMacValue加密方式
        // if (strlen($arParameters['ChkValue']) > 1) {
        //     array_push($arErrors, 'ChkValue max langth as 1.');
        // }

        if (sizeof($arErrors)>0) throw new \Exception (join('<br>', $arErrors));

        return $arParameters ;
    }

}

class EsafePay_CreditCard extends EsafePay_Verification 
{
    public $arPayMentExtend = array(
        "web" => '',
        "MN" => 0,
        'OrderInfo' => '', //交易內容
        'Td' => '', //商家訂單編號
        'sna' => '', //消費者姓名
        'sdt' => '', //消費者電話（不可有特殊符號）
        'email' => '', //消費者Email
        'note1' => '', //備註1（自行應用）
        'note2' => '', //備註2（自行應用）
        'Card_Type' => '', //交易類別(信用卡交易:請帶空字串""或"0"，銀聯卡交易:請帶"1"))
        'Country_Type' => '', //語言類別(中文:請帶空字串""，英文:請帶"EN"，日文:請帶"JIS")
        'Term' => '', //分期期數
        'CargoFlag' => '', //空白 or 0 不需搭配物流、1 搭配物流
        'StoreID' => '', //空白(紅陽端提供選擇) or 參考emap_711
        'StoreName' => '', //空白(紅陽端提供選擇) or 參考emap_711
        'BuyerCid' => '', //買方統一編號
        'DonationCode' => '', //捐贈碼
        'ChkValue'=>'',
    );
    // 清除多餘的
    function filter_string($arExtend = array()){
        $arPayMentExtend = array_keys($this->arPayMentExtend);
        foreach ($arExtend as $key => $value) {
            if (!in_array($key,$arPayMentExtend )) {
                unset($arExtend[$key]);
            }
        }
    }
}