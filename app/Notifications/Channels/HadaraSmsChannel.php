<?php

namespace App\Notifications\Channels;


use App\Services\HadaraSms;
use Illuminate\Notifications\Notification;

class HadaraSmsChannel
{
    public function send(object $notifiable, Notification $notification)
    {
        $service = new HadaraSms(config('services.hadara.key'));
        $service->send(
            $notifiable->routeNotificationForHadara($notification),
            $notification->toHadara($notifiable),
        );
    }
}