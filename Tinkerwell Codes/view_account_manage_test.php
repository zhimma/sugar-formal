<?php
$chinese_num_arr = ['一', '二', '三', '四', '五', '六', '七', '八', '九'];
$user = User::find(134572);
$user_pause_during = config('memadvauth.user.pause_during');
$api_pause_during = config('memadvauth.api.pause_during');

$userPauseMsg =
    '驗證失敗需' .
    ($user_pause_during % 1440 || $user_pause_during / 1440 >= 10
        ? $user_pause_during . '分鐘'
        : $chinese_num_arr[$user_pause_during / 1440 - 1] . '天') .
    '後才能重新申請。';
$apiPauseMsg =
    '本日進階驗證功能維修，請' .
    (intval($api_pause_during / 60)
        ? intval($api_pause_during / 60) . 'hr'
        : '') .
    ($api_pause_during % 60 ? $api_pause_during % 60 . '分鐘' : '') .
    '後再試。';

echo $userPauseMsg . "\n";
echo $apiPauseMsg;