<?php

namespace App\Http\Controllers\Api;

use App\Models\CustomFingerPrint;
use Illuminate\Http\Request;

class CfpController extends \App\Http\Controllers\BaseController
{
    private $token = 'byakmgcmnd59vypx';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
//    public function index()
//    {
//        //
//        $cfp = CustomFingerPrint::all();
//        return response($cfp, 200);
//    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
//    public function create()
//    {
//        //
//    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if(isset($request->token) && $request->token == $this->token) {
            $cfp = CustomFingerPrint::where('hash', $request->hash)->first();
            if (!isset($cfp)) {
                $cfp = New CustomFingerPrint();
                $cfp->hash = $request->hash;
                $cfp->host = $request->host;
                $cfp->save();
            }
            return response($cfp, 200);
        }

        return response('');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomFingerPrint  $cfp
     * @return \Illuminate\Http\Response
     */
    public function show(CustomFingerPrint $cfp)
    {
        return response($cfp, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomFingerPrint  $cfp
     * @return \Illuminate\Http\Response
     */
//    public function edit(CustomFingerPrint $cfp)
//    {
//        //
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomFingerPrint  $cfp
     * @return \Illuminate\Http\Response
     */
//    public function update(Request $request, CustomFingerPrint $cfp)
//    {
//        //
//    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomFingerPrint  $cfp
     * @return \Illuminate\Http\Response
     */
//    public function destroy(CustomFingerPrint $cfp)
//    {
//        //
//    }

    public function cfp()
    {
        return view('cfp');
    }
}
