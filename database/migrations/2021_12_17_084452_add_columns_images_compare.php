<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsImagesCompare extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('images_compare_status', 'is_dsort')) {
            Schema::table('images_compare_status', function (Blueprint $table) {
                $table->tinyInteger('is_dsort')->default(0)->after('is_specific');
            });
        }  

        if (!Schema::hasColumn('images_compare_status', 'encode_id')) {
            Schema::table('images_compare_status', function (Blueprint $table) {
                $table->Integer('encode_id')->default(0)->after('id');
            });
        }          

        if (!Schema::hasColumn('images_compare', 'encode_id')) {
            Schema::table('images_compare', function (Blueprint $table) {
                $table->Integer('encode_id')->default(0)->after('id');
            });
        } 

        if (!Schema::hasColumn('images_compare', 'found_encode_id')) {
            Schema::table('images_compare', function (Blueprint $table) {
                $table->Integer('found_encode_id')->default(0)->after('pic');
            });
        } 

        if (!Schema::hasColumn('images_compare', 'found_pic')) {
            Schema::table('images_compare', function (Blueprint $table) {
                $table->string('found_pic')->nullable()->after('found_encode_id');
            });
        } 

        if (Schema::hasColumn('images_compare', 'found_pic')) {
            DB::update('update images_compare set found_pic = finded_pic ');
        }  
        $exist_encode = DB::table('images_compare_encode')->get();
        $hasEncodeIdCol = Schema::hasColumn('images_compare', 'encode_id');
        $hasFoundEncodeIdCol = Schema::hasColumn('images_compare', 'found_encode_id');
        $hasStatusEncodeIdCol = Schema::hasColumn('images_compare_status', 'encode_id');
        foreach($exist_encode  as $encode) {
            if($hasEncodeIdCol) {
                DB::update("update images_compare set encode_id =  ".$encode->id." where pic='".$encode->pic."'");
            }
            
            if($hasFoundEncodeIdCol) {
                DB::update("update images_compare set found_encode_id =  ".$encode->id." where finded_pic='".$encode->pic."'");
            }

            if($hasStatusEncodeIdCol) {
                DB::update("update images_compare_status set  encode_id =  ".$encode->id." where pic='".$encode->pic."'");
            }
        }
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('images_compare_status', function (Blueprint $table) {
            $table->dropColumn('is_dsort');
        });
        
        Schema::table('images_compare', function (Blueprint $table) {
            $table->dropColumn('encode_id');
            $table->dropColumn('found_encode_id');
            $table->dropColumn('found_pic');
        });        


    }
}
