<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCanceledByAdvAuthDateTableShortMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        if (!Schema::hasColumn('short_message', 'canceled_date')) {
            Schema::table('short_message', function (Blueprint $table) {
                $table->datetime('canceled_date')->nullable()->after('active');
            });
        }     

        if (!Schema::hasColumn('short_message', 'canceled_by')) {
            Schema::table('short_message', function (Blueprint $table) {
                $table->string('canceled_by',100)->nullable()->after('canceled_date');
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
        //
        Schema::table('short_message', function (Blueprint $table) {
            $table->dropColumn('canceled_by');
            $table->dropColumn('canceled_date');
        });  
    }
}
