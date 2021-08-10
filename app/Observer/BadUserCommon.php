<?php

namespace App\Observer;

use App\Models\Message_new;
use App\Models\User;
use App\Models\SimpleTables\banned_users;
use App\Models\BannedUsersImplicitly;
use App\Models\UserMeta;
use App\Models\SimpleTables\warned_users;
use App\Models\Message;
use App\Services\AdminService;

class BadUserCommon
{
    public function __construct() {
    }

    public static function addRemindMsgFromBadId($bad_user_id) {
        $relateUserIds = [];
        $admin_id = AdminService::checkAdmin()->id;
        $bad_user = User::findById($bad_user_id);
        $msgOfBannedUser = Message_new::allSenders($bad_user->id,$bad_user->isVip(),'curMon',false);
        if($msgOfBannedUser){
            foreach($msgOfBannedUser  as $msg) {
                if($msg->to_id==$bad_user_id && !in_array($msg->from_id,$relateUserIds)) $relateUserIds[] = $msg->from_id;
                if($msg->from_id==$bad_user_id && !in_array($msg->to_id,$relateUserIds)) $relateUserIds[] = $msg->to_id;
            }

            foreach($relateUserIds as $relateUserId) {
                $relateRUserIds = [];
                $relate_user = User::findById($relateUserId);
                if(banned_users::where('member_id',$relateUserId)->first()
                    || BannedUsersImplicitly::where('target',$relateUserId)->first()
                    || $relateUserId==$admin_id)
                {
                    continue;
                }
                $msgOfRelateUser = Message_new::allSenders($relate_user->id,$relate_user->isVip(),'curMon',false);
                foreach($msgOfRelateUser  as $rmsg) {
                    if($rmsg->to_id==$relateUserId && !in_array($rmsg->from_id,$relateRUserIds)) $relateRUserIds[] = $rmsg->from_id;
                    if($rmsg->from_id==$relateUserId && !in_array($rmsg->to_id,$relateRUserIds)) $relateRUserIds[] = $rmsg->to_id;
                }

                $bannedIdsOfRelateUser =array_pluck( banned_users::whereIn('member_id',$relateRUserIds)->where('created_at','>=',\Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString())->select('member_id')->distinct()->get()->toArray(),'member_id');

                $iBannedIds = array_pluck(BannedUsersImplicitly::whereIn('target',$relateRUserIds)->where('created_at','>=',\Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString())->select('target')->distinct()->get()->toArray(),'target');

                foreach($iBannedIds as $iBannedId) {
                    if(in_array($iBannedId, $bannedIdsOfRelateUser)) continue;
                    else $bannedIdsOfRelateUser[] = $iBannedId;
                }
                $bannedIdNumOfRelateUser = count($bannedIdsOfRelateUser);
                $warnedIdNumOfRelateUser =  warned_users::select('member_id')->whereIn('member_id',$relateRUserIds)->where('created_at','>=',\Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString())->distinct()->count('member_id');

                $remindText = $relate_user->name.'您好，您本月通訊人數中，有'.$bannedIdNumOfRelateUser.'人目前被站方封鎖，有'.$warnedIdNumOfRelateUser
                    .'人已經被警示。對話記錄將移到封鎖信件夾，請您再去檢查，如果您們已經交換聯絡方式，請多加注意。';
                Message::post($admin_id, $relateUserId, $remindText,true,1);
            }
        }     
    }
}
