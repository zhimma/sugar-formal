<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyRecommendSweetheart extends Model
{
    use HasFactory;

    protected $table = 'daily_recommend_sweetheart';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public static function get_daily_recommend_popular_sweetheart_query()
    {
        $sweetheart_list_query = DailyRecommendSweetheart::where('sweetheart_type', 'popular');
        return $sweetheart_list_query;
    }

    public static function get_daily_recommend_new_sweetheart_query()
    {
        $sweetheart_list_query = DailyRecommendSweetheart::where('sweetheart_type', 'new');
        return $sweetheart_list_query;
    }

}
