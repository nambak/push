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

        $results = [
            'number_success'      => $downstreamResponse->numberSuccess(),
            'number_failure'      => $downstreamResponse->numberFailure(),
            'number_modification' => $downstreamResponse->numberModification(),
            'tokens_to_delete'    => $downstreamResponse->tokensToDelete(), // return Array - you must remove all this tokens in your database
            'tokens_to_modify'    => $downstreamResponse->tokensToModify(), // return Array (key : oldToken, value : new token - you must change the token in your database)
            'tokens_to_retry'     => $downstreamResponse->tokensToRetry(), // return Array - you should try to resend the message to the tokens in the array
            'tokens_with_error'   => $downstreamResponse->tokensWithError() // return Array (key:token, value:error) - in production you should remove from your database the tokens
        ];

        return $results;
    }
}
