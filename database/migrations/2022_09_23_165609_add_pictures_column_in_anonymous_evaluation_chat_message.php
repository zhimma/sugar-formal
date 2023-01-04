<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPicturesColumnInAnonymousEvaluationChatMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('anonymous_evaluation_messages', 'pictures')) {
            Schema::table('anonymous_evaluation_messages', function (Blueprint $table) {
                $table->mediumText('pictures')->charset('utf8mb4')->collation('utf8mb4_general_ci')->nullable()->after('content');
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
        Schema::table('anonymous_evaluation_messages', function (Blueprint $table) {
            $table->dropColumn('pictures');
        });
    }
}
