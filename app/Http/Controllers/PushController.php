<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class PushController extends Controller
{
    public function send(Request $request)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder($request->title);
        $notificationBuilder->setBody($request->message)
            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();

        if ($request->filled('data')) {
            $dataBuilder->addData(['a_data' => $request->data]);
        }

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $tokens = User::whereIn('id', $request->users)->get()->pluck('device_key')->toArray();

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        dump($tokens);

        dump($downstreamResponse->numberSuccess());
        dump($downstreamResponse->numberFailure());
        dump($downstreamResponse->numberModification());

        // return Array - you must remove all this tokens in your database
        dump($downstreamResponse->tokensToDelete());

        // return Array (key : oldToken, value : new token - you must change the token in your database)
        dump($downstreamResponse->tokensToModify());

        // return Array - you should try to resend the message to the tokens in the array
        dump($downstreamResponse->tokensToRetry());

        // return Array (key:token, value:error) - in production you should remove from your database the tokens
        dump($downstreamResponse->tokensWithError());
    }
}
