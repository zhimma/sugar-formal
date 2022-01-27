<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use DB;
use database\seeds;

class WeightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function addweight()
    {
        $weight = rand(0,20)*5;
        return $weight;
    }
    
    public function run()
    {
        //20000會跑5~10分鐘
        for($i = 0; $i < 20000; $i++)
        {
            $id = DB::table('user_meta')->where('weight',0)->first()->user_id;
            $id = json_decode($id, true); 
            DB::table('user_meta')->where('user_id',$id)->update(['weight'=>$this->addweight()]);
        }
    }
}
