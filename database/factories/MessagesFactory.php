<?php

use Faker\Generator as Faker;
use App\Models\User;
use App\Models\Message_new;
use Carbon\Carbon;

$factory->define(Message_new::class, function (Faker $faker) {

	$from_id = factory(User::class)->create()->id;
	$to_id = factory(User::class)->create()->id;

    return [
        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        'to_id' =>  $from_id,
        'from_id' =>  $to_id,
  		'content' =>  $faker->text,
  		'read' =>  function() use ($from_id, $to_id){

  			$lastMessage = Message_new::betweenMessages([$from_id, $to_id])->first();
  			if($lastMessage && $lastMessage->from_id == $to_id)
  			{
  				//將上一個對方傳的訊息設為已讀, 且此訊息設為未讀
  				Message_new::update(['read'=>true])
  					->where('id', $lastMessage->id);
  				return 'Y';
  			}
  			else
  			{
  				return 'N';
  			}
  		},
  		'all_delete_count' =>  0,
  		'is_row_delete_1' =>  0,
  		'is_row_delete_2' =>  0,
  		'is_single_delete_1' =>  0,
  		'is_single_delete_2' =>  0,
  		'temp_id' => 0 ,
  		'isReported' =>  0,
  		'reportContent' => ""  
    ];
});
