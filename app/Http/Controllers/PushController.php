<?php

namespace App\Http\Controllers;

use App\Firebase\CloudMessaging;
use App\Models\User;
use Illuminate\Http\Request;

class PushController extends Controller
{
    public function send(Request $request)
    {
        $tokens = User::getAllowPushMessage();

        $response = CloudMessaging::send([
            'title'   => $request->title,
            'message' => $request->message,
            'tokens'  => $tokens,
            'image'   => $request->filled('image') ? $request->image : null,
            'data'    => $request->filled('data') ? $request->data : null,
        ]);

        return $response;
    }
}
