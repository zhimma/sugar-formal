@if(config('app.bypass_stay_online_record') == false)
    <script>
        //上線時間紀錄
        let online_time_interval = setInterval("",stay_online_reocrd_interval*1000);
        var hiddenProperty = 'hidden' in document ? 'hidden' :    
            'webkitHidden' in document ? 'webkitHidden' :    
            'mozHidden' in document ? 'mozHidden' :    
            null;
        var stay_online_reocrd_interval = 3;
        stay_online_record_page_uid = Math.floor(Math.random()*100000).toString(36)+Date.now().toString(36);
        stay_online_record_base_time = Date.now();
        if (!document[hiddenProperty])
        {
            online_time_interval = setInterval("update_online_time("+stay_online_reocrd_interval+")",stay_online_reocrd_interval*1000);
        }
        var visibilityChangeEvent = hiddenProperty.replace(/hidden/i, 'visibilitychange');
        var onVisibilityChange = function()
        {
            if (!document[hiddenProperty]) 
            {  
                stay_online_record_base_time = Date.now();
                update_online_time(0);
                online_time_interval = setInterval("update_online_time("+stay_online_reocrd_interval+")",stay_online_reocrd_interval*1000);
            }
            else
            {
                clearInterval(online_time_interval);
                update_online_time_on_leave();
            }
        }
        
        var onHashChange = function(e) {
            console.log('onHashChange  fired');
            update_online_time_on_leave(e.oldURL);
            stay_online_record_base_time = Date.now();
            update_online_time(0);
            online_time_interval = setInterval("update_online_time("+stay_online_reocrd_interval+")",stay_online_reocrd_interval*1000);
        }
        
        document.addEventListener(visibilityChangeEvent, onVisibilityChange);
        
        window.addEventListener('hashchange', onHashChange);

        window.addEventListener('pagehide', function() {
            update_online_time_on_leave();
        })
        
        update_online_time(0);
        
        function clear_online_time_interval()
        {
            stay_online_record_request.abort();
            clearInterval(online_time_interval);            
        }
        
        function update_online_time_on_leave(page_url='') {
            stay_online_record_request.abort();
            clearInterval(online_time_interval);
            update_online_time( Math.round((Date.now()-stay_online_record_base_time)/1000),page_url);
        } 
        
        function update_online_time(second,page_url=''){
            if (typeof(page_id) == 'undefined') {
                page_id = null;
            } else {
                page_id = page_id;
            }
            
            var page_title_elt = $('.ztitle > span');
            
            if(!page_title_elt.length) page_title_elt = $('.shou span');
            
            if(!page_title_elt.length) page_title_elt = $('.n_zy span');
            
            if(!page_title_elt.length) page_title_elt = $('.zp_title');
            
            if(!page_title_elt.length) page_title_elt = $('.g_password > .gg_zh > .gg_mm > span');
            
            if(!page_title_elt.length) page_title_elt = $('.dengl > .zhuce > h2');
            
            if(!page_title_elt.length) page_title_elt = $('.ddt_list > .nn_dontt > ul > .nn_dontt_hover');
            
            if(!page_title_elt.length) page_title_elt = $('.wxsy > .wxsy_title');
            
            if(!page_title_elt.length) page_title_elt = $('.shouxq > span > a > .se_rea ');            
            
            if(!page_title_elt.length) page_title_elt = $('.shouxq > .se_rea ');
            
            if(!page_title_elt.length) page_title_elt = $('.ziliao > .ztitle > span ');
            
            var stay_online_time_formData = new FormData();
            stay_online_time_formData.append('_token', "{!! csrf_token() !!}");
            stay_online_time_formData.append('stay_second', second);
            stay_online_time_formData.append('stay_online_record_id', sessionStorage.getItem('stay_online_record_id'));
            if(page_id!=null && page_id!=undefined)
                stay_online_time_formData.append('page_id', page_id);
            stay_online_time_formData.append('page_url', (page_url==''?location.href:page_url));
            stay_online_time_formData.append('page_uid', stay_online_record_page_uid);
            stay_online_time_formData.append('page_title', page_title_elt.length?page_title_elt.eq(0).text():'');
            
            stay_online_record_request = $.ajax({
                processData: false,
                contentType: false,
                type:'post',
                url:'{{route("stay_online_time")}}?{{ csrf_token() }}='+Date.now(),
                data:stay_online_time_formData,
                dataType:'json',
                success:function(data){
                    stay_online_record_base_time = Date.now();
                    sessionStorage.setItem('stay_online_record_id',data['stay_online_record_id']);
                },
                error:function(xhr, status, error) {
                    if(error=='Unauthorized') {
                        clear_online_time_interval();
                        var unauth_error_msg = '您已登出或基於帳號安全由系統自動登出，請重新登入';
                        if($('#tabPopM').length>0) {
                            show_pop_message(unauth_error_msg);
                        }
                        else {
                            alert(unauth_error_msg);
                            location.reload();
                        }
                    }
                }
            });
        }
        //上線時間紀錄
    </script>
@endif