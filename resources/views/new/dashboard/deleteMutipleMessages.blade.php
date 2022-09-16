@extends('new.layouts.website')
@section('style')
    <style>
        .hycov{ width: 50px;height: 50px;max-width: 100%;max-height: 100%;}
        .dtl_zk{ height: 415px; overflow-y: scroll;}
        .dtl_zk::-webkit-scrollbar {height: 0;width: 0;color: transparent;}
    </style>
@endsection
@section('app-content')
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="shou"><span class="zq_font1">大量刪除訊息</span>
                    <font class="zq_font2">Delect</font><a href="/dashboard/chat2#all" class="nnn_adbut"><img src="/new/images/zz_zbjt.png" style=" margin-left: -5px;">返回</a>
                </div>
                <div class="g_pnr">
                    <div class="del_dl">
                        <div class="del_nr">
                            <h2><img src="/new/images/zz_zbicon.png"><font>加入我的最愛會員不會被刪除</font></h2>
                            <h2><img src="/new/images/zz_zbicon.png"><font>點加號可以展開預計刪除名單，可以選擇保留名單</font></h2>
                        </div>
                    </div>
                    <div class="det_k">
                        <div class="ny_zblb1">
                            <ul class="clearfix">
                                <li class="box oneWeekList">
                                    <a href="javascript:void(0)">
                                        <div class="det_ttile">
                                            <span>刪除一周以上未連絡的會員</span>
                                            <font style="display: inline-flex;">(現有通訊{{ count($oneWeekList) }}人，刪除後剩餘<span id="oneWeekAfterDeleteCnt" style="color: unset;font-size: unset;">0</span>人)</font>
                                        </div>
                                    </a>
                                    <ul class="dtl_zk" style="position: relative;max-height: 415px;">
                                        <div class="n_gd_nn"><div class="n_gd_taa"></div></div>
                                            @foreach($oneWeekList as $oneWeek)
                                                <div class="dtl_zk_1" id="zh1" data-user_id="{{ $oneWeek['user_id'] }}">
                                                    <div class="dtl_d" onclick="shanc1()">刪除</div>
                                                    <div class="dtl_tx @if($oneWeek['isBlurAvatar']) blur_img @endif"><img src="{{ $oneWeek['user_pic'] }}" @if($oneWeek['user_engroup'] == 1) onerror="this.src='/new/images/male.png'" @else onerror="this.src='/new/images/female.png'" @endif class="hycov"></div>

                                                    <div class="dt_nr">
                                                        <span>{{ $oneWeek['user_name'] }}</span>
                                                        <font>{{ $oneWeek['last_msg_content'] }}</font>
                                                        <div class="dt_time">{{ date('m/d H:i', strtotime($oneWeek['last_msg_created_at'])) }}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                    </ul>
                                </li>
                                <li class="box twoWeekList">
                                    <a href="javascript:void(0)">
                                        <dt class="det_ttile">
                                            <span>刪除兩周以上未連絡的會員</span>
                                            <font style="display: inline-flex;">(現有通訊{{ count($twoWeekList) }}人，刪除後剩餘<span id="twoWeekAfterDeleteCnt" style="color: unset;font-size: unset;">0</span>人)</font>
                                        </dt>
                                    </a>
                                    <ul class="dtl_zk" style="position: relative;max-height: 415px;">
                                        <div class="n_gd_nn"><div class="n_gd_taa"></div></div>
                                        @foreach($twoWeekList as $twoWeek)
                                            <div class="dtl_zk_1" id="zh1" data-user_id="{{ $twoWeek['user_id'] }}">
                                                <div class="dtl_d" onclick="shanc1()">刪除</div>
                                                <div class="dtl_tx @if($twoWeek['isBlurAvatar']) blur_img @endif"><img src="{{ $twoWeek['user_pic'] }}" @if($twoWeek['user_engroup'] == 1) onerror="this.src='/new/images/male.png'" @else onerror="this.src='/new/images/female.png'" @endif class="hycov"></div>

                                                <div class="dt_nr">
                                                    <span>{{ $twoWeek['user_name'] }}</span>
                                                    <font>{{ $twoWeek['last_msg_content'] }}</font>
                                                    <div class="dt_time">{{ date('m/d H:i', strtotime($twoWeek['last_msg_created_at'])) }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </ul>
                                </li>
                                <li class="box oneMonthList">
                                    <a href="javascript:void(0)">
                                        <dt class="det_ttile">
                                            <span>刪除一個月以上未連絡的會員</span>
                                            <font style="display: inline-flex;">(現有通訊{{ count($oneMonthList) }}人，刪除後剩餘<span id="oneMonthAfterDeleteCnt" style="color: unset;font-size: unset;">0</span>人)</font>
                                        </dt>
                                    </a>
                                    <ul class="dtl_zk" style="position: relative;max-height: 415px;">
                                        <div class="n_gd_nn"><div class="n_gd_taa"></div></div>
                                        @foreach($oneMonthList as $oneMonth)
                                            <div class="dtl_zk_1" id="zh1" data-user_id="{{ $oneMonth['user_id'] }}">
                                                <div class="dtl_d" onclick="shanc1()">刪除</div>
                                                <div class="dtl_tx @if($oneMonth['isBlurAvatar']) blur_img @endif"><img src="{{ $oneMonth['user_pic'] }}" @if($oneMonth['user_engroup'] == 1) onerror="this.src='/new/images/male.png'" @else onerror="this.src='/new/images/female.png'" @endif class="hycov"></div>

                                                <div class="dt_nr">
                                                    <span>{{ $oneMonth['user_name'] }}</span>
                                                    <font>{{ $oneMonth['last_msg_content'] }}</font>
                                                    <div class="dt_time">{{ date('m/d H:i', strtotime($oneMonth['last_msg_created_at'])) }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="n_txbut matop30">
                        <a onclick="send_deleteBetweenMsg_btn()" class="se_but1 "><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">確定</font></font></a>
                        <a href="/dashboard/chat2#all" class="se_but2"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">取消</font></font></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script type="text/javascript">
        $(function () {
            $(".box").click(function(){
                var thisSpan=$(this);
                if($(this).children('.dtl_zk').css('display')=='block'){
                    $(this).children('.dtl_zk').hide();
                    $(this).children().removeClass('cur');
                }else{

                    $(".ny_zblb1 ul li ul").prev("a").removeClass("cur");
                    $("ul", this).prev("a").addClass("cur");
                    $(this).children("ul").slideDown("fast");
                    $(this).siblings().children("ul").hide();//.slideDown("slow");
                }
            }).on('click', '.dtl_zk', function(e) {
                // clicked on descendant div
                e.stopPropagation();
            });
        });

        function send_deleteBetweenMsg_btn() {
            var sList_array = [];
            $('.dtl_zk_1:not(.dt_hover)').each(function(){
                if( $(this).parent().css('display')=='block'){
                    sList_array.push($(this).attr('data-user_id'));
                }
            });
            sList_array=removeDuplicates(sList_array);
            //alert('delete user array->'+sList_array  + '總共刪除：'+sList_array.length+'筆資料');
            if(sList_array.length==0){
                c5('目前暫無訊息可供刪除！')
                return false;
            }

            c6('請問確定刪除這些訊息嗎？');
            $(".n_left").on('click', function() {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('deleteBetweenMsg_multiple') }}',
                    data: {
                        _token:"{{ csrf_token() }}",
                        uid : '{{ $user->id }}',
                        sList : sList_array
                    },
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(xhr){
                        console.log(xhr.msg);
                        c5('訊息刪除成功');
                        setTimeout('location.reload()', 3000);
                    },
                    error: function(xhr, type){
                        c5('訊息讀取出現錯誤！敬請重新整理後再嘗試一次，如本錯誤持續出現，請與站長聯絡，謝謝。');
                    }
                });
            });
        }

        function removeDuplicates(arr) {
            var unique = [];
            arr.forEach(element => {
                if (!unique.includes(element)) {
                    unique.push(element);
                }
            });
            return unique;
        }
    </script>

    <script type="text/javascript">
        // div 点击事件
        $('.dtl_zk_1').click(function(e){
            $(this).toggleClass('dt_hover');
            if($(this).hasClass('dt_hover')){
                $(this).find('.dtl_d').text("保留");
                if($(this).parent().parent().hasClass('oneWeekList')){
                    $('#oneWeekAfterDeleteCnt').text(parseInt($('#oneWeekAfterDeleteCnt').text()) + 1);
                }else if($(this).parent().parent().hasClass('twoWeekList')){
                    $('#twoWeekAfterDeleteCnt').text(parseInt($('#twoWeekAfterDeleteCnt').text()) + 1);
                }else if($(this).parent().parent().hasClass('oneMonthList')){
                    $('#oneMonthAfterDeleteCnt').text(parseInt($('#oneMonthAfterDeleteCnt').text()) + 1);
                }

            }else{
                $(this).children('.dtl_d').text("删除");
                if($(this).parent().parent().hasClass('oneWeekList')){
                    $('#oneWeekAfterDeleteCnt').text(parseInt($('#oneWeekAfterDeleteCnt').text()) - 1);
                }else if($(this).parent().parent().hasClass('twoWeekList')){
                    $('#twoWeekAfterDeleteCnt').text(parseInt($('#twoWeekAfterDeleteCnt').text()) - 1);
                }else if($(this).parent().parent().hasClass('oneMonthList')){
                    $('#oneMonthAfterDeleteCnt').text(parseInt($('#oneMonthAfterDeleteCnt').text()) - 1);
                }
            }
        });
    </script>
@endsection
