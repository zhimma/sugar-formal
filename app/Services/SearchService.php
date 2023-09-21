<?php

namespace App\Services;
use App\Models\User;
use App\Models\DailyRecommendSweetheart;
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

        return ['country' => $search_county, 'district' => $search_district];
    }

    public static function get_user_search_area_constraint($country_list, $district_list)
    {
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

    public static function get_user_exclude_constraint()
    {
        $constraint = function($query){
            $query->doesntHave('banned')
                ->orDoesntHave('warned_users')
                ->orDoesntHave('implicitlyBanned')
                ->orWhere('is_hide_online', '!=', 1)
                ->orWhere('accountStatus', '!=', 1)
                ->orWhere('account_status_admin', '!=', 1)
                ;
        };

        return $constraint;
    }

    public static function get_user_search_received_message_constraint()
    {
        $constraint = function($query){
            $query->where('created_at', '>=', Carbon::now()->subWeeks(2))->where('is_truth', 1);
        };

        return $constraint;
    }

    public static function personal_page_recommend_popular_sweetheart_all_list_query()
    {
        $received_message_constraint = SearchService::get_user_search_received_message_constraint();
        $sweetheart_query = User::where('engroup', 2)
                            ->whereHas('receivedMessages', $received_message_constraint)
                            ->withCount(['receivedMessages' => $received_message_constraint])
                            ->having('received_messages_count', '>', 0)
                            ->where(SearchService::get_user_exclude_constraint())
                            ->orderByDesc('received_messages_count')
                            ;
        return $sweetheart_query;
    }

    public static function personal_page_recommend_new_sweetheart_all_list_query()
    {
        $now_time = Carbon::now();
        
        $sweetheart_query = User::where('engroup', 2)
                            ->where('created_at', '>=', $now_time->subDays(30))
                            ->where(SearchService::get_user_exclude_constraint())
                            ->inRandomOrder()
                            ;
        return $sweetheart_query;       
    }
}