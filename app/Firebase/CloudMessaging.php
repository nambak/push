<?php

namespace App\Firebase;

use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use App\Firebase\NotificationBuilder as PayloadNotificationBuilder;
class CloudMessaging
{
    public static function send(array $params)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder($params['title']);
        $notificationBuilder->setBody($params['message'])
            ->setSound('default');

        if (isset($params['image'])) {
            $notificationBuilder->setImage($params['image']);
        }

        $dataBuilder = new PayloadDataBuilder();

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $downstreamResponse = FCM::sendTo($params['tokens'], $option, $notification, $data);

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
