<?php

namespace App\Models;

use App\Models\User;
use App\Models\MemberPic;
use Illuminate\Database\Eloquent\Model;

class AccountPicUpload extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'account_pic_upload';
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    /*
    |--------------------------------------------------------------------------
    | relationships
    |--------------------------------------------------------------------------
    */
    
    public function member_pic()
    {
        return $this->belongsTo(MemberPic::class,'member_pic_id','id');
    }    

    public function user()
    {

        return $this->belongsTo(User::class,'user_id','id');
    }
    
    public function getUser() {
        return $this->user?$this->user:$this->member_pic->user;
    }
    
    public function getUserAvatarNum() {
        return !!($this->getUser()->meta->pic);
    }
    
    public static function getUserAvatarNumByUserId($user_id) {
        return !!(User::find($user_id)->meta->pic);
    }       
    
    public static function getUserMemberPicNumByUserId($user_id) {
        return (User::find($user_id)->pic()->count()?:0);
    }    
    
    public function getFirstAvatarNum() {
        return $this->where(['user_id'=>$this->user_id,'first'=>1,'member_pic_id'=>0])->count();
    }      
    
    public function getFirstMemberPicNum() {
        return $this->where(['user_id'=>$this->user_id],['first'=>1],['member_pic_id','>',0])->count();
    } 
    
    public static function getFirstAvatarNumByUserId($user_id) {
        return $this::where(['user_id'=>$user_id],['first'=>1],['member_pic_id'=>0])->count();
    }       
    
   public static function getFirstMemberPicNumByUserId($user_id) {
        return self::where(['user_id'=>$user_id],['first'=>1],['member_pic_id','>',0])->count();
    }     

    public function isFirstAvatar() {
        return (!$this->getFirstAvatarNum() && !$this->getUserAvatarNum());
    }
    
    public static function isFirstAvatarByUserId($user_id) {
        return (!self::getFirstAvatarNumByUserId($user_id) && !self::getUserAvatarNumByUserId($user_id));
    }   
    
    public static function isFirstMemberPicByUserId($user_id) {
        return (!self::getFirstMemberPicNumByUserId($user_id) && !self::getUserMemberPicNumByUserId($user_id));
    }
    
    public static function isAllowedAvatarByUserId($user_id) {
        return !!(self::where(['user_id'=>$user_id,'status'=>1,'deleted'=>0])
                    ->orwhere(['user_id'=>$user_id,'status'=>0,'first'=>1,'deleted'=>0])
                    ->count());
    }      
    
    public static function isAllowedMemberPicByMemberPicId($member_pic_id) {            
        return !!(self::where(['member_pic_id'=>$member_pic_id,'status'=>1,'deleted'=>0])->count()
                +self::where(['member_pic_id'=>$member_pic_id,'status'=>0,'first'=>1,'deleted'=>0])->count());
    } 

    public static function isShowReviewByMemberPicId($member_pic_id) {            
        return !!(self::where(['member_pic_id'=>$member_pic_id,'deleted'=>0])->count());
    }     
    
    public static function getNotDelAvatarByUserId($user_id) {
        return self::where(['user_id'=>$user_id,'member_pic_id'=>0,'deleted'=>0])->first();
                
    }
  
       
    
    
}
