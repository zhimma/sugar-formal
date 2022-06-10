<?php
if (! function_exists("db_config")) {
    function db_config($key) {
        return \DB::table("queue_global_variables")->where("name", $key)->first()->value ?? config('social.send-email') ?? false;
    }
}


if (! function_exists('search_variable')){
    function search_variable($variable, $default=null){
        $search_page_key = 'search_page_key.'.$variable;
        if (isset($_POST[$variable])){
             $return_variable = $_POST[$variable];
        }elseif(isset($_GET[$variable])){
            $return_variable = $_GET[$variable];
        }elseif(!empty(session()->get($search_page_key))){
            $return_variable = session()->get($search_page_key);
        }else{
            if(!is_null($default)){
                $return_variable = $default;
            }
            
        }

        return $return_variable;
    }
}

// if (! function_exists('isset_variable')){
//     function isset_variable($variable){
//         return isset($variable) ? $variable: "";
//     }
// }