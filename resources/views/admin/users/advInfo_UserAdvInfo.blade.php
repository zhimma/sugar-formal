@php
    //$userAdvInfo=\App\Models\User::userAdvInfo($user->id);
@endphp
<br>
{{--<span>每周平均上線次數： {{ array_get($userAdvInfo,'login_times_per_week',0) }}</span>--}}
{{--<span>收藏會員次數： {{ array_get($userAdvInfo,'fav_count',0) }}</span>--}}
{{--<span>發信次數： {{ array_get($userAdvInfo,'message_count',0) }}</span>--}}
{{--<span>過去7天發信次數： {{ array_get($userAdvInfo,'message_count_7',0) }}</span>--}}
{{--<span>過去7天罐頭訊息比例： {{ array_get($userAdvInfo,'message_percent_7',0) }}</span>--}}
{{--<span>瀏覽其他會員次數： {{ array_get($userAdvInfo,'visit_other_count',0) }}</span>--}}
{{--<span>過去7天瀏覽其他會員次數： {{ array_get($userAdvInfo,'visit_other_count_7',0) }}</span>--}}
{{--<span>封鎖多少會員： {{ array_get($userAdvInfo,'blocked_other_count',0) }}</span>--}}
{{--<span>被多少會員封鎖： {{ array_get($userAdvInfo,'be_blocked_other_count',0) }}</span>--}}

<h4>進階資料</h4>
<table class="table table-hover table-bordered" style="width: 70%;">
    <tr>
        <th width="25%">過去7天瀏覽其他會員次數： {{ array_get($userAdvInfo,'visit_other_count_7',0) }}</th>
        <th width="20%">瀏覽其他會員次數： {{ array_get($userAdvInfo,'visit_other_count',0) }}</th>
        <th width="20%">封鎖多少會員： {{ array_get($userAdvInfo,'blocked_other_count',0) }}</th>
        <th width="20%">過去7天罐頭訊息比例： {{ array_get($userAdvInfo,'message_percent_7',0) }}</th>
    </tr>
    <tr>
        <th>過去七天總通訊人數： {{ array_get($userAdvInfo,'message_people_total_7',0) }}</th>
        <th>總通訊人數： {{ array_get($userAdvInfo,'message_people_total',0) }}</th>
        <th>被多少會員封鎖： {{ array_get($userAdvInfo,'be_blocked_other_count',0) }}</th>
        <th>每周平均上線次數： {{ array_get($userAdvInfo,'login_times_per_week',0) }}</th>
    </tr>
    <tr>
        <th>過去七天未回人數： {{ array_get($userAdvInfo,'message_no_reply_count_7',0) }}</th>
        <th>總未回人數： {{ array_get($userAdvInfo,'message_no_reply_count',0) }}</th>
        <th>收藏會員次數： {{ array_get($userAdvInfo,'fav_count',0) }}</th>
        <th></th>
    </tr>
    <tr>
        <th>過去七天發訊人數： {{ array_get($userAdvInfo,'message_people_count_7',0) }}</th>
        <th>發訊人數： {{ array_get($userAdvInfo,'message_people_count',0) }}</th>
        <th></th>
        <th></th>
    </tr>
    <tr>
        <th>過去七天回訊人數： {{ array_get($userAdvInfo,'message_reply_people_count_7',0) }}</th>
        <th>回訊人數： {{ array_get($userAdvInfo,'message_reply_people_count',0) }}</th>
        <th></th>
        <th></th>
    </tr>
    <tr>
        <th>過去七天發訊次數： {{ array_get($userAdvInfo,'message_count_7',0) }}</th>
        <th>發訊次數： {{ array_get($userAdvInfo,'message_count',0) }}</th>
        <th></th>
        <th></th>
    </tr>
    <tr>
        <th>過去七天回訊次數： {{ array_get($userAdvInfo,'message_reply_count_7',0) }}</th>
        <th>回訊次數： {{ array_get($userAdvInfo,'message_reply_count',0) }}</th>
        <th></th>
        <th></th>
    </tr>
</table>