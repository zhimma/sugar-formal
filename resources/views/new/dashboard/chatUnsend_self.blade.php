<script>
    function realtime_unsend_self(e){
        let m = e.message;
        var unsend_elt = $('#unsend_form_' + m['id']);

        if(unsend_elt.length>0) {
            unsend_elt.after(
                        '<div class="">'
                           +'<div class="sebg matopj10  unsent_msg">'
                               +'<p>您已收回訊息</p>'                              
                            +'</div>' 
                        +'</div>');
            unsend_elt.remove();
        }
    }
</script>