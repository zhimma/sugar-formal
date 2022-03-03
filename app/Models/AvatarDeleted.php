<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\ImagesCompareService;

class AvatarDeleted extends Model
{
    use HasFactory;

    protected $table = 'avatar_deleted';
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
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
