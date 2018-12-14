@extends('layouts.website')

@section('app-content')
<div class="m-portlet__head">
<div class="m-portlet__head-caption">
    <div class="m-portlet__head-title">
        <h3 class="m-portlet__head-text">
        請加上大頭照 <small></small>
        </h3>
    </div>			
</div>
</div>
<div class="m-portlet__body">
<p>由於您尚未加上大頭照，因此將在<span id="countdown"></span>秒後才會進入登入後頁面。</p>
</div>
<script type="text/javascript">
    // Total seconds to wait
    var seconds = {{ config('social.user.avatar-wait-seconds') }};

    function countdown() {
        if (seconds == 0) {
            window.location = "{{ route('dashboard') }}";
        } else {
            document.getElementById("countdown").innerHTML = seconds;
            seconds = seconds - 1;
            window.setTimeout("countdown()", 1000);
        }
    }

    // Run countdown function
    countdown();
</script>
@stop

