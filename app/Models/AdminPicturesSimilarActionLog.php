<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Services\ImagesCompareService;

class AdminPicturesSimilarActionLog extends Model
{
    use HasFactory;

    public function target_user()
    {
        return $this->belongsTo(User::class, 'target_id', 'id');
    }

    public function operator_user()
    {
        return $this->belongsTo(User::class, 'operator_id', 'id');
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
