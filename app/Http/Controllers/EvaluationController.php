<?php

namespace App\Http\Controllers;
use App\Jobs\CheckECpayForValueAddedService;
use Illuminate\Http\Request;
use App\Services\EvaluationService;
use App\Models\SimpleTables\warned_users;
use App\Services\AdminService;
use App\Models\AdminCommonText;
use App\Services\UserService;
use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\AnonymousEvaluationChatPicturesRequest;
use App\Http\Requests\AnonymousEvaluationChatMessageRequest;

class EvaluationController extends BaseController
{
    protected $evaluationService;
    
    public function __construct(EvaluationService $evaluationService,UserService $userService){
        parent::__construct();
        $this->evaluationService = $evaluationService;
        $this->userService = $userService;
    }

    public function anonymousEvaluationChat($evaluationid){
        $userid = \Auth::user()->id;
        $chatRoom = $this->evaluationService->getAnonymousEvaluationChatInfo($evaluationid,$userid);
        if(!$chatRoom){
            abort(404);
        }
        $chatid = Crypt::encryptString($chatRoom);
        return redirect()->route('getAnonymousEvaluationChatRoom',["chatid"=>$chatid]);
    }

    public function anonymousEvaluationChatRoom(Request $request,$chatid){
        $dechatid = Crypt::decryptString($chatid);
        $userid = $request->user()->id;
        $roomName = $this->evaluationService->getEvaluator($dechatid,$userid);
        $this->evaluationService->chatToggler($dechatid, 1);

        return view('new.dashboard.anonymousEvaluationChat')->with('chatid',$chatid)->with('roomName',$roomName);
    }

    public function getAnonymousEvaluationChatMessage($chatid,$messageid=0){
        $dechatid = Crypt::decryptString($chatid);
        $data = [
            'anonymous_evaluation_chat_id'    => $dechatid,
            'id'    => $messageid
        ];
        $messages = $this->evaluationService->getMessage($data);
        return response()->json(['msg' => 'OK','data'=>$messages]);
    }

    public function readAnonymousEvaluationChatMessage(Request $request,$chatid){
        $dechatid = Crypt::decryptString($chatid);
        $messageid = $request->input('messageid');
        $data = [
            'anonymous_evaluation_chat_id'    => $dechatid,
            'user_id'   => $request->user()->id,
            'messageid' => $messageid
        ];
        $this->evaluationService->readMessage($data);
        return response()->json(['msg' => 'OK']);
    }

    public function sendAnonymousEvaluationChatMessage(Request $request,$chatid){
        $dechatid = Crypt::decryptString($chatid);
        $data = [
            'anonymous_evaluation_chat_id'    => $dechatid,
            'user_id'   => $request->user()->id,
            'read'   => 0,
            'content'   => $request->input('content'),
        ];
        if($request->input('replyid')!=null)
            $data['reply_id'] = $request->input('replyid');

        if($request->hasfile('files')){
            $paths = [];
            foreach($request->file('files') as $file){
                $path = \Storage::disk('public')->put('anonymous_evaluation_chat',$file);
                $paths[] = $path;
            }
            $data['pictures'] = json_encode($paths);
        }
        
        $this->evaluationService->sendMessage($data);
        return response()->json(['msg' => 'OK']);
    }

    public function accusationAnonymousEvaluationChatMessage(Request $request,$chatid){
        $dechatid = Crypt::decryptString($chatid);
        $data = [
            'anonymous_evaluation_chat_id'    => $dechatid,
            'message_id'    =>  $request->input('messageid'),
            'user_id'   => $request->user()->id,
            'content'    =>  $request->input('content'),
        ];
        $this->evaluationService->accusationMessage($data);
        return response()->json(['msg' => 'OK']);
    }

    public function revokeAnonymousEvaluationChatMessage(Request $request,$chatid){
        $dechatid = Crypt::decryptString($chatid);
        $data = [
            'anonymous_evaluation_chat_id'    => $dechatid,
            'id'    =>  $request->input('messageid'),
            'user_id'   => $request->user()->id,
        ];
        $this->evaluationService->revokeMessage($data);
        return response()->json(['msg' => 'OK']);
    }

    public function deleteBetweenGET($chatid, $uid) {
        $dechatid = Crypt::decryptString($chatid);
        $data = [
            'chat_id'    => $dechatid,
            'user_id'   => $uid,
        ];
        $this->evaluationService->deleteBetween($data);

        return redirect('dashboard/chat2/');
    }

    public function deleteBetweenGetAll($uid) {
        $data = [
            'user_id' => $uid,
        ];
        $messages = $this->evaluationService->getActiveChats($data);
        foreach($messages as $msg){
            $dechatid = Crypt::decryptString($msg['chatid']);
            $data = [
                'chat_id'    => $dechatid,
                'user_id'   => $msg['user_id'],
            ];
            $this->evaluationService->deleteBetween($data);
        }
        return redirect('dashboard/chat2/');
    }

    public function getActiveChats(Request $request){
        $data = [
            'user_id' => $request->user()->id,
        ];
        $messages = $this->evaluationService->getActiveChats($data);
        return response()->json(['msg' => 'OK','data'=>$messages]);
    }

    public function closeChatRoom(Request $request,$chatid){
        $dechatid = Crypt::decryptString($chatid);
        $this->evaluationService->chatToggler($dechatid, 0);
        
        return response()->json(['msg' => 'OK']);
    }
}
