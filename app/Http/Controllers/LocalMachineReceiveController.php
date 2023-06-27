<?php

namespace App\Http\Controllers;

use App\Jobs\BanJob;
use App\Models\SetAutoBan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LocalMachineReceiveController extends Controller
{
    public function BanAndWarn(Request $request)
    {
        Log::Info('Receive Data From Local Machine');
        $ban_list = $request->ban_list;
        Log::Info($ban_list);
        if ($request->key == config('localmachine.MISC_KEY') && ($request->ip() == config('localmachine.MISC_SERVER') || $request->ip() == config('localmachine.MISC_SECOND_SERVER'))) {
            if ($ban_list ?? false) {
                foreach ($ban_list as $item) {
                    $uid = $item[0];
                    $ban_set = SetAutoBan::where('id', $item[1])->first();
                    $user = User::find($uid);
                    $type = $item[2];
                    BanJob::dispatch($uid, $ban_set, $user, $type)->onConnection('ban-job')->onQueue('ban-job');
                }
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
	
	public function BanSetIPUpdate(Request $request)
	{
		Log::Info('Receive IP Update Data From Local Machine');
		$ip_list = $request->ip_list;
		Log::Info($ip_list);
		if($request->key == config('localmachine.MISC_KEY') && $request->ip() == config('localmachine.MISC_SERVER'))
		{
			$type = $ip_list['type'];
			$id = $ip_list['id'];
			$expiry = $ip_list['expiry'];
			$updated_at = $ip_list['updated_at'];

			if($type == 'update')
			{
				$ban_set = SetAutoBan::where('id', $id)->first();
				$ban_set->expiry = $expiry;
				$ban_set->updated_at = $updated_at;
				$ban_set->save();
			}
			else if($type == 'delete')
			{
				$ban_set = SetAutoBan::where('id', $id)->first();
				if ($ban_set ?? false) {
					$ban_set->delete();
				}
				else {
					Log::Info('自動封鎖列表-ID不存在:' . $id);
					return '自動封鎖列表--ID不存在:' . $id;
				}
			}
			return '自動封鎖列表-IP更新成功';
		}
		else
		{
			Log::Info('外部IP請求失敗:');
			Log::Info($request->ip());
			return '自動封鎖列表-IP更新失敗';
		}
	}
}
