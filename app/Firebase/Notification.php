<?php

namespace App\Firebase;

use LaravelFCM\Message\PayloadNotification;

class Notification extends PayloadNotification
{
    protected $image;

    public function __construct(NotificationBuilder $builder)
    {
        parent::__construct($builder);
        $this->image = $builder->getImage();
    }

    public function toArray()
    {
        $notification = [
            'title'          => $this->title,
            'body'           => $this->body,
            'icon'           => $this->icon,
            'sound'          => $this->sound,
            'badge'          => $this->badge,
            'tag'            => $this->tag,
            'color'          => $this->color,
            'click_action'   => $this->clickAction,
            'body_loc_key'   => $this->bodyLocationKey,
            'body_loc_args'  => $this->bodyLocationArgs,
            'title_loc_key'  => $this->titleLocationKey,
            'title_loc_args' => $this->titleLocationArgs,
            'image'          => $this->image,
        ];

        $notification = array_filter($notification, function ($value) {
            return $value !== null;
        });

        return $notification;
    }
}
