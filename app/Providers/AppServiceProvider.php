<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Factory $validator) {

        \Debugbar::disable();
        //檢查會員 VIP 是否為綠界，若為綠界，則檢查是否為下一週期前七天內取消，若是，則設定變數
        View::composer('*', function($view) {
            if (\Auth::check()){
                $user = \App\Models\User::findById(\Auth::id());
            }
            if(isset($user)) {
                if($user->isVip()) {
                    $vipData = \App\Models\Vip::findByIdWithDateDesc($user->id);
                    if(isset($vipData->updated_at)){    //有的優選資格被拔掉的會員不會有 updated_at 的值
                        $now = \Carbon\Carbon::now();
                        $vipDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $vipData->updated_at);
                        if($vipData->business_id == '3137610' && $now->diffInDays($vipDate) <= 7) {
                            View::share('vipLessThan7days', true);
                            View::share('vipData', $vipData);
                            View::share('vipRenewDay', $vipDate->day);
                            View::share('vipNextMonth', $vipDate->addMonth());
                        }
                    }
                }
            }
        });
        //
        require_once app_path() . '/validators.php';
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
