<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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

namespace Seat\Notifications\Notifications\Characters\Discord;

use Illuminate\Support\Str;
use Seat\Notifications\Jobs\AbstractNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;

/**
 * Class NewMailMessage
 *
 * @package Seat\Notifications\Notifications\Characters\Discord
 */
class NewMailMessage extends AbstractNotification
{
    /**
     * @var
     */
    private $message;

    /**
     * NewMailMessage constructor.
     *
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * @inheritDoc
     */
    public function via($notifiable)
    {
        return ['discord'];
    }

    /**
     * @param $notifiable
     *
     * @return \Seat\Notifications\Services\Discord\Messages\DiscordMessage
     */
    public function toDiscord($notifiable)
    {
        return (new DiscordMessage())
            ->content('New EVEMail Received!')
            ->embed(function (DiscordEmbed $embed) {
                $embed->timestamp($this->message->timestamp);
                $embed->author('SeAT Personal Agent', asset('web/img/favico/apple-icon-180x180.png'));

                $embed->description(Str::limit(
                    str_replace('<br>', ' ', clean_ccp_html($this->message->body->body, '<br>')),
                    2000));

                $embed->field('Subject', $this->message->subject);
                $embed->field('Sent Date', $this->message->timestamp);
            });
    }
}
