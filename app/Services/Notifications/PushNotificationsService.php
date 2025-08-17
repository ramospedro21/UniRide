<?php

namespace App\Services\Notifications;

use Illuminate\Support\Facades\Http;

class PushNotificationsService
{
    public static function sendNotification($to, $title, $body, $data = [])
    {
        if (!$to) {
            return false;
        }

        $response = Http::post('https://exp.host/--/api/v2/push/send', [
            'to' => $to,
            'sound' => 'default',
            'title' => $title,
            'body' => $body,
            'data' => $data,
        ]);

        return $response->json();
    }
}
