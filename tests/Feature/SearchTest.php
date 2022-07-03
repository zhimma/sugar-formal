<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class SearchTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // use RefreshDatabase;

    // public function test_example()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function test_search_screen_can_be_rendered_with_no_input()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = \App\Models\User::factory()->create();

        $userMeta = \App\Models\UserMeta::factory()->create();

        $hasUser = $user ? true : false;

        $response = $this->actingAs($user)->get('/dashboard/search');
        $response->assertStatus(200);
    }

    public function test_search_screen_can_be_rendered_with_correct_input()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = \App\Models\User::factory()->create();

        $userMeta = \App\Models\UserMeta::factory()->create();

        $hasUser = $user ? true : false;

        $response = $this->actingAs($user)->get('/getSearchData');
        $response->assertStatus(200);
    }

    public function test_search_result_with_correct_data_return(){
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = \App\Models\User::factory()->create();

        $userMeta = \App\Models\UserMeta::factory()->create();

        $hasUser = $user ? true : false;

        // $response = $this->actingAs($user)->postJson('dashboard/search',['_token'=>'county', 'email'=> 'sugargardentest@gmail.com', 'password'=>'123123']);

        $response = $this->actingAs($user)->post('/getSearchData');
        $response->assertStatus(200);
    }
    
    public function test_search_screen_can_be_rendered_with_incorrect_input()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = \App\Models\User::factory()->create();

        $userMeta = \App\Models\UserMeta::factory()->create();

        $hasUser = $user ? true : false;

        // $response = $this->actingAs($user)->postJson('dashboard/search',['_token'=>'county', 'email'=> 'sugargardentest@gmail.com', 'password'=>'123123']);

        $response = $this->actingAs($user)->get('/getSearchData');
        $response->assertStatus(200);
    }

    public function test_search_result_with_incorrect_data_return(){
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = \App\Models\User::factory()->create();

        $userMeta = \App\Models\UserMeta::factory()->create();

        $hasUser = $user ? true : false;

        // $response = $this->actingAs($user)->postJson('dashboard/search',['_token'=>'county', 'email'=> 'sugargardentest@gmail.com', 'password'=>'123123']);

        $response = $this->actingAs($user)->get('/getSearchData');
        $response->assertStatus(200);
    }
}
