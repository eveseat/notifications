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

namespace Seat\Notifications\Notifications\Seat\Mail;

use Illuminate\Notifications\Messages\MailMessage;
use Seat\Notifications\Notifications\AbstractNotification;
use Seat\Web\Models\Squads\SquadApplication;

/**
 * Class NewAccount.
 *
 * @package Seat\Notifications\Notifications\Seat
 */
class SquadApplicationNotification extends AbstractNotification
{
    /**
     * @var \Seat\Web\Models\Squads\SquadApplication
     */
    private $application;

    /**
     * SquadMember constructor.
     *
     * @param \Seat\Web\Models\Squads\SquadApplication $application
     */
    public function __construct(SquadApplication $application)
    {
        $this->application = $application;
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
            ->success()
            ->greeting('Heads up!')
            ->line('A squad has a new applicant on SeAT!')
            ->line(
                'The application was made by ' . $this->application->user->name . ' to the ' .
                'squad ' . $this->application->squad->name
            )
            ->action('Check it out on SeAT', $this->application->squad->link);
    }
}
