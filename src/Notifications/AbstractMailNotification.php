<?php

namespace Seat\Notifications\Notifications;

use Seat\Notifications\Jobs\AbstractNotification;

abstract class AbstractMailNotification extends AbstractNotification
{
    public const RATE_LIMIT_KEY = 'mail_notifications';
    public const RATE_LIMIT = 60;

    public function middleware(): array
    {
        return [new RateLimitedWithRedis(self::RATE_LIMIT_KEY)];
    }

    public function retryUntil(): DateTime
    {
        return now()->addMinutes(60);
    }
}