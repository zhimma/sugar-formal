<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUserMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_meta', function (Blueprint $table) {
            if (!Schema::hasColumn('budget_per_month_max', 'budget_per_month_min', 'transport_fare_max', 'transport_fare_min')) {
                Schema::table('user_meta', function (Blueprint $table) {
                    $table->integer('budget_per_month_max')->nullable()->default(null)->after('budget');
                    $table->integer('budget_per_month_min')->nullable()->default(null)->after('budget_per_month_max');
                    $table->integer('transport_fare_max')->nullable()->default(null)->after('budget_per_month_min');
                    $table->integer('transport_fare_min')->nullable()->default(null)->after('transport_fare_max');
                });
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_meta', function (Blueprint $table) {
            Schema::table('user_meta', function (Blueprint $table) {
                $table->dropColumn('budget_per_month_max');
                $table->dropColumn('budget_per_month_min');
                $table->dropColumn('transport_fare_max');
                $table->dropColumn('transport_fare_min');
            });
        });
    }
}
