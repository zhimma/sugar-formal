<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Config;
use Session;

class LineNotify extends Controller
{
    //
    /**
     * 註冊服務訊息通知
     *
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function lineNotifyCallback(Request $request) {
        $user = $request->user();
        //$username = request()->get('username');
        $code = request()->get('code');
//        $callbackUrl = route('lineNotifyCallback', ['user' => $user]);
        User::where('id',$user->id)->update(['line_notify_auth_code' => $code]);
        ### LINE Access Token ###
        $this->getNotifyAccessToken($user,$code);
        session()->flash('message', '連動完成!');
        return redirect('/dashboard');
    }
         /**
     * 取得LINE Notify Access Token
     *
     * @param [type] $store_id
     * @param [type] $user_id
     * @param [type] $code
     * @param [type] $redirect_uri
     * @return void
         */
    private function getNotifyAccessToken($user,$code) {
            $apiUrl = config('line.line_notify.token_url');

            $params = [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => config('line.line_notify.callback_url'),
                'client_id' => config('line.line_notify.client_id'),
                'client_secret' => config('line.line_notify.client_secret')
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            $output = curl_exec($ch);
            curl_close($ch);
            /**
             * {
             *      "status": 200,
             *      "message": "access_token is issued",
             *      "access_token": ""
             *  }
             */
            $result = json_decode($output, true);
            $token = $result['access_token'];
            User::where('id',$user->id)->update(['line_notify_token' => $token]);
        }

    /**
     * LINE-Notify 取消服務訊息通知
     *
     * @return RedirectResponse|Redirector
     * @throws \Exception
     */
    public function lineNotifyCancel(Request $request) {
        $user = $request->user();
        # 若使用者已連動則進行取消連動作業
        if (!empty($user->line_notify_token)) {
            $this->lineNotifyRevoke($user->id, $user->line_notify_token);
            session()->flash('message', '解除連動');
            return redirect('/dashboard');
        }

        return redirect('/dashboard');
    }


    /**
     * 取消服務通知
     *
     * @param [type] $access_token
     * @return bool
     * @throws \Exception
     */
    public function lineNotifyRevoke($id, $line_token) {
//        $response = curl_init();
//        curl_setopt($response, CURLOPT_URL, 'https://notify-api.line.me/api/revoke');
//        curl_setopt($response, CURLOPT_HTTPHEADER, [
//            'Authorization: Bearer ' . $line_token,
//            'Content-Type: application/x-www-form-urlencoded'
//        ]);
//        curl_setopt($response, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($response, CURLOPT_POST, true);
//        curl_setopt($response, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($response, CURLOPT_SSL_VERIFYHOST, false);
//        curl_setopt($response, CURLOPT_HEADER, true);
//        $output = curl_exec($response);
//        $output = json_decode($output, true);
//        curl_close($response);

        $url = config('line.line_notify.revoke_url');
        $type = "POST";
        $header = [
            "Authorization:	Bearer ".$line_token,
            "Content-Type: application/x-www-form-urlencoded"
        ];

        $response = $this->curl($url,$type,[],[],$header);
        $response = json_decode($response,true);
        if($response["status"] != "200"){
            throw new \Exception("error ".$response["Status"]." : ".$response["message"]);
        }else{
            User::where('id',$id)->update(['line_notify_token' => null]);
        }
        /**
         * {"status":200,"message":"ok"}
         */
//        if (in_array($output['status'],[200,401])) {
//            users::where('id',$id)->update(['line_notify_token' => null]);
//        }
//        return $output;
        return true;
    }

    private function curl($url,$type="GET",$data=[],$options=[],$header=[]) {
        $ch = curl_init();

        if(strtoupper($type) == "GET"){
            $url = $url."?".http_build_query($data);
        }else{//POST
            if(in_array("Content-Type: multipart/form-data", $header)){
                $options = [
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $data
                ];
            }else{
                $options = [
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => http_build_query($data)
                ];
            }
        }

        $defaultOptions = [
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_RETURNTRANSFER => true, 		// 不直接出現回傳值
            CURLOPT_SSL_VERIFYPEER => false,		// ssl
            CURLOPT_SSL_VERIFYHOST => false,		// ssl
            CURLOPT_HEADER => true 					//取得header
        ];
        $options = $options + $defaultOptions;
        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);

        if(curl_error($ch)){
            throw new \Exception(curl_error($ch), curl_errno($ch));
        }

        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        $response = substr($response, $headerSize);

        return $response;
    }


}
