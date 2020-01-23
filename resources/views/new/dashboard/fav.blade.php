@extends('new.layouts.website')

@section('app-content')
<div class="container matop70">
    <div class="row">
        <div class="col-sm-2 col-xs-2 col-md-2 dinone">
            @include('new.dashboard.panel')
        </div>
        <div class="col-sm-12 col-xs-12 col-md-10">
            <div class="shou"><span>收藏</span>
                <font>Collection</font>
                <a href="javascript:" class="shou_but" style="display: none;">全部移除</a>
            </div>
            <div class="sjlist">
                <ul>

                </ul>
                <!-- <p style="color:red; font-weight: bold; display: none;margin-left: 20px;" id="warning">載入中，請稍候</p> -->
                <p style="width: 20%;margin: 0 auto;" id="warning">
                  <img src="/new/images/Spin-1s-75px.svg">
                </p>
                <div class="fengsicon d-none"><img src="/new/images/fs_03.png" class="feng_img"><span>暫無收藏</span></div>
                <div class="fenye">
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

        var url = '{!! url("/dashboard/viewuser/:uid") !!}';
        url = url.replace(':uid', e.member_fav_id);

        li +=`
            <li  style="${ss}" class="${c}">
                <div class="si_bg">
                    <div class="sjpic"><a href="${url}"><img src="${e.pic}"></a></div>
                    <div class="sjleft">
                        <div class="sjtable"><span><a href="${url}">${e.name}<i class="cicd">●</i>${e.age}</a></span></div>
                        <font>${e.city}  ${e.area}</font>
                    </div>
                    <div class="sjright">
                        <h4 class="fengs"><a href="javascript:" class="remove" data-id="${e.member_fav_id}"><img src="/new/images/ncion_07.png">移除</a></h4>
                    </div>
                </div>
            </li>
        `;
        return li;
    }
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
                if(total>0) {
                    $('.shou_but').show();
                }
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

    $('input[name=RadioGroup1]').on('click', function(event) {
        Page.page=1;
        date= $('input[name=RadioGroup1]:checked').val();
        LoadTable()
    });

    //移除收藏清單
    {{--$(document).on('click','.remove',function(){--}}
    {{--    var id =    $(this).data('id');--}}
    {{--    swal({--}}
    {{--        title:'確定移除?',--}}
    {{--        type:'warning',--}}
    {{--        showCancelButton: true,--}}
    {{--        confirmButtonColor: '#3085d6',--}}
    {{--        cancelButtonColor: '#d33',--}}
    {{--        confirmButtonText: '確定',--}}
    {{--        cancelButtonText: '取消',--}}
    {{--    }).then(function(result){--}}
    {{--        if(result.value){--}}
    {{--            $.ajax({--}}
    {{--                url:'{{ route('fav/remove_ajax') }}',--}}
    {{--                type:'POST',--}}
    {{--                data: {--}}
    {{--                    _token:"{{ csrf_token() }}",--}}
    {{--                    favUserId:id,--}}
    {{--                    userId: '{{ $user->id }}',--}}
    {{--                },--}}
    {{--                dataType:'JSON',--}}
    {{--                success:function(result){--}}
    {{--                    ResultData(result);--}}
    {{--                    if(result.status){--}}
    {{--                        LoadTable();--}}
    {{--                    }--}}
    {{--                }--}}
    {{--            });--}}
    {{--        }--}}
    {{--    });--}}
    {{--});--}}

    $(document).on('click','.remove',function(){
        var id =    $(this).data('id');
        c4('確定移除?');
        $(".n_left").on('click', function() {
            $.ajax({
                url: '{{ route('fav/remove_ajax') }}',
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    favUserId: id,
                    userId: '{{ $user->id }}',
                },
                dataType: 'JSON',
                success: function (result) {
                    $("#tab04").hide();
                    show_message('移除成功');
                    // ResultData(result);
                    // if (result.status) {
                    //     LoadTable();
                    // }
                }
            });
        });
    });

    //移除全部
    {{--$(document).on('click','.shou_but',function(){--}}
    {{--    var id =    $(this).data('id');--}}
    {{--    swal({--}}
    {{--        title:'確定移除全部?',--}}
    {{--        type:'warning',--}}
    {{--        showCancelButton: true,--}}
    {{--        confirmButtonColor: '#3085d6',--}}
    {{--        cancelButtonColor: '#d33',--}}
    {{--        confirmButtonText: '確定',--}}
    {{--        cancelButtonText: '取消',--}}
    {{--    }).then(function(result){--}}
    {{--        if(result.value){--}}
    {{--            $.ajax({--}}
    {{--                url:'{{ route('fav/remove_ajax') }}',--}}
    {{--                type:'POST',--}}
    {{--                data: {--}}
    {{--                    _token:"{{ csrf_token() }}",--}}
    {{--                    favUserId:'all',--}}
    {{--                    userId: '{{ $user->id }}',--}}
    {{--                },--}}
    {{--                dataType:'JSON',--}}
    {{--                success:function(result){--}}
    {{--                    ResultData(result);--}}
    {{--                    if(result.status){--}}
    {{--                        LoadTable();--}}
    {{--                    }--}}
    {{--                }--}}
    {{--            });--}}
    {{--        }--}}
    {{--    });--}}
    {{--});--}}

    $(document).on('click','.shou_but',function(){
        var id =    $(this).data('id');
        c4('確定移除全部?');
        $(".n_left").on('click', function() {
            $.ajax({
                url: '{{ route('fav/remove_ajax') }}',
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    favUserId: 'all',
                    userId: '{{ $user->id }}',
                },
                dataType: 'JSON',
                success: function (result) {
                    $("#tab04").hide();
                    show_message('移除成功');
                    ResultData(result);
                    if (result.status) {
                        LoadTable();
                    }
                }
            });
        });
    });
</script>

@stop