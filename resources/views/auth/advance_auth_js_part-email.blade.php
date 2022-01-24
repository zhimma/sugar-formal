<script>
    
    function tab_confirm_send() {
        var email = $('#email').val();
        $(".blbg").show();
        clear_error_appear();
        if(!checkNotAcceptEmailMsg(email)) {
            return false;
        }        
        if(check_val()) {
            $("#tab_confirm").show().addClass('left').find('.bltext').click().html('您輸入的資料如下'
                +'<div class="confirm_edu_email_box">校內Email信箱：'+email+'</div>'
                +'<div>送出後將無法再修改用於進階驗證的校內Email信箱，所以請務必確定資料正確！</div>'
            ).parent().find('.n_left').blur().html('確定送出').attr('onclick','document.advance_auth_form.submit();this.setAttribute("onclick", "return false;");return false;')
            .parent().parent().find('.n_right').html('返回修改');   
        }
        else {
            cl(); 
        }
        return false;
    }
    
    function clear_error_appear() {
        $('#email').parent().removeClass('has_error');       
    }
    
    function check_val() {
        var empty_str = '';
        var error_msg = '';
        var email = $('#email').val().trim();
        $('#tab01 .n_fengs').html('');
        
        if(email=='') {
            
            empty_str='Email';
        }
        
        
        if(empty_str!='') {
            error_msg = '請輸入正確'+empty_str.replace('/','');
        }
        else if(!checkEmail(email)) {
            @if($_SERVER['SERVER_ADDR']!='127.0.0.1')
            error_msg = '請輸入以edu.tw網域結尾的校內email信箱';
            @endif
        }
        
        if(error_msg=='') {
            return true;
        }
        else {
            $('#email').parent().addClass('has_error');
            $('#tab01 .n_fengs').html(error_msg);
            return false;
        }
        
    }
    
    function checkEmail(email) {
        return email.match('.+@.+\.edu\.tw$');
    }
    
    function checkNotAcceptEmailMsg(email) {
        rs = true;
        if(email.match('[^a-zA-Z]tp\.edu\.tw$')) rs=false;
        if(email.match('[^a-zA-Z]educities\.edu\.tw$')) rs=false;
        if(!rs) {
            tabElt = $('#tab_general_confirm');
            tabElt.find('.bltext')
            .html('此驗證方式只能接受學校信箱'
                + '<br>即 edu.tw 結尾的 Email'
                + '<br>但不接受 educities.edu.tw 以及 tp.edu.tw 此兩組 email'
                +'<br><br>您輸入的 email 為 '+email
                +'<br>無法通過驗證');
            tabElt.find('.n_left').attr('onclick','location.href=\'{{url("/dashboard/account_manage")}}\'').html('放棄驗證');
            tabElt.find('.n_right').html('返回修改');
            tabElt.show();
            $('#email').parent().addClass('has_error');
        }
        return rs;
    }       


</script>	