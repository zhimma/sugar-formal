<?php

namespace App\Http\Livewire;

use App\Models\AnonymousChat;
use App\Models\AnonymousChatReport;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Livewire\WithFileUploads;

class AnonymousChatSubmit extends Component
{
    public $content;
    public $pic = [];
    use WithFileUploads;

    public function render()
    {
        return view('livewire.anonymous-chat-submit');
    }

    public function save()
    {

        $checkReport = AnonymousChatReport::select('user_id', 'created_at')->where('reported_user_id', auth()->user()->id)->groupBy('user_id')->orderBy('created_at', 'desc')->get();
        if(count($checkReport) >= 5 && Carbon::parse($checkReport[0]->created_at)->diffInDays(Carbon::now())<3){
            return redirect('/dashboard/personalPage')->with('message', '因被檢舉次數過多，目前已限制使用匿名聊天室');
        }

        $this->isUploaded=true;

        $this->validate([
            'pic.*' => 'image|mimes:png,jpg,jpeg',
        ]);

        $pic_array=array();

        $pic_content = null;
        if(!empty($this->pic)){


            $rootPath = public_path('/img/anonymous_chat');
            $tempPath = $rootPath . '/' . Carbon::now()->format('Ymd'). '/';

            if(!is_dir($tempPath)) {
                File::makeDirectory($tempPath, 0777, true);
            }

            foreach($this->pic as $key => $pic){
                $img = Image::make($pic->getRealPath());
                $img->resize(400, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($tempPath . $pic->hashName());

                $pic_path = '/img/anonymous_chat/'. Carbon::now()->format('Ymd'). '/';

                $pic->store('/img/anonymous_chat');
                $pic_array[$key]['origin_name']= $pic->getClientOriginalName();
                $pic_array[$key]['file_path']= $pic_path . $pic->hashName();
            }
            $pic_content = json_encode($pic_array);
        }
        //anonymous
        $check_anonymous = AnonymousChat::select('anonymous')->where('user_id',auth()->user()->id)->orderBy('created_at', 'desc')->first();
        if($check_anonymous && $check_anonymous->anonymous != ''){
            $anonymous = $check_anonymous->anonymous;
        }else{
            //產生anonymous
            $check_anonymous = AnonymousChat::select('anonymous')->max('anonymous');
//            dd($check_anonymous);
            if($check_anonymous){
                $anonymous = str_pad($check_anonymous + 1,4,"0",STR_PAD_LEFT);
            }else{
                $anonymous = '0001';
            }
        }

        if( !empty($this->pic) || isset($this->content) ){
            AnonymousChat::Create([
                'user_id' => auth()->user()->id,
                'content' => $this->content,
                'pic' => $pic_content,
                'anonymous' => $anonymous
            ]);
        }
        $this->reset('content');
        $this->reset('pic');
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
