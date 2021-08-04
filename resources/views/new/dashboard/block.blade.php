@extends('new.layouts.website')

@section('app-content')

<div class="container matop70">
    <div class="row">
        <div class="col-sm-2 col-xs-2 col-md-2 dinone">
            @include('new.dashboard.panel')
        </div>
        <div class="col-sm-12 col-xs-12 col-md-10">
            <div class="shou"><span>封鎖</span>
                <font>The blockade</font>
                @if(count($blocks)>0)
                <a href="" class="shou_but">全部解除</a>
                @endif
            </div>
            @if(count($blocks)>0)
            <div class="sjlist">
                <ul>
                    @foreach ($blocks as $block)
                        <?php
                        $blockedUser = $block->blocked_user;
                        if(!isset($blockedUser)){
                            continue;
                        }
                        $umeta = $blockedUser->meta;
                        try{
                            if(isset($umeta->city)){
                                $umeta->city = explode(",",$umeta->city);
                                $umeta->area = explode(",",$umeta->area);
                            }
                        }
                        catch (\Throwable $e){
                            logger('Blocked page bug, $umeta->user_id: ' . $umeta->user_id);
                            continue;
                        }
                        ?>
                        @php
                            $isBlurAvatar = \App\Services\UserService::isBlurAvatar($blockedUser, $user);
                        @endphp
                    <li>
                        <div class="si_bg">
                            <div class="sjpic @if($isBlurAvatar) blur_img @endif"><a href="/dashboard/viewuser/{{$blockedUser->id}}"><img src="@if($blockedUser->meta_()->isAvatarHidden) {{ 'makesomeerror' }} @else {{$blockedUser->meta_()->pic}} @endif" @if ($blockedUser->engroup == 1) onerror="this.src='/new/images/male.png'" @else onerror="this.src='/new/images/female.png'" @endif></a></div>
                            <div class="sjleft">
                                <div class="sjtable"><a href="/dashboard/viewuser/{{$blockedUser->id}}"><span>{{$blockedUser->name}}<!-- <i class="cicd">●</i>{{ $blockedUser->meta->age() }}--></span></a></div>
                                <font>
                                    @if (is_array($umeta->city) || is_object($umeta->city))
                                        @foreach($umeta->city as $key => $cityval)
                                            @if ($loop->first)
                                                {{$umeta->city[$key]}} @if($blockedUser->meta->isHideArea == 0){{$umeta->area[$key]}}@endif
                                            @else
                                                {{$umeta->city[$key]}} @if($blockedUser->meta->isHideArea == 0){{$umeta->area[$key]}}@endif
                                            @endif
                                        @endforeach
                                    @endif
{{--                                    {{ $blockedUser->meta->city }} {{ $blockedUser->meta->area }}--}}
                                </font>
                            </div>
                            <div class="sjright">
                                <h4 class="fengs"><a href="javascript:void(0);" class="unblock" data-uid="{{$user->id}}" data-to="{{$block->blocked_id}}"><img src="/new/images/ncion_11.png">解除封鎖</a></h4>
                            </div>
                        </div>
                    </li>
                    @endforeach

                </ul>



{{--                <div class="fenye">--}}
{{--                    <a id="prePage" href="{{ $blocks->previousPageUrl() }}">上一頁</a>--}}
{{--                    <a id="nextPage" href="{{ $blocks->nextPageUrl() }}">下一頁</a>--}}
{{--                </div>--}}

            </div>
                <div style="text-align: center;">
                    {!! $blocks->appends(request()->input())->links('pagination::sg-pages2') !!}
                </div>
            @else
            <div class="sjlist">
                <div class="fengsicon"><img src="/new/images/fs_06.png" class="feng_img"><span>暫無資料</span></div>
            </div>
            @endif

        </div>
    </div>
</div>

@stop
<style>
    .blur_img {
        filter: blur(1px);
        -webkit-filter: blur(1px);
    }
</style>
@section('javascript')
<script>
    $('.unblock').on('click', function() {
       c4('確定要解除封鎖嗎?')
        var uid=$(this).data('uid');
        var to=$(this).data('to');
        $(".n_left").on('click', function() {
            $.post('{{ route('unblockAJAX') }}', {
                uid: uid,
                to: to,
                _token: '{{ csrf_token() }}'
            }, function (data) {
                $("#tab04").hide();
                show_pop_message('已解除封鎖');
            });
        });
    });

    $('.shou_but').on('click', function() {
        c4('確定要全部解除封鎖嗎?');
        $(".n_left").on('click', function() {
            $.post('{{ route('unblockAll') }}', {
                uid: '{{ $user->id }}',
                _token: '{{ csrf_token() }}'
            }, function (data) {
                $("#tab04").hide();
                show_pop_message('已解除封鎖');
            });
        });
        return false;
    });
</script>
@stop