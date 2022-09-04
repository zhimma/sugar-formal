<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VvipMarginDeposit;
use Illuminate\Http\Request;

class VvipController extends \App\Http\Controllers\BaseController
{
    //

    public function viewVvipMarginDeposit()
    {
        $list = VvipMarginDeposit::with('user')->get();
        return view('admin.users.view_vvip_margin_deposit', compact('list'));
    }

    public function editVvipMarginDeposit($user_id, Request $request)
    {
        $deposit = VvipMarginDeposit::where('user_id', $user_id)->first();
        return view('admin.users.edit_vvip_margin_deposit', compact('deposit'));
    }

    public function updateVvipMarginDeposit($user_id, Request $request)
    {
        $deposit = VvipMarginDeposit::where('user_id', $user_id)->first();
        $deposit->updateBalance($deposit->balance, $request->balance);
        $request->session()->flash('success', '成功調整 ' . $deposit->user->name . ' 的保證金');
        return redirect()->route('admin.view_vvip_margin_deposit');
    }
}
