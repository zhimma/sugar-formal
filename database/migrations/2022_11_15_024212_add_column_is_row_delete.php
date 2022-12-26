<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsRowDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('anonymous_evaluation_messages', 'is_row_delete_1')) {
            Schema::table('anonymous_evaluation_messages', function (Blueprint $table) {
                $table->tinyInteger('unsend')->default(0)->after('read');
                $table->integer('is_row_delete_1')->default(0)->after('unsend');
                $table->integer('is_row_delete_2')->default(0)->after('is_row_delete_1');
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
        if (Schema::hasColumn('anonymous_evaluation_messages', 'is_row_delete_1')) {
            Schema::table('anonymous_evaluation_messages', function (Blueprint $table) {
                $table->dropColumn('unsend');
                $table->dropColumn('is_row_delete_1');
                $table->dropColumn('is_row_delete_2');
            });
        }
    }
}
