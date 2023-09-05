<?php

namespace App\Services;
use App\Models\User;
use Carbon\Carbon;

class SearchService
{
    public static function personal_page_recommend_popular_sweetheart()
    {
        $now_time = Carbon::now();
        $sweetheart = User::where('engroup', 2)
                            ->inRandomOrder()
                            ->limit(5)
                            ->get();
        return $sweetheart;
    }

    public static function personal_page_recommend_new_sweetheart()
    {
        $now_time = Carbon::now();
        $sweetheart = User::where('engroup', 2)
                            ->where('created_at', '>=', $now_time->subDays(30))
                            ->inRandomOrder()
                            ->limit(5)
                            ->get();
        return $sweetheart;       
    }
}