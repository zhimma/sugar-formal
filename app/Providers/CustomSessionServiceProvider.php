<?php
 
namespace App\Providers;
 
use App\Extensions\CustomFileSessionHandler;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

 
class CustomSessionServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
 
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Session::extend('customfile', function ($app) {
            /** 
             * 追蹤 Session 這個 facade，可以發現它的 extend 是繼承自 \Illuminate\Support\Manager，
             * 所以需閱讀 \Illuminate\Support\Manager 關於自訂 Driver 的程式碼，以確認這個匿名函式(closure)
             * 的語法應該如何撰寫，才能達到繼承 file driver 的效果。
             * 
             * 在 \Illuminate\Support\Manager 中，可以看到 createDriver() 使用了
             * 動態函式(Variable functions)，來確保所有自訂的 Session driver 都可以通過同樣的方式建立，
             * 從這個過程中，可以確定建立一個繼承自某 driver 的自訂 Session driver 時，Session::extend 的
             * 第二個參數——匿名函式(closure)的用法，應該要與所要參考的 Session driver 一樣，才能達成
             * 繼承的效果。
             *  
             * 故這時應該直接參考 \Illuminate\Session\SessionManager 的 createNativeDriver()，
             * 將 Session handler 所需的相關參數填入，才能完成繼承 File driver 的效果。
             */

            // 相關類別參照連結：
            /** @var \Illuminate\Support\Manager */    
            /** @var \Illuminate\Session\SessionManager */
            
            $lifetime = $app->config->get('session.lifetime');
            
            // Return an implementation of SessionHandlerInterface...
            return new CustomFileSessionHandler($app->container->make('files'), $app->config->get('session.files'), $lifetime);
        });
    }
}
