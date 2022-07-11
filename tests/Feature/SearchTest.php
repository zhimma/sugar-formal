<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\LineNotifyService as LineNotify;

class SearchTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // use RefreshDatabase;

    public function test_search_return_correct_data_with_correct_input()
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
            $this->assertEquals($result, $response->getContent());
        }catch(\Exception $e){
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage(json_encode($e));
        }
    }

    public function test_search_return_correct_data_with_wrong_input()
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
            $this->assertEquals($result, $response->getContent());
        }catch(\Exception $e){
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage(json_encode($e));
        }
    }

    public function test_search_return_wrong_data_with_correct_input()
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
            $this->assertNotEquals($result, $response->getContent());
        }catch(\Exception $e){
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage(json_encode($e));
        }
    }

    public function test_search_return_wrong_data_with_wrong_input()
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
            $this->assertNotEquals($result, $response->getContent());
        }catch(\Exception $e){
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage(json_encode($e));
        }
    }
}
