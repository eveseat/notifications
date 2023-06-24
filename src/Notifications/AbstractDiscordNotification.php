<?php

namespace Seat\Notifications\Notifications;

use Seat\Notifications\Jobs\AbstractNotification;

abstract class AbstractDiscordNotification extends AbstractNotification
{
    public const RATE_LIMIT_KEY = 'discord_webhook';
    public const RATE_LIMIT = 50; //https://discord.com/developers/docs/topics/rate-limits#global-rate-limit

    public function middleware(): array
    {
        return [new RateLimitedWithRedis(self::RATE_LIMIT_KEY)];
    }

    public function retryUntil(): DateTime
    {
        return now()->addMinutes(60);
    }
}