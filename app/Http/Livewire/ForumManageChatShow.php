<?php

namespace App\Http\Livewire;

use App\Models\ForumManageChat;
use App\Models\Forum;
use Livewire\Component;
use Livewire\WithPagination;

class ForumManageChatShow extends Component
{
    public $forum_id;
    public $to_id;
    public $applicant_id;
    public $forum_owner_id;
    use WithPagination;

    public function render()
    {
        $cur_user_forum_manage = auth()->user()->forum_manage->where('forum_id',$this->forum_id)->first();

        $cur_user_is_forum_manager = ($cur_user_forum_manage && $cur_user_forum_manage->is_manager);
        $cur_forum_owner_id =  Forum::find($this->forum_id)->user_id;
        
        if(!$cur_user_is_forum_manager) {
           if($cur_forum_owner_id==auth()->user()->id) {
               $cur_user_is_forum_manager = 1;
           } 
        }
        
        $cur_user_is_forum_manager = intval($cur_user_is_forum_manager);
        $is_cur_user_manager_same_with_forum_owner = intval($cur_user_forum_manage && $cur_user_forum_manage->is_manager);

        $forumManageChatContent = ForumManageChat::select(
            'forum_manage_chat.id',
            'forum_manage_chat.forum_id as forum_id',
            'forum_manage_chat.from_id',
            'forum_manage_chat.to_id',
            'forum_manage_chat.content',
            'forum_manage_chat.pic',
            'forum_manage_chat.created_at',
            'user_meta.pic as upic',
            'users.engroup',
            'from_forum_manage.is_manager as from_is_forum_manager',
            'to_forum_manage.is_manager as to_is_forum_manager',

            
        )
            ->LeftJoin('users', 'users.id','=','forum_manage_chat.from_id')
            ->join('user_meta', 'users.id','=','user_meta.user_id')
            ->LeftJoin('forum_manage as from_forum_manage', function ($q) {
                $q->on('from_forum_manage.forum_id', '=', 'forum_manage_chat.forum_id')
                    ->on('from_forum_manage.user_id', '=', 'forum_manage_chat.from_id');
            })
            ->LeftJoin('forum_manage as to_forum_manage', function ($q) {
                $q->on('to_forum_manage.forum_id', '=', 'forum_manage_chat.forum_id')
                    ->on('to_forum_manage.user_id', '=', 'forum_manage_chat.to_id');
            })
            ->where('forum_manage_chat.forum_id', $this->forum_id)
            ->where(function($query){
                $query->where([['forum_manage_chat.to_id', $this->applicant_id]])
                    
                    ->orWhere([['forum_manage_chat.from_id', $this->applicant_id]])

                    ;
            })
            ->where(function($query){
                $query->where('from_forum_manage.is_manager',1)
                ->orWhere('to_forum_manage.is_manager',1)
                ->orWhere('from_id',$this->forum_owner_id)
                ->orWhere('to_id',$this->forum_owner_id)
                ;
            })
            ->groupby('forum_manage_chat.id')
            ->orderBy('forum_manage_chat.created_at', 'desc')->paginate(10);

        return view('livewire.forum-manage-chat-show', compact('forumManageChatContent','cur_user_forum_manage','cur_user_is_forum_manager','cur_forum_owner_id','is_cur_user_manager_same_with_forum_owner'));
    }

}
