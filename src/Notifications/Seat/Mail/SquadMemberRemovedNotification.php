<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2021 Leon Jacobs
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

namespace Seat\Notifications\Notifications\Seat\Mail;

use Illuminate\Notifications\Messages\MailMessage;
use Seat\Notifications\Notifications\AbstractNotification;
use Seat\Web\Models\Squads\Squad;
use Seat\Web\Models\Squads\SquadMember;
use Seat\Web\Models\User;

/**
 * Class SquadMemberNotification.
 *
 * @package Seat\Notifications\Notifications\Seat
 */
class SquadMemberRemovedNotification extends AbstractNotification
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
     * SquadMemberRemovedNotification constructor.
     * @param \Seat\Web\Models\Squads\SquadMember $member
     */
    public function __construct(SquadMember $member)
    {
        $this->squad = Squad::find($member->squad_id);
        $this->user = User::find($member->user_id);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->error()
            ->greeting('Heads up!')
            ->line('A squad has lost a member on SeAT!')
            ->line(
                'The user  ' . $this->user->name . ' has joined the ' .
                'squad ' . $this->squad->name
            )
            ->action('Check it out on SeAT', $this->squad->link);
    }
}
