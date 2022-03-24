<?php

namespace App\Firebase;

use App\Firebase\NotificationBuilder as PayloadNotificationBuilder;
use App\Models\PushReservation;
use App\Models\User;
use App\Notifications\SendReservationPush;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification as SlackNotification;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;

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

        if (isset($params['data'])) {
            $dataBuilder->addData(['data' => $params['data']]);
        }

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        Log::info(dump($data));

        $downstreamResponse = FCM::sendTo($params['tokens'], $option, $notification, $data);

        $results = [
            'number_success'      => $downstreamResponse->numberSuccess(),
            'number_failure'      => $downstreamResponse->numberFailure(),
            'number_modification' => $downstreamResponse->numberModification(),
            'tokens_to_delete'    => $downstreamResponse->tokensToDelete(), // you must remove all this tokens in your database
            'tokens_to_modify'    => $downstreamResponse->tokensToModify(), // (key : oldToken, value : new token - you must change the token in your database)
            'tokens_to_retry'     => $downstreamResponse->tokensToRetry(), // you should try to resend the message to the tokens in the array
            'tokens_with_error'   => $downstreamResponse->tokensWithError() // (key:token, value:error) - in production you should remove from your database the tokens
        ];

        return $results;
    }

    public static function sendReservationOnce()
    {
        $messages = PushReservation::getOneTimeMessage();
        $tokens = User::getAllowPushMessage();

        foreach ($messages as $message) {
            $params = self::generateParams($message, $tokens);

            self::send($params);

            SlackNotification::route('slack', config('logging.channels.slack.url'))
                ->notify(new SendReservationPush($message));
        }
    }

    public static function sendReservations()
    {
        $messages = PushReservation::getWeeklyMessage();
        $tokens = User::getAllowPushMessage();

        foreach ($messages as $message) {
            $params = self::generateParams($message, $tokens);

            self::send($params);

            SlackNotification::route('slack', config('logging.channels.slack.url'))
                ->notify(new SendReservationPush($message));
        }
    }

    public static function generateParams($data, $tokens)
    {
        return [
            'title'   => $data->title,
            'message' => $data->message,
            'image'   => isset($data->image) ? $data->image : null,
            'data'    => isset($data->data) ? json_decode($data->data) : null,
            'tokens'  => $tokens,
        ];
    }
}
