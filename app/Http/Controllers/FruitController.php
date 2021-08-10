<?php

namespace App\Http\Controllers;
use App\Http\Controllers\PagesController;
use App\Services\UserService;
use App\Services\VipLogService;
use Illuminate\Http\Request;
use DB;

class FruitController extends BaseController {
    public function index(Request $request){
        return view('fruit.index');
    }
    public function shop(Request $request){
        return view('fruit.shop');
    }
    public function brand(Request $request){
        return view('fruit.brand');
    }
    public function contactus(Request $request){
        return view('fruit.contactus');
    }
    public function health_info(Request $request){
        return view('fruit.health_info');
    }
    public function health_info01(Request $request){
        return view('fruit.health_info01');
    }
    public function health_info02(Request $request){
        return view('fruit.health_info02');
    }
    public function health_info03(Request $request){
        return view('fruit.health_info03');
    }
    public function health_info04(Request $request){
        return view('fruit.health_info04');
    }
    public function health_info_detail(Request $request){
        return view('fruit.health_info_detail');
    }
    public function news01(Request $request){
        return view('fruit.news01');
    }
    public function news02(Request $request){
        return view('fruit.news02');
    }
    public function order_success(Request $request){
        // dd('1');
        return view('fruit.order_success');
    }
    public function order_confirm(Request $request){
        // dd('1');
        return view('fruit.order_confirm');
    }

    public function product_beauty(Request $request){
        // dd('1');
        return view('fruit.product_beauty');
    }
    public function product_berry(Request $request){
        // dd('1');
        return view('fruit.product_berry');
    }
    public function product_charantia(Request $request){
        // dd('1');
        return view('fruit.product_charantia');
    }
    public function product_key(Request $request){
        // dd('1');
        return view('fruit.product_key');
    }
    public function product_ferment(Request $request){
        // dd('1');
        return view('fruit.product_ferment');
    }

    public function product_beauty_more(Request $request){
        // dd('1');
        return view('fruit.product_beauty_more');
    }
    public function product_berry_more(Request $request){
        // dd('1');
        return view('fruit.product_berry_more');
    }
    public function product_charantia_more(Request $request){
        // dd('1');
        return view('fruit.product_charantia_more');
    }
    public function product_key_more(Request $request){
        // dd('1');
        return view('fruit.product_key_more');
    }
    public function product_ferment_more(Request $request){
        // dd('1');
        return view('fruit.product_ferment_more');
    }
    

    

}
?>