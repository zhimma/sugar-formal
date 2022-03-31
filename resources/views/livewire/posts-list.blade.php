<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
    @if(!isset($posts) || count($posts)==0)
        <div class="sjlist">
            <div class="fengsicon"><img src="/posts/images/bianji.png" class="feng_img"><span>尚無資料</span></div>
        </div>
    @else
        <div class="tou_list">
            <ul>
                @foreach($posts as $post)
                    <li @if($post->uid==1049 && $post->top==0) style='background:#ddf3ff; padding:10px 5px; border-radius:5px;' @endif
                        @if($post->top==1) style="background:#ffcf869e; padding:10px 5px; border-radius:5px;" @endif @if($post->deleted_by != null) class="huis_02" @endif
                        >
                        <div class="tou_tx">
                            <a href="/dashboard/viewuser/{{$post->uid}}">
                                <div class="tou_tx_img"><img src="@if(file_exists( public_path().$post->umpic ) && $post->umpic != ""){{$post->umpic}} @elseif($post->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
                            </a>
                            <a href="/dashboard/viewuser/{{$post->uid}}"><span>{{ $post->uname }}<i>{{ date('Y-m-d', strtotime($post->pcreated_at)) }}</i></span></a>
                            <a @if($post->deleted_by == null) href="/dashboard/post_detail/{{$post->pid}}" @else onclick="delete_alert()" @endif>
                                <font><i class="ne_talicon"><img src="/posts/images/tl_icon.png">{{ \App\Models\Posts::where('reply_id',$post->pid)->get()->count() }}</i></font>
                            </a>
                        </div>
                        <a @if($post->deleted_by == null) href="/dashboard/post_detail/{{$post->pid}}" @else onclick="delete_alert()" @endif>
                            <div class="tc_text_aa"><span>{{$post->ptitle}}</span></div>
                            <div class="tc_text_bb"><p>{!! \App\Models\Posts::showContent($post->pcontents) !!}</p></div>
                        </a>
                        @if($post->deleted_by != null && $user->id == 1049)
                            <button onclick="recover_post({{ $post->pid }});" style="right:0px;">回復文章</button>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="fenye mabot30">
            {{ $posts->links('livewire::sg-pages') }}
        </div>
    @endif
</div>
<script>
    function recover_post(pid)
    {
		c4('確定要回復嗎?');
		$(".n_left").on('click', function() {
			$.ajax({
				url: '/dashboard/posts_recover?{{ csrf_token() }}={{now()->timestamp}}',
				method: 'POST',
				data: {
					_token: "{{ csrf_token() }}",
					pid: pid
				},
				success: function(data) {
					if(data.postType=='main'){
						c5(data.msg);
						window.location.href=data.redirectTo;
					}
					else
						c5(data.msg);
				}
			});
		});
	}
</script>
