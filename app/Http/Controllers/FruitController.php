<?php

namespace App\Http\Controllers;
use App\Http\Controllers\PagesController;
use App\Services\UserService;
use App\Services\VipLogService;
use Illuminate\Http\Request;
use DB;

class FruitController extends Controller {
    public function index(Request $request){
        // dd('1');
        return view('fruit.index');
    }
    public function shop(Request $request){
        // dd('1');
        return view('fruit.shop');
    }
    public function contactus(Request $request){
        // dd('1');
        return view('fruit.contactus');
    }
    public function health_info(Request $request){
        // dd('1');
        return view('fruit.health_info');
    }
    public function health_info_detail(Request $request){
        // dd('1');
        return view('fruit.health_info_detail');
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
    

    

}
?>