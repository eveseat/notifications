<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
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

namespace Seat\Notifications\Notifications\Seat\Discord;

use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Web\Models\Squads\Squad;
use Seat\Web\Models\Squads\SquadMember;
use Seat\Web\Models\User;

/**
 * Class SquadMemberRemovedNotification.
 *
 * @package Seat\Notifications\Notifications\Seat
 */
class SquadMemberRemovedNotification extends AbstractDiscordNotification
{
    private Squad $squad;

    private User $user;

    public function __construct(SquadMember $member)
    {
        $squad = Squad::find($member->squad_id);
        $user = User::find($member->user_id);

        $this->squad = $squad;
        $this->user = $user;
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function populateMessage(DiscordMessage $message, $notifiable): void
    {
        $message
            ->error()
            ->content('A SeAT Squad has lost a Member!')
            ->embed(function (DiscordEmbed $embed) {
                $embed->author('SeAT State of Things', asset('web/img/favico/apple-icon-180x180.png'));

                $embed->title('Squad', $this->squad->link)
                    ->fields([
                        'User' => $this->user->name,
                        'Squad' => $this->squad->name,
                    ]);
            });
    }
}
