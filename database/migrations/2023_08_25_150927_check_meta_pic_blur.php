<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\MessageRoomUserXref;
use App\Models\MessageRoom;
use App\Models\Message;
class CheckMetaPicBlur extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        $meta_data = DB::table('user_meta')->whereNull('pic')->whereNotNull('pic_blur')->get();
        foreach($meta_data as $meta) {
            $blur_fullPath = '';
            $unlink_error_mark = '';
            if($meta->pic_blur) $blur_fullPath = public_path($meta->pic_blur);
            if($blur_fullPath && File::exists($blur_fullPath)) {
                if(!unlink($blur_fullPath)){
                    if(!unlink($blur_fullPath)){
                        if(!unlink($blur_fullPath)){
                            $unlink_error_mark = '.waiting_check';
                        }
                    }
                }
            }

            if($unlink_error_mark) {
                $meta->pic_blur= $meta->pic_blur.$unlink_error_mark;
            }
            else $meta->pic_blur = NULL;
            DB::table('user_meta')->where('id', $meta->id)->update(['pic_blur'=>$meta->pic_blur]);          
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('message_room', function (Blueprint $table) {
            //
        });
    }
}
