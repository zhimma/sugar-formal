<?php

namespace App\Http\Livewire;

use App\Models\ForumChat;
use Livewire\Component;
use Livewire\WithPagination;

class ForumChatShow extends Component
{
    public $forum_id;
    use WithPagination;

    public function render()
    {
        $forumChatContent = ForumChat::select(
            'forum_chat.id',
            'forum_chat.forum_id',
            'forum_chat.user_id',
            'forum_chat.color',
            'forum_chat.content',
            'forum_chat.pic',
            'forum_chat.created_at',
            'user_meta.pic as upic',
            'users.engroup',
            'users.name'
        )
            ->LeftJoin('users', 'users.id','=','forum_chat.user_id')
            ->join('user_meta', 'users.id','=','user_meta.user_id')
            ->where('forum_chat.forum_id', $this->forum_id)
            ->groupby('forum_chat.id')
            ->orderBy('forum_chat.created_at', 'desc')->paginate(10);

        return view('livewire.forum-chat-show', compact('forumChatContent'));
    }
}
