<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CfpController extends Controller
{
    //
    public function cfp(Request $request)
    {
        return view('cfp');
    }
}
