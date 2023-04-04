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
use Seat\Web\Models\Squads\SquadApplication;

/**
 * Class SquadMemberNotification.
 *
 * @package Seat\Notifications\Notifications\Seat
 */
class SquadApplicationNotification extends AbstractSlackNotification
{
    /**
     * @var \Seat\Web\Models\Squads\SquadApplication
     */
    private $application;

    /**
     * SquadMember constructor.
     *
     * @param  \Seat\Web\Models\Squads\Squad  $squad
     * @param  \Seat\Web\Models\User  $user
     */
    public function __construct(SquadApplication $application)
    {
        $this->application = $application;
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
            ->warning()
            ->content('A SeAT Squad has a new Application!')
            ->from('SeAT State of Things')
            ->attachment(function ($attachment) {

                $attachment->title('Squad Application', $this->application->squad->link)
                ->fields([
                    'User'    => $this->application->user->name,
                    'Squad'   => $this->application->squad->name,
                    'Message' => $this->application->message,
                ]);
            });
    }
}
