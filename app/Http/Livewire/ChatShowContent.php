<?php

namespace App\Http\Livewire;

use App\Models\Blocked;
use App\Models\Message;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Services\AdminService;

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
        $to = $this->to->refresh();
        $user = $this->user->refresh();
        $toUserIsBanned = $this->toUserIsBanned;
        $isBlurAvatar = $this->isBlurAvatar;
        $isVip = $this->isVip;
        $showSlide = $this->showSlide;
        $admin_id = AdminService::checkAdmin()->id;

        if (Blocked::isBlocked($to->id, auth()->user()->id)) {
            $blockTime = Blocked::getBlockTime($to->id, auth()->user()->id);
            //用model會抓不到unsend欄位 所以這邊用DB來抓
            $messages = DB::table('message')->where(function ($q) use ($to, $blockTime) {
                $q->where([
                    ['to_id', $to->id],
                    ['from_id', auth()->user()->id],
                    ['created_at', '<=', $blockTime->created_at]
                ])->orWhere([
                    ['from_id', $to->id],
                    ['to_id', auth()->user()->id]]);
                })->distinct()->orderBy('created_at', 'desc');
        } else {
            //用model會抓不到unsend欄位 所以這邊用DB來抓
            $messages = DB::table('message')->where(function ($q) use ($to) {
                $q->where([
                    ['to_id', $to->id],
                    ['from_id', auth()->user()->id]
                ])->orWhere([
                    ['from_id', $to->id],
                    ['to_id', auth()->user()->id]]);
                })->distinct()->orderBy('created_at', 'desc');

        }
        
        if ($to->id == $admin_id || $user->id == $admin_id) {
            $messages->where('chat_with_admin', 1);
        }
        
        $uid = $user->id;
        $messages->where([['message.is_row_delete_1','<>',$uid],['message.is_single_delete_1', '<>' ,$uid], ['message.all_delete_count', '<>' ,$uid],['message.is_row_delete_2', '<>' ,$uid],['message.is_single_delete_2', '<>' ,$uid],['message.temp_id', '=', DB::raw('0')]]);
       
        $messages = Message::addAutoDestroyWhereToQuery($messages)
                ->paginate($this->limitPerPage)
                ->reverse();

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