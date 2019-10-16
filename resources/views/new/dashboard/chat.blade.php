@extends('new.layouts.website')

@section('app-content')

    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="shou"><span>收件夾</span>
                    <font>inbox</font>
                    <a href="" class="shou_but">全部刪除</a>
                </div>
                <div class="sjlist">
                    <ul>
                       <!--  <li>
                            <div class="si_bg">
                                <div class="sjpic"><img src="/new/images/icon_04.png"></div>
                                <div class="sjleft">
                                    <div class="sjtable"><span>Adelle Addde</span><i class="number">9</i></div>
                                    <font><img src="/new/images/icon_35.png"></font>
                                </div>
                                <div class="sjright">
                                    <h3>08-30  20:00</h3>
                                    <h4><a href=""><img src="/new/images/del_03.png">刪除</a><a href=""><img src="/new/images/del_05.png">封鎖</a></h4>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="si_bg">
                                <div class="sjpic"><img src="/new/images/icon_04.png"></div>
                                <div class="sjleft">
                                    <div class="sjtable"><span>箬彤Baylee</span><i class="number">56</i></div>
                                    <font>現在剛下樓，妳在那</font>
                                </div>
                                <div class="sjright">
                                    <h3>08-30  20:00</h3>
                                    <h4><a href=""><img src="/new/images/del_03.png">刪除</a><a href=""><img src="/new/images/del_05.png">封鎖</a></h4>
                                </div>
                            </div>
                        </li>
                        <li class="hy_bg03">
                            <div class="si_bg">
                                <div class="sjpic"><img src="/new/images/icon_04.png"></div>
                                <div class="sjleft">
                                    <div class="sjtable"><span>王嘉蔭嘉</span><i class="number">56</i></div>
                                    <font>現在剛下樓，妳在那</font>
                                </div>
                                <div class="sjright">
                                    <h3>08-30  20:00</h3>
                                    <h4><a href=""><img src="/new/images/del_03.png">刪除</a><a href=""><img src="/new/images/del_05.png">封鎖</a></h4>
                                </div>
                            </div>
                        </li>
                        <li class="hy_bg01">
                            <div class="si_bg">
                                <div class="sjpic"><img src="/new/images/icon_04.png"></div>
                                <div class="sjleft">
                                    <div class="sjtable"><span>王嘉蔭嘉</span><i class="number">56</i></div>
                                    <font>現在剛下樓，妳在那</font>
                                </div>
                                <div class="sjright">
                                    <h3>08-30  20:00</h3>
                                    <h4><a href=""><img src="/new/images/del_03.png">刪除</a><a href=""><img src="/new/images/del_05.png">封鎖</a></h4>
                                </div>
                            </div>
                        </li>
                        <li class="hy_bg02">
                            <div class="si_bg">
                                <div class="sjpic"><img src="/new/images/icon_04.png"></div>
                                <div class="sjleft">
                                    <div class="sjtable"><span>王蔭嘉</span><i class="number">56</i></div>
                                    <font>現在剛下樓，妳在那</font>
                                </div>
                                <div class="sjright">
                                    <h3>08-30  20:00</h3>
                                    <h4><a href=""><img src="/new/images/del_03.png">刪除</a><a href=""><img src="/new/images/del_05.png">封鎖</a></h4>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="si_bg">
                                <div class="sjpic"><img src="/new/images/icon_04.png"></div>
                                <div class="sjleft">
                                    <div class="sjtable"><span>王嘉嘉</span><i class="number">56</i></div>
                                    <font>現在剛下樓，妳在那</font>
                                </div>
                                <div class="sjright">
                                    <h3>08-30  20:00</h3>
                                    <h4><a href=""><img src="/new/images/del_03.png">刪除</a><a href=""><img src="/new/images/del_05.png">封鎖</a></h4>
                                </div>
                            </div>
                        </li> -->
                    </ul>
                    <p style="color:red; font-weight: bold; display: none;margin-left: 20px;" id="warning">載入中，請稍候</p>
                    <div class="fenye">
                        <!-- <a href="javascript:" class="page-link" data-p="next">上一頁</a>
                        <a href="javascript:" class="page-link" data-p="last">下一頁</a> -->
                    </div>
                    <div class="zixun">
                        <span><input type="radio" name="RadioGroup1" value="7" id="RadioGroup1_0" checked>本周訊息</span>
                        <span><input type="radio" name="RadioGroup1" value="30" id="RadioGroup1_1">本月訊息</span>
                        <span><input type="radio" name="RadioGroup1" value="all" id="RadioGroup1_2">全部訊息</span>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <script>

        var Page = {
            page : 1,
            row  : 8,
            DrawPage:function(total){
                var total_page  = Math.ceil(total/Page.row) == 0 ? 1 : Math.ceil(total/Page.row);
                var span_u      = 0;
                var str         = '';
                if(total_page==1){
                    str   = '';
                }else if(Page.page==1){
                    str ='<a href="javascript:" class="page-link" data-p="last">下一頁</a>';
                }else if(Page.page==total_page){
                    str ='<a href="javascript:" class="page-link" data-p="next">上一頁</a>';
                }else{
                    str = `
                        <a href="javascript:" class="page-link" data-p="next">上一頁</a>
                        <a href="javascript:" class="page-link" data-p="last">下一頁</a>
                    `;
                }
                $('.fenye').html(str);
                $('.fenye a.page-link').click(function(){
                    $('.sjlist>ul').children().css('display', 'none');
                    //if ($(this).data('p') == Page.page) return false;
                    switch($(this).data('p')) {
                        case 'next': Page.page = parseInt(Page.page) - 1; break;
                        case 'last': Page.page = parseInt(Page.page) + 1; break;
                        //default: Page.page = parseInt($(this).data('p'));
                    }
                    console.log(Page.page);
                    Page.DrawPage(total);
                    $('.sjlist>ul').children().slice((Page.page-1)*Page.row, Page.page*Page.row).css('display', '');
                });
            }
        };


        // var page = 1;//初始資料
        // var row = 10;//預設產出資料筆數
        var total = 0;//總筆數
        var date=7;

        function liContent(pic,user_name,content,created_at,i){
            var li='';
            var ss =((i+1)>Page.row)?'display:none;':'display:none;';
            li +=`
                <li style="${ss}">
                    <div class="si_bg">
                        <div class="sjpic"><img src="${pic}" ></div>
                        <div class="sjleft">
                            <div class="sjtable"><span>${user_name}</span><i class="number">56</i></div>
                            <font>${content}</font>
                        </div>
                        <div class="sjright">
                            <h3>${created_at}</h3>
                            <h4><a href=""><img src="/new/images/del_03.png">刪除</a><a href=""><img src="/new/images/del_05.png">封鎖</a></h4>
                        </div>
                    </div>
                </li>
            `;
            return li;
        }

        //ajax資料
        function LoadTable(){
            div = '';
            $.ajax({
                url: '{{ route('showMessages') }}',
                type: 'POST',
                dataType: 'json',
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // },
                data: {
                    _token:"{{ csrf_token() }}",
                    date : date,
                    uid : '{{ $user->id }}',
                    isVip : '{{ $isVip }}',
                    userAgent: "Agent: " + String(navigator.userAgent) + " Platform: " + String(navigator.platform),
                },
                beforeSend:function(){//表單發送前做的事
                    $('.sjlist>ul').html('');
                    $('#warning').fadeIn(100);
                    let wait = document.getElementById("warning");
                    let text = '載入中，請稍候';
                    let length = wait.innerHTML.length + 10;
                    let dots = window.setInterval( function() {
                        let wait = document.getElementById("warning");
                        if ( wait.innerHTML.length > length )
                            wait.innerText = text;
                        else
                            wait.innerText += ".";
                    }, 200);
                },
                complete: function () {
                },
                success:function(res){
                    var li = '';//樣板容器
                    // var p = page;
                    // var data = res.list;        //回傳資料
                    // var data_num = data.length; //資料筆數
                    // page=page+data_num;
                    // //若有資料時
                    //console.log(res.msg);
                    $.each(res.msg,function(i,e){
                        if(e&&e.user_id)li = liContent(e.pic,e.user_name,e.content,e.created_at,i);
                        $('.sjlist>ul').append(li)
                    });
                    //$('.sjlist>ul').html(li);
                    setTimeout(function(){
                        Page.DrawPage(res.msg.length);
                        $('.sjlist>ul').children().slice((Page.page-1)*Page.row, Page.page*Page.row).css('display', '');
                        $('#warning').fadeOut(50);
                    }, 3000);
                    total=res.msg.length;
                }
            })
            .done(function() {
                // if(page-1>=total){
                //     $('.listMoreBtn').attr('disabled', 'true').removeClass('cursor-pointer').html('NO MORE');
                // }else{
                //     $('.listMoreBtn').removeAttr('disabled').addClass('cursor-pointer').html('MORE');
                // }
            });
        }

        LoadTable();

        $('input[name=RadioGroup1]').on('click', function(event) {
            Page.page=1;
            date= $('input[name=RadioGroup1]:checked').val();
            LoadTable()
        });
    </script>

@stop