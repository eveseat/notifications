<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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

namespace Seat\Notifications\Notifications\Seat\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Notifications\Notifications\AbstractNotification;
use Seat\Web\Models\User;

/**
 * Class EnabledToken
 *
 * @package Seat\Notifications\Notifications\Seat\Slack
 */
class EnabledToken extends AbstractNotification
{
    /**
     * @var \Seat\Eveapi\Models\RefreshToken
     */
    private $token;

    /**
     * EnabledToken constructor.
     *
     * @param \Seat\Eveapi\Models\RefreshToken $token
     */
    public function __construct(RefreshToken $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->success()
            ->content('A corporation members token has been enabled!')
            ->from('SeAT State of Things')
            ->attachment(function ($attachment) {
                $owner = User::where('id', $this->token->user_id)
                    ->first();

                $attachment->title('Token Details', route('corporation.view.tracking', [
                    'corporation_id' => $this->token->affiliation->corporation_id,
                ]))->fields([
                    'Character Name' => $this->token->character->name,
                    'Main Character' => $owner->name,
                ]);
            });
    }
}
