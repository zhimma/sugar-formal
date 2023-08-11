<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\UserMeta;

class FixIsPureDating extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        UserMeta::whereNull('is_pure_dating')->update(["is_pure_dating" => 0]);
        Schema::table('user_meta', function (Blueprint $table) {
            $table->integer('is_pure_dating')->default(0)->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
