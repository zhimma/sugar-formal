<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ValueAddedService;
use App\Models\VvipMarginDeposit;
use Illuminate\Http\Request;

class VvipController extends \App\Http\Controllers\BaseController
{
    //

    public function viewVvipMarginDeposit()
    {
        $list = ValueAddedService::with('user', 'user.VvipMargin')->where('service_name', 'VVIP')->orderByDesc('id')->get();
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

    public function viewVvipCancellationList()
    {
        $list = ValueAddedService::where([
            ['service_name', 'VVIP'],
            ['need_to_refund', 1]
        ])->get();
        return view('admin.users.view_vvip_cancellation_list', compact('list'));
    }

    public function updateVvipCancellation(Request $request)
    {
        $item = ValueAddedService::find($request->item_id);
        $item->need_to_refund = 0;
        $item->refund_amount = null;
        $item->saveOrFail();
        $request->session()->flash('success', '成功更新 ' . $item->user->name . ' 的退款狀態');
        return redirect()->route('admin.view_vvip_cancellation_list');
    }
}
