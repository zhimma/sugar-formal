<?php

namespace App\Http\Livewire;

use App\Models\AnonymousChat;
use App\Models\AnonymousChatMessage;
use App\Models\AnonymousChatReport;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class AnonymousChatShow extends Component
{
    use WithPagination;

    public function render()
    {
        $anonymousChat = AnonymousChat::select('anonymous_chat.*', 'users.engroup')
            ->LeftJoin('users', 'users.id','=','anonymous_chat.user_id')
            ->orderBy('anonymous_chat.created_at', 'asc')
            ->take(1000)->get();
//            ->paginate(10);

        return view('livewire.anonymous-chat-show', compact('anonymousChat'));
    }

    public function reply_message($content, $id, $pic)
    {
        $this->dispatchBrowserEvent('reply_message', ['content' => $content, 'id' => $id, 'pic' => $pic]);
    }

    public function show_banned($name, $id)
    {
        $this->dispatchBrowserEvent('show_banned', ['name' => $name,'id' => $id]);
    }

    public function chat_message($name, $id, $engroup)
    {
        $self_engroup =auth()->user()->engroup;

        //可發訊時間計算 一周發訊一人
        $checkMessage = AnonymousChatMessage::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->first();
        $canNotMessage = false;
        if( isset($checkMessage) && Carbon::parse($checkMessage->created_at)->diffInDays(Carbon::now())<7){
            $canNotMessage = true;
        }
        $this->dispatchBrowserEvent('chat_message', ['name' => $name,'id' => $id, 'engroup' => $engroup, 'canNotMessage' => $canNotMessage, 'self_engroup' => $self_engroup]);
    }

    public function checkReport()
    {
        $checkReport = AnonymousChatReport::select('user_id', 'created_at')->where('reported_user_id', auth()->user()->id)->groupBy('user_id')->orderBy('created_at', 'desc')->get();
        if(count($checkReport) >= 5 && Carbon::parse($checkReport[0]->created_at)->diffInDays(Carbon::now())<3){
            return redirect('/dashboard/personalPage')->with('message', '因被檢舉次數過多，目前已限制使用匿名聊天室');
        }
    }
}
