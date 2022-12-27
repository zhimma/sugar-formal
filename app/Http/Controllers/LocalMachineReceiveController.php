<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\BanJob;
use App\Models\SetAutoBan;
use App\Models\User;

class LocalMachineReceiveController extends Controller
{
    public function BanAndWarn(Request $request)
	{
		Log::Info('Receive Data From Local Machine');
		$ban_list = $request->ban_list;
		Log::Info($ban_list);
		if($request->key == env('MISC_KEY') && $request->ip() == env('MISC_SERVER'))
		{
			foreach($ban_list as $item)
			{
				$uid = $item[0];
				$ban_set = SetAutoBan::where('id', $item[1])->first();
				$user = User::find($uid);
				$type = $item[2];
				BanJob::dispatch($uid, $ban_set, $user, $type)->onConnection('ban-job')->onQueue('ban-job');
			}
			return '接收成功';
		}
		else
		{
			Log::Info('外部IP請求失敗:');
			Log::Info($request->ip());
			return '接收失敗';
		}
        
	}
}
