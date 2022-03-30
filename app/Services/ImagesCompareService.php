<?php
namespace App\Services;
use App\Models\UserMeta;
use App\Models\AvatarDeleted;
use App\Models\MemberPic;
use App\Models\ImagesCompare;
use App\Models\ImagesCompareEncode;
use App\Models\ImagesCompareStatus;
use App\Jobs\CompareSingleImageJob;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;

class ImagesCompareService {
   public static $resize_length=100;
   public static $code_seg_num=64;
   public static $need_compare_from_date = '2021-11-01 00:00:00';
   public static $sys_pic_arr = ['/img/illegal.jpg'];
    public static $memPicPicArr;
    public static $avatarPicArr;
    public static $delAvatarPicArr;    
    
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
        $pic_path = public_path().$picEntry->pic;         

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
        if(ImagesCompareService::$memPicPicArr[$pic]??null) {
            $nowMemPic = ImagesCompareService::$memPicPicArr[$pic];
        }
        else {
            $nowMemPic = MemberPic::withTrashed()->select('member_id','pic','created_at','updated_at')->where('pic',$pic)->first();
            if($nowMemPic??null) {
                ImagesCompareService::$memPicPicArr[$pic] = $nowMemPic;
            }
        }
        
        if($nowMemPic??null) {
            $picEntry = $nowMemPic;
        }
        else {
            if(ImagesCompareService::$avatarPicArr[$pic]??null) {
                $nowAvatar = ImagesCompareService::$avatarPicArr[$pic];
            }
            else {            
                $nowAvatar = UserMeta::select('user_id','pic','created_at','updated_at','is_active')->where('pic',$pic)->first();
                if($nowAvatar??null) ImagesCompareService::$avatarPicArr[$pic] = $nowAvatar;
            }
            
            if($nowAvatar) $picEntry = $nowAvatar;
            else {
                if(ImagesCompareService::$delAvatarPicArr[$pic]??null) {
                    $nowDelAvatar = ImagesCompareService::$delAvatarPicArr[$pic];
                }
                else {                  
                    $nowDelAvatar = AvatarDeleted::select('user_id','pic','created_at','updated_at','uploaded_at')->where('pic',$pic)->first();
                    if($nowDelAvatar??null) ImagesCompareService::$delAvatarPicArr[$pic] = $nowDelAvatar;
                }
                if($nowDelAvatar??null) $picEntry = $nowDelAvatar;
                else {
                    
                }
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
    
    public static  function getFileMd5ArrByPicArr($picArr) {
        return ImagesCompareEncode::whereIn('pic',$picArr)->pluck('file_md5')->all();
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
        $rsImgSet =   collect([]);
        $compareEntry = [];
        $encodeEntry = ImagesCompareEncode::where('pic',$pic)->first();
        $compareQuery = ImagesCompare::select('pic','encode_id','found_pic','found_encode_id');
        if($encodeEntry->id??null)
            $compareEntry = $compareQuery->where('encode_id',$encodeEntry->id)->selectRaw('(IFNULL(asc_percent,0)+IFNULL(desc_percent,0)+IFNULL(asc_inter_part_percent,0)+IFNULL(desc_inter_part_percent,0))  AS cpercent')->orderByDesc('cpercent')->take(15)->get();
        else
            $compareEntry = $compareQuery->where('pic',$pic)->selectRaw('(IFNULL(asc_percent,0)+IFNULL(desc_percent,0)+IFNULL(asc_inter_part_percent,0)+IFNULL(desc_inter_part_percent,0))  AS cpercent')->orderByDesc('cpercent')->take(15)->get();

        $picEntry = ImagesCompareService::getEntryByPic($pic);
        $foundPicEntryArr = [];
        if($picEntry->user??null) {
            foreach($compareEntry as $cmprEntry) {               
                if(!$cmprEntry->found_pic??null) continue;                
                $found_pic_entry = ImagesCompareService::getEntryByPic($cmprEntry->found_pic);
                $found_pic_user = $found_pic_entry->user??null;
                if(!$found_pic_user) continue;
                    
                $found_pic_entry->cpercent = $cmprEntry->cpercent;           
                $found_pic_entry->userStateStr = ImagesCompareService::getStateStrByUser($found_pic_user);
                $found_pic_entry->userLliDiffDays = ImagesCompareService::getLliDiffDaysByUser($found_pic_user);
                $foundPicEntryArr[] = $found_pic_entry;
            }
        }

        $rsImgSet = collect($foundPicEntryArr);

      
        return $rsImgSet;
 
    }
 
    public static  function getSameImgByPic($pic) {
        $sameImgSet = null;
        $sameCompareEncode = ImagesCompareService::getSameCompareEncodeByPic($pic);
        $samePicList = $sameCompareEncode?$sameCompareEncode->pluck('pic')->all():[];        
        
        foreach($samePicList as $splk=>$splv) {
            if(!$splv??null) continue;                
            $same_pic_entry = ImagesCompareService::getEntryByPic($splv);
            $same_pic_user = $same_pic_entry->user??null;
            if(!$same_pic_user) continue;  

            $same_pic_entry->userStateStr = ImagesCompareService::getStateStrByUser($same_pic_user);
            $same_pic_entry->userLliDiffDays = ImagesCompareService::getLliDiffDaysByUser($same_pic_user);
            $sameImgSet[] = $same_pic_entry;            
        }
        
        return collect($sameImgSet);
    } 
    
    public static function isFileExistsByPic($pic) {
        $pic_path = public_path($pic);         
        return file_exists($pic_path);        
    }
    
    public static function compareImagesByPic($pic,$encode_by=null,$delay=0) {
        if(!$pic) return;
        if(!ImagesCompareService::isFileExistsByPic($pic)) {
            if(!ImagesCompareEncode::select('pic')->where('pic',$pic)->first())
                return;
        }
        $is_force = ImagesCompareService::isForceViaEncodeBy($encode_by);
        $skip_compared = ImagesCompareService::isSkipComparedViaEncodeBy($encode_by);
        $is_instant = ImagesCompareService::isInstantViaEncodeBy($encode_by);
        $status = ImagesCompareStatus::firstOrNew(['pic'=>$pic]);

        if($status->id ) {
            if($skip_compared && $status->queue==0 && $status->status==0) return; 
            if($status->isQueueTooLong() || $is_force)
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

        if($is_instant) {          
            CompareSingleImageJob::dispatchAfterResponse($pic,$encode_by);
            CompareSingleImageJob::dispatchAfterResponse($pic,null,$is_force);            
        }
        else {
            $now=Carbon::now();
            $next = $now->addDay();
            $stime = Carbon::parse($now->format('Y-m-d').' 18:00:00');
            $etime = Carbon::parse($next->format('Y-m-d').' 01:00:00');
            if($now->gt($stime) && $now->lt($etime)) $delay=25200+$delay;
            CompareSingleImageJob::dispatch($pic,$encode_by);
            CompareSingleImageJob::dispatch($pic,null,$is_force)->onQueue('compare_images')->delay($delay+60);            
        }
        
        return true;
    }
    
    public static function isNeedCompareByEntry($picEntry,$force = false) {
        if(!($picEntry->pic??null)) return false;     
        if($force)  return true;
        if($picEntry->user->engroup!=2) return false;        

        if(!($picEntry->created_at??null) && !($picEntry->updated_at??null)) return true;
        
        if(!in_array($picEntry->pic,ImagesCompareService::$sys_pic_arr)) {
            
            if(($picEntry->uploaded_at??null) &&  $picEntry->uploaded_at >= ImagesCompareService::$need_compare_from_date ) {
                
                return true;
            }
            
            if(((($picEntry->created_at??null) && !($picEntry->is_active??null))?$picEntry->created_at:$picEntry->updated_at??null)>=ImagesCompareService::$need_compare_from_date  
            ) {                
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }
    
    public static function getStateStrByUser($userEntry) {
        if(($userEntry->banned??null) || $userEntry->implicitlyBanned??null) return 'banned';
        if(($userEntry->user_meta->isWarned??null) || $userEntry->aw_relation??null) return 'warned';
        if($userEntry->account_status_admin===0) return 'aclosed ';
        if($userEntry->accountStatus===0) return 'closed ';

    }
    
    public static function getLliDiffDaysByUser($userEntry) {
        $now = Carbon::now();
        $last_login_time = Carbon::parse($userEntry->last_login);
        $diff_days = $last_login_time->diffInDays($now);

        return $diff_days;
    }  

    public static function isForceViaEncodeBy($encode_by) {
        return $encode_by=='UserController@UserImagesCompareJobCreate'
            || $encode_by=='UserController@applyPicMemberList'
        ;
    }
    
    public static function isSkipComparedViaEncodeBy($encode_by) {
        return $encode_by=='UserController@applyPicMemberList'
        ;
    } 

    public static function isInstantViaEncodeBy($encode_by) {
        return $encode_by=='UserController@UserImagesCompareJobCreate';
    }
    
    public static function countComparedByEntrysArr($entrysArr) {
        $entrys = collect([]);
        
        foreach($entrysArr  as $entrysElt) {
            $entrys = $entrys->merge($entrysElt); 
        }
        return ImagesCompareService::countComparedByEntrys($entrys);
    }
    
    public static function countComparedByEntrys($entrys) {
        $picArr = $entrys->pluck('pic')->all();
        
        return ImagesCompareStatus::select('pic')->whereIn('pic', $picArr)->where('status',0)->where('queue',0)->count();
        
    }    
}
