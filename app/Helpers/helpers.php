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

if (! function_exists('test_notification')){
    function test_notification($className, $functionName, $line){
        return "【TEST SCHEDULER ERROR】: className:$className, functionName: $functionName, line: $line";
    }
}

// if (! function_exists('isset_variable')){
//     function isset_variable($variable){
//         return isset($variable) ? $variable: "";
//     }
// }

if (!function_exists('forPaginate')) {
    /**
     * @param array $items
     * @param integer $perPage
     * @param integer $page
     * @param array $options
     * @param integer|null $itemsPage
     * @param integer|null $count
     * 
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    function forPaginate($items, $perPage = 15, $page = null, $options = [], ?int $itemsPage = null, ?int $count = null)
    {
        $page = $page ?: (\Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof \Illuminate\Support\Collection || $items instanceof \Illuminate\Database\Eloquent\Collection ? $items : \Illuminate\Support\Collection::make($items);

        return new \Illuminate\Pagination\LengthAwarePaginator($items->forPage($itemsPage ?? $page, $perPage), $count ?? $items->count(), $perPage, $page, $options);
    }
}

if (!function_exists('getQueries')) {
    /** 
     * @return array
     */
    function getQueries()
    {
        return array_map(function ($queryLog) {
            $stringSQL = str_replace('?', '"%s"', $queryLog['query']);

            return sprintf($stringSQL, ...$queryLog['bindings']);
        }, \DB::getQueryLog());
    }
}

if (!function_exists('getLastQuery')) {
    /** 
     * @return array
     */
    function getLastQuery()
    {
        $queries = getQueries();

        return end($queries);
    }
}

if (!function_exists('strLimit')) {
    /** 
     * @return array
     */
    function strLimit($value, $limit = 100, $end = '...')
    {
        if (mb_strlen($value, 'UTF-8') <= $limit) {
            return $value;
        }

        return rtrim(mb_substr($value, 0, $limit, 'UTF-8')).$end;
    }
}