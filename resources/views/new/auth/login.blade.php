@extends('new.layouts.website')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/2.1.0/fingerprint2.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/UAParser.js/0.7.20/ua-parser.js"></script>
<script src="{{ url('/new/js/fingerprint.js') }}"></script>
<script src="{{ url('/new/js/fingerprint2.js') }}"></script>
@section('app-content')

	<div class="container logtop">
        <div class="row">
            <div class="col-md-12">
                @include('partials.errors')
                @include('partials.message')
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12">
                <form class="" name="login" action="/login" method="POST" class="dengl"  data-parsley-validate novalidate>
                    {!! csrf_field() !!}
                   <div class="dengl_h">登入</div>
                   <div class="de_input">
                        <div class="m-loader m-loader--right m-loader--light">
                            <div class="de_input01 dlmarbot ">
                                <div class="de_img"><img src="/new/images/lo_03.png"></div>
                                <input name="email" type="email" autocomplete="off" id="email" class="d_input" placeholder="帳號 (您的Email)" values="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="de_input01 dlmarbot m-loader m-loader--right m-loader--light">
                            <div class="de_img"><img src="/new/images/lo_11.png"></div>
                            <input name="password" type="password"  class="d_input" placeholder="密碼" required >
                        </div>
                        <a href="{!! url('password/reset') !!}" class="dlpassword">忘記密碼 ?</a>
                        <a href="javascript:void(0);" onclick="backendProcess()" class="dlbut btn-login">登入</a>
                        <a href="{!! url('/checkAdult') !!}" class="dlbut02">還沒有帳號 ?  免費註冊</a>
                   </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        var batterylevel;
        /*取得電池等級*/
        navigator.getBattery().then(function(battery) {
            batterylevel = battery.level;
        });

        function addFingerprint(){
            var options = {
                excludes: {userAgent: false, language: true}
            }
            Fingerprint2.getV18(options, function (result, components) {
                $.ajax({
                    url: "{{ url('/Fingerprint2/addFingerprint') }}", data:{"_token": "{{ csrf_token() }}", "result":result, "components":components, "batterylevel":batterylevel}, type:"POST", success: function(result){

                        console.log('code:'+result.code+';msg:'+result.msg);
                    }});
            })
        }

        var backendProcess = function(){
            addFingerprint();
            let email =  document.getElementById('email').value;
            if(email != null || email != ""){
                if (window.requestIdleCallback) {
                    requestIdleCallback(function () {
                        identifyResult('{{ csrf_token() }}', $('#email').val(), function(result){
                            console.log(result)
                        })
                    })
                }
                else {
                    setTimeout(function () {
                        identifyResult('{{ csrf_token() }}', $('#email').val(), function(result){
                            console.log(result)
                        })
                    }, 500)
                }
            }
        }

        $(document).ready(function() {
            $("form[name=login]").parsley().on('form:validate', function (formInstance) {

            })
            .on('form:error', function () {
                var error = $('ul.parsley-errors-list li');
                var msg=[];
                for (var i = 0; i <error.length; i++) {
                    msg[i]=error.eq(i).html();
                }
                msg = Array.from(new Set(msg));
                ResultData({
                  msg: msg
                });
                //c2(msg);
            })
            .on('form:success', function () {
                return true;
            });
            @if (isset($errors)&&($errors->count() > 0))
                errormsg={};
                for (var i = 0; i <$('ul.quarx-errors li').length; i++) {
                    errormsg.i=$('ul.quarx-errors li').eq(i).html();
                }
                ResultData({
                    msg: errormsg
                });
                //c2(errormsg);
            @endif
        });
        $('.alert-danger').css('display','none');


        $(".btn-login").click(function(e){
            var email = $("input[name=email]").val();
            var password = $("input[name=password]").val();
            var t = $(this).closest("form");
            if(email.length==0||password.length==0){
                c2('請輸入帳號或密碼');
                // swal({
                //     title:'請輸入帳號或密碼',
                //     type:'error'
                // });
            }else{
                t.submit();
            }
        });
    </script>
    <!-- <script src="/js/login.js" type="text/javascript"></script> -->
@stop