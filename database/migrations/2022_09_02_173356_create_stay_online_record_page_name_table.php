<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PaymentFlowChoose;
use Illuminate\Support\Facades\DB;

class CreateStayOnlineRecordPageNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('stay_online_reocrd_page_name')) {
            Schema::create('stay_online_record_page_name', function (Blueprint $table) {
                $table->id();
                $table->text('url')->nullable()->index();
                $table->string('name', 255)->nullable();
                $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('stay_online_record_page_name');
    }
}
