<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AwsSesMailLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\LogUserLogin;
use App\Models\Message;


class MailController extends Controller
{
    public function mailLog(Request $request)
    {
        $data = json_decode($request->getContent());
        //測試用JSON
        //$data = '{"notificationType":"Bounce","bounce":{"feedbackId":"0101017ef455f14a-f81f5518-ed9e-401f-b479-2860e1817f9c-000000","bounceType":"Transient","bounceSubType":"General","bouncedRecipients":[{"emailAddress":"Alex.yentz@msa.Hines.net","action":"failed","status":"4.4.7","diagnosticCode":"smtp; 550 4.4.7 Message expired: unable to deliver in 840 minutes.<421 4.4.1 Failed to establish connection>"}],"timestamp":"2022-02-13T18:26:20.000Z","remoteMtaIp":"64.99.80.121","reportingMTA":"dns; a27-35.smtp-out.us-west-2.amazonses.com"},"mail":{"timestamp":"2022-02-13T04:18:08.556Z","source":"admin@taiwan-sugar.net","sourceArn":"arn:aws:ses:us-west-2:428876234027:identity/taiwan-sugar.net","sourceIp":"172.105.237.114","sendingAccountId":"428876234027","messageId":"0101017ef14d662c-780f1b93-84cb-4286-9f9b-c9f339b31081-000000","destination":["Alex.yentz@msa.Hines.net"],"headersTruncated":false,"headers":[{"name":"Received","value":"from taiwan-sugar.net (li1891-114.members.linode.com [172.105.237.114]) by email-smtp.amazonaws.com with SMTP (SimpleEmailService-d-PPRK2UC3F) id R7k4w8ZK1XzQRkXgJ4Cr for Alex.yentz@msa.Hines.net; Sun, 13 Feb 2022 04:18:08 +0000 (UTC)"},{"name":"Message-ID","value":"<f90ca779c2f1a2305752b91c93c3fae5@taiwan-sugar.net>"},{"name":"Date","value":"Sun, 13 Feb 2022 12:18:05 +0800"},{"name":"Subject","value":"用戶驗證"},{"name":"From","value":"台灣甜心網 <admin@taiwan-sugar.net>"},{"name":"To","value":"Alex.yentz@msa.Hines.net"},{"name":"MIME-Version","value":"1.0"},{"name":"Content-Type","value":"multipart/alternative; boundary=\"_=_swift_1644725885_b53a67ed263ddcb6f5267774bab8ad16_=_\""}],"commonHeaders":{"from":["\"台灣甜心網\" <admin@taiwan-sugar.net>"],"date":"Sun, 13 Feb 2022 12:18:05 +0800","to":["Alex.yentz@msa.Hines.net"],"messageId":"<f90ca779c2f1a2305752b91c93c3fae5@taiwan-sugar.net>","subject":"用戶驗證"}}}';
        //$data = json_decode($data);
        $aws_ses_mail_log = new AwsSesMailLog;
        $aws_ses_mail_log->notificationtype = $data->notificationType;
        $aws_ses_mail_log->mail = $data->mail;
        
        switch($data->notificationType)
        {
            case 'Bounce':
                $aws_ses_mail_log->content = $data->bounce;
                break;
            case 'Delivery':
                $aws_ses_mail_log->content = $data->delivery;
                break;
            case 'Complaint':
                $aws_ses_mail_log->content = $data->complaint;
                break;
        }
        
        $aws_ses_mail_log->save();

    }

    public function viewMailLog(Request $request)
    {
        $start_date = Carbon::now()->subDays(1)->startOfDay();
        if($request->start_date)
        {
            $start_date = Carbon::createFromFormat('m/d/Y', $request->start_date)->startOfDay();
        }

        $end_date = Carbon::now();
        if($request->end_date)
        {
            $end_date = Carbon::createFromFormat('m/d/Y', $request->end_date)->endOfDay();
        }

        Log::Info($start_date);
        Log::Info($end_date);

        $mail_log = AwsSesMailLog::where('updated_at','>',$start_date)->where('updated_at','<',$end_date)->get();
        
        return view('admin.stats.mailLog')
        ->with('mail_log', $mail_log);
    }

    public function fakeMail(Request $request)
    {
        return view('admin.fakemail');
    }

    public function sendFakeMail(Request $request)
    {
        $str = "";
        $account = $request->account;
        $net = $request->net;
        $repeat = $request->repeat;
        $content = $request->content;
        if($repeat == "")
        {
            $address = $account.'@'.$net;
            \App\Jobs\SendFakeMail::dispatch($address, $content);
            $str .= $address;
        }
        else
        {
            for ($i = 0; $i <= $repeat; $i++){
                $address =$account .'+'. $i .'@'.$net;
                \App\Jobs\SendFakeMail::dispatch($address, $content);
                $str .= $address . ' , ';
            }
        }
        return back()
                ->with('message','寄送成功 '.$str);
    }

    public function test_stat(Request $request)
    {
        $start_date = $request->start_date ?? "2022-06-25 14:00:00";
        $end_date = $request->end_date ?? "2022-06-25 15:00:00";
        $men_total = $request->men_total ?? 10;

        // email/是否封鎖/暱稱/關於我/約會模式

        // 在 2022/6/25 1400~1500 間有 login 的女會員
        // and 曾經有發訊息給超過 10 個男會員
        // and 對單一男會員發出訊息都沒有超過10則的

        // 在...間有登入的女會員
        $users_id = LogUserLogin::join('users', 'users.id', '=', 'log_user_login.user_id')
            ->where('engroup', 2)
            ->where('log_user_login.created_at', '>=', $start_date)
            ->where('log_user_login.created_at', '<=', $end_date)
            ->groupBy('user_id')
            ->get()
            ->pluck('user_id');

        $messages = Message::whereIn("from_id", $users_id)
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->get()->toArray();
        
        // 不重複的發送者 ID，將這些 ID 設為陣列的 key
        $messages_from_id = array_fill_keys(array_unique(array_column($messages, 'from_id')), ["total" => 0, "to_ids" => []]);
        
        collect($messages)->each(function ($msg, $key) use (&$messages_from_id) {
            // to_ids: 對方 ID 表
            // to_id_stat: 各 ID 收到的訊息數統計
            $messages_from_id[$msg['from_id']]["total"]++;
            if(!in_array($msg['to_id'], $messages_from_id[$msg['from_id']]["to_ids"])) {
                $messages_from_id[$msg['from_id']]["to_ids"][] = $msg['to_id'];
                $messages_from_id[$msg['from_id']]["to_id_stat"][$msg['to_id']]["count"] = 1;
            }
            else {
                $messages_from_id[$msg['from_id']]["to_id_stat"][$msg['to_id']]["count"]++;
            }
        });

        // 曾經有發訊息給超過 10 個男會員
        $filtered = collect($messages_from_id)->filter(function ($from_stat, $key) use ($men_total) {
            return count($from_stat["to_ids"]) > $men_total;
        });

        return $filtered;
    }
}
