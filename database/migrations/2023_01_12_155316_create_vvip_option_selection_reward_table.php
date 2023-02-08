<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVvipOptionSelectionRewardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('vvip_option_selection_reward')) {
            Schema::create('vvip_option_selection_reward', function (Blueprint $table) {
                $table->id();
                $table->string('option_name');
                $table->string('option_content')->nullable();
            });

            DB::table('vvip_option_selection_reward')->insert([
                ['option_name' => '皮膚白皙', 'option_content'=> '此條件可能會大幅提高審核金額'],
                ['option_name' => '身高170cm以上', 'option_content'=> '此條件可能會大大提高審核金額'],
                ['option_name' => '可配合daddy調整髮色/髮型', 'option_content'=> '需指定髮色/髮型供站方審核'],
                ['option_name' => 'BMI 18~24', 'option_content'=> '此條件可能會小幅提高審核金額'],
                ['option_name' => '能接受SM', 'option_content'=> null],
                ['option_name' => '身體柔軟/有瑜珈訓練', 'option_content'=> null],
                ['option_name' => '專業健美', 'option_content'=> null],
                ['option_name' => '特定職業空姐/護士等', 'option_content'=> '某些職業可能會大幅提高審核金額'],
                ['option_name' => '九頭身', 'option_content'=> null],
                ['option_name' => '會某種樂器(鋼琴/長笛等)', 'option_content'=> null],
                ['option_name' => '會某種舞蹈(芭蕾/國標/爵士等)', 'option_content'=> null],
            ]);
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vvip_option_selection_reward');
    }
}
