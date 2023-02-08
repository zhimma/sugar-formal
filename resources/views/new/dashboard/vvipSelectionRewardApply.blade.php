@extends('new.layouts.website')
@section('style')
<style>
    .new_input dt {
        background-color: white;
    }
</style>
@stop
@section('app-content')
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10 g_pnr01">
                <!--  -->
                <div class="shou">
                    <span>VVIP發佈</span><font>Release</font>
                    <a href="" class="toug_back btn_img"><div class="btn_back"></div></a>
                </div>
                <form id="formVvipSelectionRewardApply" action="{{ route('vvipSelectionRewardApply') }}" method="post">
                    {!! csrf_field() !!}
                <div class="addpic g_inputt" style="width: 94%;">
                    <div class="new_input">

                        <dt class="bhui_new">
                            <span class="x_p5">1.請輸入徵選主題</span>
                            <font><input name="title" type="text" class="select_xx01 x_tpbo" id="title" placeholder="請輸入至多六個字標題" maxlength="6" required></font>
                        </dt>
                        <dt class="bhui_new">
                            <span class="x_p5">2.請選擇條件</span>
                            <font>
                                <div class="c_ftext">以下作為範例條件給您參考，您也可以自己設立條件</div>
                                <div id="itemssxN">
                                    <nav class="custom_nav_n">
                                        @foreach($option_selection_reward as $row)
                                            @if($row->id==3)<div class="left">@endif
                                            <div class="custom_s a1 option_selection_reward" data-id="{{$row->id}}" data-name={{$row->option_name}}>{{$row->option_name}}
                                                <b class="cr_b cr_{{$row->id}}">({{$row->option_content}})</b>
                                            </div>
                                                @if($row->id==3)</div>@endif
                                        @endforeach
{{--                                        <div class="custom_s a1">皮膚白皙<b class="cr_b cr1">,此條件可能會大幅提高審核金額</b></div>--}}
{{--                                        <div class="custom_s a2">身高170cm以上<b class="cr_b cr2">，此條件可能會大大提高審核金額</b></div>--}}
{{--                                        <div style="float: left;">--}}
{{--                                            <div class="custom_s a3">可配合daddy調整髮色/髮型<b class="cr_b cr3">，需指定髮色/髮型供站方審核</b></div>--}}
{{--                                            <input name="" type="text" class="select_xx01 x_tpbo01 yc3" placeholder="輸入髮色/髮型">--}}
{{--                                        </div>--}}

{{--                                        <div class="custom_s a4">BMI 18~24<b class="cr_b cr4">，此條件可能會小幅提高審核金額</b></div>--}}
{{--                                        <div class="custom_s a6">能接受SM</div>--}}
{{--                                        <div class="custom_s a6">身體柔軟/有瑜珈訓練</div>--}}
{{--                                        <div class="custom_s a6">專業健美</div>--}}
{{--                                        <div class="custom_s a5">特定職業空姐/護士等<b class="cr_b cr5">，某些職業可能會大幅提高審核金額</b></div>--}}
{{--                                        <div class="custom_s a6">九頭身</div>--}}
{{--                                        <div class="custom_s a6">會某種樂器(鋼琴/長笛等)</div>--}}
{{--                                        <div class="custom_s a6">會某種舞蹈(芭蕾/國標/爵士等)</div>--}}
                                    </nav>
                                </div>

                            </font>
                        </dt>
                        <dt class="bhui_new">
                            <span class="x_p5">3.請輸入此次的徵選條件</span>
                            <font>
                                <div class="c_ftext">請注意：條件必須是客觀條件站方才有辦法審核，例如身高體重等等。主觀條件例如溫柔體貼這類主觀條件站方無法審核。</div>
                                <div class="input_field_weap" >
                                    <textarea class="x_text" name="condition[]" placeholder="請輸入"></textarea>
                                </div>
                            </font>
                            <a href="javascript:void(0)" type="button" id="add_image" class="zj_tiaojian" name="button"><b>+</b>新增條件</a>
                        </dt>
                        <dt class="bhui_new">
                            <span class="x_p5">4.請輸入招選人數</span>
                            <font><input name="limit" type="number" class="select_xx01 x_tpbo" id="limit" placeholder="請輸入招選人數" required></font>
                        </dt>
                    </div>
                </div>
                <input id="option_selection_reward" type="hidden" name="option_selection_reward" value="">
                <div class="n_txbut matop20 mabot50">
                    <a class="se_but1 form_submit">確定</a>
                    <a href="/dashboard/vvipPassSelect" class="se_but2">取消</a>
                </div>
                </form>

                <!--  -->
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            @if(Session::has('message'))
            c5("{{Session::get('message')}}");
            <?php session()->forget('message');?>
            @endif

            // 01
            $("#itemssxN .a1").on("click", function() {
                $(this).toggleClass('cractive');
                if($('.cr_'+ $(this).data('id')).is(':hidden')){//如果当前隐藏
                    $('.cr_'+ $(this).data('id')).show();//点击显示
                    if($(this).data('id')==3){
                        $(this).after('<input name="hair_style" type="text" class="select_xx01 x_tpbo01 yc3" placeholder="輸入髮色/髮型">');
                        $('.x_tpbo01').show();
                    }
                }else{//否则
                    $('.cr_'+ $(this).data('id')).hide();//点击隐藏
                    if($(this).data('id')==3){
                        $('.x_tpbo01').remove();
                    }
                }

            });

            $('.form_submit').on('click',function (e) {
                if($('#title').val()==''){
                    c5('請輸入徵選主題');
                    return false;
                }
                if($('#limit').val()==''){
                    c5('請輸入招選人數');
                    return false;
                }
                option_array = [];
                $('.option_selection_reward.cractive').each(function () {
                    if($(this).data('name').indexOf("髮色")>=0){
                        option_array.push($(this).data('name')+':'+$("input[name='hair_style']").val());
                    }else {
                        option_array.push($(this).data('name'));
                    }
                });
                option_array = JSON.stringify(option_array);
                $('#option_selection_reward').val(option_array);

                $('#formVvipSelectionRewardApply').submit();
            });

            var max_fields = 0;
            var wrapper = $(".input_field_weap");
            var add_button = $("#add_image");

            var x = 1;
            $(add_button).click(function(e) {
                e.preventDefault();
                if(14 - max_fields >= x) {
                    x++;
                    if($('.x_text:last').val()==''){
                        c5('您尚未輸入文字');
                        return false;
                    }else {
                        $(wrapper).append('<div class="custom"><textarea class="x_text" name="condition[]" placeholder="請輸入"></textarea><a href="#" class="remove_field"><img src="/new/images/del_03w.png"></a></div>'); //add input box
                    }
                } else {
                    alert('');
                }
            });
            $(wrapper).on("click", ".remove_field", function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            });
            // 页面初始化基本资料不可以修改
            var inputs = document.getElementsByClassName("base_info");

        });
    </script>
@stop
