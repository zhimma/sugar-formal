<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\MemberFav;

class CreateMemberFavTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $oldFav = DB::table('member_favorite')->get();

        Schema::create('member_fav', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')->unsigned();
            $table->integer('member_fav_id')->unsigned();
            $table->timestamps();
        });

        foreach($oldFav as $fav) {
            $newFav = new MemberFav();
            $newFav->id = $fav->Id;
            $newFav->member_id = $fav->Member_Id;
            $newFav->member_fav_id = $fav->FavoriteMember_Id;
            $newFav->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_fav');
    }
}
