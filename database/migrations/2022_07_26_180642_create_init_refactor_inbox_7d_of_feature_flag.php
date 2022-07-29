<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInitRefactorInbox7dOfFeatureFlag extends Migration
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
                'key' => 'inbox-7-days',
                'feature' => 'inbox-7-days',
                'description'=> '{"introduction":"\u4f7f\u7528 branch: refactor_inbox_7d \u524d\u5f8c\u5207\u63db (on: \u4f7f\u7528\u524d, off: \u4f7f\u7528\u5f8c)","priority":"1"}',
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
        Schema::dropIfExists('init_refactor_inbox_7d_of_feature_flag');
    }
}
