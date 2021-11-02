<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogAdvAuthApiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_adv_auth_api', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->date('birth')->nullable();
            $table->string('phone',100)->nullable()->default('NULL');
            $table->string('identity_no',100)->nullable()->default('NULL');
            $table->string('return_code',100)->nullable()->default('NULL');
            $table->string('return_fullcode',100)->nullable()->default('NULL');
            $table->timestamp('return_TimeStamp')->nullable();
            $table->text('return_response')->nullable();
            $table->tinyInteger('user_fault')->default(0);
            $table->tinyInteger('api_fault')->default(0);       
            $table->tinyInteger('forbid_user')->default(0);
            $table->tinyInteger('s_notify')->default(0);
            $table->tinyInteger('l_notify')->default(0);          
            $table->tinyInteger('pause_api')->default(0);           
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
        Schema::dropIfExists('log_adv_auth_api');
    }
}
