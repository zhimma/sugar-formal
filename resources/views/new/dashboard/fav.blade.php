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
                <a href="javascript:void(0)" class="aa_shou_but"><img src="/new/images/ncion_071.png">全部移除</a>
            </div>
            <div class="sjlist">
                <ul>

                </ul>
                <!-- <p style="color:red; font-weight: bold; display: none;margin-left: 20px;" id="warning">載入中，請稍候</p> -->
{{--                <p style="width: 20%;margin: 0 auto;" id="warning">--}}
{{--                  <img src="/new/images/Spin-1s-75px.svg">--}}
{{--                </p>--}}
                <div class="loading warning" id="sjlist_alert_warning"><span class="loading_text">loading</span></div>
                <div class="fengsicon d-none"><img src="/new/images/fs_03.png" class="feng_img"><span>暫無收藏</span></div>
                <div class="fenye">
                </div>

            </div>
        </div>

    </div>
</div>

<style type="text/css">
    .blur_img {
        filter: blur(1px);
        -webkit-filter: blur(1px);
    }
</style>
<script>
    // 計算瀏覽時間
    var page_id = 'browse';

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
                str =`<a href="javascript:" class="" data-p="next">上一頁</a>
                    <span class="new_page">${Page.page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>`;
            }else if(Page.page==total_page){
                str =`<a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${Page.page}/${total_page}</span>
                    <a href="javascript:" class="" data-p="last">下一頁</a>`;
            }else{
                str = `
                    <a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${Page.page}/${total_page}</span>
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

    function liContent(e,i,isBlur=false,vvipInfo=false){
        var li='',k;
        var ss =((i+1)>Page.row)?'display:none;':'display:none;';
        var c = (e.vip)?'hy_bg01':'';
        var area_string='';
        let pic = e.pic_blur;
        if(!isBlur || !e.pic_blur) {
            pic = e.pic;
        }

        if( typeof e.city !== 'undefined' && e.city.length>1){
            for(k=0 ; k < e.city.length;k++){
                if(typeof e.area[k] !== 'undefined' && e.area[k].length>1)
                    area_string += e.city[k]+' '+e.area[k]+' '; 
                else
                    area_string += e.city[k]+' '
            }
        }else{
            if(typeof e.area !== 'undefined')
                area_string = e.city+' '+e.area;
            else
                area_string = e.city+' '
        }

        var styBlur = isBlur? "blur_img" : "";
        var url = '{!! url("/dashboard/viewuser/:uid") !!}';
        var vvip_url = '{!! url("/dashboard/viewuser_vvip/:uid") !!}';
        if(vvipInfo)
        {
            url = vvip_url.replace(':uid', e.member_fav_id);
        }
        else
        {
            url = url.replace(':uid', e.member_fav_id);
        }

        li +=`
            <li  style="${ss}" class="${c}">
                <div class="si_bg leftb5">
                    <div class="sjpic ${styBlur}"><a href="${url}"><img src="${pic}"></a></div>
                    <div class="sjleft">
                        <div class="sjtable"><span><a href="${url}">${e.name}<i class="cicd">●</i>${e.age}</a></span></div>
                        <font>${area_string}</font>
                    </div>
                    <div class="sjright">
                        <a href="javascript:" class="remove sjright_aa" data-id="${e.member_fav_id}"><img src="/new/images/ncion_07.png">移除</a>
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
            url: '{{ route('showfav') }}?{{csrf_token()}}={{now()->timestamp}}',
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
                $('.warning').fadeIn(100);
            },
            complete: function () {
            },
            success:function(res){
                console.log(res);
                var li = '';//樣板容器
                $.each(res.msg,function(i,e){
                    var isBlur = true;
                    var vvipInfo = e.vvip;
                    if('{{$user->meta->isWarned() == 1 || $user->aw_relation}}' == true){
                        // console.log("1")
                        isBlur = true;
                    }else{
                        console.log(e.blurry_avatar)
                        if(e.blurry_avatar){
                            var blurryAvatar = e.blurry_avatar.split(',');
                            if(blurryAvatar.length > 1){
                                var nowB = '{{$user->isVip() || $user->isVVIP() ? "VIP" : "general"}}';
                                console.log(e)
                                console.log(nowB);
                                if( blurryAvatar.indexOf(nowB) != -1){
                                    isBlur = true;
                                } else {
                                    isBlur = false;
                                }
                            } else {
                                isBlur = false;
                            }
                        }else{
                            isBlur = false;
                        }
                    }
                    
                    isBlur = e.isblur;
                    nn++;
                    li = liContent(e,i,isBlur,vvipInfo);
                    if(typeof e.name !== 'undefined')
                    $('.sjlist>ul').append(li)
                });
                Page.DrawPage(res.msg.length);
                $('.sjlist>ul').children().slice((Page.page-1)*Page.row, Page.page*Page.row).css('display', '');
                total=res.msg.length;
                if(total>0) {
                    $('.aa_shou_but').show();
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
          $('.warning').css("display", "none");
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
                url: '{{ route('fav/remove_ajax') }}?{{csrf_token()}}={{now()->timestamp}}',
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    favUserId: id,
                    userId: '{{ $user->id }}',
                },
                dataType: 'JSON',
                success: function (result) {
                    $("#tab04").hide();
                    show_pop_message('移除成功');
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

    $(document).on('click','.aa_shou_but',function(){
        var id =    $(this).data('id');
        c4('確定移除全部?');
        $(".n_left").on('click', function() {
            $.ajax({
                url: '{{ route('fav/remove_ajax') }}?{{csrf_token()}}={{now()->timestamp}}',
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    favUserId: 'all',
                    userId: '{{ $user->id }}',
                },
                dataType: 'JSON',
                success: function (result) {
                    $("#tab04").hide();
                    c5('移除成功');
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