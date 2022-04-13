<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorIDTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('visitor_id')) {
            Schema::create('visitor_id', function (Blueprint $table) {
                $table->id();
                $table->string('hash', 255);
                $table->string('host', 255)->nullable()->default(NULL);
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('visitor_id_user')) {
            Schema::create('visitor_id_user', function (Blueprint $table) {
                $table->id();
                $table->integer('visitor_id')->nullable()->default(NULL);
                $table->integer('user_id');
                $table->timestamps();
            });
        }

        if(!Schema::hasColumn('log_user_login', 'visitor_id')) {
            DB::statement('ALTER TABLE `log_user_login` ADD `visitor_id` int(11) unsigned DEFAULT NULL AFTER `cfp_id`, ALGORITHM = INPLACE, LOCK=NONE;');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitor_id');
        Schema::dropIfExists('visitor_id_User');
    }
}
