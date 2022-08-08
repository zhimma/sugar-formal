<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PaymentFlowChoose;
use Illuminate\Support\Facades\DB;

class CreatePaymentFlowChooseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('payment_flow_choose')) {
            Schema::create('payment_flow_choose', function (Blueprint $table) {
                $table->id();
                $table->string('payment_text', 50);
                $table->string('payment', 50);
                $table->tinyInteger('status')->default(1);
                $table->string('icon', 50)->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            });
        }

        if(!PaymentFlowChoose::where('payment_text', '付款方式1')->first()){
            $record = new PaymentFlowChoose;
            $record->payment_text = '付款方式1';
            $record->payment = 'funpoint';
            $record->status = 1;
            $record->icon = '/new/images/zh12.png';
            $record->save();
        }
        if(!PaymentFlowChoose::where('payment_text', '付款方式2')->first()){
            $record = new PaymentFlowChoose;
            $record->payment_text = '付款方式2';
            $record->payment = 'ecpay';
            $record->status = 1;
            $record->icon = '/new/images/zh13.png';
            $record->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_flow_choose');
    }
}
