@extends('new.layouts.website')

@section('app-content')
<style>
.new_poptk{width:96%;height:auto;margin: 0 auto;margin-bottom:15px; margin-top:15px; display: block; padding: 0 8px;}
@media (max-width:824px){
/*.new_poptk{height:175px;overflow-y: scroll;}*/
.new_poptk{height:initial;}
.new_poptk::-webkit-scrollbar {
  /*滚动条整体样式*/
  width :4px;  /*高宽分别对应横竖滚动条的尺寸*/
  height: 1px;
  }
.new_poptk::-webkit-scrollbar-thumb {
  /*滚动条里面小方块*/
  border-radius: 100px;
 background: #8a9fef;
  }
.new_poptk::-webkit-scrollbar-track {
  /*滚动条里面轨道*/
  border-radius: 100px;
  background:rgba(255,255,255,0.6);
  }
}

@media (max-width:824px) and (min-width:420px) and (max-height:380px) {
    #tab04 {top:8%;line-height:1;}
}    
    
@media (max-width:450px){
/*.new_poptk{height:300px;}*/
}

@media (max-width:450px) and (min-height:550px){
.new_poptk{height:initial;}
}


.bl_tab_cc{width: 100%;position: fixed;top:8%;z-index: 10;display:none;}
.new_poptk_aa{width:90%;height:50vh;margin: 0 auto;padding-bottom: 20px; padding-top:15px;overflow-y: scroll; display: block;}
.fpt_z_cc{width: 100%;margin: 0 auto;display: block;overflow-y: scroll;margin-top: 0px;height:50vh;padding: 0 5px;}

@media (max-width:1024px){
.new_poptk_aa{height:600px;}
.fpt_z_cc{height:600px;}
}

@media (max-width:824px){
.new_poptk_aa{height:240px;}
.fpt_z_cc{height:240px;}
}
@media (max-width:768px){
.new_poptk_aa{height:700px;}
.fpt_z_cc{height:700px;}
}
@media (max-width:736px){
.new_poptk_aa{height:200px;}
.fpt_z_cc{height:200px;}
}
@media (max-width:450px){
.new_poptk_aa{height:540px;}
.fpt_z_cc{height:540px;}
}

@media (max-width:375px){
.new_poptk_aa{height:500px;}
.fpt_z_cc{height:500px;}
}
@media (max-width:320px){
.new_poptk_aa{height:420px;}
.fpt_z_cc{height:420px;}
}


.new_poptk_aa  ::-webkit-scrollbar {
  /*滚动条整体样式*/
  width :4px;  /*高宽分别对应横竖滚动条的尺寸*/
  height: 1px;
  }
.new_poptk_aa  ::-webkit-scrollbar-thumb {
  /*滚动条里面小方块*/
  border-radius: 100px;
 background: #8a9fef;
  }
.new_poptk_aa  ::-webkit-scrollbar-track {
  /*滚动条里面轨道*/
  border-radius: 100px;
  background:rgba(255,255,255,0.6);
  }

.new_poptk_nn{width: 96%; padding-bottom: 0; padding-top: 0; margin-top:15px; margin-bottom: 15px;}
.new_poptk  ::-webkit-scrollbar {
  /*滚动条整体样式*/
  width :4px;  /*高宽分别对应横竖滚动条的尺寸*/
  height: 1px;
  }
.new_poptk  ::-webkit-scrollbar-thumb {
  /*滚动条里面小方块*/
  border-radius: 100px;
 background: #8a9fef;
  }
.new_poptk  ::-webkit-scrollbar-track {
  /*滚动条里面轨道*/
  border-radius: 100px;
  background:rgba(255,255,255,0.6);
  }
  
  @media (max-width:824px) {
  .fpt_z_aa{height:170px;overflow-y: scroll;}
  }
  
  @media (max-width:450px) {
  .fpt_z_aa{height:300px;}
  }

.nn_yzheight{ min-height:500px;}


@media (max-width:1024px) {
.nn_yzheight{ min-height:1175px;}
}
@media (max-width:824px) {
.nn_yzheight{ min-height:auto;}
}

@media (max-width:797px) {
.nn_yzheight{ min-height:830px;}
}

@media (max-width:736px) {
.nn_yzheight{ min-height:auto;}
}



div.new_poptk{color:#6783c7;} 
</style>
	<div class="container matop120">
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12">
                <div class="dengl matbot140">
                    <div class="col-md-12">
                        @include('partials.errors')
                        @include('partials.message')
                    </div>
                    <div class="zhuce"><h2>註冊</h2><h3>請記住您的密碼，不要留下真名</h3></div>
                    <form class="de_input " name="register" method="POST" action="/register" data-parsley-validate novalidate>
                        {!! csrf_field() !!}
                        <div class="de_input01 dlmarbot">
                            <input type="password" style="display:none" />
                            <input data-parsley-required name="email" type="email" class="zcinput" placeholder="E-mail 信箱（也是您未來的帳號）" value="{{old('email')}}" autocomplete="off" data-parsley-required-message="請輸入Email">
                        </div>
                        <div class="de_input01 dlmarbot">
                            <input type="password" style="display:none" />
                            <input style="-webkit-text-security: disc;" data-parsley-required id="pw" minlength="6"  data-parsley-minlength="6" name="password" type="password" class="zcinput" placeholder="密碼" value="{{old('password')}}" autocomplete="off" data-parsley-required-message="請輸入密碼">
                        </div>
                        <div class="de_input01 dlmarbot">
                            <input  data-parsley-equalto="#pw" data-parsley-required name="password_confirmation" type="password" class="zcinput" placeholder="密碼確認" value="{{old('password_confirmation')}}" autocomplete="off" data-parsley-required-message="請再次輸入密碼">
                        </div>
                        <div class="de_input01 dlmarbot">
                            <input data-parsley-required data-parsley-excluded=true id="name" name="name"  type="text" class="zcinput" placeholder="暱稱 (至多八個字)" value="{{old('name')}}" maxlength="8" data-parsley-required-message="請輸入暱稱">
                        </div>
                        <div class="de_input01 dlmarbot">
                            <input data-parsley-required name="title" type="text" class="zcinput" placeholder="一句話形容自已"  value="{{old('title')}}" data-parsley-required-message="請輸入一句話形容自己">
                        </div>

                        <div class="de_input02">
                            <h2>帳號類型（Daddy / Baby）</h2>
                            <h3><input data-parsley-required data-parsley-required-message="請選擇帳號類型" type="radio"  name="engroup" value="1" id="RadioGroup1_0" {{old('engroup') == '1'? 'checked' : '' }}><span>甜心大哥</span></h3>
                            <h4>你願意用禮物、美食、旅遊等種種方式，寵愛對方，為了得到他的陪伴</h4>
                            <h3><input data-parsley-required data-parsley-required-message="請選擇帳號類型" type="radio" name="engroup" value="2" id="RadioGroup1_1" {{old('engroup') == '2'? 'checked' : '' }}><span>甜心寶貝</span></h3>
                            <h4>妳想得到寵愛，陪伴甜心大哥</h4>
                        </div>
                        <br>
                        @php
                            $exchange_period_name = DB::table('exchange_period_name')->get();
                        @endphp
                        <div class="de_input02 exchange_period" style="{{old('exchange_period') == ''? 'display: none;' : ''}}">
                            <h2>包養關係</h2>
                            @foreach($exchange_period_name as $row)
                            <h3><input type="radio" name="exchange_period" value="{{$row->id}}" {{old('exchange_period') == $row->id? 'checked' : '' }}><span>{{$row->name}}</span></h3>
                            @endforeach
                        </div>

                        <div class="decheck"><input data-parsley-required data-parsley-required-message="請勾選同意使用條款和隱私政策" name="agree" type="checkbox" {{old('agree') == 'on'? 'checked' : '' }}><span>我同意甜心花園的使用條款和隱私政策</span></div>
                        <input type="hidden" name="google_recaptcha_token" id="ctl-recaptcha-token">
                        <input type="hidden" name="{{ time() }}" value="{{ time() }}">
                        <input type="hidden" name="cfp_hash" id="cfp_hash">
                        {{-- <a href="javascript:void(0);" onclick="this.disabled = true" class="dlbut btn-register">註冊</a> --}}
                        <button onclick="this.disabled = true" class="dlbut btn-register" style="border-style: none;">註冊</button>
                        <a href="" class="zcbut matop20">取消</a>

                    </form>
                    <iframe id="childFrame" src="https://www.sugar-garden.org/cfp" style="border:none;" ></iframe>
                </div>
            </div>
        </div>
    </div>
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.RECAPTCHA_SITE_KEY') }}"></script>
    <script>

        window.onmessage = function(e){
            if(e.data != 'recaptcha-setup') {
                $('#cfp_hash').attr('value', e.data);
            }
        };

        grecaptcha.ready(function() {
            grecaptcha.execute('{{ config('recaptcha.RECAPTCHA_SITE_KEY') }}', {action: 'register'}).then(function(token) {
                document.getElementById('ctl-recaptcha-token').value = token;
            });
        });
        $(document).ready(function() {
			@if(\Session::get('is_remind_puppet')=='1')
			c4('您好，本站禁止註冊多重帳號。[br][br]若偵測到多重帳號註冊，將會影響您所有帳號，可能遭受警示或者封鎖的處分。[br][br]否繼續註冊？');
            $('#tab04 .n_blnr01').addClass('new_poptk');
            $('#tab04 .n_blnr01').removeClass('n_blnr01');
			$("#tab04 .bltext").html($("#tab04 .bltext").text().replace(/\[br\]/gi,'<br>'));
            $('#tab04 .bl_gb img').hide();
			$('#tab04 .n_bbutton .n_left').html('是');
			$('#tab04 .n_bbutton .n_right').html('否');
			$(document).off('click','.blbg',closeAndReload);
			$(document).on('click','#tab04 .n_bbutton .n_left',rebuildForm);
			
			$(document).on('click','#tab04 .n_bbutton .n_right',function() {
				location.href='{!! url('') !!}';
			});
			
			function rebuildForm() {
				$(document).off('click','#tab04 .n_bbutton .n_left',rebuildForm);
				var rebuild_form = document.createElement('form');
				rebuild_form.method = $('.de_input').attr('method');
				rebuild_form.action =$('.de_input').attr('action');	
				var rebuild_elt = document.createElement('input');
				rebuild_elt.type = 'hidden';
				rebuild_elt.name = '_token';
				rebuild_elt.value = '{{ csrf_token() }}';
				rebuild_form.appendChild(rebuild_elt);
				var rebuild_elt = document.createElement('input');
				rebuild_elt.type = 'hidden';
				rebuild_elt.name = 'is_remind_puppet';
				rebuild_elt.value = '{{ \Session::get('is_remind_puppet') }}';				
				rebuild_form.appendChild(rebuild_elt);
				document.body.appendChild(rebuild_form);				
				rebuild_form.submit();
				rebuild_elt = null;
			}	
			@endif
            $("input[name='engroup']").change(function(){
                if ($(this).val() === '1') {
                    $('.exchange_period').hide();
                }else{
                    $('.exchange_period').show();
                }
            });

            window.ParsleyValidator.addMessage('en', 'minlength', '密碼欄位請輸入大於6個位元(含以上)');

            $("form[name=register]").parsley().on('form:validate', function (formInstance) {

            })
            .on('form:error', function () {
                var error = $('ul.parsley-errors-list li');
                var msg=[];
                for (var i = 0; i <error.length; i++) {
                    msg[i]=error.eq(i).html();
                    break;
                }
                msg = Array.from(new Set(msg));
                // ResultData({
                //   msg: msg
                // });
                c5(msg);
                $(".btn-register").removeAttr('disabled', 'disabled')
            })
            .on('form:success', function () {
                return true;
            });
            @if (isset($errors)&&($errors->count() > 0))
                var errormsg=[];
                for (var i = 0; i <$('ul.quarx-errors li').length; i++) {
                    errormsg[i]=$('ul.quarx-errors li').eq(i).html();
                }
                errormsg = Array.from(new Set(errormsg));
                // ResultData({
                //     msg: errormsg
                // });
                c5(errormsg);
            @endif

            window.onmessage = function(e){
                if(e.data != 'recaptcha-setup') {
                    $('#cfp_hash').attr('value', e.data);
                }
            };
        });
        $('.alert-danger').css('display','none');
        $(".btn-register").click(function(e){
            var t = $(this).closest("form");

            if($('.exchange_period').is(":visible")){

                if($("input[name='exchange_period']").is(':checked')) {
                    t.submit();
                }else{
                    c5('包養關係尚未填寫');
                    return false;
                }
            }else{
                t.submit();
            }
            // t.submit();
        });
    </script>
    <!-- <script src="/js/login.js" type="text/javascript"></script> -->
@stop