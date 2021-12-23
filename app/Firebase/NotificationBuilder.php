<?php

namespace App\Firebase;

use LaravelFCM\Message\PayloadNotificationBuilder;

class NotificationBuilder extends PayloadNotificationBuilder
{
    protected $image;

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function build()
    {
        return new Notification($this);
    }
}
