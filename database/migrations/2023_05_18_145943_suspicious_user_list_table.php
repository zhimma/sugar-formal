<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SuspiciousUserListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('suspicious_user_list_table')) {
            Schema::create('suspicious_user_list_table', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->boolean('is_medium_long_term_without_adv_verification')->default(0);
                $table->timestamp('medium_long_term_without_adv_verification_created_at')->nullable();           
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suspicious_user_list_table');
    }
}
