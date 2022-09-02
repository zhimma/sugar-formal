<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVvipOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('vvip_option_point_information')->insert([
            ['option_name' => '高資產'],
            ['option_name' => '高收入'],
            ['option_name' => '企業負責人'],
            ['option_name' => '出手大方'],
            ['option_name' => '品味高雅'],
            ['option_name' => '人生導師'],
        ]);
        DB::table('vvip_option_date_trend')->insert([
            ['option_name' => '節省時間，速戰速決'],
            ['option_name' => '品嘗美食'],
            ['option_name' => '戶外旅遊'],
            ['option_name' => '出國旅遊'],
            ['option_name' => '高爾夫球敘'],
            ['option_name' => '商務餐宴'],
            ['option_name' => '聊天傾訴'],
            ['option_name' => '安靜陪伴'],
            ['option_name' => '短暫浪漫'],
            ['option_name' => '男女朋友'],
            ['option_name' => '親密關係'],
            ['option_name' => '商務之旅'],
            ['option_name' => '固定假日陪伴'],
        ]);
        DB::table('vvip_option_background_and_assets')->insert([
            ['option_name' => '專業人士'],
            ['option_name' => '高資產人士'],
            ['option_name' => '企業家'],
        ]);
        DB::table('vvip_option_extra_care')->insert([
            ['option_name' => '專業人脈'],
            ['option_name' => '生活照顧'],
            ['option_name' => '特殊問題處理'],
        ]);
        DB::table('vvip_option_assets_image')->insert([
            ['option_name' => '/new/images/zz_1.png'],
            ['option_name' => '/new/images/zz_2.png'],
            ['option_name' => '/new/images/zz_3.png'],
            ['option_name' => '/new/images/zz_4.png'],
            ['option_name' => '/new/images/zz_5.png'],
        ]);
        DB::table('vvip_option_quality_life_image')->insert([
            ['option_name' => '/new/images/zz_1.png'],
            ['option_name' => '/new/images/zz_2.png'],
            ['option_name' => '/new/images/zz_3.png'],
            ['option_name' => '/new/images/zz_4.png'],
            ['option_name' => '/new/images/zz_5.png'],
            ['option_name' => '/new/images/zb_9.png'],
            ['option_name' => '/new/images/zb_10.png'],
        ]);
        DB::table('vvip_option_expect_date')->insert([
            ['option_name' => '節省時間，速戰速決'],
            ['option_name' => '品嘗美食'],
            ['option_name' => '戶外旅遊'],
            ['option_name' => '高爾夫球敘'],
            ['option_name' => '商務餐宴'],
            ['option_name' => '聊天傾訴'],
            ['option_name' => '安靜陪伴'],
            ['option_name' => '短暫浪漫'],
            ['option_name' => '男女朋友'],
            ['option_name' => '親密關係'],
            ['option_name' => '商務之旅'],
            ['option_name' => '固定假日陪伴'],
        ]);



        DB::table('vvip_sub_option_high_assets')->insert([
            ['option_name' => '不動產'],
            ['option_name' => '證券'],
        ]);
        DB::table('vvip_sub_option_ceo_title')->insert([
            ['option_name' => '負責人'],
            ['option_name' => '大股東'],
            ['option_name' => '董監事'],
        ]);
        DB::table('vvip_sub_option_professional')->insert([
            ['option_name' => '上市公司高管'],
            ['option_name' => '上櫃公司高管'],
            ['option_name' => '外商公司高管'],
            ['option_name' => '律師'],
            ['option_name' => '建築師'],
            ['option_name' => '會計師'],
            ['option_name' => '政治人物'],
            ['option_name' => '機師'],
            ['option_name' => '精算師'],
            ['option_name' => '工程師'],
            ['option_name' => '醫生'],
        ]);
        DB::table('vvip_sub_option_high_net_worth')->insert([
            ['option_name' => '不動產'],
            ['option_name' => '有價證券'],
            ['option_name' => '其他'],
        ]);
        DB::table('vvip_sub_option_entrepreneur')->insert([
            ['option_name' => '大型企業', 'option_content'=> '員工超過200人以上'],
            ['option_name' => '中型企業', 'option_content'=> '員工超過100人以上'],
            ['option_name' => '小型企業', 'option_content'=> '員工未達100人'],
            ['option_name' => '高收入企業', 'option_content'=> '年營業額超過一億以上'],
        ]);
        DB::table('vvip_sub_option_professional_network')->insert([
            ['option_name' => '金融'],
            ['option_name' => '法律'],
            ['option_name' => '電子'],
            ['option_name' => '影劇'],
            ['option_name' => '醫療'],
            ['option_name' => '公關'],
            ['option_name' => '能源'],
        ]);
        DB::table('vvip_sub_option_life_care')->insert([
            ['option_name' => '安排住宿'],
            ['option_name' => '出遊接送'],
            ['option_name' => '聊天陪伴'],
        ]);
        DB::table('vvip_sub_option_special_problem_handling')->insert([
            ['option_name' => '債務問題處理'],
            ['option_name' => '就學/留學幫助'],
            ['option_name' => '醫療問題協助'],
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
