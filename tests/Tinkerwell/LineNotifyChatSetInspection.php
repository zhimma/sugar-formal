<?php
use Carbon\Carbon;
use App\Models\MemberFav;

$user = User::find(69055);
$to_user = User::findById(12374);
$line_notify_send = false;
//收件夾設定通知
$line_notify_chat_set_data = lineNotifyChatSet::select(
    'line_notify_chat_set.*',
    'line_notify_chat.name',
    'line_notify_chat.gender'
)
    ->leftJoin(
        'line_notify_chat',
        'line_notify_chat.id',
        'line_notify_chat_set.line_notify_chat_id'
    )
    ->where('line_notify_chat.active', 1)
    ->where('line_notify_chat_set.user_id', $to_user->id)
    ->where('line_notify_chat_set.deleted_at', null)
    ->get();
if (!empty($line_notify_chat_set_data)) {
    $user_meta_data = UserMeta::select(
        'user_meta.isWarned',
        'exchange_period_name.*'
    )
        ->leftJoin('users', 'users.id', 'user_meta.user_id')
        ->leftJoin(
            'exchange_period_name',
            'exchange_period_name.id',
            'users.exchange_period'
        )
        ->where('user_id', $user->id)
        ->get()
        ->first();
    foreach ($line_notify_chat_set_data as $key => $row) {
        // print($key . (string)$line_notify_send);
        var_dump($row);
        if ($row->gender == 1 && $row->name == $user_meta_data->name) {
            $line_notify_send = true;
            break;
        } elseif ($row->gender == 2) {
            if ($row->name == 'VIP' && $user->isVIP()) {
                $line_notify_send = true;
                break;
            }
            if ($row->name == '普通會員' && !$user->isVIP()) {
                $line_notify_send = true;
                break;
            }
        } elseif ($row->gender == 0 && $row->name == '警示會員') {
            //警示會員
            //站方警示
            $isAdminWarned = warned_users::where('member_id', $user->id)
                ->where('expire_date', '>=', Carbon::now())
                ->orWhere('expire_date', null)
                ->get();
            if ($user_meta_data->isWarned == 1 || !empty($isAdminWarned)) {
                $line_notify_send = true;
                break;
            }
        } elseif (
            $row->gender == 0 &&
            $row->name == '收藏會員' &&
            $to_user->isVip()
        ) {
            //收藏者通知
            $line_notify_send = memberFav::where('member_id', $to_user->id)
                ->where('member_fav_id', $user->id)
                ->first();
            break;
        } elseif (
            $row->gender == 0 &&
            $row->name == '誰來看我' &&
            $to_user->isVip()
        ) {
            //誰來看我通知
            $line_notify_send = Visited::where('visited_id', $user->id)
                ->where('member_id', $to_user->id)
                ->first();
            break;
        } elseif (
            $row->gender == 0 &&
            $row->name == '收藏我的會員' &&
            $to_user->isVip()
        ) {
            //收藏我的會員通知
            $line_notify_send = memberFav::where('member_id', $user->id)
                ->where('member_fav_id', $to_user->id)
                ->first();
            break;
        }
    }
	print($key . (string)$line_notify_send);
    //站方封鎖
    $banned = banned_users::where('member_id', $user->id)
        ->get()
        ->first();
    if (
        isset($banned) &&
        ($banned->expire_date == null ||
            $banned->expire_date >= Carbon::now())
    ) {
        $line_notify_send = false;
    }

    //檢查封鎖
    $checkIsBlock = Blocked::isBlocked($to_user->id, $user->id);
    if ($checkIsBlock) {
        $line_notify_send = false;
    }
}

dump($line_notify_send);
