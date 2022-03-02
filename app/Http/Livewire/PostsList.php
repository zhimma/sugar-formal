<?php

namespace App\Http\Livewire;

use App\Models\Posts;
use Livewire\Component;
use Livewire\WithPagination;

class PostsList extends Component
{
    use WithPagination;

    public function render()
    {
        $posts = Posts::selectraw('posts.top, users.id as uid, users.name as uname, users.engroup as uengroup, posts.is_anonymous as panonymous, user_meta.pic as umpic, posts.id as pid, posts.title as ptitle, posts.contents as pcontents, posts.updated_at as pupdated_at, posts.created_at as pcreated_at, posts.deleted_by, posts.article_id as aid')
        ->selectRaw('(select updated_at from posts where (id=aid or reply_id=aid ) order by updated_at desc limit 1) as currentReplyTime')
            ->selectRaw('(case when users.id=1049 then 1 else 0 end) as adminFlag')
            ->LeftJoin('users', 'users.id','=','posts.user_id')
            ->join('user_meta', 'users.id','=','user_meta.user_id')
            ->where('posts.type','main')
            ->orderBy('posts.deleted_at','asc')
            ->orderBy('posts.top','desc')
            ->orderBy('adminFlag','desc')
            ->orderBy('currentReplyTime','desc')
            ->withTrashed()
            ->paginate(10);

        return view('livewire.posts-list', compact('posts'));
    }
}
