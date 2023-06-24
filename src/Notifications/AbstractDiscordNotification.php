<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace Seat\Notifications\Notifications;

use DateTime;
use Illuminate\Queue\Middleware\RateLimitedWithRedis;
use Seat\Notifications\Jobs\AbstractNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;

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

    /**
     * @param  $notifiable
     * @return \Seat\Notifications\Services\Discord\Messages\DiscordMessage
     */
    public function toDiscord($notifiable)
    {
        $message = new DiscordMessage();
        $message->content("@here");
        $this->populateMessage($message, $notifiable);
        return $message;
    }

    /**
     * Populate the content of the notification
     * @param DiscordMessage $message
     * @param $notifiable
     * */
    protected abstract function populateMessage(DiscordMessage $message, $notifiable);
}
