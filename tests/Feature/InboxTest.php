<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InboxTest extends TestCase
{
    public function test_example()
    {
        $response = $this->get('/dashboard/chat2');

        $response->assertStatus(200);
    }
}
