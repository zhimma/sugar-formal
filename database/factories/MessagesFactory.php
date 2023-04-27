<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Message_new;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessagesFactory extends Factory
{
    protected $model = Message_new::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();
        $from_id = User::factory()->create()->id;
        $to_id = User::factory()->create()->id;
        UserMeta::factory()->create(['user_id'=>$from_id]);
        UserMeta::factory()->create(['user_id'=>$to_id]);
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
    }
}
