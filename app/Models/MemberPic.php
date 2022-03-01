<?php

namespace App\Models;

use App\Models\User;
use App\Services\ImagesCompareService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberPic extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_pic';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'pic'
    ];

    /*
    |--------------------------------------------------------------------------
    | relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class, 'member_id', 'id');
    }
    
    
    public static function getSelf($uid)
    {
        return MemberPic::where('member_id', $uid)->whereRaw("pic not LIKE '%IDPhoto%'")->get();
    }

    public static function getRand()
    {
        $pic = MemberPic::whereIn('member_id', User::where('engroup', 2)->pluck('id')->toArray())->inRandomorder()->first();
        if (isset($pic)) return $pic->pic;
        return "";
    }

    public static function getRandD()
    {
        $pic = MemberPic::whereIn('member_id', User::where('engroup', 1)->pluck('id')->toArray())->inRandomorder()->first();
        if (isset($pic)) return $pic->pic;
        return "";
    }

    public static function getPicNums($uid) {
        return MemberPic::where('member_id', $uid)->count();
    }

    public static function getSelfIDPhoto($uid)
    {
        return MemberPic::where('member_id', $uid)->whereRaw("pic LIKE '%IDPhoto%'")->get();
    }

    public static function getIllegalLifeImagesCount($user_id)
    {
        return AdminDeleteImageLog::where('member_id', $user_id)->get()->count();
    }

    public function getCompareStatus() {
        return ImagesCompareService::getCompareStatusByPic($this->pic);
    }  
    
    public function getCompareEncode() {
        return ImagesCompareService::getCompareEncodeByPic($this->pic);
    }

    public function getCompareRsImg() {
        return ImagesCompareService::getCompareRsImgByPic($this->pic);
 
    }
 
    public function getSameImg() {
        //return MemberPic::whereHas('user')->whereIn('pic',$this->getSameCompareEncode()->pluck('pic')->all())->get();
        return ImagesCompareService::getSameImgByPic($this->pic);
 
    }
    
    public function compareImages($encode_by=null,$delay=0) {
        return ImagesCompareService::compareImagesByPic($this->pic,$encode_by,$delay);
    }  

    public function isPicFileExists() {
        return ImagesCompareService::isFileExistsByPic($this->pic);
    }    
    
    public function isPicNeedCompare() {
        return ImagesCompareService::isNeedCompareByEntry($this);
    }        
}
