<?php

namespace App\Http\Controllers\Api;

use App\Models\SetAutoBan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Carbon\Carbon;
class SetAutoBanController extends \App\Http\Controllers\BaseController
{
    private $token = 'vax59hxpcz35r9b4';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBanList(Request $request){
        // Log::Info('LocalMachine_AutoBanAndWarn_Second_Start');

        $ban_list = [];
        $users = User::where('last_login', '>', Carbon::now()->subSeconds(61))->get();
        // return $users;
        
        foreach($users as $user)
        {
            // Log::info('User: ' . $user->id);
            $start_time = Carbon::now();
            $merge_ban_list = SetAutoBan::local_machine_ban_and_warn_checkAll($user->id);
            $end_time = Carbon::now();
            $time_elapsed = $end_time->diffInSeconds($start_time, true);
            Log::Info('User: ' . $user->id . ', time elapsed: ' . $time_elapsed);
            
            if($merge_ban_list != [])
            {
                $ban_list = array_merge($ban_list, $merge_ban_list);
            }
        }
        // Log::Info($ban_list);

        // $link_address = config('localmachine.MISC_LINK_SERVER').'/LocalMachineReceive/BanAndWarn';
        // $post_data = [
        //     'form_params' => [
        //         'key' => config('localmachine.MISC_KEY'),
        //         'ban_list' => $ban_list
        //     ]
        // ];
        
        return $ban_list;
        // $client   = new Client();
        // $response = $client->request('POST', $link_address, $post_data);
        // $contents = $response->getBody()->getContents();
        // Log::Info($contents);

        // Log::Info('LocalMachine_AutoBanAndWarn_Second_End');
    }
    public function index(Request $request)
    {
        //
        if($request && $request->token == $this->token){

            $query = SetAutoBan::select('*');

            if($request->type) {
                $type = explode(',',$request->type);
                if(is_array($type)) {
                    $query->whereIn('type', $type);
                }else{
                    $query->where('type', $request->type);
                }
            }

            $SetAutoBan = $query->get();

            return response($SetAutoBan, 200);
        }
        return response();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
//    public function create()
//    {
//        //
//    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $set_auto_ban='';
        if($request && $request->token == $this->token){

            if (SetAutoBan::where([['type', $request->type], ['content', $request->value], ['set_ban', $request->set_ban]])->first() == null) {
                $set_auto_ban = SetAutoBan::setAutoBanAdd($request->type, $request->value, $request->set_ban, $request->user_id, '', $request->host);
//                $set_auto_ban = SetAutoBan::insert(['type' => $request->type, 'content' => $request->value, 'set_ban' => $request->set_ban, 'cuz_user_set' => $request->user_id, 'host' => $request->host]);
            }

        }
        return response($set_auto_ban, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SetAutoBan  $SetAutoBan
     * @return \Illuminate\Http\Response
     */
//    public function show(SetAutoBan $SetAutoBan)
//    {
//        //
//        return response($SetAutoBan, 200);
//    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SetAutoBan  $SetAutoBan
     * @return \Illuminate\Http\Response
     */
//    public function edit(SetAutoBan $SetAutoBan)
//    {
//        //
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SetAutoBan  SetAutoBan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        if($request && $request->token == $this->token) {
            $SetAutoBan = SetAutoBan::where('id', $request->id)->where('type', 'ip')->update(['expiry' => $request->expiry, 'host' => $request->host]);
            return response($request->host, 200);
        }
        return response();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SetAutoBan  $SetAutoBan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        if($request && $request->token == $this->token) {
            if($request->user_id) {
                SetAutoBan::where('cuz_user_set', $request->user_id)->where('host', $request->host)->delete();
                return response($request->host, 200);
            }
            if($request->id){
                SetAutoBan::where('id', $request->id)->delete();
                return response($request->id, 200);
            }
        }
        return response();

    }
}
