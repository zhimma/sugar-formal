<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Chat extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
        \App\Models\Message_new::post($request->from, $request->to, $request->msg);
        return event(new \App\Events\Chat($request->msg, $request->from, $request->to));
    }
}
