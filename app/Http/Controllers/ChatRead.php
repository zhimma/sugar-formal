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
        if($request->messageId??null)
            \App\Models\Message::findOrNew($request->messageId)->compactRead();
    
        if($request->messageClientId??null)
            \App\Models\Message::where('client_id',$request->messageClientId)->firstOrNew()->compactRead();
    
    }
}
