<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Option extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_options_xref', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('option_type');
            $table->integer('option_id');
            $table->timestamps();
        });
        Schema::create('option_type', function (Blueprint $table) {
            $table->id();
            $table->string('type_name');
        });
        Schema::create('option_occupation', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
            $table->boolean('is_custom')->default(false);
        });
        Schema::create('option_relationship_status', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
        });
        Schema::create('option_looking_for_relationships', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
            $table->string('option_content');
        });
        Schema::create('option_expect', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
        });
        Schema::create('option_favorite_food', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
        });
        Schema::create('option_preferred_date_location', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
        });
        Schema::create('option_expected_type', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
            $table->string('option_content');
        });
        Schema::create('option_frequency_of_getting_along', function (Blueprint $table) {
            $table->id();
            $table->string('option_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_options_xref');
        Schema::dropIfExists('option_type');
        Schema::dropIfExists('option_occupation');
        Schema::dropIfExists('option_relationship_status');
        Schema::dropIfExists('option_looking_for_relationships');
        Schema::dropIfExists('option_expect');
        Schema::dropIfExists('option_favorite_food');
        Schema::dropIfExists('option_preferred_date_location');
        Schema::dropIfExists('option_expected_type');
        Schema::dropIfExists('option_frequency_of_getting_along');
    }
}
