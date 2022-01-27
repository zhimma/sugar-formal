<script src="/new/js/birthday.js" type="text/javascript"></script>
<script>
    $.ms_DatePicker();
    
    function tab_confirm_send() {
        $("#tab_confirm").show().addClass('left').find('.bltext').click().html('您輸入的資料如下'
            +'<div>身分證字號：'+$('#id_serial').val()+'</div>'
            +'<div>手機號碼：'+$('#phone_number').val()+'</div>'
            +'<div>生日：'+$('#year').val()+'/'+$('#month').val()+'/'+$('#day').val()+'</div>'
            +'<div>{{$user_pause_during_msg}}所以請務必確定資料正確！</div>'
        ).parent().find('.n_left').blur().html('確定送出').attr('onclick','document.advance_auth_form.submit();this.setAttribute("onclick", "return false;");return false;')
        .parent().parent().find('.n_right').html('返回修改');        
    }
    
    function tab_agree() {
        $(".blbg").show();
        clear_error_appear();
        if(check_val()) {
            $("#tab_confirm").removeClass('left').show().find('.bltext').html(
                    '本站會將您的門號以及生日同步更新到會員基本資料，'+
                    '<span class="bolder red">身分證字號則只用在本次驗證並不會紀錄</span>'+
                    '<div class="margin_top_one_line">此驗證依照以下條款進行</div>'+
                    '<div><a target="_blank" href="{{url('advance_auth_midclause')}}">{{url('advance_auth_midclause')}}</a></div>'+
                    '<div class="margin_top_one_line">請詳細閱讀後選擇</div>'
                )
                .parent().find('.n_left').html('同意').attr('onclick','tab_confirm_send();return false;')
                .parent().parent().find('.n_right').html('不同意');
        }
        else cl();      
        
        return false;
    }
    
    function clear_error_appear() {
        $('#id_serial').parent().removeClass('has_error');
        $('#phone_number').parent().removeClass('has_error');
        $('#year,#month,#day').parent().parent().removeClass('has_error');        
    }
    
    function check_val() {
        var empty_str = '';
        var age_error_msg = '';
        var error_msg = '';
        $('#tab01 .n_fengs').html('');
        if(!checkIdSerial($('#id_serial').val(),'sex')) {
            $('#id_serial').parent().addClass('has_error');
            $('#tab01 .n_fengs').html('您輸入的身分證號與您登記的性別不符，請用本人申請的門號驗證');
            return false;            
        }
        
        if($('#id_serial').val()=='' || !checkIdSerial($('#id_serial').val())) {
            $('#id_serial').parent().addClass('has_error');
            empty_str='/身分證字號';
        }
        if($('#phone_number').val()=='' || !checkPhoneNumber($('#phone_number').val())) {
            $('#phone_number').parent().addClass('has_error');
            empty_str+='/門號';
        }
        if($('#year').val()=='' || $('#month').val()=='' || $('#day').val()=='') {
            $('#year,#month,#day').parent().parent().addClass('has_error');
            empty_str+='/生日';
        }
        else {
            year = $('#year').val();
            month = $('#month').val();
            day = $('#day').val();
            var now = new Date();
            nowyear=now.getFullYear();
            
            nowmonth=now.getMonth();
            nowday = now.getDate();
            
            age=nowyear-year;
            
            if(month>nowmonth || month==nowmonth && day>nowday){
                age--;
            }  

            if(age<18) {
                $('#year,#month,#day').parent().parent().addClass('has_error');
                age_error_msg = '年齡未滿18歲，不得進行驗證';
            }
        }
        
        if(empty_str!='') {
            error_msg = '請輸入正確'+empty_str.replace('/','');
            if(age_error_msg!='')  error_msg+='<br>';
        }
        
        if(age_error_msg!='') {
            error_msg+=age_error_msg;
        }
        
        if(error_msg=='') {
            return true;
        }
        else {
            $('#tab01 .n_fengs').html(error_msg);
            return false;
        } 
    }
    
    function checkPhoneNumber(phone_number) {
        return phone_number.match('^09[0-9]{8}$');
    }

    function checkIdSerial(id_serial,check_type=null) {
        var id = id_serial.trim();
        var check_id_rs = true;

        if (id.length != 10) {
            check_id_rs = false;
        }


        var regionCode = id.charCodeAt(0);
        if (regionCode < 65 | regionCode > 90) {
            check_id_rs = false;
        }

        var sexCode = id.charCodeAt(1);
        if (sexCode != {{$user->engroup+48}}) {
            check_id_rs = false;
            if(check_type=='sex') {
                return false;
            }
        } else if(check_type=='sex') return true;

        var splitCode = id.slice(2)
        for (var i in splitCode) {
            var scode = splitCode.charCodeAt(i);
            if (scode < 48 | scode > 57) {
                check_id_rs = false;
            }
        }
        if(check_id_rs) {
            var letterConverter = "ABCDEFGHJKLMNPQRSTUVXYWZIO"
            var weightArr = [1, 9, 8, 7, 6, 5, 4, 3, 2, 1, 1]

            id_checking = String(letterConverter.indexOf(id[0])+10)
                    + id.slice(1);

            total = 0
            for (let i = 0; i < id_checking.length; i++) {
                c = parseInt(id_checking[i])
                w = weightArr[i]
                total += c * w
            }

            check_id_rs = total % 10 == 0
        }
        return check_id_rs
    }
    
    function origin_phone_popup(phone) {
        if(phone!='' && phone!=undefined) {
            $(".blbg").show();
            $('#tab_general_alert').show().find('.n_fengs').html('您之前驗證的手機門號為'+phone+'，需以相同的門號進行驗證，如需更換手機請<a href="https://lin.ee/rLqcCns" target="_blank">點此加站長line <img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="36" border="0" style="all: initial;all: unset;height: 36px; float: unset;vertical-align:middle;"></a> 聯絡');
        }
    }

</script>	