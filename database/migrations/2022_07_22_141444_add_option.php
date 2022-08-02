<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('option_type')->insert([
            ['type_name' => 'occupation'],
            ['type_name' => 'relationship_status'],
            ['type_name' => 'looking_for_relationships'],
            ['type_name' => 'expect'],
            ['type_name' => 'favorite_food'],
            ['type_name' => 'preferred_date_location'],
            ['type_name' => 'expected_type'],
            ['type_name' => 'frequency_of_getting_along'],
        ]);
        DB::table('option_occupation')->insert([
            ['option_name' => '待業'],
            ['option_name' => '輪班'],
            ['option_name' => '排課'],
            ['option_name' => '上班族'],
            ['option_name' => '全職學生'],
            ['option_name' => 'soho'],
            ['option_name' => '網拍'],
        ]);
        DB::table('option_relationship_status')->insert([
            ['option_name' => '單身'],
            ['option_name' => '有曖昧對象'],
            ['option_name' => '有男友'],
            ['option_name' => '離婚'],
            ['option_name' => '結婚'],
            ['option_name' => '有男友，同居中'],
            ['option_name' => '有甜爹'],
            ['option_name' => '無甜爹'],
            ['option_name' => '暫不透漏'],
            ['option_name' => '處女'],
        ]);
        DB::table('option_looking_for_relationships')->insert([
            ['option_name' => '男女朋友', 'option_content'=> '長期穩定的男女朋友關係。'],
            ['option_name' => '談心好友', 'option_content'=> '平常會互相聊天關心，會有餐敘，出遊的朋友間交誼活動。'],
            ['option_name' => '網路情人', 'option_content'=> '平常網路聊天，有需求時才見面。'],
            ['option_name' => '保有隱私', 'option_content'=> '除了約見時間以外盡量不聯絡。'],
            ['option_name' => '一對一', 'option_content'=> '雙方都只能單一對象。'],
            ['option_name' => '人生導師', 'option_content'=> '可以給自己人生/專業上的建議/諮詢。'],
            ['option_name' => '同居情人', 'option_content'=> '可以接受同居生活。'],
            ['option_name' => '事業領航', 'option_content'=> '可以在專業/事業上，對自己有實質的協助。'],
        ]);
        DB::table('option_expect')->insert([
            ['option_name' => '尋求事業資源'],
            ['option_name' => '解決債務問題'],
            ['option_name' => '尋求人脈資源'],
            ['option_name' => '尋求表現舞台'],
            ['option_name' => '尋求生活照顧'],
            ['option_name' => '尋求就學依靠'],
            ['option_name' => '尋求人生導師'],
            ['option_name' => '尋求可依靠的心靈撫慰'],
            ['option_name' => '尋求可以幫忙解決生活大小事'],
        ]);
        DB::table('option_favorite_food')->insert([
            ['option_name' => '台式'],
            ['option_name' => '中式'],
            ['option_name' => '港式'],
            ['option_name' => '日式'],
            ['option_name' => '韓式'],
            ['option_name' => '西式'],
            ['option_name' => '法式'],
            ['option_name' => '甜點'],
            ['option_name' => '鐵板燒'],
            ['option_name' => '素食'],
        ]);
        DB::table('option_preferred_date_location')->insert([
            ['option_name' => '高級餐廳'],
            ['option_name' => 'Bar'],
            ['option_name' => '咖啡廳'],
            ['option_name' => '電影院'],
            ['option_name' => '戶外踏青'],
            ['option_name' => '購物中心'],
            ['option_name' => '美術展覽'],
            ['option_name' => '運動場'],
            ['option_name' => 'NightClub'],
            ['option_name' => 'Hotel'],
            ['option_name' => '家'],
            ['option_name' => 'KTV'],
            ['option_name' => 'Mall'],
            ['option_name' => '出國行程'],
        ]);
        DB::table('option_expected_type')->insert([
            ['option_name' => '收禮型', 'option_content'=> '希望常常收到禮物'],
            ['option_name' => '玩樂型', 'option_content'=> '經常有出遊行程'],
            ['option_name' => '交流型', 'option_content'=> '需要多聊天'],
            ['option_name' => '直接型', 'option_content'=> '給錢就好'],
            ['option_name' => '學習型', 'option_content'=> '希望daddy可以對自我提升/生涯規劃有幫助'],
        ]);
        DB::table('option_frequency_of_getting_along')->insert([
            ['option_name' => '平常不聯絡只有約會時連絡'],
            ['option_name' => '如朋友般日常問候聊天'],
            ['option_name' => '親密情人般關心聯絡'],
            ['option_name' => '偶爾見面'],
            ['option_name' => '一周一次'],
            ['option_name' => '一周兩三次'],
            ['option_name' => '同居'],
            ['option_name' => '被安排專屬住房'],
        ]);
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
