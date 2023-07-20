<?php

    test('Url' ,function ()
    {
        try{
            $this->assertEquals(200, $this->actingAs(\App\Models\User::find(1049))->call('GET', route('admin/showMessagesBetween',[15600, 1049]))->status());
            expect($this->actingAs(\App\Models\User::find(1049))->call('GET', route('admin/showMessagesBetween',[15600, 1049])))->status()->toBe(200);
        }catch(Throwable $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });
