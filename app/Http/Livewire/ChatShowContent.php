<?php

namespace App\Http\Livewire;

use App\Models\Blocked;
use App\Models\Message;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ChatShowContent extends Component
{
    public $to;
    public $user;
    public $toUserIsBanned;
    public $isBlurAvatar;
    public $isVip;
    public $showSlide;

    public $limitPerPage = 10;

    protected $listeners = [
        'load-more-chat' => 'loadMoreChat',
        'resetShowSlide' => 'resetShowSlide'
    ];

    public function loadMoreChat()
    {
        $this->limitPerPage = $this->limitPerPage + 6;
    }

    public function render()
    {
        $to = $this->to;
        $user = $this->user;
        $toUserIsBanned = $this->toUserIsBanned;
        $isBlurAvatar = $this->isBlurAvatar;
        $isVip = $this->isVip;
        $showSlide = $this->showSlide;

        if(Blocked::isBlocked($to->id, auth()->user()->id)) {
            $blockTime = Blocked::getBlockTime($to->id, auth()->user()->id);
            //用model會抓不到unsend欄位 所以這邊用DB來抓
            $messages = DB::table('message')->where([['to_id', $to->id],['from_id', auth()->user()->id],['created_at', '<=', $blockTime->created_at]])->orWhere([['from_id', $to->id],['to_id', auth()->user()->id]])
                ->distinct()->orderBy('created_at', 'desc')
                ->paginate($this->limitPerPage)
                ->reverse();
        }else{
            //用model會抓不到unsend欄位 所以這邊用DB來抓
            $messages = DB::table('message')->where([['to_id', $to->id],['from_id', auth()->user()->id]])->orWhere([['from_id', $to->id],['to_id', auth()->user()->id]])
                ->distinct()->orderBy('created_at', 'desc')
                ->paginate($this->limitPerPage)
                ->reverse();
        }

        $dataCounts = count($messages);
        $checkMoreData = $dataCounts / $this->limitPerPage;

        return view('livewire.chat-show-content', compact('messages','to', 'user', 'toUserIsBanned', 'isBlurAvatar', 'isVip', 'showSlide', 'checkMoreData'));
    }

    public function resetShowSlide()
    {
        $this->showSlide = false;
    }

    public function showSlideStopPoll()
    {
        $this->showSlide = false;
    }

    public function showSlide($id)
    {
        $this->showSlide = true;
        $this->dispatchBrowserEvent('showSlide', ['id' => $id]);
    }

}
