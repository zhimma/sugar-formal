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
        ];
    }
}