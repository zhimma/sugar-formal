<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Models\MessageRoom;
use App\Models\MessageRoomUserXref;
class Chat extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user = request()->user();
        //
        $msgController = resolve(Message_newController::class);
        $m = $msgController->postChat($request, true);
        if(is_array($m)) return $m;
        $m->load('parent_message');
        if($m->parent_message) {
            $m->parent_msg_sender_id = $m->parent_message->sender->id;
            
            if(file_exists(public_path().$m->parent_msg_sender_pic) && $m->parent_msg_sender_pic!='') {
                $m->parent_msg_sender_pic = $m->parent_message->sender->meta->pic;
            }
            else {
                if($m->parent_message->sender->engroup==2) {
                    $m->parent_msg_sender_pic='/new/images/female.png';
                }
                else {
                    $m->parent_msg_sender_pic='/new/images/male.png';
                }
            }
            if($m->parent_message->sender->id==$user->id) {
                $m->parent_msg_sender_isAvatarHidden = 0;
                $m->parent_msg_sender_blurryAvatar = 0;
            }
            else {
                $m->parent_msg_sender_isAvatarHidden = $m->parent_message->sender->meta->isAvatarHidden;
                $m->parent_msg_sender_blurryAvatar = UserService::isBlurAvatar($m->parent_message->sender,$user);                
            }
            
            if($m->parent_msg_sender_isAvatarHidden??null) {
                if($m->parent_message->sender->engroup==2) {
                    $m->parent_msg_sender_pic='/new/images/female.png';
                }
                else {
                    $m->parent_msg_sender_pic='/new/images/male.png';
                }                
            }
            
            if(!$m->parent_client_id??null) {
                $m->parent_client_id = $m->parent_message->client_id??null; 
            }
            //23.03.01 目前傳訊不是使用pusher，所以先將parent_message刪除
            //日後若要改回用pusher，則需要先將parent_message用不到的欄位刪除
            //避免超過pusher的大小限制
            $m->parent_message = null;
            unset($m->parent_message);
        }

        $m->pic_bak 
        = $m->reportContent
        = $m->reportContentPic
        = $m->hide_reported_log
        = $m->handle
        = $m->all_delete_count
        = $m->is_row_delete_1
        = $m->is_row_delete_2
        = $m->is_single_delete_1
        = $m->is_single_delete_2
        = $m->isReported
        = $m->cancel
        = $m->is_write
        = null;
        
        unset(
            $m->pic_bak 
            ,$m->reportContent
            ,$m->reportContentPic
            ,$m->hide_reported_log
            ,$m->handle
            ,$m->all_delete_count
            ,$m->is_row_delete_1
            ,$m->is_row_delete_2
            ,$m->is_single_delete_1
            ,$m->is_single_delete_2
            ,$m->isReported
            ,$m->cancel
            ,$m->is_write        
        );
        
        if(!isset($m['error'])){
            \App\Events\NewMessage::dispatch($m->id, $m->content, $m->from_id, $m->to_id,$m->pic?1:0);
        }
        return event(new \App\Events\Chat($m, $request->from, $request->to));
    }
}
