<?php

namespace Seat\Notifications\Notifications;

use DateTime;
use Illuminate\Queue\Middleware\RateLimitedWithRedis;

abstract class AbstractSlackNotification extends AbstractNotification
{
    public const RATE_LIMIT_KEY = "slack_webhook";

    public function middleware(): array
    {
        return [(new RateLimitedWithRedis(self::RATE_LIMIT_KEY))];
    }

    public function retryUntil(): DateTime
    {
        return now()->addMinutes(60);
    }
}