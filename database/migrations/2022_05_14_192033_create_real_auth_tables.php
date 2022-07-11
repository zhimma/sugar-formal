<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRealAuthTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        if (!Schema::hasTable('real_auth_questions')) {
            Schema::create('real_auth_questions', function (Blueprint $table) {
                $table->id();
                $table->integer('auth_type_id')->index();
                $table->integer('parent_id')->nullable()->index();
                $table->string('type')->nullable();
                $table->text('question')->nullable();
                $table->boolean('required')->nullable()->default(0)->index();
                $table->boolean('has_children')->nullable()->default(0)->index();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
            });
        } 
        
        \Illuminate\Support\Facades\DB::table('real_auth_questions')->insert([
            ['id'=>1,'auth_type_id'=>2,'parent_id'=>null,'required'=>1,'type'=>'是非','has_children'=>0,'question'=>'是否單一daddy','created_at'=>\Carbon\Carbon::now()]
            ,['id'=>2,'auth_type_id'=>2,'parent_id'=>null,'required'=>1,'type'=>'是非','has_children'=>0,'question'=>'目前是否有交往/曖昧中的異性','created_at'=>\Carbon\Carbon::now()]
            ,['id'=>3,'auth_type_id'=>2,'parent_id'=>null,'required'=>1,'type'=>'單選','has_children'=>0,'question'=>'包養關係持續中，如果有遇上其他異性','created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>4,'auth_type_id'=>2,'parent_id'=>null,'required'=>1,'type'=>'單選','has_children'=>0,'question'=>'生活作息','created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>5,'auth_type_id'=>2,'parent_id'=>null,'required'=>0,'type'=>null,'has_children'=>1,'question'=>'其他加分資料','created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>6,'auth_type_id'=>2,'parent_id'=>5,'required'=>0,'type'=>'簡答','has_children'=>0,'question'=>'☆ 多人追蹤的社群帳號','created_at'=>\Carbon\Carbon::now()]
            ,['id'=>7,'auth_type_id'=>2,'parent_id'=>5,'required'=>0,'type'=>'上傳','has_children'=>0,'question'=>'☆ 兼差展場show girl','created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>8,'auth_type_id'=>2,'parent_id'=>5,'required'=>0,'type'=>'簡答','has_children'=>0,'question'=>'☆ 校花/系花','created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>9,'auth_type_id'=>2,'parent_id'=>5,'required'=>0,'type'=>'簡答','has_children'=>0,'question'=>'☆ 曾上Ptt or Dcard 表特版 (或是其他論壇)','created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>10,'auth_type_id'=>2,'parent_id'=>5,'required'=>0,'type'=>null,'has_children'=>0,'question'=>'☆ 在學證明','created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>11,'auth_type_id'=>2,'parent_id'=>5,'required'=>0,'type'=>null,'has_children'=>0,'question'=>'☆ 工作證明','created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>12,'auth_type_id'=>2,'parent_id'=>5,'required'=>0,'type'=>null,'has_children'=>0,'question'=>'☆ 其他可加分事項','created_at'=>\Carbon\Carbon::now()] 

            ,['id'=>13,'auth_type_id'=>3,'parent_id'=>null,'required'=>0,'type'=>'簡答','has_children'=>0,'question'=>'FB/IG 超過 5000 人追蹤','created_at'=>\Carbon\Carbon::now()]
            ,['id'=>14,'auth_type_id'=>3,'parent_id'=>null,'required'=>0,'type'=>null,'has_children'=>0,'question'=>'曾參與超過三場以上走秀/演出','created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>15,'auth_type_id'=>3,'parent_id'=>null,'required'=>0,'type'=>'簡答','has_children'=>0,'question'=>'公眾人物','created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>16,'auth_type_id'=>3,'parent_id'=>null,'required'=>0,'type'=>'簡答','has_children'=>0,'question'=>'公認校花/系花','created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>17,'auth_type_id'=>3,'parent_id'=>null,'required'=>0,'type'=>null,'has_children'=>0,'question'=>'其他特殊條件','created_at'=>\Carbon\Carbon::now()]                
        ]);            

        if (!Schema::hasTable('real_auth_choices')) {
            Schema::create('real_auth_choices', function (Blueprint $table) {
                $table->id();
                $table->integer('question_id')->nullable()->index();
                $table->integer('parent_id')->nullable()->index();
                $table->string('type')->nullable();
                $table->string('name',255)->nullable();
                $table->string('placeholder',255)->nullable();
                $table->boolean('has_children')->nullable()->default(0)->index();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
            });
        }
        
        \Illuminate\Support\Facades\DB::table('real_auth_choices')->insert([
            ['id'=>1,'question_id'=>3,'parent_id'=>null,'type'=>null,'name'=>'A:通知daddy 雙方協商後續','placeholder'=>null,'has_children'=>0,'created_at'=>\Carbon\Carbon::now()]
            ,['id'=>2,'question_id'=>3,'parent_id'=>null,'type'=>null,'name'=>'B:中止包養關係','placeholder'=>null,'has_children'=>0,'created_at'=>\Carbon\Carbon::now()]
            ,['id'=>3,'question_id'=>4,'parent_id'=>null,'type'=>null,'name'=>'A:正常上班族，晚上及周末有空','placeholder'=>null,'has_children'=>0,'created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>4,'question_id'=>4,'parent_id'=>null,'type'=>null,'name'=>'B:學生，正常晚上及周末有空，周間看課表','placeholder'=>null,'has_children'=>0,'created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>5,'question_id'=>4,'parent_id'=>null,'type'=>null,'name'=>'C:排班人，可每月提供班表','placeholder'=>null,'has_children'=>0,'created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>6,'question_id'=>4,'parent_id'=>null,'type'=>null,'name'=>'D:其他不固定作息時間','placeholder'=>null,'has_children'=>1,'created_at'=>\Carbon\Carbon::now()]
            ,['id'=>7,'question_id'=>4,'parent_id'=>6,'type'=>'簡答','name'=>null,'placeholder'=>'請輸入作息時間','has_children'=>0,'created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>8,'question_id'=>6,'parent_id'=>null,'type'=>null,'name'=>null,'placeholder'=>'請輸入連結','has_children'=>0,'created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>9,'question_id'=>8,'parent_id'=>null,'type'=>null,'name'=>null,'placeholder'=>'請輸入學校名稱','has_children'=>0,'created_at'=>\Carbon\Carbon::now()]  
            ,['id'=>10,'question_id'=>8,'parent_id'=>null,'type'=>null,'name'=>null,'placeholder'=>'請輸入系級','has_children'=>0,'created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>11,'question_id'=>8,'parent_id'=>null,'type'=>null,'name'=>null,'placeholder'=>'請輸入真實姓名','has_children'=>0,'created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>12,'question_id'=>9,'parent_id'=>null,'type'=>null,'name'=>null,'placeholder'=>'請輸入連結','has_children'=>0,'created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>13,'question_id'=>10,'parent_id'=>null,'type'=>'問答','name'=>null,'placeholder'=>'請輸入','has_children'=>0,'created_at'=>\Carbon\Carbon::now()] 
            ,['id'=>14,'question_id'=>10,'parent_id'=>null,'type'=>'上傳','name'=>null,'placeholder'=>null,'has_children'=>0,'created_at'=>\Carbon\Carbon::now()]
            ,['id'=>15,'question_id'=>11,'parent_id'=>null,'type'=>'問答','name'=>null,'placeholder'=>'請輸入','has_children'=>0,'created_at'=>\Carbon\Carbon::now()] 
            ,['id'=>16,'question_id'=>11,'parent_id'=>null,'type'=>'上傳','name'=>null,'placeholder'=>null,'has_children'=>0,'created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>17,'question_id'=>12,'parent_id'=>null,'type'=>'問答','name'=>null,'placeholder'=>'請輸入','has_children'=>0,'created_at'=>\Carbon\Carbon::now()] 
            ,['id'=>18,'question_id'=>12,'parent_id'=>null,'type'=>'上傳','name'=>null,'placeholder'=>null,'has_children'=>0,'created_at'=>\Carbon\Carbon::now()] 
            
            ,['id'=>19,'question_id'=>13,'parent_id'=>null,'type'=>null,'name'=>null,'placeholder'=>'請輸入連結','has_children'=>0,'created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>20,'question_id'=>14,'parent_id'=>null,'type'=>'簡答','name'=>null,'placeholder'=>'請輸入連結','has_children'=>0,'created_at'=>\Carbon\Carbon::now()] 
            ,['id'=>21,'question_id'=>14,'parent_id'=>null,'type'=>'上傳','name'=>null,'placeholder'=>null,'has_children'=>0,'created_at'=>\Carbon\Carbon::now()]            
            ,['id'=>22,'question_id'=>15,'parent_id'=>null,'type'=>null,'name'=>null,'placeholder'=>'請輸入姓名或外號','has_children'=>0,'created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>23,'question_id'=>16,'parent_id'=>null,'type'=>null,'name'=>null,'placeholder'=>'請輸入學校名稱','has_children'=>0,'created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>24,'question_id'=>16,'parent_id'=>null,'type'=>null,'name'=>null,'placeholder'=>'請輸入系級','has_children'=>0,'created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>25,'question_id'=>16,'parent_id'=>null,'type'=>null,'name'=>null,'placeholder'=>'請輸入真實姓名','has_children'=>0,'created_at'=>\Carbon\Carbon::now()]               
            ,['id'=>26,'question_id'=>17,'parent_id'=>null,'type'=>'問答','name'=>null,'placeholder'=>'請輸入','has_children'=>0,'created_at'=>\Carbon\Carbon::now()] 
            ,['id'=>27,'question_id'=>17,'parent_id'=>null,'type'=>'上傳','name'=>null,'placeholder'=>null,'has_children'=>0,'created_at'=>\Carbon\Carbon::now()]         
        ]);         

        if (!Schema::hasTable('real_auth_user_replies')) {        
            Schema::create('real_auth_user_replies', function (Blueprint $table) {
                $table->id();
                //$table->integer('user_id')->index();
                //$table->integer('apply_id')->index();
                //$table->integer('from_modify_id')->nullable()->index();
                $table->integer('modify_id')->nullable()->index();
                $table->integer('question_id')->nullable()->index();
                $table->integer('choice_id')->nullable()->index();
                $table->integer('pic_choice_id')->nullable()->index();
                $table->string('reply_choices',100)->nullable();
                $table->boolean('reply_bit')->nullable();
                $table->text('reply_context')->nullable();
                $table->string('context_choices',100)->nullable();                
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
                $table->index(['modify_id', 'question_id']);
            });                   
        } 

        if (!Schema::hasTable('real_auth_user_reply_pic')) {        
            Schema::create('real_auth_user_reply_pic', function (Blueprint $table) {
                $table->increments('id');
                //$table->integer('apply_id')->index();                
                $table->integer('reply_id')->index();
                $table->string('pic', 255);
                $table->string('pic_origin_name', 255);
                $table->softDeletes();
                $table->nullableTimestamps();
            });                 
        } 

        if (!Schema::hasTable('real_auth_user_applies')) {        
            Schema::create('real_auth_user_applies', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->index();
                $table->integer('auth_type_id')->index();
                //$table->integer('video_record_id')->nullable()->index();
                $table->integer('height_modify_id')->nullable()->index();
                $table->integer('weight_modify_id')->nullable()->index();
                $table->integer('exchange_period_modify_id')->nullable()->index();
                $table->integer('pic_modify_id')->nullable()->index();
                $table->integer('video_modify_id')->nullable()->index();                
                $table->integer('reply_modify_id')->nullable()->index();                
                //$table->integer('state_id')->nullable()->default(0)->index();
                //$table->integer('apply_times')->nullable()->default(0);
                //$table->smallInteger('exchange_period')->nullable();
                //$table->integer('height')->nullable();
                //$table->integer('weight')->nullable();               
                //$table->integer('avatar_num')->nullable()->default(0);
                //$table->integer('mem_pic_num')->nullable()->default(0);
                $table->boolean('from_auto')->nullable()->default(0);
                $table->smallInteger('status')->nullable()->default(0);
                //$table->boolean('from_admin')->nullable()->default(0);
                //$table->integer('from_admin_apply_id')->nullable()->default(0);
                
//                $table->timestamp('apply_at')->nullable();
                $table->timestamp('status_at')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
                $table->index(['user_id', 'auth_type_id']);
            });                   
        } 
        
        
        if (!Schema::hasTable('real_auth_user_apply_log')) {        
            Schema::create('real_auth_user_apply_log', function (Blueprint $table) {
                $table->id();
                $table->integer('apply_id')->index();
                $table->integer('user_id')->index();
                $table->integer('auth_type_id')->index();                
                $table->integer('height_modify_id')->nullable()->index();
                $table->integer('weight_modify_id')->nullable()->index();
                $table->integer('exchange_period_modify_id')->nullable()->index();                
                $table->integer('pic_modify_id')->nullable()->index();
                $table->integer('video_modify_id')->nullable()->index();
                $table->integer('reply_modify_id')->nullable()->index();
                //$table->integer('video_record_id')->nullable()->index();
                //$table->integer('state_id')->nullable()->default(0)->index();
                //$table->integer('apply_times')->nullable()->default(0);
                //$table->smallInteger('exchange_period')->nullable();
                //$table->integer('height')->nullable();
                //$table->integer('weight')->nullable();               
                //$table->integer('avatar_num')->nullable()->default(0);
                //$table->integer('mem_pic_num')->nullable()->default(0);
                $table->smallInteger('status')->nullable()->default(0);
                //$table->boolean('from_admin')->nullable()->default(0);
                //$table->integer('from_admin_apply_id')->nullable()->default(0);
                $table->boolean('from_auto')->nullable()->default(0);
//                $table->timestamp('apply_at')->nullable();
                $table->timestamp('status_at')->nullable();
                $table->timestamp('apply_created_at')->nullable();
                $table->timestamp('apply_updated_at')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
                $table->index(['user_id', 'auth_type_id']);
            });                   
        }         
        
        if (!Schema::hasTable('real_auth_type')) {
            Schema::create('real_auth_type', function (Blueprint $table) {
                $table->increments('id'); 
                $table->string('name',100)->nullable();                             	            
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            });
        }
        
        \Illuminate\Support\Facades\DB::table('real_auth_type')->insert([
            ['id'=>1,'name'=>'本人認證','created_at'=>\Carbon\Carbon::now()]
            ,['id'=>2,'name'=>'美顏推薦','created_at'=>\Carbon\Carbon::now()]
            ,['id'=>3,'name'=>'名人認證','created_at'=>\Carbon\Carbon::now()]          
        ]);           
/*
        if (!Schema::hasTable('real_auth_user_modify_item')) {
            Schema::create('real_auth_user_modify_item', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->index();
                $table->integer('auth_type_id')->index();
                $table->integer('item_id')->index();
                $table->integer('modify_id')->index();
                $table->smallInteger('state')->nullable()->default(0)->index();
                $table->integer('modify_times')->nullable()->default(0);
                //$table->timestamp('modify_at')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
                //$table->index(['user_id','auth_type_id', 'item_id','modify_id']);
                //$table->index(['user_id', 'item_id','auth_type_id','modify_id']);
            });
        }
*/        
        if (!Schema::hasTable('real_auth_modify_item')) {
            Schema::create('real_auth_modify_item', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name',100)->nullable();                             	            
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            });
        }        
        
        \Illuminate\Support\Facades\DB::table('real_auth_modify_item')->insert([
        /*
            ['id'=>1,'auth_type_id'=>0,'show_auth_type_id'=>2,'name'=>'照片新增','created_at'=>\Carbon\Carbon::now()]
            ,['id'=>2,'auth_type_id'=>0,'show_auth_type_id'=>2,'name'=>'照片刪除','created_at'=>\Carbon\Carbon::now()]
            ,['id'=>3,'auth_type_id'=>0,'show_auth_type_id'=>2,'name'=>'重錄視訊','created_at'=>\Carbon\Carbon::now()] 
            ,['id'=>4,'auth_type_id'=>2,'show_auth_type_id'=>0,'name'=>'表格異動','created_at'=>\Carbon\Carbon::now()]   
            ,['id'=>5,'auth_type_id'=>3,'show_auth_type_id'=>0,'name'=>'表格異動','created_at'=>\Carbon\Carbon::now()]                      
        */
            ['id'=>1,'name'=>'新申請','created_at'=>\Carbon\Carbon::now()]
            ,['id'=>2,'name'=>'基本資料','created_at'=>\Carbon\Carbon::now()]
            ,['id'=>3,'name'=>'照片新增','created_at'=>\Carbon\Carbon::now()]
            //,['id'=>3,'name'=>'照片刪除','created_at'=>\Carbon\Carbon::now()]
            ,['id'=>4,'name'=>'重錄視訊','created_at'=>\Carbon\Carbon::now()] 
            ,['id'=>5,'name'=>'表格異動','created_at'=>\Carbon\Carbon::now()]             
        ]);         
        
        if (!Schema::hasTable('real_auth_user_modify')) 
        {        
            Schema::create('real_auth_user_modify', function (Blueprint $table) {
                $table->id();
                //$table->integer('user_id')->index();
                //$table->integer('auth_type_id')->index();
                //$table->integer('modify_item_id')->index();
                $table->integer('item_id')->index();
                $table->integer('apply_id')->index();
                $table->integer('apply_status_shot')->nullable()->index();
                $table->integer('patch_id_shot')->nullable()->index();


                $table->string('now_height',255)->nullable();                
                $table->string('old_height',255)->nullable();
                $table->string('new_height',255)->nullable();
                $table->string('now_weight',255)->nullable();                
                $table->string('old_weight',255)->nullable();
                $table->string('new_weight',255)->nullable(); 
                $table->string('now_exchange_period',255)->nullable()->index();                
                $table->string('old_exchange_period',255)->nullable()->index();
                $table->string('new_exchange_period',255)->nullable()->index();                
                $table->integer('now_avatar_num')->nullable();
                $table->integer('old_avatar_num')->nullable();
                $table->integer('new_avatar_num')->nullable();
                $table->integer('now_mem_pic_num')->nullable();                
                $table->integer('old_mem_pic_num')->nullable();                
                $table->integer('new_mem_pic_num')->nullable();                
                $table->integer('now_video_record_id')->nullable()->index();
                $table->integer('old_video_record_id')->nullable()->index();
                $table->integer('new_video_record_id')->nullable()->index();                
                $table->boolean('has_reply')->default(0);
                $table->boolean('from_auto')->nullable()->default(0);
                $table->boolean('status')->default(0);
                //$table->integer('modify_times')->nullable()->default(0);
                //$table->boolean('is_pass')->nullable()->default(0);
                $table->timestamp('status_at')->nullable();
                $table->softDeletes();                
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
                //$table->index(['user_id', 'item_id']);
            });                   
        } 

        if (!Schema::hasTable('real_auth_user_modify_pic')) 
        {        
            Schema::create('real_auth_user_modify_pic', function (Blueprint $table) {
                $table->id();
                $table->integer('modify_id')->index();
                //$table->smallInteger('state')->nullable()->default(0)->index();
                $table->boolean('operate')->default(0);
                $table->string('pic_cat',100)->nullable()->index();
                $table->string('old_pic',255)->nullable()->index();                
                $table->string('pic',255)->nullable()->index();
                $table->string('original_name', 255)->nullable()->index();
                $table->boolean('isHidden')->default(0);
                $table->softDeletes();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
            });                   
        }  
        
        if (!Schema::hasTable('real_auth_user_patch')) {        
            Schema::create('real_auth_user_patch', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->index();
                $table->integer('message_id')->nullable()->index();
                $table->integer('auth_type_id')->nullable()->index();
                $table->integer('item_id')->nullable()->index();
                $table->integer('apply_id_shot')->nullable()->index();
                $table->integer('apply_status_shot')->nullable()->index();
                $table->integer('modify_id')->nullable()->index();                
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
                $table->index(['user_id', 'auth_type_id']);
            });                   
        }         

/*
        if (!Schema::hasTable('real_auth_user_modify_profile')) 
        {        
            Schema::create('real_auth_user_modify_profile', function (Blueprint $table) {
                $table->id();
                $table->integer('modify_id')->index();
                $table->string('old_exchange_period',255)->nullable()->index();
                $table->string('new_exchange_period',255)->nullable()->index();
                $table->string('old_height',255)->nullable()->index();
                $table->string('new_height',255)->nullable()->index();  
                $table->string('old_weight',255)->nullable()->index();
                $table->string('new_weight',255)->nullable()->index();               
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
            });                   
        }  
*/        
     
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('real_auth_questions');
        Schema::dropIfExists('real_auth_choices');
        Schema::dropIfExists('real_auth_user_replies');
        Schema::dropIfExists('real_auth_user_applies');
        Schema::dropIfExists('real_auth_user_apply_log');       
        Schema::dropIfExists('real_auth_type');  
        Schema::dropIfExists('real_auth_modify_item'); 
        Schema::dropIfExists('real_auth_user_modify'); 
        Schema::dropIfExists('real_auth_user_modify_item');         
        Schema::dropIfExists('real_auth_user_reply_pic');   
        Schema::dropIfExists('real_auth_user_modify_pic');
        Schema::dropIfExists('real_auth_user_modify_profile');        
        Schema::dropIfExists('real_auth_user_patch');
    }
}
