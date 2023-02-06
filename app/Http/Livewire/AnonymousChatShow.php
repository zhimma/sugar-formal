<?php

namespace App\Http\Livewire;

use App\Models\AnonymousChat;
use App\Models\AnonymousChatMessage;
use App\Models\AnonymousChatReport;
use App\Models\SimpleTables\warned_users;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class AnonymousChatShow extends Component
{
    public $limitPerPage = 10;

    protected $listeners = [
        'load-more-chat' => 'loadMoreChat'
    ];

    public function loadMoreChat()
    {
        $this->limitPerPage = $this->limitPerPage + 6;
    }

    public function render()
    {

        $bannedUsers = \App\Services\UserService::getBannedId();
        $isAdminWarnedList = warned_users::select('member_id')->where('expire_date','>=',Carbon::now())->orWhere('expire_date',null)->get();

        $anonymousChat = AnonymousChat::select('anonymous_chat.*', 'users.engroup')
            ->LeftJoin('users', 'users.id','=','anonymous_chat.user_id')
            ->whereNotIn('anonymous_chat.user_id', $isAdminWarnedList)
            ->whereNotIn('anonymous_chat.user_id', $bannedUsers)
            ->where('anonymous_chat.created_at', '>', \DB::raw('DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())-1 DAY)'))
            ->orderBy('anonymous_chat.created_at', 'desc')
            ->paginate($this->limitPerPage)
            ->reverse();

        $dataCounts = count($anonymousChat);
        $checkMoreData = $dataCounts / $this->limitPerPage;

        return view('livewire.anonymous-chat-show', compact('anonymousChat', 'checkMoreData'));
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
        if(User::isAnonymousChatReportedSilence(auth()->user()->id)){
            return redirect('/dashboard/personalPage')->with('message', '因被檢舉次數過多，目前已限制使用匿名聊天室');
        }

        if(User::isAnonymousChatForbid(auth()->user()->id)){
            return redirect('/dashboard/personalPage')->with('message', '您目前已被禁止進入匿名聊天室');
        }

        if(User::isWarned(auth()->user()->id)){
            return redirect('/dashboard/personalPage')->with('message', '您已被站方警示，目前已限制使用匿名聊天室');
        }

        if(User::isBanned_v2(auth()->user()->id)){
            return redirect('/dashboard/personalPage')->with('message', '您已被站方封鎖，目前已限制使用匿名聊天室');
        }
    }
}
