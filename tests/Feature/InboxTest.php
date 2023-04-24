<?php
    uses(Illuminate\Foundation\Testing\WithoutMiddleware::class);
    test('inbox_get_data_with_input', function ()
    {
        try{
            /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
            $message = \App\Models\Message_new::factory()->create();
            $user = \App\Models\User::find($message->from_id);
            $hasUser = $user ? true : false;
            $userMeta = null;
            $postJson = [];
            if($hasUser) {
                $userMeta = $user->meta;
                
                $postJson = array(
                                'date'=> 7,
                                'uid'=> $user->id,
                                'isVip'=> 1,
                            );                
            }

            
            $response = $this->actingAs($user)->postJson('/dashboard/chat2/showMessages', $postJson);

            expect($response->getContent())->toBeJson()->json()->toBeArray()->status->toBe(1);           
            //expect($response->getContent())->toBe($result); 
        }catch(Throwable $e){
            
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });

    test('inbox_get_no_data_with_input', function ()
    {
        try{
            /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
            $user = \App\Models\User::factory()->create();

            $userMeta = \App\Models\UserMeta::factory()->create();

            $hasUser = $user ? true : false;

            $postJson = array(
                'date'=> 7,
                'uid'=> $user->id,
                'isVip'=> 1,
            );
            $response = $this->actingAs($user)->postJson('/dashboard/chat2/showMessages', $postJson);
            $result = env("INBOX_NO_VALUE_RESULT");
            expect($response->getContent())->toBeJson()->json()->toBeArray()->msg->toBe([$result]);           
        }catch(Throwable $e){
            
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });

