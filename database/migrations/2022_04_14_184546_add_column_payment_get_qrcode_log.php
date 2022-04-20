<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPaymentGetQrcodeLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('payment_get_barcode_log', function (Blueprint $table) {
            //
            if (!Schema::hasColumn('payment_get_barcode_log', 'payment_type')) {
                Schema::table('payment_get_barcode_log', function (Blueprint $table) {
                    $table->string('payment_type', 30)->nullable()->default(null)->after('TradeDate');
                    $table->timestamp('updated_at')->nullable()->default(null)->after('created_at');
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
        //
        Schema::table('payment_get_barcode_log', function (Blueprint $table) {
            //
            Schema::table('payment_get_barcode_log', function (Blueprint $table) {
                $table->dropColumn('payment_type');
            });
        });
    }
}
