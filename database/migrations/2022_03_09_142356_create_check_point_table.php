<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckPointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_points', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        DB::table('check_points')->insert(
            array(
                'name' => 'step_1_ischecked'
            )
        );

        Schema::create('check_point_user', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('check_point_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('check_points');
        Schema::dropIfExists('check_point_user');
    }
}
