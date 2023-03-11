<?php

namespace App\Services;

use App\Models\UserMeta;
use App\Repositories\AnonymousEvaluationChatReportRepository;
use App\Repositories\AnonymousEvaluationChatRepository;
use App\Repositories\AnonymousEvaluationMessageRepository;
use App\Repositories\EvaluationRepository;
use Illuminate\Support\Facades\Crypt;

class EvaluationService
{
    
    protected $evaluation;
    protected $anonymousEvaluationChat;
    protected $anonymousEvaluationMessage;
    public function __construct(EvaluationRepository $evaluation,AnonymousEvaluationChatRepository $anonymousEvaluationChat,AnonymousEvaluationChatReportRepository $anonymousEvaluationChatReport,AnonymousEvaluationMessageRepository $anonymousEvaluationMessage) {
        $this->evaluation = $evaluation;
        $this->anonymousEvaluationChat = $anonymousEvaluationChat;
        $this->anonymousEvaluationChatReport = $anonymousEvaluationChatReport;
        $this->anonymousEvaluationMessage = $anonymousEvaluationMessage;
    }

    public function getActiveChats($data){
        $item = [];
        $chats = $this->anonymousEvaluationChat->getActiveChats($data['user_id']);

        foreach($chats as $k => $v){
            if ($v->messages()->count() == 0) continue;
            if (!$v->evaluation()->first()) continue;
            $is_anonymous = $v->evaluation()->first()->from_id!=$data['user_id'];
            $to_id =  $v->evaluation()->first()->to_id;
            $user_meta = UserMeta::where('user_id', $to_id)->first();
            $message = $v->messages()->orderBy('created_at','DESC')->first();
            $avatar = file_exists(public_path().$user_meta->pic)?$user_meta->pic:null;
            $arr = [
                'user_id'   => $data['user_id'],
                'avatar'    => $is_anonymous?null:$avatar,
                'blurry_avatar' =>  $user_meta->blurryAvatar,
                'name'      => $is_anonymous?"匿名帳號":$v->evaluation()->first()->receiver()->first()->name,
                'chatid'    => Crypt::encryptString($v->id),
                'count'     => $v->messages()->where('read',0)->where('user_id','!=',$data['user_id'])->count(),
                'content'   => isset($message->content)?$message->content:"",
                'created_at'=> date('Y-m-d H:i:s',$message->created_at->timestamp),
            ];
            if($message->is_row_delete_1 <> $data['user_id'] && $message->is_row_delete_2 <> $data['user_id']) {
                $item[] = $arr;
            }
            
        }
        return $item;
    }

    public function getAnonymousEvaluationChatInfo($evaluationid,$userid){
        if(!$this->evaluation->checkAnonymous($evaluationid,$userid)){
            return false;
        }
        if(!$this->anonymousEvaluationChat->checkChat($evaluationid)){
            $members = $this->evaluation->getEvaluationMembers($evaluationid);
            $m = [];
            $m[]=$members->from_id;
            $m[]=$members->to_id;
            $membersid = implode("@",$m);
            $data = [
                "evaluation_id"  => $evaluationid,
                "members"   => "@{$membersid}@",
                "status"    => 1,
            ];
            $this->anonymousEvaluationChat->createChat($data);
        }
        $chatid = $this->anonymousEvaluationChat->getChatIdByEvaluationId($evaluationid);
        return $chatid;
    }

    public function getEvaluator($chatid,$userid){
        $evaluationId = $this->anonymousEvaluationChat->getEvaluationIdByChatId($chatid);
        $members = $this->evaluation->getEvaluationMembers($evaluationId);
        return ($members->from_id==$userid)?$members->receiver()->first()->name:"匿名評價溝通";
    }

    public function getMessage($data){
        $messages = $this->anonymousEvaluationMessage->getMessage($data);
        $data = [];
        foreach($messages as $key => $message){
            $arr = [];
            $arr['status'] = ($message->deleted_at!=null)?0:1;
            $arr['unsend'] = $message->unsend;
            $arr['id'] = $message->id;
            if($arr['unsend'] == 0){
                $arr['role'] = ((\Auth::user()->id==$message->user_id)?"sender":"receiver");
                $arr['gender']  = $message->user->engroup;
                $arr['reply_id']  = $message->reply_id;
                $arr['read']  = $message->read;
                $arr['content']  = $message->content;
                $arr['pictures']  = json_decode($message->pictures);

                $post_msg_user_meta = UserMeta::where('user_id', $message->user_id)->first();
                $arr['avatar']  = $post_msg_user_meta->pic;
                $blurryAvatar = $post_msg_user_meta->blurryAvatar? explode(",", $post_msg_user_meta->blurryAvatar): '';
                if(count($blurryAvatar) > 1 && \Auth::user()->id!=$message->user_id){
                    $nowB = \Auth::user()->isVip()? "VIP" : "general";
                    
                    if( in_array($nowB, $blurryAvatar)){
                        $isBlur = true;
                    } else {
                        $isBlur = false;
                    }
                } else {
                    $isBlur = false;
                }
                $arr['isBlur'] = $isBlur;
                
                $avatar_exists = file_exists(public_path().$post_msg_user_meta->pic);
                if(!$avatar_exists) {
                    $arr['avatar']  = null;
                }else if($avatar_exists && (\Auth::user()->id!=$message->user_id)){
                    $evaluationId = $this->anonymousEvaluationChat->getEvaluationIdByChatId($message->anonymous_evaluation_chat_id);
                    $evaluation_to_user = $this->evaluation->getEvaluationMembers($evaluationId)->receiver()->first()->id;
                    if(\Auth::user()->id == $evaluation_to_user){                        
                        $arr['avatar']  = null;
                    }
                }
                
            }else{
                $arr['content']  = ((\Auth::user()->id==$message->user_id)?"您":"對方")."已收回訊息";
            }
            $arr['created_at']  = $message->created_at->timestamp;
            $data[date('Y-m-d',$message->created_at->timestamp)][] = $arr;
        }
        return $data;
    }

    public function readMessage($data){
        $members = $this->anonymousEvaluationChat->getChatMembersById($data['anonymous_evaluation_chat_id']);
        $senderid = $this->anonymousEvaluationMessage->getMessageUserById($data['messageid']);

        $memberArr = explode('@',trim($members,'@'));
        if($senderid!=$data['user_id']&&in_array($data['user_id'],$memberArr)){
            $this->anonymousEvaluationMessage->readMessage($data['messageid']);
        }else{
            abort(404);
        }
    }
    public function revokeMessage($data){
        $this->anonymousEvaluationMessage->revoke($data);
    }

    public function checkMessageInChat($messageid,$chatid){
        $message = $this->anonymousEvaluationMessage->getMessageInfoById($messageid);
        return ($message->anonymous_evaluation_chat_id==$chatid);
    }
    public function accusationMessage($data){
        $message = $this->anonymousEvaluationMessage->getMessageInfoById($data['message_id']);

        if($message->anonymous_evaluation_chat_id == $data['anonymous_evaluation_chat_id']){
            $data['accused_user_id'] = $message->user_id;
            return $this->anonymousEvaluationChatReport->create($data);
        }else{
            return false;
        }
    }

    public function sendMessage($data){
        $this->anonymousEvaluationMessage->create($data);
    }

    public function chatToggler($chat_id, $open_channel){
        $this->anonymousEvaluationChat->chatToggler($chat_id, $open_channel);
    }

    public function deleteBetween($data) {
        $this->anonymousEvaluationMessage->deleteBetween($data);
    }
}
