<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageUsesScoutFeatureFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('features')->insert(
            array(
                'key' => 'message_uses_scout',
                'feature' => 'message_uses_scout',
                'description'=> '{"introduction":"\u8a0a\u606f\u4f7f\u7528\u65b0\u7248\u641c\u5c0b\u65b9\u5f0f","priority":"2"}',
                'active_at'=>null,
                'updated_at'=>now()
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_search_feature_flag');
    }
}

