<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Reported;
use App\Models\LogUserLogin;
use App\Models\DataForFilterByInfo;
use App\Models\DataForFilterByInfoSub;
use App\Models\DataForFilterByInfoIgnores;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class FillDataForFilterByInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'FillDataForFilterByInfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill Data For Filter By User AdvInfo';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set("max_execution_time",'0');
        ini_set('memory_limit','-1');
        ini_set("request_terminate_timeout",'0');
        set_time_limit(0);
        error_reporting(0);        
        //
        try {

            $date_start = \Carbon\Carbon::now()->subDays(1)->format('Y-m-d H:i:s');
            $date_end = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            $whereDateArr = [['created_at', '>=', $date_start],['created_at', '<=', $date_end]];
/*
            if(\App::environment('local')){
                if(LogUserLogin::where($whereDateArr)->count()<1000) {
                    $date_start = \Carbon\Carbon::now()->subDays(3)->format('Y-m-d H:i:s');
                    $whereDateArr = [['created_at', '>=', $date_start],['created_at', '<=', $date_end]];
                }
            }  
*/
            $date_go_to_end_tmp = \Carbon\Carbon::now()->subWeeks(2)->format('Y-m-d H:i:s');
            DataForFilterByInfoIgnores::where('level',14)->where('created_at','<=',$date_go_to_end_tmp)->delete();
            $wantIndexArr = array('message_count_7'
				,'visit_other_count_7'
				,'message_count'
				,'visit_other_count'
				,'be_blocked_other_count'
				,'blocked_other_count'
			);
			$query = LogUserLogin::where($whereDateArr)->select('user_id')->groupBy('user_id')->with('user');
			echo $query->toSql()."\n\r";
			echo '$date_start='.$date_start."\n\r";
			echo '$date_end='.$date_end."\n\r";
			$gUser_set =$query->get();

			$data = [];
			DataForFilterByInfo::truncate();
            DataForFilterByInfoSub::truncate();
			foreach($gUser_set  as $k => $row) {
				$gUser = $row->user;
				if(!isset($gUser) || !$gUser) $gUser=new User;
				if(!isset($gUser->id)) $gUser->id = $row->user_id;
                
                $cur_subInfo = [];
				$cur_advInfo = $gUser->getAdvInfo($wantIndexArr);
                $cur_subInfo['device_count'] = $cur_advInfo['device_count'];
                $cur_subInfo['from_country_count'] = $cur_advInfo['from_country_count'];
                $cur_advInfo['device_count'] = $cur_advInfo['from_country_count'] = null;
                unset($cur_advInfo['device_count'],$cur_advInfo['from_country_count']);
				$cur_advInfo['be_reported_other_count'] = Reported::cntr($gUser->id);
				$cur_advInfo['created_at'] = $date_end;
				$cur_advInfo['updated_at'] = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
				$cur_advInfo['user_id'] = $gUser->id;

                $differ_ip_arr=[];
                $differ_cfpid_arr=[];
                
                $device_count['android']=0;
                $device_count['linux']=0;
                $device_count['windows']=0;
                $device_count['iphone']=0;

                $from_country_count=[];
              
                foreach($gUser->log_user_login as $lidx=>$lval) {
                    foreach($device_count  as $dkey=>$dval) {
                        if(stripos(($lval->userAgent??''),$dkey)!==false) {
                            $device_count[$dkey] = $dval+1;
                            break;
                        }            
                    }
                    
                    if(!in_array($lval->ip??'',$differ_ip_arr)) {
                        $differ_ip_arr[]=$lval->ip??'';
                    }
                    
                    if(!in_array($lval->cfp_id??'',$differ_cfpid_arr)) {
                        $differ_cfpid_arr[]=$lval->cfp_id??'';
                    } 

                    if(!($lval->country??'')) {
                        $from_country_count[''] = $from_country_count['']??0;
                        $from_country_count['']++;
                    }
                    else {
                        $from_country_count[strtolower($lval->country)] = $from_country_count[strtolower($lval->country)]??0;
                        $from_country_count[strtolower($lval->country)]++;
                    }
                }
                
                $device_count = array_filter($device_count);     

                $cur_advInfo['differ_ip_count'] = count($differ_ip_arr); 
                $cur_advInfo['differ_cfpid_count'] = count($differ_cfpid_arr); 
                
                $pic_name_regular_count = 0;
                $pic_name_notregular_count=0;
                $pic_name_empty_count=0;
                
                foreach($gUser->pic as $pidx=>$pval) {
                    if(!$pval->original_name) {
                        $pic_name_empty_count++;
                    }
                    else {
                        if(preg_match('/[0-9A-Z]{5,}-[0-9A-Z]{4}-[0-9A-Z]{4}-[0-9A-Z]{4}-[0-9A-Z]{5,}\.[a-zA-Z0-9]{2,6}/',$pval->original_name)) {
                            $pic_name_regular_count++;
                        }
                        else $pic_name_notregular_count++;
                    }
                }
                
                if(!($gUser->meta->pic_original_name??false)) {
                    $pic_name_empty_count++;
                }
                else {
                    if(preg_match('/[0-9A-Z]{5,}-[0-9A-Z]{4}-[0-9A-Z]{4}-[0-9A-Z]{4}-[0-9A-Z]{5,}\.[a-zA-Z0-9]{2,6}/',$gUser->meta->pic_original_name)) {
                        $pic_name_regular_count++;
                    }
                    else $pic_name_notregular_count++;                   
                }
                
                $cur_advInfo['pic_name_regular_count'] = $pic_name_regular_count;
                $cur_advInfo['pic_name_notregular_count'] = $pic_name_notregular_count;
                $cur_advInfo['pic_name_empty_count'] = $pic_name_empty_count;
                
				$curDataEntry = DataForFilterByInfo::create($cur_advInfo);

                $sub_data = null;
                foreach($device_count  as $dkey=>$dval) {
                    $sub_data = [];
                    $sub_data['cat'] = 'device';
                    $sub_data['type'] = $dkey;
                    $sub_data['count_num'] = $dval;
                    $curDataEntry->sub()->create($sub_data);
                }
                
                $sub_data = null;
                
                foreach($from_country_count  as $fckey=>$fcval) {
                    $sub_data = [];
                    $sub_data['cat'] = 'country';
                    $sub_data['type'] = $fckey;
                    $sub_data['count_num'] = $fcval;
                    $curDataEntry->sub()->create($sub_data);
                }                
                
				$cur_advInfo = null;
                $cur_subInfo = null;
                $sub_data = null;
				$gUser = null;                
			}
            
        } catch (\Exception $e) {
            Log::info('PR新增失敗'.$e->getMessage() .' LINE:'.$e->getLine());
        }
    }
}
