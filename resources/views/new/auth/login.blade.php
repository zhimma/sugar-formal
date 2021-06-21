<?
header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
?>
@extends('new.layouts.website')
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/2.1.0/fingerprint2.js"></script>--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/UAParser.js/0.7.20/ua-parser.js"></script>--}}
{{--<script src="{{ url('/new/js/fingerprint.js?time=' . \Carbon\Carbon::now()->timestamp) }}"></script>--}}
{{--<script src="{{ url('/new/js/fingerprint2.js?time=' . \Carbon\Carbon::now()->timestamp) }}"></script>--}}
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
{{--                    <input type="hidden" name="fp" id="fp">--}}
{{--                    <input type="hidden" name="userAgent" id="userAgent">--}}
{{--                    <input type="hidden" name="webdriver" id="webdriver">--}}
{{--                    <input type="hidden" name="colorDepth" id="colorDepth">--}}
{{--                    <input type="hidden" name="deviceMemory" id="deviceMemory">--}}
{{--                    <input type="hidden" name="pixelRatio" id="pixelRatio">--}}
{{--                    <input type="hidden" name="hardwareConcurrency" id="hardwareConcurrency">--}}
{{--                    <input type="hidden" name="screenResolution" id="screenResolution">--}}
{{--                    <input type="hidden" name="availableScreenResolution" id="availableScreenResolution">--}}
{{--                    <input type="hidden" name="timezoneOffset" id="timezoneOffset">--}}
{{--                    <input type="hidden" name="timezone" id="timezone">--}}
{{--                    <input type="hidden" name="sessionStorage" id="sessionStorage">--}}
{{--                    <input type="hidden" name="localStorage" id="localStorage">--}}
{{--                    <input type="hidden" name="indexedDb" id="indexedDb">--}}
{{--                    <input type="hidden" name="openDatabase" id="openDatabase">--}}
{{--                    <input type="hidden" name="cpuClass" id="cpuClass">--}}
{{--                    <input type="hidden" name="platform" id="platform">--}}
{{--                    <input type="hidden" name="doNotTrack" id="doNotTrack">--}}
{{--                    <input type="hidden" name="plugins" id="plugins">--}}
{{--                    <input type="hidden" name="canvas" id="canvas">--}}
{{--                    <input type="hidden" name="webgl" id="webgl">--}}
{{--                    <input type="hidden" name="webglVendorAndRenderer" id="webglVendorAndRenderer">--}}
{{--                    <input type="hidden" name="adBlock" id="adBlock">--}}
{{--                    <input type="hidden" name="hasLiedLanguages" id="hasLiedLanguages">--}}
{{--                    <input type="hidden" name="hasLiedResolution" id="hasLiedResolution">--}}
{{--                    <input type="hidden" name="hasLiedOs" id="hasLiedOs">--}}
{{--                    <input type="hidden" name="hasLiedBrowser" id="hasLiedBrowser">--}}
{{--                    <input type="hidden" name="touchSupport" id="touchSupport">--}}
{{--                    <input type="hidden" name="fonts" id="fonts">--}}
{{--                    <input type="hidden" name="fontsFlash" id="fontsFlash">--}}
{{--                    <input type="hidden" name="audio" id="audio">--}}
{{--                    <input type="hidden" name="enumerateDevices" id="enumerateDevices">--}}
{{--                    <input type="hidden" name="batterylevel" id="batterylevel">--}}
{{--                    <input type="hidden" name="uniqueVisitorId" id="uniqueVisitorId">--}}

                    <div class="dengl_h" id="login">登入</div>
                    <div id="notice" class="de_input">如果看不到輸入框請開啟 JavaScript 後重新嘗試。若有問題請按下方 <a href="{!! url('contact') !!}" style="color: #33B2FF; text-decoration: underline;">聯絡我們</a> 加站長 line 回報。</div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        {{--function guidGenerator() {
            var S4 = function() {
                return (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
            };
            return (S4() + S4() + "-" + S4() + "-" + S4() + "-" + S4() + "-" + S4()    + S4() + S4());
        }

        var cookie = {};
        cookie.set = function(n, v, t) {
            var exp = new Date();
            exp.setTime(exp.getTime() + (t || 24) * 60 * 60 * 1000 * 365);
            document.cookie = n + "=" + escape(v) + ";expires=" + exp.toGMTString()    + ';path=/';
        }

        cookie.get = function(n) {
            var arr = document.cookie.match(new RegExp("(^| )" + n + "=([^;]*)(;|$)"));
            if (arr != null) {
                return unescape(arr[2]);
            }
            return null;
        }

        var uniqueVisitorId;
        var co = cookie.get('uniqueVisitorId');
        if (co != null) {
            uniqueVisitorId = cookie.get('uniqueVisitorId');
        } else {
            uniqueVisitorId = guidGenerator();
            cookie.set('uniqueVisitorId', uniqueVisitorId);
        }
        var url = window.location.href;

        document.getElementById("uniqueVisitorId").value=uniqueVisitorId;
        document.write(code);--}}




        function getCookie(name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length == 2) return parts.pop().split(";").shift();
        }
    </script>
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
        "                            <input name=\"password\" type=\"password\"  class=\"d_input\" id=\"password\" placeholder=\"密碼\" required >\n" +
        "                        </div>\n" +
        "                        <div class='wknr'>" +
        "                            <h4>若有開啟 AdBlock，請使用無痕模式登入。</h4>" +
        "                            <a href=\"{!! url('password/reset') !!}\" class=\"dlpassword\">忘記密碼 ?</a>\n" +
        "                        </div>" +
        "                        <a href=\"javascript:void(0);\" onclick=\"backendProcess()\" class=\"dlbut btn-login\" id=\"btn-login\">登入</a>\n" +
        "                        <a href=\"{!! url('/checkAdult') !!}\" class=\"dlbut02\">還沒有帳號 ?  免費註冊</a>\n" +
        "                   </div>";
    $("#notice").remove();
    $("#login").after(form);

    {{--var batterylevel = "XX";--}}
    {{--/*取得電池等級*/--}}

    {{--function addFingerprint(){--}}
    {{--    var options = {--}}
    {{--        excludes: {userAgent: false, language: true}--}}
    {{--    }--}}
    {{--    Fingerprint2.getV18(options, function (result, components) {--}}
    {{--        $(components).each(function (key, value) {--}}
    {{--            $("#" + value.key).val(value.value);--}}
    {{--        });--}}
    {{--        $("#fp").val(result);--}}
    {{--        $("#batterylevel").val(batterylevel);--}}
    {{--        --}}{{--$.ajax({--}}
    {{--            --}}{{--    url: "{{ url('/Fingerprint2/addFingerprint') }}", data:{"_token": "{{ csrf_token() }}", "result":result, "components":components, "batterylevel":batterylevel}, type:"POST", success: function(result){--}}

    {{--            --}}{{--        console.log('code:'+result.code+';msg:'+result.msg);--}}
    {{--            --}}{{--    }});--}}
    {{--        })--}}
    {{--    }--}}
    {{--    addFingerprint();--}}
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
            {{--let data = analysisFingerpirntForm();--}}
            $("form[name=login]").parsley().on('form:validate', function (formInstance) {

            })
            .on('form:error', function () {
                var error = $('ul.parsley-errors-list li');
                var msg=[];
                for (var i = 0; i <error.length; i++) {
                    msg[i]=error.eq(i).html();
                }
                msg = Array.from(new Set(msg));
                // ResultData({
                //   msg: msg
                // });
                c5(msg);
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
                    msg: errormsg,
                    showLink: '<a class="n_bllbut matop30" href="/password/reset">忘記密碼 (請點我)</a>'
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

        var input = document.getElementById("password");
        input.addEventListener("keyup", function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                document.getElementById("btn-login").click();
            }
        });

        var input1 = document.getElementById("email");
        input1.addEventListener("keyup", function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                document.getElementById("btn-login").click();
            }
        });
    </script>
    
    <!-- <script src="/js/login.js" type="text/javascript"></script> -->
@stop