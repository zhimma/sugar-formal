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

    /* fingerprintJS (visitorID) */
    // let visitorIDLocal = window.localStorage.getItem('visitorID');
    // let visitorID_hash = null;
    // if(!visitorIDLocal){
    //     const fpPromise = import('https://fpcdn.io/v3/fNibEASAcoUCkR3kDSsd')
    //         .then(FingerprintJS => FingerprintJS.load())

    //     // Get the visitor identifier when you need it.
    //     fpPromise
    //         .then(fp => fp.get())
    //         .then(result => {
    //             // This is the visitor identifier:
    //             const visitorId = result.visitorId
    //             const visitorID = { hash: visitorId };
    //             visitorID_hash = visitorID.hash;
    //             {{-- 若無 visitorID，則建立 visitorID --}}
    //             window.localStorage.setItem('visitorID', JSON.stringify(visitorID));
    //             visitorIDLocal = window.localStorage.getItem('visitorID');
    //         })
    // }
    // else{
    //     {{-- 若有 CFP，則記錄 CFP --}}
    //     visitorIDLocal = JSON.parse(visitorIDLocal);
    //     visitorID_hash = visitorIDLocal.hash;
    // }

    // parent.postMessage(visitorID_hash, '*');
</script>
</body>
</html>
