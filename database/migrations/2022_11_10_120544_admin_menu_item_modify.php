<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AdminMenuItems;

class AdminMenuItemModify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        AdminMenuItems::where('title','付費 VIP 會員訂單資料')->update(['status' => 0]);
        AdminMenuItems::where('title','綠界 VIP 付費取消資料')->update(['title' => '綠界 / FunPoint VIP 付費取消資料']);
        AdminMenuItems::where('title','未啟動會員')->update(['status' => 0]);
        AdminMenuItems::where('title','指定會員發訊息')->update(['status' => 0]);
        AdminMenuItems::where('title','VVIP申請管理')->update(['title' => 'VVIP 申請管理']);

        $max_sort = AdminMenuItems::orderByDesc('sort')->first()->sort;

        $item_array = [];
        $item_array[] = ['title' => '付款方式管理', 'route_path' => '/admin/dashboard/paymentFlowChoose', 'status' => 1];
        $item_array[] = ['title' => '初階站長權限管理', 'route_path' => '/admin/dashboard/accessPermission', 'status' => 1];
        $item_array[] = ['title' => 'VVIP 待取消名單', 'route_path' => '/admin/users/VVIP_cancellation_list', 'status' => 1];
        $item_array[] = ['title' => 'VVIP 入會費 / 保證金管理', 'route_path' => '/admin/users/VVIP_margin_deposit', 'status' => 1];
        $item_array[] = ['title' => '照片會員列表', 'route_path' => '/admin/users/picMemberList', 'status' => 1];
        $item_array[] = ['title' => '寄退信Log紀錄查詢', 'route_path' => '/admin/maillog', 'status' => 1];
        $item_array[] = ['title' => '寄送統計沖洗郵件', 'route_path' => '/admin/fakeMail', 'status' => 1];
        $item_array[] = ['title' => '進階資訊統計工具', 'route_path' => '/admin/users/informationStatistics', 'status' => 1];
        $item_array[] = ['title' => 'FAQ機制', 'route_path' => '/admin/faq', 'status' => 1];
        $item_array[] = ['title' => '廣告紀錄統計', 'route_path' => '/admin/admin/advertiseStatistics', 'status' => 1];
        $item_array[] = ['title' => '停留時間', 'route_path' => '/admin/admin/user_record_view', 'status' => 1];
        $item_array[] = ['title' => '視訊驗證影片紀錄', 'route_path' => '/admin/users/video_chat_verify_record_list', 'status' => 1];
        $item_array[] = ['title' => '精華文章統計資料', 'route_path' => '/admin/getEssenceStatisticsRecord', 'status' => 1];
        $item_array[] = ['title' => 'Feature Flags', 'route_path' => '/admin/global/feature_flags', 'status' => 1];
        $item_array[] = ['title' => '留言板管理', 'route_path' => '/admin/users/board', 'status' => 0];
        $item_array[] = ['title' => '異動檔手動修改', 'route_path' => '/admin/users/customizeMigrationFiles', 'status' => 0];
        $item_array[] = ['title' => '異動檔上傳/檢查記錄', 'route_path' => '/admin/stats/date_file_log', 'status' => 0];
        $item_array[] = ['title' => 'VIP 排程檢查記錄', 'route_path' => '/admin/stats/cron_log', 'status' => 0];
        $item_array[] = ['title' => '會員搜尋(變更男女、VIP資料)', 'route_path' => '/admin/users/search', 'status' => 0];
        $item_array[] = ['title' => '會員訊息管理', 'route_path' => '/admin/users/message/search', 'status' => 0];

        for($i = 0; $i < count($item_array); $i++)
        {
            $max_sort = $max_sort + 1;
            $item = new AdminMenuItems;
            $item->title = $item_array[$i]['title'];
            $item->route_path = $item_array[$i]['route_path'];
            $item->status = $item_array[$i]['status'];
            $item->sort = $max_sort;
            $item->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
