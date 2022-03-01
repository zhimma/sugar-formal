<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class SimilarImages extends Model
{
    use HasFactory;

    protected $table = 'similar_images';

    protected $apiUrl = 'https://vision.googleapis.com/v1/images:annotate';
    protected $apiKey = 'AIzaSyCpYhhS_wfpnO18KRxXRx7lZaHqI2zDUyQ';
    protected $maxResults = 3;


    public function find_by_path($path)
    {
        return $this->where('pic', $path)->first();
    }

    public function update_or_create($pic_path)
    {
        $data = [
            "requests" => [
                [
                    "image" => [
                        "source" => [
                            "imageUri" => url($pic_path)
                        ]
                    ],
                    "features" => [
                        [
                            "maxResults" => $this->maxResults,
                            "type" => "WEB_DETECTION"
                        ]
                    ]
                ]
            ]
        ];

        // request_google_vision_api
        $response = Http::post($this->apiUrl . '?key=' . $this->apiKey, $data);

        if ($response->ok()) {

            $raw_response_json = $response->json();

            if($this->find_by_path($pic_path)){
                $similar_images = $this->find_by_path($pic_path);
            }else{
                $similar_images = new $this;
            }
            
            $similar_images->pic = $pic_path;
            $similar_images->raw_request = json_encode($data, JSON_UNESCAPED_UNICODE);
            $similar_images->raw_response = json_encode($raw_response_json, JSON_UNESCAPED_UNICODE);

            if(isset($raw_response_json['responses'][0]['error'])){
                $similar_images->status = 'failed';
            }

            if(isset($raw_response_json['responses'][0]['webDetection'])){
                $similar_images->status = 'success';
            }

            if($similar_images->status == 'success'){

                $webDetection = $raw_response_json['responses'][0]['webDetection'];

                if (isset($webDetection['fullMatchingImages'])) {
                    $similar_images->fullMatchingImages = json_encode($webDetection['fullMatchingImages'], JSON_UNESCAPED_UNICODE);
                }

                if (isset($webDetection['partialMatchingImages'])) {
                    $similar_images->partialMatchingImages = json_encode($webDetection['partialMatchingImages'], JSON_UNESCAPED_UNICODE);
                }

                if (isset($webDetection['pagesWithMatchingImages'])) {
                    $similar_images->pagesWithMatchingImages = json_encode($webDetection['pagesWithMatchingImages'], JSON_UNESCAPED_UNICODE);
                }

                if (isset($webDetection['visuallySimilarImages'])) {
                    $similar_images->visuallySimilarImages = json_encode($webDetection['visuallySimilarImages'], JSON_UNESCAPED_UNICODE);
                }
            }
            
            $similar_images->save();
        }

        // dd($response->json());
    }
    
    public static function countSearchedByEntrysArr($entrysArr) {
        $entrys = collect([]);
        foreach($entrysArr  as $entrysElt) {
            $entrys = $entrys->merge($entrysElt); 
        }
        
        return SimilarImages::countSearchedByEntrys($entrys);
    }
    
    public static function countSearchedByEntrys($entrys) {
        $picArr = $entrys->pluck('pic')->all();
        return SimilarImages::select('pic')->whereIn('pic', $picArr)->where('status','success')->distinct()->count();
        
    }    
    
    
}
