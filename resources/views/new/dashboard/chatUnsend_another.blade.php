<script>
    function realtime_unsend_another(e){
        let m = e.message;
        var is_too_fast = e.is_too_fast;
        var unsend_elt = $('#chat_msg_' + m['id']);
        if(!unsend_elt.length) unsend_elt = $('#chat_msg_client_' + m['client_id']);
        if(unsend_elt.length>0) {
            unsend_elt.after(
                        '<div class="">'
                           +'<div class="sebg matopj10  unsent_msg">'
                               +'<p>'+((is_too_fast!=undefined && is_too_fast)?'照片接收失敗，因{{ $to->name }}發訊頻率過快':'{{ $to->name }}已收回訊息')+'</p>'                              
                            +'</div>' 
                        +'</div>');
            unsend_elt.remove();
        }
    }
   
</script>