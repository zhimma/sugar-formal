<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    //
    protected $user;

    public function __construct()
    {
        $this->user = auth()->user();
        \View::share('user', $this->user);
        if(isset($this->user)) {
            if($this->user->isVip()) {
                $vipData = $this->user->vip->sortByDesc("created_at")->first();
                if(isset($vipData->updated_at)){    //有的優選資格被拔掉的會員不會有 updated_at 的值
                    $now = \Carbon\Carbon::now();
                    $vipDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $vipData->updated_at);
                    // 全域 VIP 資料
                    View::share('vipData', $vipData);
                    //檢查會員 VIP 是否為綠界，若為綠界，則檢查是否為下一週期前七天內取消，若是，則設定變數
                    if($vipData->business_id == '3137610' && $now->diffInDays($vipDate) <= 7) {
                        View::share('vipLessThan7days', true);
                        View::share('vipRenewDay', $vipDate->day);
                        View::share('vipNextMonth', $vipDate->addMonth());
                    }
                }
            }
        }

        // View::composer 使用場合 https://castion2293.medium.com/laravel%E4%BD%BF%E7%94%A8view-composer%E7%B5%84%E4%BB%B6-4b07b0753692
        // https://adon988.logdown.com/posts/7831625-laravel-view-composer-introduction
        //  \View::composer('*', function($view) {
        //
        // });
    }
}
