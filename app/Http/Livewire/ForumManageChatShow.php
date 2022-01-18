<?php

namespace App\Http\Livewire;

use App\Models\ForumManageChat;
use Livewire\Component;
use Livewire\WithPagination;

class ForumManageChatShow extends Component
{
    public $forum_id;
    public $to_id;
    use WithPagination;

    public function render()
    {
        $forumManageChatContent = ForumManageChat::select(
        'forum_manage_chat.id',
        'forum_manage_chat.forum_id',
        'forum_manage_chat.from_id',
        'forum_manage_chat.to_id',
        'forum_manage_chat.content',
        'forum_manage_chat.pic',
        'forum_manage_chat.created_at',
        'user_meta.pic as upic',
        'users.engroup'
        )
            ->LeftJoin('users', 'users.id','=','forum_manage_chat.from_id')
            ->join('user_meta', 'users.id','=','user_meta.user_id')
            ->where('forum_manage_chat.forum_id', $this->forum_id)
            ->where(function($query){
                $query->where([['forum_manage_chat.to_id', $this->to_id], ['forum_manage_chat.from_id', auth()->user()->id]])
                    ->orWhere([['forum_manage_chat.to_id', auth()->user()->id], ['forum_manage_chat.from_id', $this->to_id]]);
            })
            ->groupby('forum_manage_chat.id')
            ->orderBy('forum_manage_chat.created_at', 'desc')->paginate(10);

        return view('livewire.forum-manage-chat-show', compact('forumManageChatContent'));
    }

}
