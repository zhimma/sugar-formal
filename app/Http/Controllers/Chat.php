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
        $msgController = resolve(Message_newController::class);
        $m = $msgController->postChat($request, true);
        if(!isset($m['error'])){
            \App\Events\NewMessage::dispatch($m->id, $m->content, $m->from_id, $m->to_id);
        }
        return event(new \App\Events\Chat($m, $request->from, $request->to));
    }
}
