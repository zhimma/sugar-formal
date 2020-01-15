@extends('heary.layouts.website')

@section('app-content')

<style>
    body {
        background-color: #F7EEEB;
    }
</style>
<div class="m-content zlleftbg">
    <div class="row">
        <div class="col-sm-12 col-xs-12 col-md-3 zleft">
            @include('heary.dashboard.panel')
        </div>
        <div class="col-md-9" style="background-color:white;height: 100%;">
            <div class="p100 weui-f18">
                <div class="lytitle ffs"><i></i>收藏會員
                    <a href="javascript:" class="yichu_t">移除</a>
                </div>
                <div class="row weui-t_c weui-mt30">
                    <div class="row weui-t_c weui_mt19 sjlist">
                        <ul>
                            <!-- 迴圈收藏人物-->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    var Page = {
        page : 1,
        row  : 15,
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
                Page.DrawPage(total);
                $('.sjlist>ul').children().slice((Page.page-1)*Page.row, Page.page*Page.row).css('display', '');
            });
        }
    };


    // var page = 1;//初始資料
    // var row = 10;//預設產出資料筆數
    var total = 0;//總筆數
    var date=7;

    function liContent(e,i){
        var li='';
        var ss =((i+1)>Page.row)?'display:none;':'display:none;';
        var c = (e.vip)?'hy_bg01':'';
        var vippng = (e.vip)?'<img src="/images/05.png" class="weui-v_t">':'';
        li +=`
            <div class="col-md-3 col-lg-3 col-sm-3 col-xs-6 weui-pb20">
                <div class="yicw">
                    <img src="${e.pic}" onerror="this.src=&#39;/img/male-avatar.png&#39;" alt="" class="hypic yichub">
                    <form action="{{ route('fav/remove') }}" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                        <input type="hidden" name="userId" value="{{$user->id}}">
                        <input type="hidden" name="favUserId" value="${e.member_fav_id}">
                        <button type="submit" class="yichu" style="display:none">移除</button>
                    </form>
                </div>
                <a href="/user/view/${e.member_fav_id}" class="weui-db">
                    <p class="weui-pt15">${e.name} ${vippng} </p>
                    <p class="weui-c_9 weui-f12">${e.created_at}</p>
                </a>
            </div>
        `;
        return li;
    }

    //按下移除
    $(".yichu_t").click(function() {
        $(".yichu").toggle();
    });

    //ajax資料
    function LoadTable(){
        var nn=0;
        div = '';
        $.ajax({
            url: '{{ route('showfav') }}',
            type: 'POST',
            dataType: 'json',
            // headers: {
            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            // },
            data: {
                _token:"{{ csrf_token() }}",
                uid : '{{ $user->id }}',
            },
            beforeSend:function(){//表單發送前做的事
                $('.sjlist>ul').html('');
                $('#warning').fadeIn(100);
            },
            complete: function () {
            },
            success:function(res){
                var li = '';//樣板容器
                $.each(res.msg,function(i,e){
                    nn++;
                    li = liContent(e,i);
                    $('.sjlist>ul').append(li)
                });
                Page.DrawPage(res.msg.length);
                $('.sjlist>ul').children().slice((Page.page-1)*Page.row, Page.page*Page.row).css('display', '');
                total=res.msg.length;
            }
        })
        .done(function() {
            if (nn == 0) $('.fengsicon').removeClass('d-none');
            // if(page-1>=total){
            //     $('.listMoreBtn').attr('disabled', 'true').removeClass('cursor-pointer').html('NO MORE');
            // }else{
            //     $('.listMoreBtn').removeAttr('disabled').addClass('cursor-pointer').html('MORE');
            // }
        })
        .always(function () {
          $('#warning').css("display", "none");
          if (nn == 0) $('.fengsicon').removeClass('d-none');
        });
    }

    LoadTable();
</script>

@stop