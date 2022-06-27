<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class SearchTest extends TestCase
{
    public function test_search_screen_can_be_rendered_with_no_input()
    {
        $response = $this->get('dashboard/search');
 
        $response->assertStatus(200);
    }
}
