<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserInviteRequest;
use App\Models\User;
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
        $users = $this->service->all();
        return view('admin.users.index')->with('users', $users);
    }

    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        if (! $request->search) {
            return redirect('admin/users');
        }
        
        //$user = $this->service->search($request->search);
        $gender = '';
        $vip_order_id = '';
        $user = User::select('id', 'name', 'email', 'engroup')
                ->where('email', $request->search)
                ->get()->first();
        $now = Carbon::now();
        $vip_data = member_vip::select('order_id', 'expiry')
                    ->where('member_id', $user->id)
                    ->where('expiry', '>=', $now->toDateTimeString())
                    ->get()->first();
        if($user->engroup == 1){
            $gender_ch = '男';
        }
        else{
            $gender_ch = '女';
        }
        if($vip_data == null){
            $is_vip = false;
        }
        else{
            $is_vip = true;
            $vip_order_id = $vip_data->order_id;
        }
        return view('admin.users.index')
               ->with('user_id',   $user->id)
               ->with('name',      $user->name)
               ->with('email',     $user->email)
               ->with('gender_ch', $gender_ch)
               ->with('gender',    $user->engroup)
               ->with('is_vip',    $is_vip)
               ->with('vip_order_id',  $vip_order_id);
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
