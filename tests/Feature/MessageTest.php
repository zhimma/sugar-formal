<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageTest extends TestCase
{
    /**
     * test url which the prefix is 'users/message'
     *
     * @return void
     */
    public function testUrl()
    {
        $this->assertEquals(200, $this->call('GET', route('admin/showMessagesBetween'), [41759, 1049])->status());

        //$this->assertEquals(200, $this->call('GET', route('admin/showMessagesBetween'), [41759, 1049])->status());
    }
}
