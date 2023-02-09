<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertAdminCommonTextVvipSelectionReward extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $data1 = DB::table('admin_common_text')->where('category_alias', 'vvip_selection_reward')->where('alias', 'vvip_selection_reward_area1_title')->first();
        if(!$data1) {
            DB::table('admin_common_text')->insert(
                array(
                    'status' => 1,
                    'category' => '未分類',
                    'category_alias' => 'vvip_selection_reward',
                    'alias' => 'vvip_selection_reward_area1_title',
                    'title' => 'VVIP徵選活動-選拔規範副標題',
                    'content' => '本功能為本站 VVIP 專有，可以通過站方審核後發布徵選活動。'
                )
            );
        }

        $data2 = DB::table('admin_common_text')->where('category_alias', 'vvip_selection_reward')->where('alias', 'vvip_selection_reward_area1')->first();
        if(!$data2) {
            DB::table('admin_common_text')->insert(
                array(
                    'status' => 1,
                    'category' => '未分類',
                    'category_alias' => 'vvip_selection_reward',
                    'alias' => 'vvip_selection_reward_area1',
                    'title' => 'VVIP徵選活動-選拔規範',
                    'content' => '<li>
                                <span class="v_ficon"></span>
                                <span class="v_ftext">徵選活動將以醒目的方式出現，當您發佈的活動通過審核，女方一上線則會看到。<a onclick="fanli()">(點我看範例)</a></span>
                            </li>
                            <li>
                                <span class="v_ficon"></span>
                                <span class="v_ftext">站方將依照您的徵選條件指定酬金，同意後匯款至指定帳戶即開始徵選活動。匯款後請於專屬頁面填入帳號後五碼。</span>
                            </li>
                            <li>
                                <span class="v_ficon"></span>
                                <span class="v_ftext">若有女會員應徵，站方將依您的選拔條件進行審核。</span>
                            </li>'
                )
            );
        }

        $data3 = DB::table('admin_common_text')->where('category_alias', 'vvip_selection_reward')->where('alias', 'vvip_selection_reward_area2')->first();
        if(!$data3) {
            DB::table('admin_common_text')->insert(
                array(
                    'status' => 1,
                    'category' => '未分類',
                    'category_alias' => 'vvip_selection_reward',
                    'alias' => 'vvip_selection_reward_area2',
                    'title' => 'VVIP徵選活動-審核規範',
                    'content' => '<li class="v_fanr02_o">
                                <span class="v_ficon01"></span>
                                <span class="v_ftext">只要女會員通過條件審核，不論是否約會成功，站方將立即發放50%的酬金予女會員。</span>
                            </li>
                            <li class="v_fanr02_o">
                                <span class="v_ficon01"></span>
                                <span class="v_ftext">如果該女會員看過您的資料拒絕與您互動，此筆費用不退還。</span>
                            </li>
                            <li class="v_fanr02_o">
                                <span class="v_ficon01"></span>
                                <span class="v_ftext">通過條件驗證的女會員將出現在您的徵選收件中。</span>
                            </li>
                            <li class="v_fanr02_o">
                                <span class="v_ficon01"></span>
                                <span class="v_ftext">通過條件驗證女會員達到徵選人數後停止該項活動。</span>
                            </li>'
                )
            );
        }

        $data4 = DB::table('admin_common_text')->where('category_alias', 'vvip_selection_reward')->where('alias', 'vvip_selection_reward_area3')->first();
        if(!$data4) {
            DB::table('admin_common_text')->insert(
                array(
                    'status' => 1,
                    'category' => '未分類',
                    'category_alias' => 'vvip_selection_reward',
                    'alias' => 'vvip_selection_reward_area3',
                    'title' => 'VVIP徵選活動-通訊規範',
                    'content' => '<li class="v_fanr02_o">
                                <span class="v_ficon01"></span>
                                <span class="v_ftext">剩餘50% 酬金作為車馬費。只要雙方約見成功或者私下交換聯絡方式，則無條件發放予女方。</span>
                            </li>
                            <li class="v_fanr02_o">
                                <span class="v_ficon01"></span>
                                <span class="v_ftext">若見面後女方並不符合條件，請務必拍照或者用其他方式舉證，否則此筆費用將預設發予女會員</span>
                            </li>'
                )
            );
        }

        $data1 = DB::table('admin_common_text')->where('category_alias', 'vvip_selection_reward')->where('alias', 'vvip_selection_reward_area4')->first();
        if(!$data1) {
            DB::table('admin_common_text')->insert(
                array(
                    'status' => 1,
                    'category' => '未分類',
                    'category_alias' => 'vvip_selection_reward',
                    'alias' => 'vvip_selection_reward_area4',
                    'title' => 'VVIP徵選活動-末端提示警語',
                    'content' => '<h2>任何爭議問題皆以站方解釋為主。本人絕無意見！</h2>
                        <h2>以上文字若有任何一點不同意請點[取消]</h2>'
                )
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
        //
    }
}
