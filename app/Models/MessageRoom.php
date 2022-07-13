<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\AdminService;
use App\Models\SimpleTables\banned_users;
use Carbon\Carbon;

use function Clue\StreamFilter\fun;

class MessageRoom extends Model
{
    use HasFactory;

    protected $table = 'message_rooms';

    static $date = null;

    public function messages() {
        $user = \auth()->user();
        return $this->hasMany(Message::class, 'room_id')
                    ->where([['created_at','>=', self::$date]])
                    ->where([['is_row_delete_1', '<>', $user->id], 
                             ['message.is_single_delete_1', '<>', $user->id], 
                             ['message.all_delete_count', '<>', $user->id],
                             ['message.is_row_delete_2', '<>', $user->id],
                             ['message.is_single_delete_2', '<>', $user->id],
                             ['message.temp_id', '=', 0]]);
    }

    public function latestMessage() {
        return $this->messages()??"";
    }

    public function roomMembers() {
        return $this->belongsToMany(User::class, MessageRoomUserXref::class);
    }

    public function joinedMessageRooms() {
        return $this->belongsTo(User::class, MessageRoomUserXref::class, 'user_id', 'id', 'id');
    }

    public static function getRooms($uid, $isVip, $d = 7,$forEventSenders=false)
    {
		if($forEventSenders) $user = null;
        else 
			$user = \View::shared('user');
        if(!$user){
            $user = User::find($uid);
        }
		if(!$forEventSenders) {
			$banned_users = banned_users::where('member_id', $uid)->first();
			$BannedUsersImplicitly = BannedUsersImplicitly::where('target', $uid)->first();
			if( (isset($banned_users) && ($banned_users->expire_date == null || $banned_users->expire_date >= Carbon::now())) || isset($BannedUsersImplicitly)){
				return false;
			}
		}
        
		if($forEventSenders) 
		{
			self::$date = \Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString();
		}
        else if($d==7){
            self::$date = \Carbon\Carbon::parse("7 days ago")->toDateTimeString();
        }else if($d==30){
            self::$date = \Carbon\Carbon::parse("30 days ago")->toDateTimeString();
        }else if($d=='all'){
            if($isVip) {
                self::$date =\Carbon\Carbon::parse("180 days ago")->toDateTimeString();
            }else {
                self::$date = \Carbon\Carbon::parse("30 days ago")->toDateTimeString();
            }
        }

        $admin_id = AdminService::checkAdmin()->id;
        /**
         * 效能調整：使用左結合取代 where in 以取得更好的效能
         *
         * @author LZong <lzong.tw@gmail.com>
         */
        $query = Message::with(['sender',                  'receiver', 
                                'sender.banned',           'receiver.banned',
                                'sender.implicitlyBanned', 'receiver.implicitlyBanned',
                                'sender.aw_relation',      'receiver.aw_relation'])
            ->select("message.*")
            //->from('message as m')
            ->leftJoin('users as u1', 'u1.id', '=', 'message.from_id')
            ->leftJoin('users as u2', 'u2.id', '=', 'message.to_id')
            ->leftJoin('banned_users as b1', 'b1.member_id', '=', 'message.from_id')
            ->leftJoin('banned_users as b2', 'b2.member_id', '=', 'message.to_id')
            ->leftJoin('banned_users_implicitly as b3', 'b3.target', '=', 'message.from_id')
            ->leftJoin('banned_users_implicitly as b4', 'b4.target', '=', 'message.to_id')
            ->leftJoin('blocked as b5', function($join) use($uid) {
                $join->on('b5.blocked_id', '=', 'message.from_id')
                    ->where('b5.member_id', $uid); })
            ->leftJoin('blocked as b6', function($join) use($uid) {
                $join->on('b6.blocked_id', '=', 'message.to_id')
                    ->where('b6.member_id', $uid); })
            ->leftJoin('blocked as b7', function($join) use($uid) {
                $join->on('b7.member_id', '=', 'message.from_id')
                    ->where('b7.blocked_id', $uid); });
        $query = $query->whereNotNull('u1.id')
                ->whereNotNull('u2.id')
                ->whereNull('b5.blocked_id')
                ->whereNull('b6.blocked_id')
                ->whereNull('b7.member_id')
                ->where(function ($query) use ($uid,$admin_id) {
                    $query->where([['message.to_id', $uid], ['message.from_id', '!=', $uid],['message.from_id','!=',$admin_id]])
                        ->orWhere([['message.from_id', $uid], ['message.to_id', '!=',$uid],['message.to_id','!=',$admin_id]]);
                });

        $query->where([['message.created_at','>=',self::$date]]);
        $query->whereRaw('message.created_at < IFNULL(b1.created_at,"2999-12-31 23:59:59")');
        $query->whereRaw('message.created_at < IFNULL(b2.created_at,"2999-12-31 23:59:59")');
        $query->whereRaw('message.created_at < IFNULL(b3.created_at,"2999-12-31 23:59:59")');
        $query->whereRaw('message.created_at < IFNULL(b4.created_at,"2999-12-31 23:59:59")');
        $query->where([['message.is_row_delete_1','<>',$uid],['message.is_single_delete_1', '<>' ,$uid], ['message.all_delete_count', '<>' ,$uid],['message.is_row_delete_2', '<>' ,$uid],['message.is_single_delete_2', '<>' ,$uid],['message.temp_id', '=', 0]]);
        $query->orderBy('message.created_at', 'desc');
        if($user->id != 1049){
            $query->where(function($query){
                $query->where(DB::raw('(u1.engroup + u2.engroup)'), '<>', '2');
                $query->orWhere(DB::raw('(u1.engroup + u2.engroup)'), '<>', '4');
            });
        }
        $messages = $query->get();

        // $constraint = function ($users) use ($admin_id) {
        //     $query = $users->where('users.id', '<>', $admin_id);
        //     if(auth()->user()->id != $admin_id) {
        //         // 如果非站長帳號，則只讀取異性對話
        //         return $query->having(\DB::raw('SUM(engroup)'), 3);
        //     }
        //     return $query;
        // };

        // $messages = User::with([
        //     'joinedMessageRooms',
        //     'joinedMessageRooms.roomMembers' => $constraint,
        //     'joinedMessageRooms.latestMessage'
        // ])->without([
        //     'joinedMessageRooms.roomMembers.isCurrentlyBanned',
        //     'joinedMessageRooms.roomMembers.implicitlyBanned',
        //     'joinedMessageRooms.roomMembers.isCurrentlyWarned'
        // ])
        // ->whereHas('joinedMessageRooms')
        // ->whereHas('joinedMessageRooms.roomMembers', $constraint)
        // ->whereHas('joinedMessageRooms.latestMessage')
        // ->whereDoesntHave('joinedMessageRooms.roomMembers.isCurrentlyBanned')
        // ->whereDoesntHave('joinedMessageRooms.roomMembers.implicitlyBanned')
        // ->whereDoesntHave('joinedMessageRooms.roomMembers.isCurrentlyWarned')
        // ->where('users.id', auth()->user()->id)
        // ->get();

        
        
        $mCount = count($messages);
        $mm = [];
        foreach ($messages as $key => $v) {
            if(!isset($mm[$v->from_id])){
                $mm[$v->from_id] = 0;
            }
            if($v->read=='N' && $v->all_delete_count != $uid && $v->is_row_delete_1 != $uid && $v->is_row_delete_2 != $uid && $v->is_single_delete_1 != $uid && $v->is_single_delete_2 != $uid){
                $mm[$v->from_id]++;
            }

        }
        $saveMessages = Message_new::newChatArrayAJAX($uid, $messages);
        if(count($saveMessages) == 0){
            return array_values(['No data']);
        }else{
            return Message_new::sortMessages($saveMessages, null, $mm, $mCount,$forEventSenders?$uid:null);
        }
    }
}
