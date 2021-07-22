<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatRead extends BaseController
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
        \App\Models\Message::find($request->messageId)->compactRead();
    }
}
