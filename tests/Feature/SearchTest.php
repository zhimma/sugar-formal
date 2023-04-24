<?php

    uses(Illuminate\Foundation\Testing\WithoutMiddleware::class);

    test('search_return_correct_data_with_correct_input' ,function ()
    {
        try{
            /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
            $user = \App\Models\User::factory()->create();

            $userMeta = \App\Models\UserMeta::factory()->create(['user_id'=>$user->id]);

            $hasUser = $user ? true : false;

            $user = \App\Models\User::orderBy('id', 'desc')->first();
            $user_meta = $user->id;
            $postJson = array(
                "agefrom" => "",
                "ageto" => "",
                "area" => "",
                "area2" => "",
                "area3" => "",
                "body" => "",
                "budget" => "",
                "city" => "",
                "city2" => "",
                "city3" => "",
                "cup" => "",
                "drinking" => "",
                "education" => "",
                "exchange_period" => "",
                "heightfrom" => "",
                "heightto" => "",
                "income" => "",
                "isAdvanceAuth" => "0",
                "isBlocked" => "1",
                "isPhoneAuth" => "",
                "isVip" => "",
                "isWarned" => "",
                "marriage" => "",
                "page" => "1",
                "perPageCount" => "12",
                "pic" => "",
                "prRange" => "",
                "prRange_none" => "",
                "seqtime" => "1",
                "situation" => "",
                "smoking" => "",
                "tattoo" => "",
                "userIsAdvanceAuth" => "0",
                "userIsVip" => "1",
                "weight" => "",
                "umeta" => $user_meta,
                "user" => $user
            );
            $response = $this->actingAs($user)->postJson('/getSearchData', $postJson);
            $result = env("SEARCH_HAS_VALUE_RESULT");
            expect($response->getContent())->toBeJson()->json()->toBeArray()->allPageDataCount->toBeInt();
        }catch(Throwable $e){
            
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
			$this->handleCatchedException($e,$notification_string);
        }
    });

    test('search_return_correct_data_with_wrong_input' ,function ()
    {
        try{
            /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
            $user = \App\Models\User::factory()->create();

            $userMeta = \App\Models\UserMeta::factory()->create(['user_id'=>$user->id]);

            $hasUser = $user ? true : false;

            $user = \App\Models\User::orderBy('id', 'desc')->first();
            $user_meta = $user->id;
            $postJson = array(
                "agefrom" => "",
                "ageto" => "",
                "area" => "",
                "area2" => "",
                "area3" => "",
                "body" => "",
                "budget" => "",
                "city" => "",
                "city2" => "",
                "city3" => "",
                "cup" => "",
                "drinking" => "",
                "education" => "",
                "exchange_period" => "",
                "heightfrom" => "",
                "heightto" => "",
                "income" => "",
                "isAdvanceAuth" => "0",
                "isBlocked" => "1",
                "isPhoneAuth" => "",
                "isVip" => "",
                "isWarned" => "",
                "marriage" => "",
                "page" => "1",
                "perPageCount" => "12",
                "pic" => "",
                //"prRange" => "wrong pr percent",
                "prRange" => "wrong pr percent - wrong input",
                "prRange_none" => "",
                "seqtime" => "1",
                "situation" => "",
                "smoking" => "",
                "tattoo" => "",
                "userIsAdvanceAuth" => "0",
                "userIsVip" => "1",
                "weight" => "",
                "umeta" => $user_meta,
                "user" => $user
            );
            $response = $this->actingAs($user)->postJson('/getSearchData', $postJson);

            $result = env("SEARCH_HAS_VALUE_RESULT");
            //expect($response->getContent())->toBe($result);
            expect($response->getContent())->toBeJson();
        }catch(Throwable $e){
            
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
			$this->handleCatchedException($e,$notification_string);
        }
    });

    test('search_return_wrong_data_with_correct_input' ,function ()
    {
        try{
            /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
            $user = \App\Models\User::factory()->create();

            $userMeta = \App\Models\UserMeta::factory()->create();

            $hasUser = $user ? true : false;

            $user = \App\Models\User::orderBy('id', 'desc')->first();
            $user_meta = $user->id;
            $postJson = array(
                "agefrom" => "",
                "ageto" => "",
                "area" => "",
                "area2" => "",
                "area3" => "",
                "body" => "",
                "budget" => "",
                "city" => "",
                "city2" => "",
                "city3" => "",
                "cup" => "",
                "drinking" => "",
                "education" => "",
                "exchange_period" => "",
                "heightfrom" => "",
                "heightto" => "",
                "income" => "",
                "isAdvanceAuth" => "0",
                "isBlocked" => "1",
                "isPhoneAuth" => "",
                "isVip" => "",
                "isWarned" => "",
                "marriage" => "",
                "page" => "1",
                "perPageCount" => "12",
                "pic" => "",
                "prRange" => "",
                "prRange_none" => "",
                "seqtime" => "1",
                "situation" => "",
                "smoking" => "",
                "tattoo" => "",
                "userIsAdvanceAuth" => "0",
                "userIsVip" => "1",
                "weight" => "",
                "umeta" => $user_meta,
                "user" => $user
            );
            $response = $this->actingAs($user)->postJson('/getSearchData', $postJson);

            $result = env("SEARCH_HAS_VALUE_RESULT");
            expect($response->getContent())->not->toBe($result);
        }catch(Throwable $e){
            
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
			$this->handleCatchedException($e,$notification_string);
        }
    });

    test('search_return_wrong_data_with_wrong_input' ,function ()
    {
        try{
            /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
            $user = \App\Models\User::factory()->create();

            $userMeta = \App\Models\UserMeta::factory()->create();

            $hasUser = $user ? true : false;

            $user = \App\Models\User::orderBy('id', 'desc')->first();
            $user_meta = $user->id;
            $postJson = array(
                "agefrom" => "",
                "ageto" => "",
                "area" => "",
                "area2" => "",
                "area3" => "",
                "body" => "",
                "budget" => "",
                "city" => "",
                "city2" => "",
                "city3" => "",
                "cup" => "",
                "drinking" => "",
                "education" => "",
                "exchange_period" => "",
                "heightfrom" => "",
                "heightto" => "",
                "income" => "",
                "isAdvanceAuth" => "0",
                "isBlocked" => "1",
                "isPhoneAuth" => "",
                "isVip" => "",
                "isWarned" => "",
                "marriage" => "",
                "page" => "1",
                "perPageCount" => "12",
                "pic" => "",
                "prRange" => "wrong pr percent",
                "prRange_none" => "",
                "seqtime" => "1",
                "situation" => "",
                "smoking" => "",
                "tattoo" => "",
                "userIsAdvanceAuth" => "0",
                "userIsVip" => "1",
                "weight" => "",
                "umeta" => $user_meta,
                "user" => $user
            );
            $response = $this->actingAs($user)->postJson('/getSearchData', $postJson);

            $result = env("SEARCH_HAS_VALUE_RESULT");
            expect($response->getContent())->not->toBe($result);
        }catch(Throwable $e){
            
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
			$this->handleCatchedException($e,$notification_string);
        }
    });
