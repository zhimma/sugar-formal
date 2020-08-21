<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Message;

class CreateMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $oldMessage = DB::table('member_message')->get();

        Schema::create('message', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('to_id')->unsigned();
            $table->integer('from_id')->unsigned();
            $table->text('content');
            $table->char('read', 1)->default('N');
            $table->integer('all_delete_count');
            $table->integer('is_row_delete_1');
            $table->integer('is_row_delete_2');
            $table->integer('is_single_delete_1');
            $table->integer('is_single_delete_2');
            $table->integer('temp_id');
        });

        $count = 0;

        foreach($oldMessage as $message) {
            $newMessage = new Message();
            $newMessage->id = $message->Id;
            $newMessage->from_id = $message->Member_Id;
            $newMessage->to_id = $message->To_Member_Id;
            $newMessage->content = $message->Content;
            if($message->Is_Read == NULL) $newMessage->read = 'N';
            else $newMessage->read = $message->Is_Read;
            $newMessage->all_delete_count = 0;
            $newMessage->is_row_delete_1 = 0;
            $newMessage->is_row_delete_2 = 0;
            $newMessage->is_single_delete_1 = 0;
            $newMessage->is_single_delete_2 = 0;
            $newMessage->temp_id = 0;
            $exists = DB::table('message')->where('from_id', $newMessage->from_id)->where('to_id', $newMessage->to_id)->where('content', $newMessage->content)->first();
            if($exists) continue;
            $newMessage->save();
            //$count++;
            //if($count == 100) break;
        }

        DB::statement("ALTER TABLE message AUTO_INCREMENT = 1;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message');
    }
}
