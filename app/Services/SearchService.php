<?php

namespace App\Services;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SearchService
{
    public static function get_user_search_page_key()
    {
        $search_page_key = [];

        if(session()->get('search_page_key',[]) ?? false)
        {
            $search_page_key = session()->get('search_page_key',[]);
        }
        elseif(auth()->user()->search_filter_remember)
        {
            $search_page_key = json_decode(auth()->user()->search_filter_remember?->filter, true);
        }
        else
        {
            $search_page_key = [];
        }


        return $search_page_key;
    }

    public static function get_user_search_country_and_district()
    {
        $search_page_key = SearchService::get_user_search_page_key();

        $county_key_list = ['county','county2','county3','county4','county5'];
        $district_key_list = ['district','district2','district3','district4','district5'];

        $search_county = [];
        $search_district = [];

        foreach($county_key_list as $key => $county_key)
        {
            if($search_page_key[$county_key] ?? false)
            {
                $search_county[] = $search_page_key[$county_key];
                $search_district[] = $search_page_key[$district_key_list[$key]];
            }
        }

        return [$search_county, $search_district];
    }

    public static function get_user_search_constraint()
    {
        $country_and_district_list = SearchService::get_user_search_country_and_district();
        $country_list = $country_and_district_list[0];
        $district_list = $country_and_district_list[1];

        $constraint = function($query) use($country_list, $district_list){
            $query->where(function($query) use($country_list, $district_list){
                foreach($country_list as $key => $country)
                {
                    $district = $district_list[$key];
                    if($country)
                    {
                        $query->orWhere(function($query) use ($country,$district) {
                            if($district) {
                                $query->whereRaw('SUBSTRING_INDEX(city,",", 1) like "%'.$country.'%" AND SUBSTRING_INDEX(area,",", 1) like "%'.$district.'%"');
                            }else{
                                $query->where('city','like','%'.$country.'%');
                            }
                        });
                        $query->orWhere(function($query) use ($country,$district) {
                            if($district) {
                                $query->whereRaw('SUBSTRING_INDEX(SUBSTRING_INDEX(city,",", 2),",",-1) like "%'.$country.'%" AND SUBSTRING_INDEX(SUBSTRING_INDEX(area,",", 2),",",-1) like "%'.$district.'%"');
                            }else{
                                $query->where('city','like','%'.$country.'%');
                            }
                        });
                        $query->orWhere(function($query) use ($country,$district) {
                            if($district) {
                                $query->whereRaw('SUBSTRING_INDEX(city,",", -1) like "%'.$country.'%" AND SUBSTRING_INDEX(area,",", -1) like "%'.$district.'%"');
                            }else{
                                $query->where('city','like','%'.$country.'%');
                            }
                        });
                    }
                }
            });
            
        };

        return $constraint;
    }

    public static function personal_page_recommend_popular_sweetheart()
    {
        $constraint = SearchService::get_user_search_constraint();
        $sweetheart = User::where('engroup', 2)
                            ->whereHas('user_meta', $constraint)
                            ->inRandomOrder()
                            ->limit(5)
                            ->get();
        return $sweetheart;
    }

    public static function personal_page_recommend_new_sweetheart()
    {
        $now_time = Carbon::now();
        $constraint = SearchService::get_user_search_constraint();
        $sweetheart = User::where('engroup', 2)
                            ->where('created_at', '>=', $now_time->subDays(30))
                            ->whereHas('user_meta', $constraint)
                            ->inRandomOrder()
                            ->limit(5)
                            ->get();
        return $sweetheart;       
    }
}