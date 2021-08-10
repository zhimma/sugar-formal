<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class refillML extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refillML';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $cfp_users = \App\Models\CFP_User::where('created_at', '>', '2021-06-21 15:22:00')->where('created_at', '<', '2021-06-21 21:58:00')->take(50)->get();
        $cfp = array();
        foreach ($cfp_users as $cfp_user){
            $position = array_search($cfp_user->cfp_id, $cfp);
            if(!$position) {
                $tmp_array[$cfp_user->cfp_id] = array();
                array_push($tmp_array[$cfp_user->cfp_id], $cfp_user->user_id);
                array_push($cfp, $tmp_array);
            }
            else{
                array_push($cfp[$position][$cfp_user->cfp_id], $cfp_user->user_id);
            }
        }
        dd($cfp);

//        function search($array, $key, $value) {
//            $results = array();
//
//            if (is_array($array)) {
//                if (isset($array[$key]) && $array[$key] == $value) {
//                    $results[] = $array;
//                }
//
//                foreach ($array as $subarray) {
//                    $results = array_merge($results, search($subarray, $key, $value));
//                }
//            }
//
//            return $results;
//        }
    }
}
