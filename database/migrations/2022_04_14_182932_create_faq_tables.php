<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaqTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('faq_groups')) {
            Schema::create('faq_groups', function (Blueprint $table) {
                $table->id();
                $table->string('name',255)->nullable();
                $table->integer('engroup')->nullable()->default(0);
                $table->integer('is_vip')->nullable()->default(-1);
                $table->integer('faq_login_times')->nullable()->default(0);
                $table->boolean('has_answer')->default(0);
                $table->boolean('act')->default(0);
                $table->timestamp('act_at')->nullable();       
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
                $table->index(['engroup','is_vip','act','faq_login_times']);
            });
        }
        
        if (!Schema::hasTable('faq_questions')) {
            Schema::create('faq_questions', function (Blueprint $table) {
                $table->id();
                $table->integer('group_id')->index();
                $table->string('type')->nullable();
                $table->text('question')->nullable();
                $table->boolean('answer_bit')->nullable();
                $table->text('answer_context')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
            });
        } 

        if (!Schema::hasTable('faq_choices')) {
            Schema::create('faq_choices', function (Blueprint $table) {
                $table->id();
                $table->integer('question_id')->index();
                $table->string('name',255)->nullable();
                $table->boolean('is_answer')->nullable()->default(0);
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
            });
        }    

        if (!Schema::hasTable('faq_user_groups')) {
            Schema::create('faq_user_groups', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->index();
                $table->integer('group_id')->index();
                $table->boolean('is_pass')->default(0)->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
                $table->index(['group_id','is_pass']);
                $table->index(['user_id','is_pass']);
                $table->index(['user_id', 'group_id','is_pass']);
                $table->index(['group_id', 'user_id','is_pass']);
            });             
        }

        if (!Schema::hasTable('faq_user_replies')) {        
            Schema::create('faq_user_replies', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->index();
                $table->integer('question_id')->nullable()->index();
                $table->integer('choice_id')->nullable()->index();
                $table->string('reply_choices',100)->nullable();
                $table->boolean('reply_bit')->nullable();
                $table->text('reply_context')->nullable();
                $table->boolean('is_pass')->default(0)->nullable()->index();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
                $table->index(['question_id','is_pass']);
                 $table->index(['user_id','is_pass']);
                $table->index(['user_id', 'question_id','is_pass']);
            });                   
        } 

        if (!Schema::hasTable('faq_group_act_log')) {
            Schema::create('faq_group_act_log', function (Blueprint $table) {
                $table->id();
                $table->integer('group_id')->index();
                $table->boolean('act')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
            });
        }  

        if (!Schema::hasTable('faq_setting')) {
            Schema::create('faq_setting', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name',100)->unique();
                $table->string('value',100);	                
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
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
        Schema::dropIfExists('faq_groups');
        Schema::dropIfExists('faq_questions');
        Schema::dropIfExists('faq_choices');
        Schema::dropIfExists('faq_user_groups');
        Schema::dropIfExists('faq_user_replies');
        Schema::dropIfExists('faq_group_act_log');        
        Schema::dropIfExists('faq_setting');    
    }
}
