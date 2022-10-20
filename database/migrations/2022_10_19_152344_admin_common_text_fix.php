<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdminCommonTextFix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_common_text', function ($table) {
            $table->string('content', 700)->change();
        });
        DB::table('admin_common_text')
        ->where('id', 2)
        ->update([
            'content' => '系統通知: 車馬費邀請<br> NAME 已經向 您 發動車馬費邀請。NAME<br>流程如下：：<br>1:網站上進行車馬費邀請<br>2:網站上訊息約見(重要，站方判斷約見時間地點，以網站留存訊息為準)<br>3:雙方見面(建議約在知名連鎖店丹堤星巴克或者麥當勞之類)<br>●<br>若成功見面男方沒有提出異議，那站方會在發動後 7~14 個工作天<br>將 1500 匯入您指定的帳戶。若您不想提供銀行帳戶。<br>也可以用現金袋或者西聯匯款方式進行。<br>(聯繫我們有站方聯絡方式)<br><br>若男方提出當天女方未到場的爭議。請您提出當天消費的發票證明之。<br>所以請約在知名連鎖店以利站方驗證。
            請加站長 line <a href="https://lin.ee/rLqcCns"><img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="26" border="0" style="all: initial;all: unset;height: 26px"></a>',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_common_text', function ($table) {
            $table->string('content', 500)->change();
        });
        DB::table('admin_common_text')
        ->where('id', 2)
        ->update([
            'content' => '系統通知: 車馬費邀請<br> NAME 已經向 您 發動車馬費邀請。NAME<br>流程如下：：<br>1:網站上進行車馬費邀請<br>2:網站上訊息約見(重要，站方判斷約見時間地點，以網站留存訊息為準)<br>3:雙方見面(建議約在知名連鎖店丹堤星巴克或者麥當勞之類)<br>●<br>若成功見面男方沒有提出異議，那站方會在發動後 7~14 個工作天<br>將 1500 匯入您指定的帳戶。若您不想提供銀行帳戶。<br>也可以用現金袋或者西聯匯款方式進行。<br>(聯繫我們有站方聯絡方式)<br><br>若男方提出當天女方未到場的爭議。請您提出當天消費的發票證明之。<br>所以請約在知名連鎖店以利站方驗證。',
        ]);
    }
}
