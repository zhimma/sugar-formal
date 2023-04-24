<?php
    test('render_show_list' ,function ()
    {
        try{
            $this->withoutMiddleware(\App\Http\Middleware\FaqCheck::class);
            $user = \App\Models\User::whereHas('vip')
                            ->where(function($q){
                                    $q->where('engroup',2)->whereHas('short_message',function($smq){$smq->where('active',1);})
                                      ->orWhere('engroup',1);  
                                })->first();

            $response = $this->actingAs($user)->get('/MessageBoard/showList');
            $response->assertStatus(200);
        }catch(Throwable $e){
            
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });

