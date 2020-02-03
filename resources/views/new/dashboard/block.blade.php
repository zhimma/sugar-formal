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
                        <?php $blockedUser = \App\Models\User::findById($block->blocked_id) ?>
                    <li>
                        <div class="si_bg">
                            <div class="sjpic"><a href="/dashboard/viewuser/{{$blockedUser->id}}"><img src="{{ $blockedUser->meta_()->pic }}"></a></div>
                            <div class="sjleft">
                                <div class="sjtable"><a href="/dashboard/viewuser/{{$blockedUser->id}}"><span>{{$blockedUser->name}}<!-- <i class="cicd">●</i>{{ $blockedUser->meta_()->age() }}--></span></a></div>
                                <font>{{ $blockedUser->meta_()->city }} {{ $blockedUser->meta_()->area }}</font>
                            </div>
                            <div class="sjright">
                                <h4 class="fengs"><a href="javascript:void(0);" class="unblock" data-uid="{{$user->id}}" data-to="{{$block->blocked_id}}"><img src="/new/images/ncion_11.png">解除封鎖</a></h4>
                            </div>
                        </div>
                    </li>
                    @endforeach

                </ul>
                @if(count($blocks)>15)
                <div class="fenye">
                    <a id="prePage" href="{{ $blocks->previousPageUrl() }}">上一頁</a>
                    <a id="nextPage" href="{{ $blocks->nextPageUrl() }}">下一頁</a>
                </div>
                @endif

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
                show_message('已解除封鎖');
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
                show_message('已解除封鎖');
            });
        });
        return false;
    });
</script>
@stop