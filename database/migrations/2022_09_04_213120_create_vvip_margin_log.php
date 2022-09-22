<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVvipMarginLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vvip_margin_logs', function (Blueprint $table) {
            //
            $table->id();
            $table->bigInteger('user_id')->index('user_id');
            $table->bigInteger('balance_before')->default(0);
            $table->bigInteger('balance_after')->default(0);
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
        Schema::dropIfExists('vvip_margin_logs');
    }
}
