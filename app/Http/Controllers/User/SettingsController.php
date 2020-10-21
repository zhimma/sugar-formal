<?php

namespace App\Http\Controllers\User;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;

class SettingsController extends Controller
{
    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    /**
     * View current user's settings
     *
     * @return \Illuminate\Http\Response
     */
    public function settings(Request $request)
    {
        // $user = $request->user();
        //
        // if ($user) {
        //     return view('user.settings')
        //     ->with('user', $user);
        // }
        //
        // return back()->withErrors(['Could not find user']);
    }

    /**
     * Update the user
     *
     * @param  UpdateAccountRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileUpdateRequest $request)
    {
        $payload = $request->all();
        if ($this->service->update(auth()->id(), $payload)) {
            return back()->with('message', '成功更新資料');
        }

        return back()->withErrors(['更新失敗']);
    }
}
