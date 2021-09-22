<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdminMenuItemsAddValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $updateMenuList=[
            '訂單查詢' =>array('route_path'=>'/admin/order', 'sort'=>1),
            '自動封鎖警示設定' =>array('route_path'=>'/admin/stats/set_autoBan', 'sort'=>2),
            'VIP 會員統計資料' =>array('route_path'=>'/admin/stats/vip', 'sort'=>3),
            '付費 VIP 會員訂單資料' =>array('route_path'=>'/admin/stats/vip/paid', 'sort'=>4),
            '其他 VIP 相關統計資料' =>array('route_path'=>'/admin/stats/other', 'sort'=>5),
            '綠界 VIP 付費取消資料' =>array('route_path'=>'/admin/users/VIP/ECCancellations', 'sort'=>6),
            '會員列表' =>array('route_path'=>'/admin/users/memberList', 'sort'=>7),
            '會員搜尋(變更男女、VIP資料)' =>array('route_path'=>'/admin/users/search', 'sort'=>8),
            '進階會員搜尋' =>array('route_path'=>'/admin/users/advSearch', 'sort'=>9),
            '會員封鎖清單' =>array('route_path'=>'/admin/users/bannedList', 'sort'=>10),
            '警示名單' =>array('route_path'=>'/admin/users/warning', 'sort'=>11),
            '多重登入_CFP' =>array('route_path'=>'/admin/users/showDuplicate?only=cfpid', 'sort'=>12),
            '多重登入_完整版' =>array('route_path'=>'/admin/users/showDuplicate', 'sort'=>13),
            '異常連線記錄' =>array('route_path'=>'/admin/too_many_requests', 'sort'=>14),
            '異常連線記錄(純記錄)' =>array('route_path'=>'/admin/too_many_requests?pseudo=1', 'sort'=>15),
            '可疑名單列表' =>array('route_path'=>'/admin/users/suspiciousUser', 'sort'=>16),
            '會員照片管理' =>array('route_path'=>'/admin/users/pictures', 'sort'=>17),
            '會員照片管理簡化版' =>array('route_path'=>'/admin/users/picturesSimple', 'sort'=>18),
            '會員被檢舉次數' =>array('route_path'=>'/admin/users/reported/count', 'sort'=>19),
            '討論區管理' =>array('route_path'=>'/admin/users/posts', 'sort'=>20),
            '留言板管理' =>array('route_path'=>'/admin/users/messageBoard', 'sort'=>21),
            '會員訊息管理' =>array('route_path'=>'/admin/users/message/search', 'sort'=>22),
            '會員訊息統計' =>array('route_path'=>'/admin/statistics', 'sort'=>23),
            '罐頭訊息查詢' =>array('route_path'=>'/admin/users/spam_text_message/search', 'sort'=>24),
            '被檢舉會員清單' =>array('route_path'=>'/admin/users/reported', 'sort'=>25),
            '被檢舉照片清單' =>array('route_path'=>'/admin/users/pics/reported', 'sort'=>26),
            '基本設定' =>array('route_path'=>'/admin/users/basic_setting', 'sort'=>27),
            '修改會員密碼' =>array('route_path'=>'/admin/users/changePassword', 'sort'=>28),
            '切換會員身份' =>array('route_path'=>'/admin/users/switch', 'sort'=>29),
            '關閉會員帳號原因統計' =>array('route_path'=>'/admin/users/closeAccountReason', 'sort'=>30),
            '未啟動會員' =>array('route_path'=>'/admin/users/inactive', 'sort'=>31),
            '指定會員發訊息' =>array('route_path'=>'/admin/users/message/sendUserMessage', 'sort'=>32),
            '站長公告' =>array('route_path'=>'/admin/announcement', 'sort'=>33),
            '站長的話' =>array('route_path'=>'/admin/masterwords', 'sort'=>34),
            '網站公告本月封鎖名單' =>array('route_path'=>'/admin/web/announcement', 'sort'=>35),
            '站長信箱' =>array('route_path'=>'/admin/chat', 'sort'=>36),
            '編輯文案' =>array('route_path'=>'/admin/commontext', 'sort'=>37),
            '站長審核' =>array('route_path'=>'/admin/check', 'sort'=>38),
            'Admin後台操作記錄' =>array('route_path'=>'/admin/getAdminActionLog', 'sort'=>39),
            '發信_檢舉_封鎖異常查詢' =>array('route_path'=>'/admin/users/filterByInfo', 'sort'=>40),
        ];

        //將未使用的項目,status=0
        $getMenuList= \App\Models\AdminMenuItems::groupBy('title')->pluck('title');
        foreach ($getMenuList as $item) {
            if(!in_array($item, array_keys($updateMenuList))){
                \App\Models\AdminMenuItems::where('title', $item)->update(['status'=> 0, 'updated_at' => now()]);
            }
        }

        //更新menu項目＆排序
        foreach ($updateMenuList as $key =>$value){
            \App\Models\AdminMenuItems::updateOrInsert(['title'=>$key], $value);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
