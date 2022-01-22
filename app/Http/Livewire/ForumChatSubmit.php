<?php

namespace App\Http\Livewire;

use App\Models\ForumChat;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Livewire\Component;
use Livewire\WithFileUploads;

class ForumChatSubmit extends Component
{
    public $forum_id;
    public $color;
    public $content;
    public $pic = [];
    use WithFileUploads;

    public function render()
    {
        return view('livewire.forum-chat-submit');
    }

    public function save()
    {
//        dd($this->color);
        $this->isUploaded=true;

        $this->validate([
            'pic.*' => 'image|mimes:png,jpg,jpeg',
        ]);

        $pic_array=array();

        $pic_content = null;
        if(!empty($this->pic)){


            $rootPath = public_path('/img/forum_chat');
            $tempPath = $rootPath . '/' . Carbon::now()->format('Ymd'). '/';

            if(!is_dir($tempPath)) {
                File::makeDirectory($tempPath, 0777, true);
            }

            foreach($this->pic as $key => $pic){
                $img = Image::make($pic->getRealPath());
                $img->resize(400, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($tempPath . $pic->hashName());

                $pic_path = '/img/forum_chat/'. Carbon::now()->format('Ymd'). '/';

                $pic->store('/img/forum_chat');
                $pic_array[$key]['origin_name']= $pic->getClientOriginalName();
                $pic_array[$key]['file_path']= $pic_path . $pic->hashName();
            }
            $pic_content = json_encode($pic_array);
        }

        if((!empty($this->pic) || !empty($this->content))){
            ForumChat::Create([
                'forum_id' => $this->forum_id,
                'user_id' => auth()->user()->id,
                'color' => $this->color,
                'content' => $this->content,
                'pic' => $pic_content,
            ]);
            $this->reset('content');
            $this->reset('pic');
        }
    }

    public function removeMe($index)
    {
        array_splice($this->pic, $index, 1);
    }

    public function removeAll($count)
    {
        array_splice($this->pic,0, $count);
    }

}
