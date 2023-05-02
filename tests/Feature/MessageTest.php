<?php

    test('Url' ,function ()
    {
        $this->assertEquals(200, $this->actingAs(\App\Models\User::find(1049))->call('GET', route('admin/showMessagesBetween',[15600, 1049]))->status());
        expect($this->actingAs(\App\Models\User::find(1049))->call('GET', route('admin/showMessagesBetween',[15600, 1049])))->status()->toBe(200);
    });
