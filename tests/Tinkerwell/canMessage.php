$uid = 123158;
$targetUser = User::where('id', $uid)
  ->where('accountStatus', 1)
  ->where('account_status_admin', 1)
  ->get()
  ->first();
$date_start = date("Y-m-d", strtotime("-6 days", strtotime(date('Y-m-d'))));
$date_end = date('Y-m-d');
$query = Message::select(
  'users.email',
  'users.name',
  'users.title',
  'users.engroup',
  'users.created_at',
  'users.last_login',
  'message.id',
  'message.from_id',
  'message.content',
  'user_meta.about'
)
  ->join('users', 'message.from_id', '=', 'users.id')
  ->join('user_meta', 'message.from_id', '=', 'user_meta.user_id')
  ->leftJoin('banned_users as b1', 'b1.member_id', '=', 'message.from_id')
  ->leftJoin(
    'banned_users_implicitly as b3',
    'b3.target',
    '=',
    'message.from_id'
  )
  ->leftJoin('warned_users as wu', function ($join) {
    $join
      ->on('wu.member_id', '=', 'message.from_id')
      ->where('wu.expire_date', '>=', \Carbon\Carbon::now())
      ->orWhere('wu.expire_date', null);
  })
  ->whereNull('b1.member_id')
  ->whereNull('b3.target')
  ->whereNull('wu.member_id')
  ->where('users.accountStatus', 1)
  ->where('users.account_status_admin', 1)
  ->where(function ($query) use ($date_start, $date_end) {
    $query
      ->where('message.from_id', '<>', 1049)
      ->where('message.sys_notice', 0)
      ->orWhereNull('message.sys_notice')
      ->whereBetween('message.created_at', array(
        $date_start . ' 00:00',
        $date_end . ' 23:59',
      ));
  });
$query->where('users.email', $targetUser->email);
$results_a = $query->distinct('message.from_id')->get();

if ($results_a != null) {
  $msg = array();
  $from_content = array();
  $user_similar_msg = array();

  $messages = Message::select('id', 'content', 'created_at')
    ->where('from_id', $targetUser->id)
    ->where('sys_notice', 0)
    ->orWhereNull('sys_notice')
    ->whereBetween('created_at', array(
      $date_start . ' 00:00',
      $date_end . ' 23:59',
    ))
    ->orderBy('created_at', 'desc')
    ->take(100)
    ->get();

  foreach ($messages as $row) {
    array_push($msg, array(
      'id' => $row->id,
      'content' => $row->content,
      'created_at' => $row->created_at,
    ));
  }

  array_push($from_content, array('msg' => $msg));

  $unique_id = array(); //過濾重複ID用
  //比對訊息
  foreach ($from_content as $data) {
    foreach ($data['msg'] as $word1) {
      foreach ($data['msg'] as $word2) {
        if ($word1['created_at'] != $word2['created_at']) {
          if (strlen($word1['content']) > 200) {
            continue;
          }
          echo similar_text($word1['content'], $word2['content'], $percent);
          if ($percent >= 70) {
            if (!in_array($word1['id'], $unique_id)) {
              array_push($unique_id, $word1['id']);
              array_push($user_similar_msg, array(
                $word1['id'],
                $word1['content'],
                $word1['created_at'],
                $percent,
              ));
            }
          }
        }
      }
    }
  }
}
echo count($user_similar_msg);
$message_percent_7 =
  count(

) > 0
    ? round((count($user_similar_msg) / count($messages)) * 100)
    : 0;
