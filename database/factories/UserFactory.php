<?php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'email'                  => 'TEST'.rand(1, 1000000).'@test.com',
            'password'              => bcrypt('123123'),
            'created_at'            => \Carbon\Carbon::now(),
            'updated_at'            => \Carbon\Carbon::now(),
            // 'is_active'             => 1,
        ];
    }
}