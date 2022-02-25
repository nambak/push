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

        return $this->sendPush($request, $tokens);
    }

    public function testSend(Request $request)
    {
        $tokens = User::getTestUser();

        return $this->sendPush($request, $tokens);
    }

    private function sendPush($request, $tokens)
    {
        $validate = $request->validate([
            'title' => 'string|required',
            'message' => 'string|required',
        ]);

        $response = CloudMessaging::send([
            'title'   => $validate['title'],
            'message' => $validate['message'],
            'tokens'  => $tokens,
            'image'   => $request->isFilled('image') ? $request->image : null,
            'data'    => $request->isFilled('data') ? $request->data : null,
        ]);

        return $response;
    }
}
