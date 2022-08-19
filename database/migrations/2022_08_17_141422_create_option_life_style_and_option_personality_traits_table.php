<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionLifeStyleAndOptionPersonalityTraitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_life_style', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
            $table->boolean('is_custom')->default(false);
        });
        Schema::create('option_personality_traits', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
            $table->boolean('is_custom')->default(false);
        });

        DB::table('option_life_style')->insert([
            ['option_name' => '外向'],
            ['option_name' => '宅在家'],
            ['option_name' => '踏青'],
            ['option_name' => '追劇'],
            ['option_name' => '電影迷'],
            ['option_name' => '動漫迷'],
            ['option_name' => '事業心強'],
            ['option_name' => '普通白領'],
            ['option_name' => '旅遊'],
            ['option_name' => '夜店咖'],
            ['option_name' => '運動健身風格'],
            ['option_name' => '哥德風'],
            ['option_name' => '氣質路線'],
            ['option_name' => '音樂人'],
            ['option_name' => '藝術性格'],
            ['option_name' => '夜貓子'],
            ['option_name' => '普通學生'],
        ]);

        DB::table('option_personality_traits')->insert([
            ['option_name' => '小女人'],
            ['option_name' => '女強人'],
            ['option_name' => '黏人'],
            ['option_name' => '獨立'],
            ['option_name' => 'BDSM(偏S)'],
            ['option_name' => 'BDSM(偏M)'],
            ['option_name' => '無主見'],
            ['option_name' => '主導性強'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('option_life_style');
        Schema::dropIfExists('option_personality_traits');
    }
}
