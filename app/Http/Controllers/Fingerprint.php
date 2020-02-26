<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Exception;

class Fingerprint extends Controller {

	public function index()
	{
		return view('/new/fingerprint');
	}

	public function addFingerprint(Request $request)
	{
		$fp = $request->get('result');
		
		$components = $request->get('components');

		$batterylevel = $request->get('batterylevel');


		$fp_components = array();
		try{
			foreach($components as $components){
				$fp_components[$components['key']] = $components['value'];
			}
		}
		catch(\Exception $e){
			return false;
		}

		
		/*是否有Fp紀錄在資料庫*/
		$isFp = DB::table('fingerprint2')->where('fp', $fp)->get()->count();

		
		if($isFp<=0){
			$fp_components['fp'] = $fp;
			$fp_components['batterylevel'] = $batterylevel;
			unset($fp_components['plugins']);
			
			$result = DB::table('fingerprint2')->insert($fp_components);
		}
		if(isset($result)&&$result==true){
			$res = array(
				'code'=>'200',
				'msg' =>'儲存成功'
			);
		}else if($isFp>0){
			$res = array(
				'code'=>'400',
				'msg' =>'資料庫已有相同的fp'
			);
		}else{
			$res = array(
				'code'=>'600',
				'msg' =>'儲存失敗'
			);
		}

		return json_encode($res);
	}

}
?>