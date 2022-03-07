<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AwsSesMailLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


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

    public function sendFakeMail(Request $request)
    {
        $str = "";
        $repeat = request()->repeat ?? 1;
        $content = request()->str ?? "123";
        for ($i = 0; $i < $repeat; $i++){
            $address = 'lzong.tw+'. $i .'@gmail.com';
            \App\Jobs\SendFakeMail::dispatch($address, $content);
            $str .= $address . '<br>';
        }
        return $str;
    }
}
