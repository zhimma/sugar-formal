<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\MemberPic;

class CreateMemberPicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $oldPicture = DB::table('member_picture')->get();

        Schema::create('member_pic', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')->unsigned();
            $table->string('pic');
            $table->timestamps();
        });

        foreach($oldPicture as $picture) {
            $newPicture = new MemberPic();
            $newPicture->id = $picture->Id;
            $newPicture->member_id = $picture->Member_Id;
            $newPicture->pic = '/img/Member/' . substr($picture->Pic, 0, 4) . '/' . substr($picture->Pic, 4, 2) . '/' . substr($picture->Pic, 6, 2) . '/' . $picture->Pic;
            $newPicture->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_pic', function (Blueprint $table) {
            //
        });
    }
}
