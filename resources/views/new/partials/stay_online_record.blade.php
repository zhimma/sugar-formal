    <script>
        //上線時間紀錄
        var hiddenProperty = 'hidden' in document ? 'hidden' :    
            'webkitHidden' in document ? 'webkitHidden' :    
            'mozHidden' in document ? 'mozHidden' :    
            null;
        stay_online_record_page_uid = Math.floor(Math.random()*100000).toString(36)+Date.now().toString(36);
        stay_online_record_base_time = Date.now();
        if (!document[hiddenProperty])
        {
            online_time_interval = setInterval("update_online_time(5)","5000");
        }
        var visibilityChangeEvent = hiddenProperty.replace(/hidden/i, 'visibilitychange');
        var onVisibilityChange = function()
        {
            if (!document[hiddenProperty]) 
            {  
                stay_online_record_base_time = Date.now();
                update_online_time(0);
                online_time_interval = setInterval("update_online_time(5)","5000");
            }
            else
            {
                clearInterval(online_time_interval);
                update_online_time_on_leave();
            }
        }
        document.addEventListener(visibilityChangeEvent, onVisibilityChange);

        window.addEventListener('pagehide', function() {
            update_online_time_on_leave();
        })
        
        update_online_time(0);
        
        function update_online_time_on_leave() {
            stay_online_record_request.abort();
            clearInterval(online_time_interval);
            update_online_time( Math.round((Date.now()-stay_online_record_base_time)/1000));
        } 
        
        function update_online_time(second){
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
            
            stay_online_record_request = $.ajax({
                type:'post',
                url:'{{route("stay_online_time")}}',
                data:
                {
                    _token: '{{ csrf_token() }}',
                    stay_second: second,
                    stay_online_record_id: sessionStorage.getItem('stay_online_record_id'),
                    page_id: page_id,
                    page_url:location.href,
                    page_uid:stay_online_record_page_uid,
                    page_title:page_title_elt.length?page_title_elt.eq(0).text():''
                },
                success:function(data){
                    stay_online_record_base_time = Date.now();
                    sessionStorage.setItem('stay_online_record_id',data['stay_online_record_id']);
                }
            });
        }
        //上線時間紀錄
    </script>
