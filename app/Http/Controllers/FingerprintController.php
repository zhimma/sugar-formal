<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\FingerprintRepository;

class FingerprintController extends BaseController
{
    protected $repositroy;
    public function __construct(FingerprintRepository $repositroy)
    {
        $this->repositroy = $repositroy;
    }

    public function fingerprint(Request $request)
    {
     
      return view('fingerpirnt_gpu');
    }

    public function features(Request $request)
    {
        $agent = "";
        $accept = "";
        $encoding = "";
        $language = "";
        $IP = "";

        try
        {
            $agent = $request->header('user-agent');
            $accept = $request->header('accept');
            $encoding = $request->header('accept-encoding');
            $language = $request->header('accept-language');
            $IP = $request->server('REMOTE_ADDR');
        }
        catch(Excption $e)
        {
            //
        }

        $features_list = [
            "agent",
            "accept",
            "encoding",
            "language",
            "langsDetected",
            "resolution",
            "fonts",
            "WebGL", 
            "inc", 
            "gpu", 
            "gpuImgs", 
            "timezone", 
            "plugins", 
            "cookie", 
            "localstorage", 
            "adBlock", 
            "cpu_cores", 
            "canvas_test", 
            "audio"
        ];

        $cross_feature_list = [
            "timezone",
            "fonts",
            "langsDetected",
            "audio"
        ];

        $result = $request->all();
        $data = array();
        $single_hash = "single";
        $cross_hash = "cross";

        $mask = json_decode(file_get_contents(resource_path().'/fingerprint/mask.txt'),true);
        $mac_mask = json_decode(file_get_contents(resource_path().'/fingerprint/mac_mask.txt'),true);

        $fonts = str_split($result['fonts']);

        for($i=0; $i<count($mask); $i++)
        {
            $fonts[$i] = (string)((int)$fonts[$i] & $mask[$i] & $mac_mask[$i]);
        }

        $result['agent'] = $agent;
        $result['accept'] = $accept;
        $result['encoding'] = $encoding;
        $result['language'] = $language;
        $result['fonts'] = join($fonts);

        $value_str = "'" . $IP . "'";
        foreach($features_list as $feature)
        {

            $value = $result[$feature] ? $result[$feature] : "NULL";

            if($feature == 'gpuImgs')
            {
                $arr = array();
                foreach($value as $index => $v){
                    array_push($arr, ",".$index."_".$v);
                }
                $value = join($arr);
                $value = substr($value, 1);
            }
            else
            {
                $value = gettype($value) == 'array' ? implode(",", $value) : (string)$value;
            }

            if($feature == 'cpu_cores')
                $value = (int)$value;

            if($feature == 'langsDetected')
            {
                //In php , the type of value is string 
                $value = str_replace(",", "_", $value);
            }

            $value_str .= ",'".$value."'";
            $data[$feature] = $value;
        }

        foreach($cross_feature_list as $feature)
        {
            if(gettype($result[$feature]) == 'array')
            {
                $cross_hash .= implode("", $result[$feature]);
            }
            else
            {
                $cross_hash .= (string)$result[$feature];
            }
            
        }

        $single_hash = md5($value_str);
        $cross_hash = md5($cross_hash);
        $data['IP'] = $IP;
        $data['browser_fingerprint'] = $single_hash;
        $data['cross_browser_fingerprint'] = $cross_hash;
        $this->repositroy->insert($data);

        return json_encode(['single_browser' => $single_hash, 'cross_browser' => $cross_hash]);
    }
}
