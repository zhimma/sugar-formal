function pick_real_error(data) {
    var real_error = '';
    if(data.length>2500) {
        var error_keyword = [
        ['401 Unauthorized']
        , ['404 錯誤']
        , ['網頁過期','可能閒置過久導致頁面過期']
        , ['連線過多','目前連線次數過多','強制登出']
        , ['503 錯誤']
        , ['發生錯誤','網站目前正在更新','請半小時後重試']
        , ['發生不明錯誤','系統錯誤']
        , ['錯誤：沒有資料','使用者已關閉帳號']
        , ['被封鎖了','如有誤封','請點選網頁右下方的聯絡我們']
        , ['被封鎖了','已在被封鎖的會員列表中','詳情請洽站長']
        , ['未知的錯誤','發生未預期錯誤']
        ];
        
        var is_all_finded = false;
        var err_page_msg = '';
        
        for(var ei=0;ei<error_keyword.length;ei++) {
            
            for(var ek=0;ek<error_keyword[ei].length;ek++) {
                if(ek==0 || is_all_finded) is_all_finded = (data.indexOf(error_keyword[ei][ek])>=0);
            } 

            if(is_all_finded) {     
                real_error = error_keyword[ei].join('，')
                break;
            }
        }
        
        if(!is_all_finded) {
            var logout_keyword = ['註冊','登入','忘記密碼','還沒有帳號' ,'免費註冊','login','name="login"','id="login"'];
            var logout_all_finded = false;
            
            for(var lgi=0;lgi<logout_keyword.length;lgi++) {
                if(lgi==0 || logout_all_finded) logout_all_finded = (data.indexOf(logout_keyword[lgi])>=0);
            }    

            if(logout_all_finded) real_error='因帳號已登出所以上傳失敗！請重新登入';
        }
        
    }
    
    return real_error;
}