<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Visited;

class CreateVisitedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $oldVisit = DB::table('member_click')->get();

        Schema::create('visited', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')->unsigned();
            $table->integer('visited_id')->unsigned();

            $table->timestamps();
        });

        $count = 0;

        foreach($oldVisit as $visit) {
            $newVisit = new Visited();
            $newVisit->id = $visit->Id;
            $newVisit->member_id = $visit->Member_Id;
            $newVisit->visited_id = $visit->ClickMember_Id;
            $newVisit->save();
            //$count++;
            //if($count == 100) break;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visited');
    }
}
