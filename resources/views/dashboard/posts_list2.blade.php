<html>
<head>
<script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script>
</head>

<body>

<div class="posts_list">


<?php

foreach($posts as $post){
?>
    <div class="post">
<?php
    if($post->panonymous){
        echo '匿名';
    }else{
        echo $post->uname;
    }
    echo $post->ptitle;
    echo $post->pcontents;
    echo $post->pupdated_at;

?>
</div>
<?php
}
?>
<input type="hidden" id="page" value="1">
</div>

</body>

<script>

$(window).scroll(function(){
    //  last=$(this).scrollTop()-$(window).height()-1000
    var scrollBottom = $(window).height() - $(this).scrollTop();
     console.log($(window).height(), $(this).scrollTop(),scrollBottom)
     if(scrollBottom==1000){
         console.log('1');
        
        $.ajax({
            type: 'POST',
            url: '/dashboard/getPosts?{{csrf_token()}}={{now()->timestamp}}',
            data: {
                _token: '{{csrf_token()}}',
                page  : $("#page").val(),
                },
            success: function(xhr, status, error){
                console.log();
            },
            
        });
     }
});

</script>

</html>