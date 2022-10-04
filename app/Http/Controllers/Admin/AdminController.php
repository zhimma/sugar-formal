<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends \App\Http\Controllers\BaseController
{
    public function special_industries_judgment_training()
    {
        return view('admin.special_industries_judgment_training');
    }
}
