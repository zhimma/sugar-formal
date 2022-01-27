<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
                $m->parent_msg_sender_blurryAvatar = $m->parent_message->sender->meta->blurryAvatar;                
            }
            
            if($m->parent_msg_sender_isAvatarHidden??null) {
                if($m->parent_message->sender->engroup==2) {
                    $m->parent_msg_sender_pic='/new/images/female.png';
                }
                else {
                    $m->parent_msg_sender_pic='/new/images/male.png';
                }                
            }
        }
        if(!isset($m['error'])){
            \App\Events\NewMessage::dispatch($m->id, $m->content, $m->from_id, $m->to_id);
        }
        return event(new \App\Events\Chat($m, $request->from, $request->to));
    }
}
