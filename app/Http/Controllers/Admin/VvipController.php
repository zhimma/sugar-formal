<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use App\Models\ValueAddedService;
use App\Models\VvipMarginDeposit;
use App\Models\VvipApplication;
use App\Models\Order;
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
        $deposit = VvipMarginDeposit::where('user_id', $user_id)->firstOrCreate(['user_id' => $user_id]);
        return view('admin.users.edit_vvip_margin_deposit', compact('deposit'));
    }

    public function updateVvipMarginDeposit($user_id, Request $request)
    {
        $deposit = VvipMarginDeposit::where('user_id', $user_id)->first();
        $deposit->updateBalance($request->balance_before, $request->balance_after);
        $request->session()->flash('success', '成功調整 ' . $deposit->user->name . ' 的保證金');
        return redirect()->route('users/VVIP_margin_deposit');
    }

    public function viewVvipCancellationList()
    {
        $VVIPplanA = VvipApplication::where([['plan', 'VVIP_A'], ['created_at', '<', now()->subDays(3)]])->orderBy('id', 'desc')->get();
        $VVIPplanA->each(function ($item) {
            if(($item->user->VvipMargin ?? true) || $item->user->VvipMargin?->balance < 20000) {
                [$refund, ] = PaymentService::calculatesRefund($item->user, 'vvip_without_remittance');
                if($refund) {
                    $record = ValueAddedService::where('order_id', $item->order_id)->first();
                    $record->need_to_refund = 1;
                    $record->refund_amount = $refund;
                    $record->saveOrFail();
                }
            }
        });
        
        $VVIPplanB = VvipApplication::where([['plan', 'VVIP_B'], ['created_at', '<', now()->subDays(3)]])->orderBy('id', 'desc')->get();
        $VVIPplanB->each(function ($item) {
            if(($item->user->VvipMargin ?? true) || $item->user->VvipMargin?->balance < 50000) {
                [$refund, ] = PaymentService::calculatesRefund($item->user, 'vvip_without_remittance');
                if($refund) {
                    $record = ValueAddedService::where('order_id', $item->order_id)->first();
                    $record->need_to_refund = 1;
                    $record->refund_amount = $refund;
                    $record->saveOrFail();
                }
            }
        });
        
        $VIPlist = Order::where([
            ['service_name', 'VIP'],
            ['need_to_refund', 1]
        ])->get();

        $VVIPlist = ValueAddedService::where([
            ['service_name', 'VVIP'],
            ['need_to_refund', 1]
        ])->get();

        $list = $VIPlist->merge($VVIPlist);

        return view('admin.users.view_vvip_cancellation_list', compact('list'));
    }

    public function updateVvipCancellation(Request $request)
    {
        $item = "\\" . $request->class::find($request->item_id);
        $item->need_to_refund = 0;
        $item->refund_amount = null;
        $item->saveOrFail();
        $request->session()->flash('success', '成功更新 ' . $item->user->name . ' 的退款狀態');
        return redirect()->route('users/VVIP_cancellation_list');
    }
}
