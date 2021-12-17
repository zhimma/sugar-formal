<?php
namespace App\Services;
use App\Models\UserMeta;
use App\Models\AvatarDeleted;
use App\Models\MemberPic;
use App\Models\ImagesCompare;
use App\Models\ImagesCompareEncode;
use App\Models\ImagesCompareStatus;
use App\Jobs\CompareImagesCaller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;

class ImagesCompareService {
   public static $resize_length=100;
   public static $code_seg_num=64;
   public static $need_compare_from_date = '2021-11-01 00:00:00';
   public static $sys_pic_arr = ['/img/illegal.jpg'];

    public static function convertCode($code) {
        if(ImagesCompareService::$code_seg_num) {
            $seg_interval = intval(256/ImagesCompareService::$code_seg_num);
            for($i=0;$i<ImagesCompareService::$code_seg_num;$i++) {
                if($code>=$i*$seg_interval && $code<($i+1)*$seg_interval ) {
                    if(ImagesCompareService::$code_seg_num<=32)
                    return base_convert($i,10,32);
                    else
                        return sprintf('%02s',base_convert($i,10,32));
                }
            }
        }
        else {
            return sprintf('%02s',base_convert($code,10,32));
        }        
    } 

   public static function createImg($file_path){     
       if(!file_exists($file_path)){ return false; }        
       $type = exif_imagetype($file_path);   
        switch($type) {
            case IMAGETYPE_GIF:
                if(function_exists('imageCreateFromGIF'))
                    return imageCreateFromGIF($file_path);
                return false;
            break;
            case IMAGETYPE_JPEG:
                if(function_exists('imageCreateFromJPEG'))
                    return imageCreateFromJPEG($file_path);
                return false;            
            break;
            case IMAGETYPE_PNG:
                if(function_exists('imageCreateFromPNG'))
                    return imageCreateFromPNG($file_path);
                return false;            
            break;
            case IMAGETYPE_BMP:
                if(function_exists('imageCreateFromBMP'))
                    return imageCreateFromBMP($file_path);
                return false;            
            break;
            case IMAGETYPE_WBMP:
                if(function_exists('imageCreateFromWBMP'))
                    return imageCreateFromWBMP($file_path);
                return false;            
            break; 
            case IMAGETYPE_XBM:
                if(function_exists('imageCreateFromXBM'))
                    return imageCreateFromXBM($file_path);
                return false;            
            break;   
            default:
                return false; 
            break;
        }    
    } 
    
    public static function encodeImgFile($pic_path) {
       $src = ImagesCompareService::createImg($pic_path);   
       $hashStr = ImagesCompareService::encodeImg($src);
       imagedestroy($src);     
     
       return $hashStr;          
    }

   public static function encodeImg($src){     
        if(!$src){ return false; }     
        global $time;

        if(ImagesCompareService::$resize_length) {
            $length = ImagesCompareService::$resize_length; 
            $ratio = null;
            if(imagesX($src)>=imagesY($src)) {
                if(imagesX($src)>$length) {
                    $length_x = $length;

                    $ratio = $length_x/imagesX($src);
                    $length_y = imagesY($src)*$ratio;
                }
            }
            else {
                if(imagesY($src)>$length) {
                    $length_y = $length;
                    $ratio = $length_y/imagesY($src);
                    $length_x = imagesX($src)*$ratio;
                }           
            }       
            $length_x = $length_x??imagesX($src);
            $length_y = $length_y??imagesY($src);

            $img = imageCreateTrueColor($length_x,$length_y);        
            imageCopyResized($img,$src, 0,0,0,0, $length_x,$length_y,imagesX($src),imagesY($src));     
        }
        else {
            $img=$src;
            $length_y = imagesY($src);
            $length_x = imagesX($src);           
        }
        $originArr = []; 
        $encodeArr = [];     
        for ($y=0; $y<intval($length_y); $y++){                
            for ($x=0; $x<intval($length_x); $x++){
                $col = imagecolorat($img,$x,$y);     
                $colArr = imagecolorsforindex($img, $col);     
                $colEncode = ImagesCompareService::convertCode($colArr['red']).ImagesCompareService::convertCode($colArr['green']).ImagesCompareService::convertCode($colArr['blue']);    
                $originArr[$colEncode]= ($originArr[$colEncode]??0)+1;     
                if($originArr[$colEncode]>=10) $encodeArr[$colEncode] = ($encodeArr[$colEncode]??0)+1; 
            }     
        } 
        if($img!=$src)
        imagedestroy($img);       
        if(!$originArr) return false;
        if(!$encodeArr) return false;

        arsort($encodeArr); 
        return $encodeArr;
    }

    public static function addEncodeByEntry($picEntry,$from=null) {
        if(!($picEntry??null)) return false;
        $pic_path = public_path($picEntry->pic);         

        if(!ImagesCompareService::isFileExistsByPic($picEntry->pic)) return false;
        $pic_encode = ImagesCompareService::encodeImgFile($pic_path);
        if(!$pic_encode) return false;
        $pic_type = null;
        if($picEntry->user_id && $picEntry->operator) $pic_type='avatar_deleted';
        elseif($picEntry->user_id) $pic_type='avatar';
        elseif($picEntry->member_id && $picEntry->deleted_at) $pic_type='member_pic_deleted';
        elseif($picEntry->member_id) $pic_type='member_pic';
        ImagesCompareEncode::create(['pic'=>$picEntry->pic,'encode'=>json_encode($pic_encode),'file_md5'=>md5_file($pic_path)
                                    ,'total_spot'=>array_sum($pic_encode),'total_diff_code'=>count($pic_encode)
                                    ,'encode_by'=>$from,'pic_cat'=>$pic_type
                                ]); 
        return $pic_encode;
    }
    
    public static function getEntryByPic($pic) {
        $picEntry = null;
        $nowMemPic = MemberPic::withTrashed()->select('member_id','pic','created_at','updated_at')->where('pic',$pic)->first();
        if($nowMemPic) {
            $picEntry = $nowMemPic;
        }
        else {
            $nowAvatar = UserMeta::select('user_id','pic','created_at','updated_at')->where('pic',$pic)->first();
            if($nowAvatar) $picEntry = $nowAvatar;
            else {
                $nowDelAvatar = AvatarDeleted::select('user_id','pic','created_at','updated_at')->where('pic',$pic)->first();
                if($nowDelAvatar) $picEntry = $nowDelAvatar;
            }
        }  
        return $picEntry;
    }
    
    public static  function getQueryOfCompareByPic($pic) {
        return ImagesCompare::where('pic',$pic);
    }
    
    public static  function getQueryOfCompareEncodeByPic($pic) {
        return ImagesCompareEncode::where('pic',$pic);
    }
    
    public static  function getQueryOfCompareStatusByPic($pic) {
        return ImagesCompareStatus::where('pic',$pic);
    }    

    public static  function getResultOfCompareByPic($pic) {
        return ImagesCompareService::getQueryOfCompareByPic($pic)
            ->get();
    }
    
    public static  function getCompareEncodeByPic($pic) {
        return ImagesCompareService::getQueryOfCompareEncodeByPic($pic)->orderBy('id')->first();
    }
    
    public static  function getCompareStatusByPic($pic) {
        return ImagesCompareService::getQueryOfCompareStatusByPic($pic)->orderBy('id')->first();
    }        
    
    public static  function getSameCompareEncodeByPic($pic) {
        if(!$pic) return;
        $encode = ImagesCompareService::getCompareEncodeByPic($pic);
        if(!$encode) {
            if(ImagesCompareService::addEncodeByEntry(ImagesCompareService::getEntryByPic($pic),'ImagesCompareService@getSameCompareEncodeByPic')) {
                $encode = ImagesCompareService::getCompareEncodeByPic($pic);
            }
        }
        
        
        
        if($encode) {
            $md5 = $encode->file_md5??'';
            $selfPic = [];
            $picEntry = ImagesCompareService::getEntryByPic($pic);
            $nowUserId = $picEntry->user_id??$picEntry->member_id;
            $userMemPic =MemberPic::withTrashed()->where('member_id',$nowUserId)->whereNotNull('pic')->where('pic','<>','')->get()->pluck('pic')->all();
            $userAvatar = UserMeta::where('user_id',$nowUserId)->whereNotNull('pic')->where('pic','<>','')->get()->pluck('pic')->all();
            $userDelAvatar = AvatarDeleted::where('user_id',$nowUserId)->whereNotNull('pic')->where('pic','<>','')->get()->pluck('pic')->all();
            if($userMemPic) $selfPic = $userMemPic;
            if($userAvatar) $selfPic = array_merge($selfPic,$userAvatar);
            if($userDelAvatar) $selfPic = array_merge($selfPic,$userDelAvatar);
            
            return ImagesCompareEncode::where('file_md5',$md5)->where('pic','<>','/img/illegal.jpg')->whereNotIn('pic',$selfPic)->get();
        }
    }

    /**
     * 
     * @param mixed $pic 
     * @return  Collection|SupportCollection 
     */
    public static  function getCompareRsImgByPic($pic) {
        $rsImgSet = null;
        //$compareRsPicList = ImagesCompareService::getResultOfCompareByPic($pic)->where('pic','<>','/img/illegal.jpg')->pluck('finded_pic')->all();

        $compare_tb = with(new ImagesCompare)->getTable();
        $mempic_tb = with(new MemberPic)->getTable();
        $avatar_tb = with(new UserMeta)->getTable();
        $delavatar_tb = with(new AvatarDeleted)->getTable();            
        $userMemPic =MemberPic::withTrashed()->join($compare_tb, $mempic_tb.'.pic', '=', $compare_tb.'.finded_pic')->select('member_id')->selectRaw($mempic_tb.'.pic')->selectRaw('(IFNULL(asc_percent,0)+IFNULL(desc_percent,0)+IFNULL(asc_inter_part_percent,0)+IFNULL(desc_inter_part_percent,0))  AS cpercent')->whereHas('user')->where($compare_tb.'.pic',$pic)->get();
        $rsImgSet = $userMemPic;
        $userAvatar = UserMeta::join($compare_tb, $avatar_tb.'.pic', '=', $compare_tb.'.finded_pic')->where($compare_tb.'.pic',$pic)->select('user_id')->selectRaw($avatar_tb.'.pic')->selectRaw('(IFNULL(asc_percent,0)+IFNULL(desc_percent,0)+IFNULL(asc_inter_part_percent,0)+IFNULL(desc_inter_part_percent,0))  AS cpercent')->get();
        
        if($rsImgSet->count()) $rsImgSet = $rsImgSet->concat($userAvatar);
        else $rsImgSet = $userAvatar;             

        $userDelAvatar = AvatarDeleted::join($compare_tb, $delavatar_tb.'.pic', '=', $compare_tb.'.finded_pic')->where($compare_tb.'.pic',$pic)->select('user_id')->selectRaw($delavatar_tb.'.pic')->selectRaw('(IFNULL(asc_percent,0)+IFNULL(desc_percent,0)+IFNULL(asc_inter_part_percent,0)+IFNULL(desc_inter_part_percent,0))  AS cpercent')->get();        
        
        if($rsImgSet->count()) $rsImgSet = $rsImgSet->concat($userDelAvatar);
        else $rsImgSet = $userDelAvatar;        

        $rsImgSet = $rsImgSet->sortByDesc('cpercent')->take(15);;

        return $rsImgSet;
 
    }
 
    public static  function getSameImgByPic($pic) {
        $sameImgSet = null;
        $sameCompareEncode = ImagesCompareService::getSameCompareEncodeByPic($pic);
        $samePicList = $sameCompareEncode?$sameCompareEncode->pluck('pic')->all():[];        
        $userMemPic =MemberPic::withTrashed()->whereHas('user')->whereIn('pic',$samePicList )->get();
        $sameImgSet = $userMemPic;
        $userAvatar = UserMeta::whereIn('pic',$samePicList )->select('user_id','pic')->get();
        if($sameImgSet->count()) $sameImgSet = $sameImgSet->concat($userAvatar);
        else $sameImgSet = $userAvatar;        
        $userDelAvatar = AvatarDeleted::whereIn('pic',$samePicList )->select('user_id','pic')->get();        
        if($sameImgSet->count()) $sameImgSet = $sameImgSet->concat($userDelAvatar);
        else $sameImgSet = $userDelAvatar;          

        return $sameImgSet;
    } 
    
    public static function isFileExistsByPic($pic) {
        $pic_path = public_path($pic);         
        return file_exists($pic_path);        
    }
    
    public static function compareImagesByPic($pic,$encode_by=null) {
        if(!$pic) return;
        if(!ImagesCompareService::isFileExistsByPic($pic)) return;
        $status = ImagesCompareStatus::firstOrNew(['pic'=>$pic]);
        if($status->id ) {
            if($status->isQueueTooLong())
                $status->queue=2;
            else  return;
        }
        else  $status->queue=1;
        $status->qstart_time=Carbon::now();;
        $status->status=0;
        $status->start_time=null;
        $status->is_specific=1;
        $status->is_error=0;
        $status->save();
       
        $delay = 0;
        $now=Carbon::now();
        $next = $now->addDay();
        $stime = Carbon::parse($now->format('Y-m-d').' 18:00:00');
        $etime = Carbon::parse($next->format('Y-m-d').' 01:00:00');
        if($now->gt($stime) && $now->lt($etime)) $delay=25200;
        
        CompareImagesCaller::dispatch($pic,$encode_by)->delay($delay);
        CompareImagesCaller::dispatch($pic)->onQueue('compare_images')->delay($delay+10);
    }
    
    public static function isNeedCompareByEntry($picEntry) {
        if(!($picEntry->pic??null)) return false;
        if(!($picEntry->updated_at??null)) return true;
        if($picEntry->updated_at>=ImagesCompareService::$need_compare_from_date 
            && !in_array($picEntry->pic,ImagesCompareService::$sys_pic_arr)
        ) { 
            return true;
        }
        else {
            return false;
        }
    }
}
