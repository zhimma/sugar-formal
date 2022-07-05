<?php
namespace Database\Factories;

use App\Models\UserMeta;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserMetaFactory extends Factory
{
    protected $model = UserMeta::class;

    public function definition()
    {
        $user = \DB::table('users')->orderBy('id','desc')->first();
        return [
            'user_id' => $user->id,
            'phone' => '0912345678',
            'marketing' => 1,
            'terms_and_cond' => 1,
            'is_active'=>1,
            'city'=>'基隆市,雲林縣,彰化縣',
            'blockcity'=>'嘉義市,雲林縣',
            'area'=>'仁愛區',
            'blockarea'=>'東區,斗南鎮',
            'budget'=>'基礎',
            'birthdate'=>'1983-04-01',
            'height'=>'180',
            'weight'=>'60',
            'cup'=>'A',
            'body'=>'標準',
            'about'=>'一起聊天認識彼此吧交給我，我給你全世界',
            'style'=>'可商議',
            'situation'=>'學生',
            'occupation'=>'工程師',
            'education'=>'研究所',
            'marriage'=>'單身',
            'drinking'=>'偶爾喝',
            'smoking'=>'不抽',
            
            'pic'=>'/img/Member/2016/03/01/20160301053836628.gif',
            'domainType'=>'資訊科技',
            'domain'=>'半導體業',

            'assets'=>'100',
            'income'=>'50~100萬',
            'notifmessage'=>'不通知',
            'notifhistory'=>'顯示普通會員信件'
        ];
    }
}