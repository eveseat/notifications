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

namespace Seat\Notifications\Notifications\Seat;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Notifications\Notifications\AbstractNotification;

/**
 * Class NewAccount.
 *
 * @package Seat\Notifications\Notifications\Seat
 */
class NewAccount extends AbstractNotification
{
    /**
     * @var
     */
    private $user;

    /**
     * Create a new notification instance.
     *
     * @param $user
     */
    public function __construct($user)
    {

        $this->user = $user;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {

        return ['mail', 'slack'];
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
            ->line('We have a new account created on to SeAT!')
            ->line(
                'The key was added by ' . $this->user->name . ' that last ' .
                'logged in from ' . $this->user->last_login_source . ' at ' .
                $this->user->last_login . '.'
            )
            ->action('Check it out on SeAT', route('configuration.users.edit', ['user_id' => $this->user->id]));
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
            ->content('A new SeAT account was created!')
            ->attachment(function ($attachment) {

                $attachment->title('Account Details', route('configuration.users.edit', [
                    'user_id' => $this->user->id,
                ]))->fields([
                    'Account Name'            => $this->user->name,
                    'Owner Last Login Source' => $this->user->last_login_source,
                    'Owner Last Login Time'   => $this->user->last_login,
                ]);
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {

        return [
            'key_id'                  => $this->user->id,
            'key_owner'               => $this->user->name,
            'owner_last_login_source' => $this->user->last_login_source,
            'owner_last_login_time'   => $this->user->last_login,
        ];
    }
}
