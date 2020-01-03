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
                <a href="" class="shou_but">全部解除</a>
            </div>
            <div class="sjlist">
                <ul>
                    @foreach ($blocks as $block)
                        <?php $blockedUser = \App\Models\User::findById($block->blocked_id) ?>
                    <li>
                        <div class="si_bg">
                            <div class="sjpic"><img src="{{ $blockedUser->meta_()->pic }}"></div>
                            <div class="sjleft">
                                <div class="sjtable"><a href="/user/view/{{$blockedUser->id}}"><span>{{$blockedUser->name}}<i class="cicd">●</i>{{ $blockedUser->meta_()->age() }}</span></a></div>
                                <font>{{ $blockedUser->meta_()->city }} {{ $blockedUser->meta_()->area }}</font>
                            </div>
                            <div class="sjright">
                                <h4 class="fengs"><a href="javascript:void(0);" class="unblock" data-uid="{{$user->id}}" data-to="{{$block->blocked_id}}"><img src="/new/images/ncion_11.png">解除封鎖</a></h4>
                            </div>
                        </div>
                    </li>
                    @endforeach

                </ul>
                <div class="fenye">
                    <a id="prePage" href="{{ $blocks->previousPageUrl() }}">上一頁</a>
                    <a id="nextPage" href="{{ $blocks->nextPageUrl() }}">下一頁</a>
                </div>

            </div>


{{--            <div class="fs_name">--}}
{{--                <div class="fs_title">本月封鎖名單<h2>共{{ $blocks->count() }}筆資料</h2></div>--}}
{{--                <div class="fs_table">--}}
{{--                    <table>--}}
{{--                        <tr class="fs_tb">--}}
{{--                            <th style=" border-radius:5px 0 0 5px;">名稱</th>--}}
{{--                            <th>封鎖原因</th>--}}
{{--                            <th>開始日期</th>--}}
{{--                            <th style=" border-radius:0 5px 5px 0;">封鎖時間</th>--}}
{{--                        </tr>--}}
{{--                        @foreach ($blocks as $block)--}}
{{--                            <tr>--}}
{{--                                <td>{{ $user[$block->blocked_id]->name or "此會員不存在"}}</td>--}}
{{--                                <td>{{ $block->content or "無" }}</td>--}}
{{--                                <td>{{ $block->created_at}}</td>--}}
{{--                                <td>{{ $block->days or ""}}</td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                    </table>--}}
{{--                    <div class="fenye">--}}
{{--                        <a id="prePage" href="{{ $blocks->previousPageUrl() }}">上一頁</a>--}}
{{--                        <a id="nextPage" href="{{ $blocks->nextPageUrl() }}">下一頁</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
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
                window.location.reload();
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
                window.location.reload();
            });
        });
        return false;
    });
</script>
@stop