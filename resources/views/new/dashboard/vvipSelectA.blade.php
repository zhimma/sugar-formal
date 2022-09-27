@extends('new.layouts.website')

@section('app-content')
    <style>
        .fileuploader {
            /*max-width: 100px;*/
            text-align: center !important;
        }
        .fileuploader-thumbnails-input-inner{
            background: url('/new/images/v1_06.png') no-repeat center !important;
            background-size: 100% 100% !important;
            border: unset !important;
        }
        .fileuploader-thumbnails-input{
            width: 240px !important;
            height: 240px !important;
        }
        @media (max-width:360px){
            .fileuploader-thumbnails-input{
                width: 170px !important;
                height: 170px !important;
            }
        }
        .fileuploader-thumbnails-input-inner i{
            top: 75% !important;
        }
        .fileuploader-item .fileuploader-action.fileuploader-action-remove i:after {
            content: unset !important;
        }

        .vvip_tfont r {
            color: #f00;
        }

    </style>
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="g_password">
                    <div class="g_pwicon">
                        <li><a href="@if($user->isVVIP()) /dashboard/viewuser_vvip/{{$user->id}} @else /dashboard/viewuser/{{$user->id}} @endif" class="g_pwicon_t5 "><span>自我預覽</span></a></li>
                        <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t "><span>基本資料</span></a></li>
                        <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2"><span>照片管理</span></a></li>
                        <li><a href="{!! url('dashboard/account_manage') !!}" class="g_pwicon_t3 "><span>帳號設定</span></a></li>
{{--                        <li><a href="{!! url('dashboard/vipSelect') !!}" class="g_pwicon_t4 g_hicon4"><span>升級付費</span></a></li>--}}
                    </div>
                    <div class="zhapian vvip_hg">
                        <div class="vip_bt">花園網VVIP方案</div>
                        <div class="vvip_a_1">
                            <div class="vip_bg">
                                <div class="vip_title02">老會員優惠方案</div>
                                <div class="vvip_tab matop10">
                                    <div class="vvip_sq"><img src="/new/images/v1_05.png">申請流程</div>
                                    <div class="vvip_tfont">
                                        <div class="vip_h3"><font>1:上傳資產證明文件，接受以下</font></div>
                                        <div class="tabfont">
                                            <font>a:房屋土地權狀</font>
                                            <font>b:存摺(六個月)</font>
                                            <font>c:保險月結帳單</font>
                                            <font>d:證券月結帳單</font>
                                            <font>e:定存單</font>
                                            <font>f:扣繳憑單</font>
                                            <font>g:所得清單</font>
                                            <span>以上文件擇一上傳，必須手機高清拍照，完整包含個人訊息，不可以有任何塗改</span>
                                        </div>
{{--                                        <font>2:申請流程：申請時刷季費9888，若審核未過扣除手續費 4000，剩餘刷退；通過則成為當期費用；若 VVIP 到期未續費則須重新審核。</font>--}}
{{--                                        <font>3:申請通過後需繳交站方20000作為入會費，若不同意請勿申請。</font>--}}
{{--                                        <font>4:若審核通過兩周內未完成 VVIP 月費繳費，視為審核失敗，取消申請，扣除手續費 4000，剩餘刷退。</font>--}}
                                        <div class="vip_h3"><font>2:VVIP 季費 9888 元。</font></div>
                                        <div class="vip_h3"><font>3:繳交 2 萬元予站方作為入會費。</font></div>
                                        {{--<font>4:入會費用途：此帳號所有爭議處理費用皆由入會費扣除。</font>--}}
                                        <div class="vip_h3"><font>4:入會費保留：若帳號暫停使用，不支付 VVIP 會費。入會費保留，願意支付 VVIP 會費時會繼續享有 VVIP 權益。</font></div>
                                        <div class="vip_h3"><font>5:帳號/入會費不得轉讓他人使用。</font></div>
                                        <div class="vip_h3"><font><b>6:帳號停止使用：若不再使用本站，入會費不退還。</b></font></div>
                                        {{--<font>8:入會費不足額：入會費低於2萬時須補足到5萬，否則取消 VVIP 權限。取消 VVIP 權限時，入會費不退還。已繳之 VVIP 費用依照使用比例天數退還。</font>--}}
                                        <div class="vip_h3"><font>7:刷卡完成後，須於<r>72小時內匯20000元入指定帳戶，否則將取消此次 VVIP 申請。9888 元扣除手續費4000，剩餘刷退。</r></font></div>
                                        <div class="vip_h3"><font><b>8.若違反本網站用戶規定，被申訴次數達一定次數，造成站方管理上困難，<r>本網站有權取消用戶 VVIP 之身份。</r></b></font></div>
                                        <div class="vip_h3"><font><b>9.又，上述申訴不僅以次數作為判定標準，亦依情節嚴重性而認定。<r>上述所提及之認定資格在站方，站方亦無說明義務。</r></b></font></div>
                                        <div class="vip_h3"><font><b>10.<r>被申訴次數過多會造成帳號被取消</r>，申請用戶需三思。</b></font></div>
                                        <div class="vip_h3"><font>11.若本網站自行斟酌後認為您的個人檔案內容或您在本網站中之行為違反使用條款，或您違反本協議，或因任何其他理由，本網站得暫停或終止您在本網站中使用者帳戶，以及您於本網站中全部或部分之使用。本網站亦得隨時移除您使用者帳戶之全部或部分或任何使用者內容。</font></div>
                                        <div class="vip_h3"><font>12.您同意上述終止事項無需事前通知即逕行生效，且本網站不需對您或任何第三方負責。</font></div>
                                        <div class="vip_h3"><font>13.若您欲取消申請、訂閱，您得隨時依照本協議之條款取消您的 VVIP 服務。您必須依照本網站服務內提供之說明取消，取消額外服務之說明如上所述。</font></div>
                                        <div class="vip_h3"><font>14.本網站保留權利更正錯誤（無論是透過更改本網站之服務內資訊、或是將錯誤通知於您並提供您取消訂購的機會，此外本站亦擁有不經通知下隨時更新資訊之權利）</font></div>
                                        <div class="vip_h3"><font style="color: #f00;background-color: yellow;">★以上若有爭議由站方全權決定，站方亦無說明入會費使用義務，申請人絕無異議。</font></div>
                                        <br>
                                        <input type="checkbox" id="i_agree">我同意
                                    </div>
                                </div>
                            </div>
                            <a href="javascript:void(0);" class="zpback zp_bbut mabot50 vvip_upload_img">上傳證明文件</a>
                        </div>

                        <div class="vvip_a_2" style="display: none;">
                            @if($user->applyingVVIP_getDeadline() != 0)
                            <div class="vip_bgt_zm refill" style="display: none;"><h2>您的申請已在審核中，當前尚需補足證明文件，補件期限：{{$user->applyingVVIP_getDeadline()}}</h2></div>
                            @endif
                            <div class="vip_bgt_zm"><h2>接下來，請上傳您的財力證明。請注意，拍攝的照片必須包含以下內容：「文件名稱、文件發行單位名稱」</h2></div>
                            <form id="form1" action="{{ url('/dashboard/vvipImages/upload') }}" method="post" enctype="multipart/form-data">
                                {!! csrf_field() !!}
                            <div class="vip_bgt_zm02">
                                <h2>如果您上傳的文件照片中，「文件名稱」等被切到的話，會需要您再次重新上傳，因此請務必完整地拍攝您的文件。</h2>

{{--                                <img src="/new/images/v1_06.png" class="vip_aimg">--}}
                                <input type="file" id="files" name="files" data-fileuploader-files="">
{{--                                <div class="vip_dbut">--}}
{{--                                    <a class="vipanleft" href="">--}}
{{--                                        <img src="/new/images/v1_07.png">拍攝照片</a>--}}
{{--                                    <div class="vipancent">of</div>--}}
{{--                                    <a class="vipanright" href="">從相簿選擇</a>--}}
{{--                                </div>--}}
                            </div>
                            <a class="vip_ba mabot50 submit_next">下一步</a>
                                <input type="hidden" name="mode" value="">
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="bl bl_tab " id="tab01">
        <div class="bltitle"><font>老會員優惠方案</font></div>
        <div class="new_poptk" style="width: 90%">
            <div class="viptkft" >
                <h2 class="matop00">●按確定後，將刷卡 9888 元。若審核通過，將成為本期VVIP季費。</h2>
                <h2 class="matop00">●若審核不通過，9888扣除手續費4000，剩餘刷退。</h2>
                <h2 class="matop00">●刷卡完成後，須於72小時內匯20000元入指定帳戶，否則將取消此次 VVIP 申請。9888 元扣除手續費4000，剩餘刷退。</h2>
            </div>
            <div class="n_bbutton">
                <span><a class="n_left">確定</a></span>
                <span><a class="n_right" onclick="$('.blbg').click();">取消</a></span>
            </div>
        </div>
        <a id="" onclick="$('.blbg').click();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>

    <form id="form2" action="{{ route('valueAddedService_ec') }}" method=post>
        {!! csrf_field() !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
        <input type="hidden" name="userId" value="{{$user->id}}">
        <input type="hidden" name="payment" value="cc_quarterly_payment">
        <input type="hidden" name="choosePayment" value="Credit">
        <input type="hidden" name="service_name" value="VVIP">
        <input type="hidden" name="plan" value="VVIP_A">
        <input type="hidden" name="amount" value="9888">
    </form>
@stop

@section('javascript')
    <link href="{{ asset('css/jquery.fileuploader.min.css') }}" media="all" rel="stylesheet">
    <link href="{{ asset('new/css/fileupload.css') }}" media="all" rel="stylesheet">
    <link href="{{ asset('css/font/font-fileuploader.css') }}" media="all" rel="stylesheet">
    <script src="{{ asset('js/jquery.fileuploader.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {

            let hash='';
            if(window.location.hash) {
                hash = window.location.hash.substring(1);
            }
            $('.vvip_upload_img').on('click',function () {
                if(!$('#i_agree').is(':checked'))
                {
                    c5("請勾選我同意");
                    return false;
                }
                $('.vvip_a_2').show();
                $('.vvip_a_1').hide();
            });

            $('.submit_next').on('click',function () {
                if($('input[name="fileuploader-list-files"]').val()=='[]'){
                    c5('您尚未選擇任何檔案');
                }else {
                    if(hash == 'refill') {
                        $('#form1').submit();
                    }else {
                        $('.vvip_a_2').show();
                        $('.vvip_a_1').hide();
                        $(".blbg").show();
                        $("#tab01").show();
                        $('body').css("overflow", "hidden");
                    }
                }
            });

            $('.n_right, .bl_gb, .blbg').on('click',function () {
                window.location.href = '/dashboard/vvipSelectA';
            });

            $('.n_left').on('click',function () {
                $('#form1').submit();
            });

            $('input[name="mode"]').val('pay');

            if(hash=='refill'){
                $('.vvip_a_2').show();
                $('.refill').show();
                $('.vvip_a_1').hide();
                $('input[name="mode"]').val('refill');
            }

            if(hash=='pay'){
                $('.vvip_a_2').show();
                $('.vvip_a_1').hide();
                $('#form2').submit();
            }

            if(hash=='file_error'){
                $('.vvip_a_2').show();
                $('.vvip_a_1').hide();
            }

            @if(Session::has('message') && Session::get('message') != '')
            c5("{{ Session::get('message') }}");
            @endif

            //errors
            @foreach ($errors->all() as $error)
            c5('{{$error}}');
            @endforeach


            // enable fileuploader plugin
            $('input[name="files"]').fileuploader({
                extensions: ['image/*', 'pdf'],
                changeInput: ' ',
                theme: 'thumbnails',
                enableApi: true,
                addMore: true,
                thumbnails: {
                    box: '<div class="fileuploader-items">' +
                        '<ul class="fileuploader-items-list">' +
                        '<li class="fileuploader-thumbnails-input"><br><div class="fileuploader-thumbnails-input-inner"><i>上傳文件</i></div></li>' +
                        '</ul>' +
                        '</div>',
                    item: '<li class="fileuploader-item">' +
                        '<div class="fileuploader-item-inner">' +
                        '<div class="type-holder">${extension}</div>' +
                        '<div class="actions-holder">' +
                        '<button type="button" class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="fileuploader-icon-remove"></i></button>' +
                        '</div>' +
                        '<div class="thumbnail-holder">' +
                        '${image}' +
                        '<span class="fileuploader-action-popup"></span>' +
                        '</div>' +
                        '<div class="content-holder"><h5>${name}</h5><span>${size2}</span></div>' +
                        '<div class="progress-holder">${progressBar}</div>' +
                        '</div>' +
                        '</li>',
                    item2: '<li class="fileuploader-item">' +
                        '<div class="fileuploader-item-inner">' +
                        '<div class="type-holder">${extension}</div>' +
                        '<div class="actions-holder">' +
                        '<a href="${file}" class="fileuploader-action fileuploader-action-download" title="${captions.download}" download><i class="fileuploader-icon-download"></i></a>' +
                        '<button type="button" class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="fileuploader-icon-remove"></i></button>' +
                        '</div>' +
                        '<div class="thumbnail-holder">' +
                        '${image}' +
                        '<span class="fileuploader-action-popup"></span>' +
                        '</div>' +
                        '<div class="content-holder"><h5 title="${name}">${name}</h5><span>${size2}</span></div>' +
                        '<div class="progress-holder">${progressBar}</div>' +
                        '</div>' +
                        '</li>',
                    startImageRenderer: true,
                    canvasImage: false,
                    _selectors: {
                        list: '.fileuploader-items-list',
                        item: '.fileuploader-item',
                        start: '.fileuploader-action-start',
                        retry: '.fileuploader-action-retry',
                        remove: '.fileuploader-action-remove'
                    },
                    onItemShow: function(item, listEl, parentEl, newInputEl, inputEl) {
                        var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                            api = $.fileuploader.getInstance(inputEl.get(0));

                        plusInput.insertAfter(item.html)[api.getOptions().limit && api.getChoosedFiles().length >= api.getOptions().limit ? 'hide' : 'show']();

                        if(item.format == 'image') {
                            item.html.find('.fileuploader-item-icon').hide();
                        }
                    },
                    onItemRemove: function(html, listEl, parentEl, newInputEl, inputEl) {
                        var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                            api = $.fileuploader.getInstance(inputEl.get(0));

                        html.children().animate({'opacity': 0}, 200, function() {
                            html.remove();

                            if (api.getOptions().limit && api.getChoosedFiles().length - 1 < api.getOptions().limit)
                                plusInput.show();
                        });
                    }
                },
                dragDrop: {
                    container: '.fileuploader-thumbnails-input'
                },
                afterRender: function(listEl, parentEl, newInputEl, inputEl) {
                    var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                        api = $.fileuploader.getInstance(inputEl.get(0));

                    plusInput.on('click', function() {
                        api.open();
                    });

                    api.getOptions().dragDrop.container = plusInput;
                },

            });
        });



    </script>
@stop
