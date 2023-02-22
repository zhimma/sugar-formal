<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVvipSelectionRewardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('vvip_selection_reward')) {
            Schema::create('vvip_selection_reward', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->string('title', 255)->nullable()->comment('徵選主題');
                $table->text('condition')->nullable()->comment('徵選條件');
                $table->text('identify_method')->nullable()->comment('驗證方法');
                $table->text('bonus_distribution')->nullable()->comment('獎金發放');
                $table->tinyInteger('limit')->default(0)->comment('核定人數');
                $table->timestamp('expire_date')->nullable()->comment('徵選到期日');
                $table->decimal('per_person_price', 5,0)->default(10000)->comment('單人費用');
                $table->tinyInteger('status')->default(0)->comment('0:申請中; 1:通過; 2:不通過; 3:活動結束; 4:關閉;');
                $table->text('user_note')->nullable()->comment('使用者備註用');
                $table->text('note')->nullable()->comment('管理者備註用');
                $table->tinyInteger('notice_status')->default(0)->comment('0:尚未通知 1:已通知;');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent();
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
        Schema::dropIfExists('vvip_selection_reward');
    }
}
