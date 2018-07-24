<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserInviteRequest;
use App\Models\User;
use App\Models\VipLog;
use App\Models\Vip;
use App\Models\SimpleTables\member_vip;
use Carbon\Carbon;

class UserController extends Controller
{
    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$users = $this->service->all();
        return view('admin.users.index');
    }

    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        if (! $request->search) {
            return redirect('users/search');
        }
        
        //$user = $this->service->search($request->search);
        $gender = '';
        $vip_order_id = '';
        $vip_data = '';
        $user = User::select('id', 'name', 'email', 'engroup')
                ->where('email', $request->search)
                ->get()->first();
        $isVip = $user->isVip();        
        if($user->engroup == 1){
            $gender_ch = '男';
        }
        else{
            $gender_ch = '女';
        }
        if($isVip == 1){
            $isVip = true;
            $vip_data = Vip::select('id', 'free', 'created_at', 'updated_at')
                        ->where('member_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->get()->first();
            $vip_order_id = member_vip::select("order_id")
                            ->where('member_id', $user->id)
                            ->get()->first()->order_id;
        }
        else{
            $isVip = false;
        }
        return view('admin.users.index')
               ->with('user_id',    $user->id)
               ->with('name',       $user->name)
               ->with('email',      $user->email)
               ->with('gender_ch',  $gender_ch)
               ->with('gender',     $user->engroup)
               ->with('isVip',      $isVip)
               ->with('vip_order_id',    $vip_order_id)
               ->with('vip_log_id',      isset($vip_data->id)         ? $vip_data->id : null)
               ->with('vip_free',        isset($vip_data->free)       ? $vip_data->free : null)
               ->with('vip_create_time', isset($vip_data->created_at) ? $vip_data->created_at : null)
               ->with('vip_update_time', isset($vip_data->updated_at) ? $vip_data->updated_at : null);
    }

    /**
     * Toggle the gender of a specific member.
     *
     * @return \Illuminate\Http\Response
     */
    public function toggleGender(Request $request){
        $user = User::select('id', 'engroup', 'email')
                ->where('id', $request->user_id)
                ->get()->first();
        if($request->gender_now == 1){
            $user->engroup = '2';    
        }
        else{
            $user->engroup = '1';
        }
        $user->save();
        return view('admin.users.success')
               ->with('email', $user->email);
    }

    /**
     * Toggle the gender of a specific member.
     *
     * @return \Illuminate\Http\Response
     */
    public function toggleVIP(Request $request){
        if($request->isVip == 1){
            $setVip = 0;
        }
        else{
            $setVip = 1;
        }
        $user = Vip::select('member_id', 'active')
                ->where('member_id', $request->user_id)
                ->where('active', $request->isVip)
                ->update(array('active' => $setVip));
        if($user == 0){
            $vip_user = new Vip;
            $vip_user->member_id = $request->user_id;
            $vip_user->active = $setVip;
            $vip_user->created_at =  Carbon::now()->toDateTimeString();
            $vip_user->save();
        }
        $user = User::select('id', 'email')
                ->where('id', $request->user_id)
                ->get()->first();

        return view('admin.users.success')
               ->with('email', $user->email);
    }

    public function advIndex()
    {
        //$users = $this->service->all();
        //dd($users);
        return view('admin.users.advIndex');
    }

    /**
     * Display a listing of the resource searched. (Advanced)
     *
     * @return \Illuminate\Http\Response
     */
    public function advSearch(Request $request)
    {
        if (! $request->email && ! $request->name) {
            return redirect(route('users/advSearch'));
        }
        
        $user = User::where('email', 'like', '%' . $request->email . '%')
                ->orWhere('name', 'like', '%' . $request->email . '%')
                ->get();
        return view('admin.users.advIndex')->with('users', $user);
    }
    
    /**
     * Show the form for inviting a customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInvite()
    {
        return view('admin.users.invite');
    }

    /**
     * Show the form for inviting a customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function postInvite(UserInviteRequest $request)
    {
        $result = $this->service->invite($request->except(['_token', '_method']));

        if ($result) {
            return redirect('admin/users')->with('message', 'Successfully invited');
        }

        return back()->with('error', 'Failed to invite');
    }

    /**
     * Switch to a different User profile
     *
     * @return \Illuminate\Http\Response
     */
    public function switchToUser($id)
    {
        if ($this->service->switchToUser($id)) {
            return redirect('dashboard')->with('message', 'You\'ve switched users.');
        }

        return redirect('dashboard')->with('message', 'Could not switch users');
    }

    /**
     * Switch back to your original user
     *
     * @return \Illuminate\Http\Response
     */
    public function switchUserBack()
    {
        if ($this->service->switchUserBack()) {
            return back()->with('message', 'You\'ve switched back.');
        }

        return back()->with('message', 'Could not switch back');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->service->find($id);
        return view('admin.users.edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = $this->service->update($id, $request->except(['_token', '_method']));

        if ($result) {
            return back()->with('message', 'Successfully updated');
        }

        return back()->with('message', 'Failed to update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->service->destroy($id);

        if ($result) {
            return redirect('admin/users')->with('message', 'Successfully deleted');
        }

        return redirect('admin/users')->with('message', 'Failed to delete');
    }
}
