<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AdminActionLog;

class AddActionLogColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('admin_action_log', function($table) {
            $table->integer('action_id')->default(0)->after('act');
        });
        Schema::create('admin_action_item', function (Blueprint $table) {
            $table->id();
            $table->string('action_name');
            $table->timestamps();
        });
        $item_name = [
        '修改會員資本資料',
        '封鎖會員',
        '解除封鎖',
        '隱性封鎖',
        '站方警示',
        '解除站方警示',
        '給予優選',
        '取消優選',
        '變更性別(男->女)',
        '變更性別(女->男)',
        '取消VIP',
        '切換成此會員前台',
        '溜覽所有訊息',
        '升級VIP',
        '取消警示用戶',
        '刪除大頭照',
        '撰寫站長訊息',
        '警示用戶',
        '取消隱藏',
        '升級隱藏',
        '會員檢查等待更多資料',
        '查看會員基本資料',
        '會員檢查 Step2 通過',
        '禁止進入匿名聊天室',
        '解除禁止進入匿名聊天室',
        '查看會員基本資料',
        '等待更多資料(發回)',
        '加入可疑名單',
        '刪除可疑名單'
        ];
        foreach($item_name as $name)
        {
            DB::table('admin_action_item')->insert(
                ['action_name' => $name]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_action_log', function($table) {
            $table->dropColumn('action_id');
        });
        Schema::dropIfExists('admin_action_item');
    }
}
