<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPaymentGetBarcodeLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('payment_get_barcode_log', 'BankCode')) {
            Schema::table('payment_get_barcode_log', function (Blueprint $table) {
                $table->string('service_name', 128)->nullable()->after('user_id');
                $table->string('payment', 60)->nullable()->after('TradeDate');
                $table->string('BankCode', 3)->nullable()->after('payment_type');
                $table->string('vAccount', 16)->nullable()->after('BankCode');
                $table->string('PaymentNo', 14)->nullable()->after('vAccount');
                $table->string('Barcode1', 20)->nullable()->after('PaymentNo');
                $table->string('Barcode2', 20)->nullable()->after('Barcode1');
                $table->string('Barcode3', 20)->nullable()->after('Barcode2');
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
        if (Schema::hasColumn('payment_get_barcode_log', 'BankCode')) {
            Schema::table('payment_get_barcode_log', function (Blueprint $table) {
                $table->dropColumn('service_name');
                $table->dropColumn('payment');
                $table->dropColumn('BankCode');
                $table->dropColumn('vAccount');
                $table->dropColumn('PaymentNo');
                $table->dropColumn('Barcode1');
                $table->dropColumn('Barcode2');
                $table->dropColumn('Barcode3');
            });
        }
    }
}
