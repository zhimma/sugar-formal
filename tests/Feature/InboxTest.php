<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InboxTest extends TestCase
{
    public function test_inbox_screen_can_be_rendered_with_no_input()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = \App\Models\User::factory()->create();

        $userMeta = \App\Models\UserMeta::factory()->create();

        $hasUser = $user ? true : false;

        $response = $this->actingAs($user)->get('/dashboard/chat2');
        $response->assertStatus(200);
    }

    public function test_inbox_result_with_correct_data_return()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = \App\Models\User::factory()->create();

        $userMeta = \App\Models\UserMeta::factory()->create();

        $hasUser = $user ? true : false;

        $response = $this->actingAs($user)->get('/dashboard/chat2');
        $response->assertStatus(200);
    }
}
