<?php

namespace App\Http\Livewire;

use App\Models\AnonymousChat;
use Livewire\Component;
use Livewire\WithPagination;

class AnonymousChatShow extends Component
{
    use WithPagination;

    public function render()
    {
        $anonymousChat = AnonymousChat::select('anonymous_chat.*', 'users.engroup')
            ->LeftJoin('users', 'users.id','=','anonymous_chat.user_id')
            ->orderBy('anonymous_chat.created_at', 'desc')->paginate(10);

        return view('livewire.anonymous-chat-show', compact('anonymousChat'));
    }
}
