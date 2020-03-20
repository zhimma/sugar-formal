<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use App\Notifications\TipEmail;

class Tip extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_tip';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'to_id',
        'txn_id',
        'message'
    ];

    public static function findTipById($member_id, $to_id) {
        return Tip::where('member_id', $member_id)->where('to_id', $to_id)->orderBy('created_at', 'desc')->first();
    }

    public static function lastid()
    {
        $lid = Tip::orderBy('created_at', 'desc')->first();
        if ($lid == null) return 0;
        return $lid->id + 1;
    }

    public static function isPost($member_id, $to_id) {
        $tip = Tip::findTipById($member_id, $to_id);

        if(empty($tip)) return false;
        return true;
    }

    public static function isComment($member_id, $to_id) {
        $tip = Tip::findTipById($member_id, $to_id);

        if(isset($tip) && $tip->message != '') return true;
        return false;
    }

    public static function upgrade($member_id, $to_id, $txn_id)
    {
        $tip = new Tip();
        $tip->member_id = $member_id;
        $tip->txn_id = $txn_id;
        $tip->to_id = $to_id;

        $tip->save();
        $curUser = User::findById($member_id);
        $toUser = User::findById($to_id);
        $admin = User::findByEmail(Config::get('social.admin.email'));

        if ($curUser != null && $toUser != null)
        {
            $admin->notify(new TipEmail($member_id, $to_id, Config::get('social.payment.tip-amount'), '761404', $member_id));
            // $curUser->notify(new MessageEmail($member_id, $member_id, "車馬費邀請成功發送給 ".$toUser->name));
            // $toUser->notify(new MessageEmail($to_id, $to_id, "收到".$curUser->name."的車馬費邀請！"));
        }
    }

    public static function comment($member_id, $to_id, $msg) {
        $user = Tip::findTipById($member_id, $to_id);

        $user->message = $msg;
        $user->save();
    }

    public static function isCommentNoEnd($member_id, $to_id) {
        $user = Tip::findTipById($member_id, $to_id);
        $now = Carbon::now();

        if(isset($user) && $user->created_at->diffInSeconds($now) <= Config::get('social.comment.end')) {
            return true;
        }
        return false;
    }

    public static function getAllComment($to_id) {
        $toUser = Tip::where('to_id', $to_id)->get();

        if(empty($toUser)) return NULL;
        return $toUser;
    }    

    public static function TipCount_ChangeGood($id) {
        $tipcount = Tip::where('member_id', $id)->orWhere('to_id','=',$id)->count();
        if(in_array($tipcount,array(2,3,4))){
            $tipcount = 2;
        }elseif($tipcount>=5){
            $tipcount = 3;
        }
        return $tipcount;
    }

    /**
     * 找尋日期間的車馬費邀請
     * 
     * @param date start
     * @param date end
     *
     * @return collection 
     */
    public static function selectTipMessage($start, $end)
    {
        return Tip::whereBetween('created_at', [$start, $end])->get();
    }

    /**
     * 找尋日期間的車馬費邀請
     * 
     * @param date start
     * @param date end
     *
     * @return collection 
     */
    public static function selectTipMessage($start, $end)
    {
        return Tip::whereBetween('created_at', [$start, $end])->get();
    }
}
