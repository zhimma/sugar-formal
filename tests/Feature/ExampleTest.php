<?php

    test('BasicTest', function ()  {
        $response = $this->get('/');

        $response->assertStatus(200);
    });

