<?php

namespace App\Http\Controllers;

use App\Firebase\CloudMessaging;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PushController extends Controller
{
    public function send(Request $request)
    {
        $tokens = User::getAllowPushMessage();

        if (count($tokens) === 0) {
            return response('no user has a device key', 400);
        }

        return $this->sendPush($request, $tokens);
    }

    public function testSend(Request $request)
    {
        $tokens = User::getTestUser();

        if (count($tokens) === 0) {
            return response('no registered test user', 400);
        }

        return $this->sendPush($request, $tokens);
    }

    private function sendPush($request, $tokens)
    {
        $validate = $request->validate([
            'title'   => 'string|required',
            'message' => 'string|required',
        ]);

        $response = CloudMessaging::send([
            'title'   => $validate['title'],
            'message' => $validate['message'],
            'tokens'  => $tokens,
            'image'   => $request->filled('image') ? $request->image : null,
            'data'    => $request->filled('data') ? $request->data : null,
        ]);

        return $response;
    }
}
