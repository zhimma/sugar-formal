<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimilarImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('similar_images', function (Blueprint $table) {
            $table->id();
            $table->string('pic', 255)->nullable();
            $table->string('status', 10)->nullable();
            $table->longText('fullMatchingImages')->nullable();
            $table->longText('partialMatchingImages')->nullable();
            $table->longText('pagesWithMatchingImages')->nullable();
            $table->longText('visuallySimilarImages')->nullable();
            $table->longText('raw_request')->nullable();
            $table->longText('raw_response')->nullable();
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
        Schema::dropIfExists('similar_images');
    }
}
