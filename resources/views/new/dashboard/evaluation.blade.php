@extends('new.layouts.website')

@section('app-content')
    <style>
        button{
            outline:none;
        }

        label{
            display: inherit;
        }

        .hide {
            display: none;
        }

        .clear {
            float: none;
            clear: both;
        }

        .rating {
            width: 130px;
            unicode-bidi: bidi-override;
            direction: rtl;
            text-align: center;
            position: relative;
        }

        .rating > label {
            float: right;
            display: inline;
            padding: 0;
            margin: 5px;
            position: relative;
            width: 1.1em;
            cursor: pointer;
            color: #000;
        }

        .rating > label:hover,
        .rating > label:hover ~ label,
        .rating > input.radio-btn:checked ~ label {
            color: transparent;
        }

        .rating > label:hover:before,
        .rating > label:hover ~ label:before,
        .rating > input.radio-btn:checked ~ label:before,
        .rating > input.radio-btn:checked ~ label:before {
            /*content: "\2605";*/
            content: url(/new/images/sxx_1.png);
            transform: scale(.8);
            /*background-image: url(/new/images/sxx_1.png);*/
            /*background-size: auto 20px;*/
            position: absolute;
            /*display: inline-block;*/
            left: -11.5px;
            z-index: 1;
            /*color: #FFD700;*/
            /*height: 20px;*/
        }
        /*.rating > label:hover:after {*/
        /*    position: absolute;*/
        /*    !*content:attr(data-title);*!*/
        /*    left:-2px;*/
        /*    top:-16px;*/
        /*    width:10px;*/
        /*    !*height:1.25rem;*!*/
        /*    !*background-color: white;*!*/
        /*    !*border: solid 1px #fd5678;*!*/
        /*    color:#fd5678;*/
        /*    !*padding: 2px;*!*/

        /*    !*margin-left: -10px;*!*/
        /*}*/
        /*!* HIDE RADIO *!*/
        /*[type=radio] {*/
        /*    position: absolute;*/
        /*    opacity: 0;*/
        /*    width: 0;*/
        /*    height: 0;*/
        /*}*/

        /*!* IMAGE STYLES *!*/
        /*[type=radio] + img {*/
        /*    cursor: pointer;*/
        /*}*/

        /*!* CHECKED STYLES *!*/
        /*[type=radio]:checked + img {*/
        /*    outline: 1px solid #fe92a8;*/
        /*}*/
    </style>
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <!--左-->
            <div class="col-sm-12 col-xs-12 col-md-10">

                @if(($user->id != $to->id) && ($user->engroup==2 && ($user->isSent3Msg($to->id)==0 || $auth_check==0)) || ($user->engroup==1 && ($user->isSent3Msg($to->id)==0 || $vipDays<=30)) || isset($evaluation_self))
                <div class="potitle poys"><img src="/new/images/ly03_h.png">{{$to->name}}的評價</div>
                @else
                <div class="potitle"><img src="/new/images/ly03.png">{{$to->name}}的評價</div>
                @endif
                @if($user->id != $to->id)
                <div class="pot_vh">
                        @if($user->engroup==2 && ($user->isSent3Msg($to->id)==0 || $auth_check==0))
                        <div class="tw_textinput01">
                            <h2>您目前未達評價標準<br>不可對{{$to->name}}會員評價</h2>
                            <div class="al_b">
                                <table>
                                    <tr>
                                        <td width="316">女生須通過手機驗證</td>
                                        <td width="56">@if($auth_check>0)<img src="/new/images/ticon_01.png">@else<img src="/new/images/ticon_02.png">@endif</td>
                                    </tr>
                                    <tr>
                                        <td>男方須回覆女方三次以上</td>
                                        <td>@if($user->isSent3Msg($to->id)==0)<img src="/new/images/ticon_02.png">@else<img src="/new/images/ticon_01.png">@endif</td>
                                    </tr>

                                </table>
                            </div>
                        </div>
                        <a class="pj_but">確定</a>
                        @elseif($user->engroup==1 && ($user->isSent3Msg($to->id)==0 || $vipDays<=30))
                        <div class="tw_textinput01">
                            <h2>您目前未達評價標準<br>不可對{{$to->name}}會員評價</h2>
                            <div class="al_b">
                                <table>
                                    <tr>
                                        <td width="316">男方須為一個月(不含一個月)以上VIP</td>
                                        <td width="56">@if($vipDays>=30)<img src="/new/images/ticon_01.png">@else<img src="/new/images/ticon_02.png">@endif</td>
                                    </tr>
                                    <tr>
                                        <td>女方須有回覆男方三次以上</td>
                                        <td>@if($user->isSent3Msg($to->id)==0)<img src="/new/images/ticon_02.png">@else<img src="/new/images/ticon_01.png">@endif</td>
                                    </tr>

                                </table>
                            </div>
                        </div>
                        <a class="pj_but">確定</a>
                        @elseif(!isset($evaluation_self))
                        <form id="form1" action="{{ route('evaluation') }}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="rating">
                                <input id="star5" name="rating" type="radio" value="5" class="radio-btn hide" data-title="5"/>
                                <label for="star5" data-title="5"><img src="/new/images/sxx_4.png" style="transform: scale(.8);"></label>
                                <input id="star4" name="rating" type="radio" value="4" class="radio-btn hide"/>
                                <label for="star4" data-title="4"><img src="/new/images/sxx_4.png" style="transform: scale(.8);"></label>
                                <input id="star3" name="rating" type="radio" value="3" class="radio-btn hide"/>
                                <label for="star3" data-title="3"><img src="/new/images/sxx_4.png" style="transform: scale(.8);" ></label>
                                <input id="star2" name="rating" type="radio" value="2" class="radio-btn hide"/>
                                <label for="star2" data-title="2"><img src="/new/images/sxx_4.png" style="transform: scale(.8);" alt="2"></label>
                                <input id="star1" name="rating" type="radio" value="1" class="radio-btn hide"/>
                                <label for="star1" data-title="1"><img src="/new/images/sxx_4.png" style="transform: scale(.8);" data-toggle="tooltip" data-placement="top" title="1"></label>
                                <div class="clear"></div>
                            </div>
                            <textarea id="content" name="content" cols="" rows="" class="tw_textinput" placeholder="請輸入您對{{$to->name}}的評價">@if(isset($evaluation_self)){{$evaluation_self->content}}@endif</textarea>
                            <input type="hidden" name="uid" value={{$user->id}}>
                            <input type="hidden" name="eid" value={{$to->id}}>
                            <a class="dlbut" onclick="form_submit()">確定</a>
                            {{--                            <button type="submit" class="dlbut">確定</button>--}}
                        </form>
                        @else
                            <div class="nhuiy_a">
                                <img src="/new/images/sxx_4.png">
                                <img src="/new/images/sxx_4.png">
                                <img src="/new/images/sxx_4.png">
                                <img src="/new/images/sxx_4.png">
                                <img src="/new/images/sxx_4.png">
                            </div>
                            <textarea name="" cols="" rows="" class="tw_textinput had_e" placeholder="請輸入您對{{$to->name}}的評價"></textarea>

                        <a class="pj_but">確定</a>
                        @endif

                </div>
                @endif


                <div class="shou"><span>會員評價</span>
                    <font>Evaluation</font>
                </div>
                <div class="pjliuyan02">
                    <ul>
                        @foreach( $evaluation_data as $row)
                            @php
                                $row_user = \App\Models\User::findById($row->from_id);
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
                                <a href="/dashboard/viewuser/{{$row_user->id}}?time={{ \Carbon\Carbon::now()->timestamp }}">{{$row_user->name}}</a><font>{{ substr($row->created_at,0,10)}}</font>
                            </div>
                            <div class="con">
                                <p class="many-txt">{!! $row->content !!}</p>
                                <h4><button type="button" class="al_but">完整評價</button></h4>
                            </div>
                        </li>
                        @endforeach

                    </ul>
                    <div style="text-align: center;">
                        {!! $evaluation_data->appends(request()->input())->links('pagination::sg-pages2') !!}
                    </div>
{{--                    <div class="fenye"><a href="">上一頁</a><span class="new_page">1/5</span><a href="">下一頁</a></div>--}}
                </div>
            </div>

            <!--右-->
        </div>
    </div>

@stop

@section('javascript')
    <script>
        $('.radio-btn').tooltip();

        @if (Session::has('message'))
        c2('{{Session::get('message')}}');
        @endif

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

        function form_submit(){
            if( $("input[name='rating']:checked").val() == undefined) {
                c5('請先點擊星等再評價');
            }else if($.trim($(".tw_textinput").val())=='') {
                c5('請輸入評價內容');
            }else{
                $('#form1').submit();
            }
        }

        $('.pj_but,.had_e').click(function() {
            var e_user = '{{$to->name}}';
            c5('您已評價過' + e_user);

        });
    </script>
@stop
