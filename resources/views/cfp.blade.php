<?php
header("Access-Control-Allow-Origin: *");
?>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" >
<head>
    <meta charset="UTF-8">
    <title>CFP</title>
</head>
<body>
{{--<div class="cfp"></div>--}}
<script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
<script>
    let cfpLocal = window.localStorage.getItem('cfp');
    let cfp_hash = null;
    if(!cfpLocal){
        const cfp = { hash: "{{ str_random(50) }}" };
        cfp_hash = cfp.hash;
        {{-- 若無 CFP，則建立 CFP --}}
        window.localStorage.setItem('cfp', JSON.stringify(cfp));
        cfpLocal = window.localStorage.getItem('cfp');
    }
    else{
        {{-- 若有 CFP，則記錄 CFP --}}
        cfpLocal = JSON.parse(cfpLocal);
        cfp_hash = cfpLocal.hash;
    }

    parent.postMessage(cfp_hash, '*');
</script>
</body>
</html>
