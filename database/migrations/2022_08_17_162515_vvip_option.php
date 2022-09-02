<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VvipOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vvip_option_xref', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('option_type');
            $table->integer('option_id');
            $table->string('option_remark')->nullable();
            $table->timestamps();
        });
        Schema::create('vvip_sub_option_xref', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('option_type');
            $table->integer('option_id');
            $table->string('option_remark')->nullable();
            $table->timestamps();
        });



        Schema::create('vvip_option_point_information', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
            $table->boolean('is_custom')->default(false);
        });
        Schema::create('vvip_option_date_trend', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
            $table->boolean('is_custom')->default(false);
        });
        Schema::create('vvip_option_background_and_assets', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
            $table->boolean('is_custom')->default(false);
        });
        Schema::create('vvip_option_extra_care', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
            $table->boolean('is_custom')->default(false);
        });
        Schema::create('vvip_option_assets_image', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
            $table->boolean('is_custom')->default(false);
        });
        Schema::create('vvip_option_quality_life_image', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
            $table->boolean('is_custom')->default(false);
        });
        Schema::create('vvip_option_expect_date', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
            $table->boolean('is_custom')->default(false);
        });



        Schema::create('vvip_sub_option_high_assets', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
            $table->boolean('is_custom')->default(false);
        });
        Schema::create('vvip_sub_option_ceo_title', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
            $table->boolean('is_custom')->default(false);
        });
        Schema::create('vvip_sub_option_professional', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
            $table->boolean('is_custom')->default(false);
        });
        Schema::create('vvip_sub_option_high_net_worth', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
            $table->boolean('is_custom')->default(false);
        });
        Schema::create('vvip_sub_option_entrepreneur', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
            $table->string('option_content');
            $table->boolean('is_custom')->default(false);
        });
        Schema::create('vvip_sub_option_professional_network', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
            $table->boolean('is_custom')->default(false);
        });
        Schema::create('vvip_sub_option_life_care', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
            $table->boolean('is_custom')->default(false);
        });
        Schema::create('vvip_sub_option_special_problem_handling', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
            $table->boolean('is_custom')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vvip_option_xref');
        Schema::dropIfExists('vvip_sub_option_xref');
        Schema::dropIfExists('vvip_option_type');

        Schema::dropIfExists('vvip_option_point_information');
        Schema::dropIfExists('vvip_option_date_trend');
        Schema::dropIfExists('vvip_option_background_and_assets');
        Schema::dropIfExists('vvip_option_extra_care');
        Schema::dropIfExists('vvip_option_assets_image');
        Schema::dropIfExists('vvip_option_quality_life_image');
        Schema::dropIfExists('vvip_option_expect_date');

        Schema::dropIfExists('vvip_sub_option_high_assets');
        Schema::dropIfExists('vvip_sub_option_ceo_title');
        Schema::dropIfExists('vvip_sub_option_professional');
        Schema::dropIfExists('vvip_sub_option_high_net_worth');
        Schema::dropIfExists('vvip_sub_option_entrepreneur');
        Schema::dropIfExists('vvip_sub_option_professional_network');
        Schema::dropIfExists('vvip_sub_option_life_care');
        Schema::dropIfExists('vvip_sub_option_special_problem_handling');
    }
}
