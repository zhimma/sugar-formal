<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MenuItemFeature extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_menu_item_folder', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('folder_name');
            $table->timestamps();
        });
        Schema::create('admin_menu_item_xref', function (Blueprint $table) {
            $table->id();
            $table->integer('folder_id');
            $table->integer('item_id');
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
        Schema::dropIfExists('admin_menu_item_folder');
        Schema::dropIfExists('admin_menu_item_xref');
    }
}
