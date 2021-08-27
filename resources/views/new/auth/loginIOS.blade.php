<?
header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
?>
@extends('new.layouts.website')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/2.1.0/fingerprint2.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/UAParser.js/0.7.20/ua-parser.js"></script>
<script src="{{ url('/new/js/fingerprint.js?time=' . \Carbon\Carbon::now()->timestamp) }}"></script>
<script src="{{ url('/new/js/fingerprint2.js?time=' . \Carbon\Carbon::now()->timestamp) }}"></script>
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
                <form name="login" action="/login" method="POST" class="dengl"  data-parsley-validate novalidate>
                    {!! csrf_field() !!}
                    <div class="dengl_h" id="login">登入</div>
                    <div id="notice" class="de_input">如果看不到輸入框請開啟 JavaScript 後重新嘗試。若有問題請按下方 <a href="{!! url('contact') !!}" style="color: #33B2FF; text-decoration: underline;">聯絡我們</a> 加站長 line 回報。</div>
                </form>
            </div>
        </div>
    </div>
    <script>
        let form = "<div class=\"de_input\">\n" +
            "                        <div class=\"m-loader m-loader--right m-loader--light\">\n" +
            "                            <div class=\"de_input01 dlmarbot \">\n" +
            "                                <div class=\"de_img\"><img src=\"/new/images/lo_03.png\"></div>\n" +
            "                                <input name=\"email\" type=\"email\" autocomplete=\"off\" id=\"email\" class=\"d_input\" placeholder=\"帳號 (您的Email)\" values=\"{{ old('email') }}\" required>\n" +
            "                            </div>\n" +
            "                        </div>\n" +
            "                        <div class=\"de_input01 dlmarbot m-loader m-loader--right m-loader--light\">\n" +
            "                            <div class=\"de_img\"><img src=\"/new/images/lo_11.png\"></div>\n" +
            "                            <input name=\"password\" type=\"password\"  class=\"d_input\" placeholder=\"密碼\" required >\n" +
            "                        </div>\n" +
            "                        <div class='wknr'>" +
            "                            <a href=\"{!! url('password/reset') !!}\" class=\"dlpassword\">忘記密碼 ?</a>\n" +
            "                        </div>" +
            "                        <a href=\"javascript:void(0);\" onclick=\"backendProcess()\" class=\"dlbut btn-login\">登入</a>\n" +
            "                        <a href=\"{!! url('/checkAdult') !!}\" class=\"dlbut02\">還沒有帳號 ?  免費註冊</a>\n" +
            "                   </div>";
        $("#notice").remove();
        $("#login").after(form);

        var batterylevel = "XX";
        /*取得電池等級*/

        var backendProcess = function(){
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
            let data = analysisFingerpirntForm();
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
                //c5(msg);
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
                //c5(errormsg);
            @endif
        });
        $('.alert-danger').css('display','none');


        $(".btn-login").click(function(e){
            var email = $("input[name=email]").val();
            var password = $("input[name=password]").val();
            var t = $(this).closest("form");
            if(email.length==0||password.length==0){
                c5('請輸入帳號或密碼');
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