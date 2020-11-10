@extends('new.layouts.website')

@section('app-content')
    <style>
        .hf_i {
            min-height: 35px;
            line-height:26px;
            transition: width 0.25s;
            resize:none;
            overflow:hidden;
        }
        .re_area{
            position: relative;
            float: right;
        }
        .show_more{
            display: block;
        }
        .hide_more{
            display: none;
        }
    </style>
<div class="container matop70">
    <div class="row">
        <div class="col-sm-2 col-xs-2 col-md-2 dinone">
            @include('new.dashboard.panel')
        </div>
        <div class="col-sm-12 col-xs-12 col-md-10">
            <div class="shou"><span>評價</span>
                <font>Evaluation</font>
                @if(count($evaluation_data)>0)
                <a href="" class="shou_but">全部刪除</a>
                @endif
            </div>

            @if(count($evaluation_data)>0)
            <div class="pjliuyan02">
                <ul>
                    @foreach( $evaluation_data as $row)
                        @php
                            $row_user = \App\Models\User::findById($row->from_id);
                            $to_user = \App\Models\User::findById($row->to_id);
                        @endphp
                        <li>
                            <div class="piname">
                                <span>
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if($row->rating>=$i)
                                            <img src="/new/images/sxx_1.png">
                                        @else
                                            <img src="/new/images/sxx_4.png">
                                        @endif
                                    @endfor
                                </span>
                                <a href="/dashboard/viewuser/{{$to_user->id}}?time={{ \Carbon\Carbon::now()->timestamp }}">{{$to_user->name}}</a>
                                    <font class="sc content_delete" data-id="{{$row->id}}"><img src="/new/images/del_03.png">刪除</font>
                            </div>
                            <div class="con">
                                <p class="many-txt">{!! nl2br($row->content) !!}</p>
                                <h4>
                                    <span class="btime">{{ substr($row->created_at,0,10)}}</span>
                                    <button type="button" class="al_but">完整評價</button>
                                </h4>
                            </div>

                            @if(empty($row->re_content) && $to_user->id == $user->id)
                                <div class="huf">
                                    <form id="form_re_content" action="{{ route('evaluation_re_content')."?n=".time() }}" method="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <span class="huinput">
                                                                <textarea name="re_content" type="text" class="hf_i" placeholder="請輸入回覆（最多120個字符）" maxlength="120"></textarea>
                                                            </span>
                                        <div class="re_area">
                                            <a class="hf_but" onclick="form_re_content_submit()">回覆</a>
                                        </div>
                                        <input type="hidden" name="id" value={{$row->id}}>
                                        <input type="hidden" name="eid" value={{$row->to_id}}>
                                    </form>
                                </div>
                            @elseif(!empty($row->re_content))
                                <div class="hu_p">
                                    <div class="he_b">
                                        <span class="left"><img src="@if(file_exists( public_path().$to_user->meta_()->pic ) && $to_user->meta_()->pic != ""){{$to_user->meta_()->pic}} @elseif($to_user->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="he_zp">{{$to_user->name}}</span>
                                        @if($to_user->id==$user->id)
                                            <font class="sc re_content_delete" data-id="{{$row->id}}"><img src="/new/images/del_03.png">刪除</font>
                                        @endif

                                    </div>
                                    <div class="he_two">
                                        <div class="context">
                                            <div id="test" class="context-wrap" style="word-break: break-all;">{!! nl2br($row->re_content) !!}</div>
                                        </div>
                                    </div>
                                    <div class="he_twotime">{{ substr($row->re_created_at,0,10)}}<span class="z_more">展開</span></div>
                                </div>
                            @endif

                        </li>
                    @endforeach

                </ul>
                <div style="text-align: center;">
                    {!! $evaluation_data->appends(request()->input())->links('pagination::sg-pages2') !!}
                </div>
            </div>
            @else
            <div class="sjlist">
                <div class="fengsicon"><img src="/new/images/pjicon.png" class="feng_img"><span>暫無資料</span></div>
            </div>
            @endif

        </div>
    </div>
</div>

@stop

@section('javascript')
<script>

    $('.shou_but').on('click', function() {
        c4('確定要全部刪除嗎?');
        $(".n_left").on('click', function() {
            $.post('{{ route('evaDeleteAll') }}', {
                from_id: '{{ $user->id }}',
                _token: '{{ csrf_token() }}'
            }, function (data) {
                $("#tab04").hide();
                show_message('已刪除');
            });
        });
        return false;
    });

    $('.content_delete').on( "click", function() {
        c4('確定要刪除嗎?');
        $(".n_left").on('click', function() {
            $.post('{{ route('evaluation_delete') }}', {
                id: $('.content_delete').data('id'),
                _token: '{{ csrf_token() }}'
            }, function (data) {
                $("#tab04").hide();
                show_message('評價已刪除');
            });
        });
    });

    function form_re_content_submit(){
        if($.trim($(".hf_i").val())=='') {
            c5('請輸入內容');
        }else{
            $('#form_re_content').submit();
        }
    }

    $('.re_content_delete').on( "click", function() {
        c4('確定要刪除嗎?');
        $(".n_left").on('click', function() {
            $.post('{{ route('evaluation_re_content_delete') }}', {
                id: $('.re_content_delete').data('id'),
                _token: '{{ csrf_token() }}'
            }, function (data) {
                $("#tab04").hide();
                show_message('回覆已刪除');
            });
        });
    });

    let button = document.getElementsByTagName('button');
    let p = document.getElementsByTagName('p');

    for(let i=0; i<button.length; i++) {
        button[i].onclick = function () {
            if(this.innerHTML == "完整評價"){
                p[i].classList.remove("many-txt");
                p[i].classList.add("all-txt");
                this.innerHTML = "點擊收起";
            }
            else{
                p[i].classList.remove("all-txt");
                p[i].classList.add("many-txt");
                this.innerHTML = "完整評價";
            }
        }
    }

    $(".z_more").on( "click", function() {
        $(this).parent().prev().find('.context').find("div").first().toggleClass('on context-wrap')
        $(this).html($(this).text() === '展開' ? '收起' : '展開');
    });

    $('textarea.hf_i').on({input: function(){
            var totalHeight = $(this).prop('scrollHeight') - parseInt($(this).css('padding-top')) - parseInt($(this).css('padding-bottom'));
            $(this).css({'height':totalHeight});
            if(totalHeight>40) {
                $('.re_area').css({'top': totalHeight - 40});
            }
        }
    });

    $('div.context-wrap').each(function(i) {
        if (isEllipsisActive(this)) {
            $(this).parents('.hu_p').find('span.z_more').removeClass('hide_more');
            $(this).parents('.hu_p').find('span.z_more').removeClass('show_more');
            $(this).parents('.hu_p').find('span.z_more').addClass('show_more');
        }
        else {
            $(this).parents('.hu_p').find('span.z_more').removeClass('show_more');
            $(this).parents('.hu_p').find('span.z_more').removeClass('hide_more');
            $(this).parents('.hu_p').find('span.z_more').addClass('hide_more');
        }
    });

    $(window).resize(function() {
        $('div.context-wrap').each(function(i) {
            if (isEllipsisActive(this)) {
                $(this).parents('.hu_p').find('span.z_more').removeClass('hide_more');
                $(this).parents('.hu_p').find('span.z_more').removeClass('show_more');

                $(this).parents('.hu_p').find('span.z_more').addClass('show_more');
            }
            else {
                $(this).parents('.hu_p').find('span.z_more').removeClass('show_more');
                $(this).parents('.hu_p').find('span.z_more').removeClass('hide_more');

                $(this).parents('.hu_p').find('span.z_more').addClass('hide_more');
            }
        });
    });

    $('.many-txt').each(function(i) {
        if (isEllipsisActive(this)) {
            $(this).parents('.con').find('.al_but').removeClass('hide_more');
            $(this).parents('.con').find('.al_but').removeClass('show_more');

            $(this).parents('.con').find('.al_but').addClass('show_more');
        }
        else {
            $(this).parents('.con').find('.al_but').removeClass('hide_more');
            $(this).parents('.con').find('.al_but').removeClass('show_more');

            $(this).parents('.con').find('.al_but').addClass('hide_more');
        }
    });

    $(window).resize(function() {
        $('.many-txt').each(function(i) {
            if (isEllipsisActive(this)) {
                $(this).parents('.con').find('.al_but').removeClass('hide_more');
                $(this).parents('.con').find('.al_but').removeClass('show_more');

                $(this).parents('.con').find('.al_but').addClass('show_more');
            }
            else {
                $(this).parents('.con').find('.al_but').removeClass('hide_more');
                $(this).parents('.con').find('.al_but').removeClass('show_more');

                $(this).parents('.con').find('.al_but').addClass('hide_more');
            }
        });
    });

    function isEllipsisActive(e) {
        return ($(e).innerHeight() < $(e)[0].scrollHeight);
    }
</script>
@stop