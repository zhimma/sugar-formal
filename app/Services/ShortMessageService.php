<?php
namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\SimpleTables\short_message;

class ShortMessageService 
{
    static $forbidden_deleted_from_arr =['admin/users/phone/delete','admin/users/phone/modify'];
    
    public static function deleteShortMessageByUserId($user_id,$auto_deleted=false)
    {
        $user = User::find($user_id);
        return ShortMessageService::deleteShortMessageByUser($user,$auto_deleted);       
    }
    
    public static function deleteShortMessageByUser($user,$auto_deleted=false)
    {
        $sms_query = $user->short_message();
        
        return ShortMessageService::deleteShortMessageByQuery($sms_query,$auto_deleted);
    } 
    
    public static function deleteAnyNotActShortMessageByUser($user,$auto_deleted=false)
    {
        $sms_query = $user->short_message()->where(function($q){$q->whereNull('active')->orWhere('active',0);});
        
        return ShortMessageService::deleteShortMessageByQuery($sms_query,$auto_deleted);
    }     

    public static function deleteOldNotActShortMessageByUser($user,$auto_deleted=false)
    {
        $sms_query = $user->short_message()->whereRaw('createdate < DATE_SUB(NOW(),INTERVAL 5 MINUTE)')->where(function($q){$q->whereNull('active')->orWhere('active',0);});      
        return ShortMessageService::deleteShortMessageByQuery($sms_query,$auto_deleted);
    }     

    public static function deleteShortMessageByQuery($query,$auto_deleted=false)
    {
        (clone $query)->where('active',1)->update(['active'=>0,'canceled_by'=>auth()->id(),'canceled_from'=>request()->path(),'canceled_date'=>Carbon::now(),'deleted_by'=>auth()->id(),'deleted_from'=>request()->path(),'auto_deleted'=>intval($auto_deleted)]);
        (clone $query)->where('active',0)->update(['active'=>0,'deleted_by'=>auth()->id(),'deleted_from'=>request()->path(),'auto_deleted'=>intval($auto_deleted)]);
        
        if($query instanceof \Illuminate\Database\Query\Builder)
            return $query->update(['deleted_at'=>Carbon::now()]);        
        else return $query->delete();        
    }
    
    public static function isForbiddenByPhoneNumber($phone_number)
    {
        $arr =self::$forbidden_deleted_from_arr;
        $memIdData = short_message::withTrashed()->select('member_id')->where('mobile',$phone_number)->distinct()->get();
        $phoneData = collect([]);
        if($memIdData->count()==1) {
            $member_id=$memIdData->first()->member_id;
            $phoneData = short_message::withTrashed()->select('mobile')->where('member_id',$member_id)->whereNotNull('mobile')->distinct()->get();
        }
        
        if(($memIdData->count()>1 ||  $phoneData->count()>1  ) && short_message::withTrashed()->where('mobile',$phone_number)->whereIn('deleted_from',$arr)->count()) {
            return true;
        }
    }
    
    public static function getFirstUserByForbiddenPhoneNumber($phone_number)
    {
        $arr =self::$forbidden_deleted_from_arr;
        
        $first_forbidden_sms = short_message::withTrashed()->where('mobile',$phone_number)->whereIn('deleted_from',$arr)->orderByDesc('id')->first();

        if($first_forbidden_sms) {
            return User::find($first_forbidden_sms->member_id);
        }
    }

    public static  function  getWithTrashedByPhoneNumberQuery($phone_number)
    {
         return short_message::withTrashed()->where('mobile',$phone_number);
    }  

    public static  function  getWithTrashedByPhoneNumber($phone_number)
    {
         return ShortMessageService::getWithTrashedByPhoneNumberQuery($phone_number)->get();
    } 

    public static  function  forceDeleteWithTrashedByPhoneNumber($phone_number)
    {
         return ShortMessageService::getWithTrashedByPhoneNumberQuery($phone_number)->forceDelete();
    }     
}


