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

namespace Seat\Notifications\Notifications\Seat\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Notifications\Notifications\AbstractSlackNotification;
use Seat\Web\Models\Squads\Squad;
use Seat\Web\Models\Squads\SquadMember;
use Seat\Web\Models\User;

/**
 * Class SquadMemberRemovedNotification.
 *
 * @package Seat\Notifications\Notifications\Seat
 */
class SquadMemberRemovedNotification extends AbstractSlackNotification
{
    /**
     * @var \Seat\Web\Models\Squads\Squad
     */
    private $squad;

    /**
     * @var \Seat\Web\Models\User
     */
    private $user;

    /**
     * SquadMember constructor.
     *
     * @param  \Seat\Web\Models\Squads\SquadMember  $member
     */
    public function __construct(SquadMember $member)
    {

        $squad = Squad::find($member->squad_id);
        $user = User::find($member->user_id);

        $this->squad = $squad;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
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
            ->error()
            ->content('A SeAT Squad has lost a Member!')
            ->from('SeAT State of Things')
            ->attachment(function ($attachment) {

                $attachment->title('Squad', $this->squad->link)
                ->fields([
                    'User'  => $this->user->name,
                    'Squad' => $this->squad->name,
                ]);
            });
    }
}
